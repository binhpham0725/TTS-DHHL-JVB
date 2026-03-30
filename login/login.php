<?php

include "../database/db.php";

$email = $_POST["email"];
$password = $_POST["password"];

$stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();

$result = $stmt->get_result();

if($result->num_rows > 0){

$user = $result->fetch_assoc();

if(password_verify($password,$user["password"])){
echo "success";
}else{
echo "wrong_password";
}

}else{
echo "not_found";
}

$stmt->close();

?>