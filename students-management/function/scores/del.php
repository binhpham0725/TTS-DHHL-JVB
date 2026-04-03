<?php
session_start();
require_once "../../config/db.php";

$id = (int)($_GET['id'] ?? 0);
$subject_id = (int)($_GET['subject_id'] ?? 0);
$class = trim($_GET['class'] ?? '');

if ($id > 0) {
    $stmt = mysqli_prepare($conn, "DELETE FROM scores WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
}

header("Location: ../../interface/scores.php?subject_id={$subject_id}&class=" . urlencode($class) . "&msg=del_success");
exit;