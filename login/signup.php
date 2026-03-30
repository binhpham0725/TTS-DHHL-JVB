<?php
include "../database/db.php";

$username = $_POST["username"];
$email = $_POST["email"];
$password = password_hash($_POST["password"], PASSWORD_DEFAULT);
$birthday = $_POST["birthday"];

$stmt = $conn->prepare("INSERT INTO users(username, email, password, birthday) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $username, $email, $password, $birthday);

if($stmt->execute()){
    echo "success";
} else {
    echo "error";
}

$stmt->close();
?>