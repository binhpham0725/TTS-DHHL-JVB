<?php

class SubjectModel
{
    public function __construct(private readonly mysqli $db)
    {
    }

    public function all(): array
    {
        $result = $this->db->query('SELECT * FROM subject ORDER BY id DESC');
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function options(): array
    {
        $result = $this->db->query('SELECT id, subject_code, subject_name FROM subject ORDER BY subject_name ASC');
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM subject WHERE id = ? LIMIT 1');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $subject = $result->fetch_assoc() ?: null;
        $stmt->close();

        return $subject;
    }

    public function existsByCode(string $code, ?int $excludeId = null): bool
    {
        if ($excludeId !== null) {
            $stmt = $this->db->prepare('SELECT id FROM subject WHERE subject_code = ? AND id != ? LIMIT 1');
            $stmt->bind_param('si', $code, $excludeId);
        } else {
            $stmt = $this->db->prepare('SELECT id FROM subject WHERE subject_code = ? LIMIT 1');
            $stmt->bind_param('s', $code);
        }

        $stmt->execute();
        $stmt->store_result();
        $exists = $stmt->num_rows > 0;
        $stmt->close();

        return $exists;
    }

    public function create(array $data): bool
    {
        $stmt = $this->db->prepare(
            'INSERT INTO subject (subject_code, subject_name, credits, description, attendance_weight, midterm_weight, final_weight)
             VALUES (?, ?, ?, ?, ?, ?, ?)'
        );
        $stmt->bind_param(
            'ssisiii',
            $data['subject_code'],
            $data['subject_name'],
            $data['credits'],
            $data['description'],
            $data['attendance_weight'],
            $data['midterm_weight'],
            $data['final_weight']
        );
        $ok = $stmt->execute();
        $stmt->close();

        return $ok;
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare(
            'UPDATE subject
             SET subject_code = ?, subject_name = ?, credits = ?, description = ?, attendance_weight = ?, midterm_weight = ?, final_weight = ?
             WHERE id = ?'
        );
        $stmt->bind_param(
            'ssisiiii',
            $data['subject_code'],
            $data['subject_name'],
            $data['credits'],
            $data['description'],
            $data['attendance_weight'],
            $data['midterm_weight'],
            $data['final_weight'],
            $id
        );
        $ok = $stmt->execute();
        $stmt->close();

        return $ok;
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM subject WHERE id = ?');
        $stmt->bind_param('i', $id);
        $ok = $stmt->execute();
        $stmt->close();

        return $ok;
    }

    public function validate(array $data, ?int $excludeId = null): array
    {
        $errors = [];

        $subjectCode = trim($data['subject_code'] ?? '');
        $subjectName = trim($data['subject_name'] ?? '');
        $credits = (int)($data['credits'] ?? 0);
        $attendanceWeight = (int)($data['attendance_weight'] ?? 0);
        $midtermWeight = (int)($data['midterm_weight'] ?? 0);
        $finalWeight = (int)($data['final_weight'] ?? 0);

        if ($subjectCode === '') {
            $errors[] = 'Vui lòng nhập mã môn.';
        }

        if ($subjectName === '') {
            $errors[] = 'Vui lòng nhập tên môn học.';
        }

        if ($credits <= 0) {
            $errors[] = 'Số tín chỉ phải lớn hơn 0.';
        }

        if (($attendanceWeight + $midtermWeight + $finalWeight) !== 100) {
            $errors[] = 'Tổng tỷ trọng điểm phải bằng 100%.';
        }

        if ($subjectCode !== '' && $this->existsByCode($subjectCode, $excludeId)) {
            $errors[] = 'Mã môn đã tồn tại.';
        }

        return $errors;
    }
}
