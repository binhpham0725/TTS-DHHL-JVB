<?php

// ✅ QUAN TRỌNG: thêm header
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

// KẾT NỐI DB
$conn = new mysqli("localhost", "root", "", "student_db");

if ($conn->connect_error) {
    echo json_encode(["status"=>"error", "message"=>"DB connection failed"]);
    exit();
}

// LẤY DATA
$data = json_decode(file_get_contents("php://input"), true);

// CHECK DATA
if(!$data){
    echo json_encode(["status"=>"error", "message"=>"No data received"]);
    exit();
}

$name = $data['name'];
$gender = $data['gender'];
$dob = $data['dob'];
$age = $data['age'];
$email = $data['email'];
$address = $data['address'];
$course = $data['course'];

// INSERT
$sql = "INSERT INTO students(name, gender, dob, age, email, address, course)
VALUES ('$name','$gender','$dob','$age','$email','$address','$course')";

if ($conn->query($sql) === TRUE) {
    echo json_encode(["status"=>"success"]);
} else {
    echo json_encode([
        "status"=>"error",
        "message"=>$conn->error
    ]);
}

$conn->close();
?>