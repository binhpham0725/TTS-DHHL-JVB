<?php
require_once __DIR__ . '/../../app/bootstrap.php';

(new ScoreController())->delete((int)($_GET['id'] ?? 0));
