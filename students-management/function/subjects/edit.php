<?php
require_once __DIR__ . '/../../app/bootstrap.php';

(new SubjectController())->edit((int)($_GET['id'] ?? 0));
