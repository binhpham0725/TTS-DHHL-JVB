<?php
require_once __DIR__ . '/../../core/StudentService.php';
/* lấy id sinh viên cần cập nhật */
$studentId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
/* dữ liệu cá nhân */
$studentData = [
    'name' => normalizeTextInput($_POST['name'] ?? ''),
    'gender' => trim($_POST['gender'] ?? ''),
    'dob' => trim($_POST['dob'] ?? ''),
    'email' => trim($_POST['email'] ?? ''),
    'address' => normalizeTextInput($_POST['address'] ?? ''),
];
/* dữ liệu học tập */
$academicData = [
    'major' => normalizeTextInput($_POST['major'] ?? ''),
    'course' => normalizeTextInput($_POST['course'] ?? ''),
    'gpa' => trim($_POST['gpa'] ?? ''),
    'status' => trim($_POST['status'] ?? ''),
    'rank' => trim($_POST['rank'] ?? ''),
];
/* validate dữ liệu trước khi update để tránh redirect success sai */
$validationError = validateStudentData($studentData, $academicData);
if ($validationError) {
    header('Location: ../../pages/students/edit.php?id=' . $studentId . '&error=' . $validationError);
    exit;
}
/* email sinh viên không được trùng với record khác */
if (studentEmailExists($studentData['email'], $studentId)) {
    header('Location: ../../pages/students/edit.php?id=' . $studentId . '&error=duplicate_email');
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
