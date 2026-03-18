<?php
include "db.php";

$result = $conn->query("SELECT COUNT(*) AS total FROM students");
echo $result->fetch_assoc()['total'];
?>