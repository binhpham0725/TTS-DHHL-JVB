<?php
include "db.php";

$id = $_POST['id'];

/* xóa academic trước */
$stmt1 = $conn->prepare("DELETE FROM student_academic WHERE student_id = ?");
$stmt1->bind_param("i", $id);
$stmt1->execute();
$stmt1->close();

/* xóa student */
$stmt2 = $conn->prepare("DELETE FROM students WHERE id = ?");
$stmt2->bind_param("i", $id);
$stmt2->execute();
$stmt2->close();

echo "success";
?>