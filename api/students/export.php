<?php
require_once __DIR__ . '/../../services/StudentService.php';
/* export csv theo view hiện tại */
$view = (isset($_GET['view']) && $_GET['view'] === 'academic') ? 'academic' : 'personal';
streamStudentCsv($view);
