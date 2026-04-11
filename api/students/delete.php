<?php
require_once __DIR__ . '/../../services/StudentService.php';
/* xóa sinh viên theo id */
$studentId = isset($_POST['id']) ? (int) $_POST['id'] : 0;
echo deleteStudentRecord($studentId) ? 'success' : 'error';
