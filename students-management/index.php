<?php
require_once __DIR__ . '/app/bootstrap.php';

Session::start();

if (Auth::check()) {
    header('Location: ' . APP_BASE . '/interface/index.php');
    exit;
}

header('Location: ' . APP_BASE . '/auth/login.php');
exit;
