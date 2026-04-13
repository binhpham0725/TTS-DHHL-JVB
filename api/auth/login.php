<?php
require_once __DIR__ . '/../../core/AuthService.php';
/* nhận dữ liệu đăng nhập từ form */
$email = trim($_POST['email'] ?? '');
$password = trim($_POST['password'] ?? '');
$user = findUserByEmail($email);
if (!$user) {
    echo 'not_found';
    exit;
}
echo password_verify($password, $user['password']) ? 'success' : 'wrong_password';
