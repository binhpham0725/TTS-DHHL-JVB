<?php
include "db.php";

if($_SERVER["REQUEST_METHOD"]=="POST"){

    $name = $_POST['name'];
    $gender = $_POST['gender'];
    $dob = $_POST['dob'];
    $email = $_POST['email'];
    $address = $_POST['address'];

    /* insert student */
    $sql = "INSERT INTO students(ho_ten, gioi_tinh, ngay_sinh, email, dia_chi)
            VALUES('$name','$gender','$dob','$email','$address')";

    if($conn->query($sql)){

        $student_id = $conn->insert_id;

        /* academic data */
        $major = $_POST['major'];
        $course = $_POST['course']; // new field
        $gpa = $_POST['gpa'];
        $status = $_POST['status'];
        $rank = $_POST['rank'];

        /* insert academic including khoa_hoc */
        $conn->query("
            INSERT INTO student_academic(student_id, chuyen_nganh, khoa_hoc, gpa, tinh_trang, xep_loai)
            VALUES('$student_id','$major','$course','$gpa','$status','$rank')
        ");

        echo "success";

    } else {
        echo "error";
    }

}
?>