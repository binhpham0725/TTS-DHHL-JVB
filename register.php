<?php
include "db.php";

$name = $_POST["name"];
$gender = $_POST["gender"];
$age = $_POST["age"];
$email = $_POST["email"];
$address = $_POST["address"];

$sql = "INSERT INTO students(ho_ten,gioi_tinh,tuoi,email,dia_chi)
VALUES('$name','$gender','$age','$email','$address')";

if($conn->query($sql)){
    echo "success";
}else{
    echo "error";
}

?>