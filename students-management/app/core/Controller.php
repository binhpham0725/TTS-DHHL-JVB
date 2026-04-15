<?php

abstract class Controller
{
    protected mysqli $db;

    public function __construct()
    {
        $this->db = app_db();
    }

    protected function render(string $view, array $data = []): void
    {
        // Truyền APP_BASE vào mọi view để các liên kết nội bộ luôn đúng đường dẫn gốc.
        $data['appBase'] = APP_BASE;
        extract($data, EXTR_SKIP);
        require __DIR__ . '/../views/' . $view . '.php';
    }

    protected function redirect(string $url): never
    {
        header('Location: ' . $url);
        exit;
    }

    protected function json(array $payload, int $status = 200): never
    {
        // Dùng cho các endpoint AJAX hoặc API báo cáo trả về dữ liệu JSON.
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($payload, JSON_UNESCAPED_UNICODE);
        exit;
    }

    protected function reportException(Throwable $exception): void
    {
        error_log(sprintf(
            '[%s] %s in %s:%d',
            static::class,
            $exception->getMessage(),
            $exception->getFile(),
            $exception->getLine()
        ));
    }
}
