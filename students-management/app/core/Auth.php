<?php

class Auth
{
    public static function check(): bool
    {
        return Session::has('teacher_id');
    }

    public static function requireLogin(): void
    {
        if (!self::check()) {
            header('Location: ' . APP_BASE . '/auth/login.php');
            exit;
        }
    }

    public static function login(array $teacher): void
    {
        Session::set('teacher_id', $teacher['id']);
        Session::set('teacher_name', $teacher['name']);
        Session::set('teacher_email', $teacher['email']);
    }

    public static function logout(): void
    {
        Session::destroy();
    }
}
