<?php

class Session
{
    public static function start(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function set(string $key, mixed $value): void
    {
        self::start();
        $_SESSION[$key] = $value;
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        self::start();
        return $_SESSION[$key] ?? $default;
    }

    public static function has(string $key): bool
    {
        self::start();
        return isset($_SESSION[$key]);
    }

    public static function flash(string $key, mixed $default = null): mixed
    {
        self::start();

        // Flash chỉ được đọc một lần rồi xóa, phù hợp cho thông báo sau khi redirect.
        if (!isset($_SESSION[$key])) {
            return $default;
        }

        $value = $_SESSION[$key];
        unset($_SESSION[$key]);
        return $value;
    }

    public static function destroy(): void
    {
        self::start();
        $_SESSION = [];

        // Xóa cả dữ liệu session trong bộ nhớ lẫn cookie để đăng xuất triệt để.
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }

        session_destroy();
    }
}
