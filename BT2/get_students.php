<?php

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

$conn = new mysqli("localhost", "root", "", "student_db");

if ($conn->connect_error) {
    echo json_encode(["status"=>"error", "message"=>"DB lỗi"]);
    exit();
}

$sql = "SELECT * FROM students ORDER BY id DESC";

$result = $conn->query($sql);

$data = [];

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

echo json_encode($data);

$conn->close();

?>