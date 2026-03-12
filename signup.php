<?php

include "db.php";

$username = $_POST["username"];
$email = $_POST["email"];
$password = password_hash($_POST["password"],PASSWORD_DEFAULT);
$birthday = $_POST["birthday"];

$sql = "INSERT INTO users(username,email,password,birthday)
VALUES('$username','$email','$password','$birthday')";

if($conn->query($sql)){
echo "success";
}else{
echo "error";
}

?>