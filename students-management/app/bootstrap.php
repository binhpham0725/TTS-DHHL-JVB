<?php

// Bootstrap trung tâm: nạp kết nối DB, helper, text cấu hình và các lớp nền cho toàn bộ ứng dụng.
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../helpers/functions.php';

if (!defined('APP_BASE')) {
    define('APP_BASE', '/' . basename(dirname(__DIR__)));
}

require_once __DIR__ . '/core/Controller.php';
require_once __DIR__ . '/core/Session.php';
require_once __DIR__ . '/core/Auth.php';

spl_autoload_register(function (string $class): void {
    $directories = [
        __DIR__ . '/core/',
        __DIR__ . '/models/',
        __DIR__ . '/controllers/',
    ];

    foreach ($directories as $directory) {
        $file = $directory . $class . '.php';
        if (is_file($file)) {
            require_once $file;
            return;
        }
    }
});

function app_db(): mysqli
{
    // Trả về kết nối mysqli đã được khởi tạo sẵn trong file cấu hình DB.
    global $conn;
    return $conn;
}

function app_url(string $path = ''): string
{
    // Ghép APP_BASE với đường dẫn tương đối để tạo URL nội bộ thống nhất.
    $path = ltrim($path, '/');
    return APP_BASE . ($path !== '' ? '/' . $path : '');
}

function app_messages(): array
{
    static $messages = null;

    if ($messages === null) {
        $messages = require __DIR__ . '/../config/messages.php';
    }

    return $messages;
}

function app_text(string $key, array $replace = []): string
{
    $value = app_messages();

    foreach (explode('.', $key) as $segment) {
        if (!is_array($value) || !array_key_exists($segment, $value)) {
            return $key;
        }

        $value = $value[$segment];
    }

    if (!is_string($value)) {
        return $key;
    }

    foreach ($replace as $placeholder => $replacement) {
        $value = str_replace(':' . $placeholder, (string)$replacement, $value);
    }

    return $value;
}

function app_text_group(string $key): array
{
    $value = app_messages();

    foreach (explode('.', $key) as $segment) {
        if (!is_array($value) || !array_key_exists($segment, $value)) {
            return [];
        }

        $value = $value[$segment];
    }

    return is_array($value) ? $value : [];
}
