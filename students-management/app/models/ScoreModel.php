<?php

class ScoreModel
{
    public function __construct(private readonly mysqli $db)
    {
    }

    public function getAllClasses(): array
    {
        $result = $this->db->query("SELECT DISTINCT class FROM students WHERE class IS NOT NULL AND class != '' ORDER BY class ASC");
        $rows = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
        return array_column($rows, 'class');
    }

    public function getScoreRows(int $subjectId, string $selectedClass = ''): array
    {
        $rows = [];
        // Dùng LEFT JOIN để vẫn hiển thị cả sinh viên chưa có điểm, từ đó có thể nhập điểm hàng loạt trên cùng một màn hình.
        $sql = "
            SELECT
                st.id AS student_id,
                st.mssv,
                st.fullname,
                st.class,
                sc.id AS score_id,
                COALESCE(sc.attendance_score, 0) AS attendance_score,
                COALESCE(sc.midterm_score, 0) AS midterm_score,
                COALESCE(sc.final_score, 0) AS final_score,
                COALESCE(sc.scores, 0) AS total_score
            FROM students st
            LEFT JOIN scores sc
                ON sc.student_id = st.id
                AND sc.subject_id = ?
            WHERE (? = '' OR st.class = ?)
            ORDER BY st.mssv ASC
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('iss', $subjectId, $selectedClass, $selectedClass);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }

        $stmt->close();
        return $rows;
    }

    public function saveBatch(int $subjectId, array $scores): bool
    {
        $subject = (new SubjectModel($this->db))->find($subjectId);
        if ($subject === null) {
            return false;
        }

        // Mỗi dòng điểm đều được tính lại điểm tổng kết theo đúng trọng số cấu hình của môn học đang chọn.
        foreach ($scores as $studentId => $row) {
            $studentId = (int)$studentId;
            $attendance = max(0, min(10, round((float)($row['attendance_score'] ?? 0), 1)));
            $midterm = max(0, min(10, round((float)($row['midterm_score'] ?? 0), 1)));
            $final = max(0, min(10, round((float)($row['final_score'] ?? 0), 1)));
            $total = calculateAverage(
                $attendance,
                $midterm,
                $final,
                (int)$subject['attendance_weight'],
                (int)$subject['midterm_weight'],
                (int)$subject['final_weight']
            );

            $checkStmt = $this->db->prepare('SELECT id FROM scores WHERE student_id = ? AND subject_id = ?');
            $checkStmt->bind_param('ii', $studentId, $subjectId);
            $checkStmt->execute();
            $result = $checkStmt->get_result();
            $existing = $result->fetch_assoc();
            $checkStmt->close();

            // Upsert thủ công: nếu đã có bản ghi điểm thì UPDATE, nếu chưa có thì INSERT mới.
            if ($existing) {
                $stmt = $this->db->prepare(
                    'UPDATE scores SET attendance_score = ?, midterm_score = ?, final_score = ?, scores = ? WHERE id = ?'
                );
                $scoreId = (int)$existing['id'];
                $stmt->bind_param('ddddi', $attendance, $midterm, $final, $total, $scoreId);
            } else {
                $stmt = $this->db->prepare(
                    'INSERT INTO scores (student_id, subject_id, attendance_score, midterm_score, final_score, scores)
                     VALUES (?, ?, ?, ?, ?, ?)'
                );
                $stmt->bind_param('iidddd', $studentId, $subjectId, $attendance, $midterm, $final, $total);
            }

            $stmt->execute();
            $stmt->close();
        }

        return true;
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM scores WHERE id = ?');
        $stmt->bind_param('i', $id);
        $ok = $stmt->execute();
        $stmt->close();

        return $ok;
    }

    public function dashboardAverage(string $class = 'all'): array
    {
        $where = '';
        if ($class !== '' && $class !== 'all') {
            $escaped = $this->db->real_escape_string($class);
            $where = "AND s.class = '{$escaped}'";
        }

        // Dashboard đọc 3 chỉ số tổng quát: tổng số sinh viên, GPA trung bình và tỷ lệ đạt học phần.
        $totalResult = $this->db->query("SELECT COUNT(*) AS total FROM students s WHERE 1=1 {$where}");
        $gpaResult = $this->db->query("SELECT ROUND(AVG(sc.scores), 2) AS gpa FROM scores sc JOIN students s ON sc.student_id = s.id WHERE 1=1 {$where}");
        $passResult = $this->db->query("
            SELECT COUNT(*) AS total_scores, SUM(CASE WHEN sc.scores >= 5 THEN 1 ELSE 0 END) AS passed
            FROM scores sc
            JOIN students s ON sc.student_id = s.id
            WHERE 1=1 {$where}
        ");

        $totalRow = $totalResult ? $totalResult->fetch_assoc() : ['total' => 0];
        $gpaRow = $gpaResult ? $gpaResult->fetch_assoc() : ['gpa' => 0];
        $passRow = $passResult ? $passResult->fetch_assoc() : ['total_scores' => 0, 'passed' => 0];
        $passRate = (int)$passRow['total_scores'] > 0
            ? round(((int)$passRow['passed'] / (int)$passRow['total_scores']) * 100, 1)
            : 0;

        return [
            'total' => (int)$totalRow['total'],
            'gpa' => (float)($gpaRow['gpa'] ?? 0),
            'pass_rate' => $passRate,
        ];
    }

    public function rankingStats(string $class = 'all'): array
    {
        $where = '';
        if ($class !== '' && $class !== 'all') {
            $escaped = $this->db->real_escape_string($class);
            $where = "AND s.class = '{$escaped}'";
        }

        $result = $this->db->query("
            SELECT AVG(sc.scores) AS avg_score
            FROM scores sc
            JOIN students s ON sc.student_id = s.id
            WHERE 1=1 {$where}
            GROUP BY sc.student_id
        ");

        // Gom sinh viên vào các mức xếp loại để biểu đồ doughnut dùng trực tiếp dữ liệu đã tổng hợp.

        $ranks = ['Xuất sắc' => 0, 'Giỏi' => 0, 'Khá' => 0, 'Trung bình' => 0, 'Yếu' => 0];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $avg = (float)$row['avg_score'];
                if ($avg >= 9) {
                    $ranks['Xuất sắc']++;
                } elseif ($avg >= 8) {
                    $ranks['Giỏi']++;
                } elseif ($avg >= 6.5) {
                    $ranks['Khá']++;
                } elseif ($avg >= 5) {
                    $ranks['Trung bình']++;
                } else {
                    $ranks['Yếu']++;
                }
            }
        }

        return $ranks;
    }

    public function resultStats(string $class = 'all'): array
    {
        $where = '';
        if ($class !== '' && $class !== 'all') {
            $escaped = $this->db->real_escape_string($class);
            $where = "AND s.class = '{$escaped}'";
        }

        // Trả về bảng tổng hợp theo môn và lớp để tái sử dụng cho biểu đồ, bảng thống kê hoặc báo cáo khác.
        $result = $this->db->query("
            SELECT
                sub.subject_name,
                sub.subject_code,
                s.class,
                COUNT(DISTINCT sc.student_id) AS total_sv,
                ROUND(AVG(sc.scores), 2) AS gpa,
                ROUND(SUM(CASE WHEN sc.scores >= 5 THEN 1 ELSE 0 END) / COUNT(*) * 100, 1) AS pass_rate
            FROM scores sc
            JOIN students s ON sc.student_id = s.id
            JOIN subject sub ON sc.subject_id = sub.id
            WHERE 1=1 {$where}
            GROUP BY sc.subject_id, s.class
            ORDER BY sub.subject_name, s.class
        ");

        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }
}
