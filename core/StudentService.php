<?php
require_once __DIR__ . '/../config/database.php';
/* escape dữ liệu trước khi render ra html */
function escapeValue($value): string
{
    return htmlspecialchars((string) ($value ?? ''), ENT_QUOTES, 'UTF-8');
}
/* validate dữ liệu sinh viên trước khi create hoặc update */
function validateStudentData(array $studentData, array $academicData): ?string
{
    $validGenders = ['Nam', 'Nữ'];
    $validStatuses = ['Năm 1', 'Năm 2', 'Năm 3', 'Năm 4', 'Đã tốt nghiệp', 'Khác', ''];
    $validRanks = ['Xuất sắc', 'Giỏi', 'Khá', 'Trung bình', 'Yếu', ''];

    if (($studentData['name'] ?? '') === '') {
        return 'missing_name';
    }
    if (($studentData['gender'] ?? '') === '' || !in_array($studentData['gender'], $validGenders, true)) {
        return 'invalid_gender';
    }
    if (($studentData['dob'] ?? '') === '') {
        return 'missing_dob';
    }
    if (($studentData['email'] ?? '') !== '' && !filter_var($studentData['email'], FILTER_VALIDATE_EMAIL)) {
        return 'invalid_email';
    }
    if (!in_array(($academicData['status'] ?? ''), $validStatuses, true)) {
        return 'invalid_status';
    }
    if (!in_array(($academicData['rank'] ?? ''), $validRanks, true)) {
        return 'invalid_rank';
    }
    if (isset($academicData['gpa']) && $academicData['gpa'] !== '' && $academicData['gpa'] < 0) {
        return 'invalid_gpa';
    }
    if (isset($academicData['gpa']) && $academicData['gpa'] > 4.0) {
        return 'gpa_too_high';
    }

    return null;
}
/* lấy tổng số sinh viên */
function getStudentCountValue(): int
{
    global $conn;
    $result = $conn->query('SELECT COUNT(*) AS total FROM students');
    $row = $result->fetch_assoc();
    return (int) $row['total'];
}
/* map tên cột sort từ url sang cột thật trong sql */
function getSortColumn(string $view, string $sortKey): string
{
    $academicSortMap = [
        'ho_ten' => 's.ho_ten',
        'chuyen_nganh' => 'a.chuyen_nganh',
        'khoa_hoc' => 'a.khoa_hoc',
        'gpa' => 'a.gpa',
        'tinh_trang' => 'a.tinh_trang',
        'xep_loai' => 'a.xep_loai',
    ];

    $personalSortMap = [
        'ho_ten' => 'ho_ten',
        'gioi_tinh' => 'gioi_tinh',
        'ngay_sinh' => 'ngay_sinh',
        'email' => 'email',
        'dia_chi' => 'dia_chi',
    ];

    if ($view === 'academic') {
        return $academicSortMap[$sortKey] ?? 's.id';
    }

    return $personalSortMap[$sortKey] ?? 'id';
}
/* build điều kiện search theo từng view */
function getSearchWhereClause(string $view): string
{
    if ($view === 'academic') {
        return "(
            s.ho_ten LIKE ?
            OR COALESCE(a.chuyen_nganh, '') LIKE ?
            OR COALESCE(a.khoa_hoc, '') LIKE ?
            OR COALESCE(a.tinh_trang, '') LIKE ?
            OR COALESCE(a.xep_loai, '') LIKE ?
            OR CAST(COALESCE(a.gpa, '') AS CHAR) LIKE ?
        )";
    }

    return "(
        ho_ten LIKE ?
        OR gioi_tinh LIKE ?
        OR email LIKE ?
        OR dia_chi LIKE ?
        OR CAST(ngay_sinh AS CHAR) LIKE ?
    )";
}
/* lấy dữ liệu trang danh sách theo view và phân trang */
function getStudentListViewData(string $view, int $page, int $limit): array
{
    global $conn;
    $page = max($page, 1);
    $limit = max($limit, 1);
    $offset = ($page - 1) * $limit;
    $search = trim($_GET['search'] ?? '');
    $sortKey = $_GET['sort'] ?? 'ho_ten';
    $sortColumn = getSortColumn($view, $sortKey);
    $sortOrder = (isset($_GET['order']) && $_GET['order'] === 'desc') ? 'DESC' : 'ASC';
    $searchLike = '%' . $search . '%';
    $whereClause = $search !== '' ? ' WHERE ' . getSearchWhereClause($view) : '';

    if ($view === 'academic') {
        $countSql = "
            SELECT COUNT(*) AS total
            FROM students s
            LEFT JOIN student_academic a ON s.id = a.student_id
            $whereClause
        ";
        $countStatement = $conn->prepare($countSql);
        if ($search !== '') {
            $countStatement->bind_param(
                'ssssss',
                $searchLike,
                $searchLike,
                $searchLike,
                $searchLike,
                $searchLike,
                $searchLike
            );
        }
        $countStatement->execute();
        $countResult = $countStatement->get_result();
        $totalStudents = (int) $countResult->fetch_assoc()['total'];
        $countStatement->close();

        $sql = "
            SELECT
                s.id,
                s.ho_ten,
                COALESCE(a.chuyen_nganh, '') AS chuyen_nganh,
                COALESCE(a.khoa_hoc, '') AS khoa_hoc,
                COALESCE(a.gpa, '') AS gpa,
                COALESCE(a.tinh_trang, '') AS tinh_trang,
                COALESCE(a.xep_loai, '') AS xep_loai
            FROM students s
            LEFT JOIN student_academic a ON s.id = a.student_id
            $whereClause
            ORDER BY $sortColumn $sortOrder
            LIMIT ?, ?
        ";
        $statement = $conn->prepare($sql);
        if ($search !== '') {
            $statement->bind_param(
                'ssssssii',
                $searchLike,
                $searchLike,
                $searchLike,
                $searchLike,
                $searchLike,
                $searchLike,
                $offset,
                $limit
            );
        } else {
            $statement->bind_param('ii', $offset, $limit);
        }
    } else {
        $countSql = "SELECT COUNT(*) AS total FROM students$whereClause";
        $countStatement = $conn->prepare($countSql);
        if ($search !== '') {
            $countStatement->bind_param(
                'sssss',
                $searchLike,
                $searchLike,
                $searchLike,
                $searchLike,
                $searchLike
            );
        }
        $countStatement->execute();
        $countResult = $countStatement->get_result();
        $totalStudents = (int) $countResult->fetch_assoc()['total'];
        $countStatement->close();

        $sql = "
            SELECT *
            FROM students
            $whereClause
            ORDER BY $sortColumn $sortOrder
            LIMIT ?, ?
        ";
        $statement = $conn->prepare($sql);
        if ($search !== '') {
            $statement->bind_param(
                'sssssii',
                $searchLike,
                $searchLike,
                $searchLike,
                $searchLike,
                $searchLike,
                $offset,
                $limit
            );
        } else {
            $statement->bind_param('ii', $offset, $limit);
        }
    }

    $totalPages = max((int) ceil($totalStudents / $limit), 1);
    $statement->execute();
    $result = $statement->get_result();

    $students = [];
    while ($row = $result->fetch_assoc()) {
        $students[] = $row;
    }
    $statement->close();

    return [
        'students' => $students,
        'page' => $page,
        'limit' => $limit,
        'total_pages' => $totalPages,
        'total_students' => $totalStudents,
        'search' => $search,
        'sort_key' => $sortKey,
        'sort_order' => strtolower($sortOrder),
    ];
}
/* lấy 1 sinh viên theo id */
function getStudentById(int $studentId): ?array
{
    global $conn;
    $statement = $conn->prepare('SELECT * FROM students WHERE id = ?');
    $statement->bind_param('i', $studentId);
    $statement->execute();

    $result = $statement->get_result();
    $student = $result->fetch_assoc() ?: null;

    $statement->close();

    return $student;
}
/* lấy dữ liệu học tập theo student_id */
function getStudentAcademicByStudentId(int $studentId): ?array
{
    global $conn;
    $statement = $conn->prepare('SELECT * FROM student_academic WHERE student_id = ?');
    $statement->bind_param('i', $studentId);
    $statement->execute();

    $result = $statement->get_result();
    $academic = $result->fetch_assoc() ?: null;

    $statement->close();

    return $academic;
}
/* tạo mới sinh viên và dữ liệu học tập */
function createStudentRecord(array $studentData, array $academicData): bool
{
    global $conn;
    $conn->begin_transaction();
    $statement = $conn->prepare(
        'INSERT INTO students(ho_ten, gioi_tinh, ngay_sinh, email, dia_chi) VALUES (?, ?, ?, ?, ?)'
    );
    $statement->bind_param(
        'sssss',
        $studentData['name'],
        $studentData['gender'],
        $studentData['dob'],
        $studentData['email'],
        $studentData['address']
    );

    $isCreated = $statement->execute();
    if (!$isCreated) {
        $statement->close();
        $conn->rollback();
        return false;
    }

    $studentId = $conn->insert_id;
    $statement->close();

    $academicStatement = $conn->prepare(
        'INSERT INTO student_academic(student_id, chuyen_nganh, khoa_hoc, gpa, tinh_trang, xep_loai) VALUES (?, ?, ?, ?, ?, ?)'
    );
    $academicStatement->bind_param(
        'issdss',
        $studentId,
        $academicData['major'],
        $academicData['course'],
        $academicData['gpa'],
        $academicData['status'],
        $academicData['rank']
    );
    $isAcademicCreated = $academicStatement->execute();
    $academicStatement->close();
    if (!$isAcademicCreated) {
        $conn->rollback();
        return false;
    }

    $conn->commit();

    return true;
}
/* xóa academic trước rồi xóa student */
function deleteStudentRecord(int $studentId): bool
{
    global $conn;
    $academicStatement = $conn->prepare('DELETE FROM student_academic WHERE student_id = ?');
    $academicStatement->bind_param('i', $studentId);
    $academicStatement->execute();
    $academicStatement->close();

    $studentStatement = $conn->prepare('DELETE FROM students WHERE id = ?');
    $studentStatement->bind_param('i', $studentId);
    $isDeleted = $studentStatement->execute();
    $studentStatement->close();

    return $isDeleted;
}
/* cập nhật thông tin sinh viên và học tập */
function updateStudentRecord(int $studentId, array $studentData, array $academicData): bool
{
    global $conn;
    if ($studentId <= 0 || !getStudentById($studentId)) {
        return false;
    }

    $conn->begin_transaction();
    $statement = $conn->prepare(
        'UPDATE students SET ho_ten = ?, gioi_tinh = ?, ngay_sinh = ?, email = ?, dia_chi = ? WHERE id = ?'
    );
    $statement->bind_param(
        'sssssi',
        $studentData['name'],
        $studentData['gender'],
        $studentData['dob'],
        $studentData['email'],
        $studentData['address'],
        $studentId
    );
    $isStudentUpdated = $statement->execute();
    $statement->close();
    if (!$isStudentUpdated) {
        $conn->rollback();
        return false;
    }

    $academic = getStudentAcademicByStudentId($studentId);

    if ($academic) {
        $academicStatement = $conn->prepare(
            'UPDATE student_academic SET chuyen_nganh = ?, khoa_hoc = ?, gpa = ?, tinh_trang = ?, xep_loai = ? WHERE student_id = ?'
        );
        $academicStatement->bind_param(
            'ssdssi',
            $academicData['major'],
            $academicData['course'],
            $academicData['gpa'],
            $academicData['status'],
            $academicData['rank'],
            $studentId
        );
        $isAcademicUpdated = $academicStatement->execute();
        $academicStatement->close();
        if (!$isAcademicUpdated) {
            $conn->rollback();
            return false;
        }

        $conn->commit();
        return true;
    }

    $academicStatement = $conn->prepare(
        'INSERT INTO student_academic(student_id, chuyen_nganh, khoa_hoc, gpa, tinh_trang, xep_loai) VALUES (?, ?, ?, ?, ?, ?)'
    );
    $academicStatement->bind_param(
        'issdss',
        $studentId,
        $academicData['major'],
        $academicData['course'],
        $academicData['gpa'],
        $academicData['status'],
        $academicData['rank']
    );
    $isAcademicCreated = $academicStatement->execute();
    $academicStatement->close();
    if (!$isAcademicCreated) {
        $conn->rollback();
        return false;
    }

    $conn->commit();

    return true;
}
/* xử lý sửa nhanh ngay trên bảng */
function handleInlineStudentUpdate(int $studentId, string $view, array $payload): array
{
    global $conn;
    if ($studentId <= 0) {
        return ['status' => 'error', 'message' => 'Dữ liệu không hợp lệ'];
    }

    if ($view === 'personal') {
        $email = trim($payload['email'] ?? '');
        $address = trim($payload['dia_chi'] ?? '');

        if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['status' => 'error', 'message' => 'Email không hợp lệ'];
        }

        $statement = $conn->prepare('UPDATE students SET email = ?, dia_chi = ? WHERE id = ?');
        $statement->bind_param('ssi', $email, $address, $studentId);
        $isUpdated = $statement->execute();
        $statement->close();

        if (!$isUpdated) {
            return ['status' => 'error', 'message' => 'Không thể cập nhật dữ liệu'];
        }

        return [
            'status' => 'success',
            'data' => [
                'email' => $email,
                'dia_chi' => $address,
            ],
        ];
    }

    $major = trim($payload['chuyen_nganh'] ?? '');
    $status = trim($payload['tinh_trang'] ?? '');
    $rank = trim($payload['xep_loai'] ?? '');

    $validStatuses = ['Năm 1', 'Năm 2', 'Năm 3', 'Năm 4', 'Đã tốt nghiệp', 'Khác'];
    $validRanks = ['Xuất sắc', 'Giỏi', 'Khá', 'Trung bình', 'Yếu'];

    if ($status !== '' && !in_array($status, $validStatuses, true)) {
        return ['status' => 'error', 'message' => 'Tình trạng không hợp lệ'];
    }

    if ($rank !== '' && !in_array($rank, $validRanks, true)) {
        return ['status' => 'error', 'message' => 'Xếp loại không hợp lệ'];
    }

    $academic = getStudentAcademicByStudentId($studentId);

    if ($academic) {
        $statement = $conn->prepare(
            'UPDATE student_academic SET chuyen_nganh = ?, tinh_trang = ?, xep_loai = ? WHERE student_id = ?'
        );
        $statement->bind_param('sssi', $major, $status, $rank, $studentId);
    } else {
        $emptyCourse = '';
        $emptyGpa = 0;
        $statement = $conn->prepare(
            'INSERT INTO student_academic(student_id, chuyen_nganh, khoa_hoc, gpa, tinh_trang, xep_loai) VALUES (?, ?, ?, ?, ?, ?)'
        );
        $statement->bind_param('issdss', $studentId, $major, $emptyCourse, $emptyGpa, $status, $rank);
    }

    $isUpdated = $statement->execute();
    $statement->close();

    if (!$isUpdated) {
        return ['status' => 'error', 'message' => 'Không thể cập nhật dữ liệu'];
    }

    return [
        'status' => 'success',
        'data' => [
            'chuyen_nganh' => $major,
            'tinh_trang' => $status,
            'xep_loai' => $rank,
        ],
    ];
}
/* xuất csv theo đúng view đang xem */
function streamStudentCsv(string $view): void
{
    global $conn;
    $search = trim($_GET['search'] ?? '');
    $sortKey = $_GET['sort'] ?? 'ho_ten';
    $sortColumn = getSortColumn($view, $sortKey);
    $sortOrder = (isset($_GET['order']) && $_GET['order'] === 'desc') ? 'DESC' : 'ASC';
    $filename = 'students_' . $view . '_' . date('Ymd_His') . '.csv';
    $searchLike = '%' . $search . '%';
    $whereClause = $search !== '' ? ' WHERE ' . getSearchWhereClause($view) : '';

    header('Content-Type: text/csv; charset=UTF-8');
    header('Content-Disposition: attachment; filename="' . $filename . '"');

    $output = fopen('php://output', 'w');
    fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

    if ($view === 'academic') {
        fputcsv($output, ['STT', 'Họ tên', 'Chuyên ngành', 'Khóa học', 'GPA', 'Tình trạng', 'Xếp loại']);

        $statement = $conn->prepare("
            SELECT
                s.ho_ten,
                COALESCE(a.chuyen_nganh, '') AS chuyen_nganh,
                COALESCE(a.khoa_hoc, '') AS khoa_hoc,
                COALESCE(a.gpa, '') AS gpa,
                COALESCE(a.tinh_trang, '') AS tinh_trang,
                COALESCE(a.xep_loai, '') AS xep_loai
            FROM students s
            LEFT JOIN student_academic a ON s.id = a.student_id
            $whereClause
            ORDER BY $sortColumn $sortOrder
        ");
        if ($search !== '') {
            $statement->bind_param(
                'ssssss',
                $searchLike,
                $searchLike,
                $searchLike,
                $searchLike,
                $searchLike,
                $searchLike
            );
        }
        $statement->execute();
        $result = $statement->get_result();

        $index = 1;
        while ($row = $result->fetch_assoc()) {
            fputcsv($output, [
                $index,
                $row['ho_ten'],
                $row['chuyen_nganh'],
                $row['khoa_hoc'],
                $row['gpa'],
                $row['tinh_trang'],
                $row['xep_loai'],
            ]);
            $index++;
        }
        $statement->close();
    } else {
        fputcsv($output, ['STT', 'Họ tên', 'Giới tính', 'Ngày sinh', 'Email', 'Địa chỉ']);

        $statement = $conn->prepare("
            SELECT *
            FROM students
            $whereClause
            ORDER BY $sortColumn $sortOrder
        ");
        if ($search !== '') {
            $statement->bind_param(
                'sssss',
                $searchLike,
                $searchLike,
                $searchLike,
                $searchLike,
                $searchLike
            );
        }
        $statement->execute();
        $result = $statement->get_result();

        $index = 1;
        while ($row = $result->fetch_assoc()) {
            $dateOfBirth = $row['ngay_sinh'] ? date('d/m/Y', strtotime($row['ngay_sinh'])) : '';
            fputcsv($output, [
                $index,
                $row['ho_ten'],
                $row['gioi_tinh'],
                $dateOfBirth,
                $row['email'],
                $row['dia_chi'],
            ]);
            $index++;
        }
        $statement->close();
    }

    fclose($output);
}
