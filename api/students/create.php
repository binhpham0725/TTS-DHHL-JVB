<?php
require_once __DIR__ . '/../../services/StudentService.php';
/* gom dữ liệu cá nhân */
$studentData = [
    'name' => trim($_POST['name'] ?? ''),
    'gender' => trim($_POST['gender'] ?? ''),
    'dob' => trim($_POST['dob'] ?? ''),
    'email' => trim($_POST['email'] ?? ''),
    'address' => trim($_POST['address'] ?? ''),
];
/* gom dữ liệu học tập */
$academicData = [
    'major' => trim($_POST['major'] ?? ''),
    'course' => trim($_POST['course'] ?? ''),
    'gpa' => (float) ($_POST['gpa'] ?? 0),
    'status' => trim($_POST['status'] ?? ''),
    'rank' => trim($_POST['rank'] ?? ''),
];
$validationError = validateStudentData($studentData, $academicData);
if ($validationError) {
    echo $validationError;
    exit;
}
echo createStudentRecord($studentData, $academicData) ? 'success' : 'error';
