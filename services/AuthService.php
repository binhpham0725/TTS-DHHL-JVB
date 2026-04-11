<?php
require_once __DIR__ . '/../config/database.php';
/* validate dữ liệu signup trước khi tạo tài khoản */
function validateSignupData(string $username, string $email, string $password, string $birthday): ?string
{
    if ($username === '') {
        return 'missing_username';
    }
    if ($email === '') {
        return 'missing_email';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return 'invalid_email';
    }
    if ($birthday === '') {
        return 'missing_birthday';
    }
    if ($password === '') {
        return 'missing_password';
    }
    if (strlen($password) < 8) {
        return 'weak_password';
    }
    return null;
}
/* lấy user theo email để phục vụ login và check trùng email */
function findUserByEmail(string $email): ?array
{
    global $conn;
    $statement = $conn->prepare('SELECT * FROM users WHERE email = ?');
    $statement->bind_param('s', $email);
    $statement->execute();
    $result = $statement->get_result();
    $user = $result->num_rows > 0 ? $result->fetch_assoc() : null;
    $statement->close();
    return $user;
}
/* tạo tài khoản mới cho user */
function createUserAccount(string $username, string $email, string $password, string $birthday): bool
{
    global $conn;
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
    $statement = $conn->prepare(
        'INSERT INTO users(username, email, password, birthday) VALUES (?, ?, ?, ?)'
    );
    $statement->bind_param('ssss', $username, $email, $passwordHash, $birthday);
    $isCreated = $statement->execute();
    $statement->close();
    return $isCreated;
}
