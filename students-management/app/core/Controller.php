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
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($payload, JSON_UNESCAPED_UNICODE);
        exit;
    }
}
