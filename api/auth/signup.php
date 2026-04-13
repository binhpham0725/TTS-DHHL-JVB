<?php
require_once __DIR__ . '/../../core/AuthService.php';
/* nhận dữ liệu đăng ký từ form */
$username = trim($_POST['username'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$birthday = $_POST['birthday'] ?? '';
$validationError = validateSignupData($username, $email, $password, $birthday);
if ($validationError) {
    echo $validationError;
    exit;
}
if (findUserByEmail($email)) {
    echo 'email_exists';
    exit;
}
echo createUserAccount($username, $email, $password, $birthday) ? 'success' : 'error';
