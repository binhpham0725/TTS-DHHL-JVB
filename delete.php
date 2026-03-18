<?php
include "db.php";

$id = $_POST['id'];

/* xóa academic trước */
$conn->query("DELETE FROM student_academic WHERE student_id=$id");

/* xóa student */
$conn->query("DELETE FROM students WHERE id=$id");

echo "success";
?>