<?php
require_once __DIR__ . '/../../core/StudentService.php';
/* lấy id sinh viên cần cập nhật */
$studentId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
/* dữ liệu cá nhân */
$studentData = [
    'name' => trim($_POST['name'] ?? ''),
    'gender' => trim($_POST['gender'] ?? ''),
    'dob' => trim($_POST['dob'] ?? ''),
    'email' => trim($_POST['email'] ?? ''),
    'address' => trim($_POST['address'] ?? ''),
];
/* dữ liệu học tập */
$academicData = [
    'major' => trim($_POST['major'] ?? ''),
    'course' => trim($_POST['course'] ?? ''),
    'gpa' => (float) ($_POST['gpa'] ?? 0),
    'status' => trim($_POST['status'] ?? ''),
    'rank' => trim($_POST['rank'] ?? ''),
];
$validationError = validateStudentData($studentData, $academicData);
if ($validationError) {
    header('Location: ../../pages/students/edit.php?id=' . $studentId . '&error=' . $validationError);
    exit;
}
$isUpdated = updateStudentRecord($studentId, $studentData, $academicData);
if (!$isUpdated) {
    header('Location: ../../pages/students/edit.php?id=' . $studentId . '&error=update_failed');
    exit;
}
/* redirect lại trang edit để tránh submit lặp */
header('Location: ../../pages/students/edit.php?id=' . $studentId . '&success=1');
exit;
