<?php
session_start();
require_once "../../config/db.php";

$id = $_GET['id'] ?? '';

if ($id !== '') {
    $stmt = $conn->prepare("DELETE FROM students WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

header('Location: ../../interface/listsv.php');
exit;