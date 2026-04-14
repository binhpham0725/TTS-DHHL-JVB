<?php
require_once __DIR__ . '/../../app/bootstrap.php';

$controller = new StudentController();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->update();
}

$controller->editForm((int)($_GET['id'] ?? 0));
