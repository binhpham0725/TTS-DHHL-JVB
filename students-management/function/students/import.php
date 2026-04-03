<?php
session_start();
require_once "../config/db.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['csv_file'])) {
    if ($_FILES['csv_file']['error'] !== UPLOAD_ERR_OK) {
        exit('Upload file thất bại.');
    }

    $tmpName = $_FILES['csv_file']['tmp_name'];
    $handle = fopen($tmpName, 'r');

    if (!$handle) {
        exit('Không thể đọc file CSV.');
    }

    $allowedCourses = ['D16CNTT', 'D17CNTT', 'D18CNTT'];
    $rowIndex = 0;

    while (($row = fgetcsv($handle, 1000, ',')) !== false) {
        $rowIndex++;

        if ($rowIndex === 1) {
            $firstCell = strtolower(trim($row[0] ?? ''));
            if ($firstCell === 'mssv') {
                continue;
            }
        }

        if (count($row) < 7) {
            continue;
        }

        $mssv = trim($row[0] ?? '');
        $fullname = trim($row[1] ?? '');
        $birthday = trim($row[2] ?? '');
        $phone = trim($row[3] ?? '');
        $email = trim($row[4] ?? '');
        $class = trim($row[5] ?? '');
        $address = trim($row[6] ?? '');

        if ($mssv === '' || $fullname === '' || $birthday === '' || $phone === '' || $email === '' || $class === '' || $address === '') {
            continue;
        }

        if (!in_array($class, $allowedCourses, true)) {
            continue;
        }

        $check = $conn->prepare("SELECT id FROM students WHERE mssv = ?");
        $check->bind_param("s", $mssv);
        $check->execute();
        $result = $check->get_result();

        if ($result->num_rows > 0) {
            $check->close();
            continue;
        }
        $check->close();

        $stmt = $conn->prepare("INSERT INTO students (mssv, fullname, birthday, phone, email, class, address) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $mssv, $fullname, $birthday, $phone, $email, $class, $address);
        $stmt->execute();
        $stmt->close();
    }

    fclose($handle);

    header('Location: ../../interface/listsv.php');
    exit;
}