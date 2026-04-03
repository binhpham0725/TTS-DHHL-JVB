<?php
session_start();
require_once "../../config/db.php";

$id = (int)($_GET["id"] ?? 0);

if ($id <= 0) {
    header("Location: ../../interface/subjects.php");
    exit;
}

$checkSql = "SELECT id FROM subject WHERE id = ?";
$checkStmt = mysqli_prepare($conn, $checkSql);
mysqli_stmt_bind_param($checkStmt, "i", $id);
mysqli_stmt_execute($checkStmt);
$checkResult = mysqli_stmt_get_result($checkStmt);

if (mysqli_num_rows($checkResult) === 0) {
    header("Location: ../../interface/subjects.php?msg=not_found");
    exit;
}

$deleteSql = "DELETE FROM subject WHERE id = ?";
$deleteStmt = mysqli_prepare($conn, $deleteSql);
mysqli_stmt_bind_param($deleteStmt, "i", $id);

if (mysqli_stmt_execute($deleteStmt)) {
    header("Location: ../../interface/subjects.php?msg=del_success");
    exit;
} else {
    header("Location: ../../interface/subjects.php?msg=del_error");
    exit;
}