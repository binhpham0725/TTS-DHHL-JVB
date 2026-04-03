<?php
session_start();
require_once "../../config/db.php";
if (!isset($_SESSION['students'])) {
    $_SESSION['students'] = [];
}

function isDuplicateMssv($mssv, $ignoreId = null) {
    foreach ($_SESSION['students'] as $student) {
        if ($student['mssv'] === $mssv) {
            if ($ignoreId === null || $student['id'] !== $ignoreId) {
                return true;
            }
        }
    }
    return false;
}

$mode = $_GET['mode'] ?? '';

if ($mode === 'manual' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $mssv = trim($_POST['mssv'] ?? '');
    $fullname = trim($_POST['fullname'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $birthday = trim($_POST['birthday'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $class = trim($_POST['class'] ?? '');
    $address = trim($_POST['address'] ?? '');

    $allowedCourses = ['D16CNTT', 'D17CNTT', 'D18CNTT'];

    if ($mssv === '' || $fullname === '' || $birthday === '' || $phone === '' || $email === '' || $class === '' || $address === '') {
        exit('Vui lòng nhập đầy đủ thông tin.');
    }

    if (!in_array($class, $allowedCourses, true)) {
        exit('Lớp không hợp lệ.');
    }

    if (isDuplicateMssv($mssv)) {
        exit('MSSV đã tồn tại.');
    }

    $_SESSION['students'][] = [
        'id' => uniqid('std_'),
        'mssv' => $mssv,
        'fullname' => $fullname,
        'birthday' => $birthday,
        'phone' => $phone,
        'email' => $email,
        'class' => $class,
        'address' => $address
    ];

    header('Location: ../../interface/listsv.php');
    exit;
}

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

        if (count($row) < 6) {
            continue;
        }

        $mssv = trim($row[0] ?? '');
        $fullname = trim($row[1] ?? '');
        $birhtday = trim($row[2] ?? '');
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

        if (isDuplicateMssv($mssv)) {
            continue;
        }

        $_SESSION['students'][] = [
            'id' => uniqid('std_'),
            'mssv' => $mssv,
            'fullname' => $fullname,
            'birthday' => $birthday,
            'phone' => $phone,
            'email' => $email,
            'class' => $class,
            'address' => $address
        ];
    }

    fclose($handle);

    header('Location: ../../interface/listsv.php');
    exit;
}

header('Location: ../../interface/listsv.php');
exit;
?>

