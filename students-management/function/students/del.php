<?php
require_once __DIR__ . '/../../app/bootstrap.php';

(new StudentController())->delete((int)($_GET['id'] ?? 0));
