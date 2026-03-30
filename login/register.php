<?php
include "../database/db.php";

if($_SERVER["REQUEST_METHOD"]=="POST"){

    $name = $_POST['name'];
    $gender = $_POST['gender'];
    $dob = $_POST['dob'];
    $email = $_POST['email'];
    $address = $_POST['address'];

    /* insert student */
    $stmt = $conn->prepare("
        INSERT INTO students(ho_ten, gioi_tinh, ngay_sinh, email, dia_chi)
        VALUES (?, ?, ?, ?, ?)
    ");
    $stmt->bind_param("sssss", $name, $gender, $dob, $email, $address);

    if($stmt->execute()){

        $student_id = $conn->insert_id;
        $stmt->close();

        /* academic data */
        $major = $_POST['major'];
        $course = $_POST['course'];
        $gpa = $_POST['gpa'];
        $status = $_POST['status'];
        $rank = $_POST['rank'];

        /* insert academic */
        $stmt2 = $conn->prepare("
            INSERT INTO student_academic(student_id, chuyen_nganh, khoa_hoc, gpa, tinh_trang, xep_loai)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmt2->bind_param("isssss", $student_id, $major, $course, $gpa, $status, $rank);
        $stmt2->execute();
        $stmt2->close();

        echo "success";

    } else {
        echo "error";
        $stmt->close();
    }

}
?>