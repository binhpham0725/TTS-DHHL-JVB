<?php
require_once __DIR__ . '/../../core/StudentService.php';
/* export csv theo view hiện tại */
$view = (isset($_GET['view']) && $_GET['view'] === 'academic') ? 'academic' : 'personal';
streamStudentCsv($view);
