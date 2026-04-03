<?php
session_start();
require_once "../../config/db.php";

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=students_export.csv');

$output = fopen('php://output', 'w');

fputcsv($output, ['mssv', 'fullname', 'birthday', 'phone', 'email', 'class', 'address']);

$result = $conn->query("SELECT mssv, fullname, birthday, phone, email, class, address FROM students");

while ($student = $result->fetch_assoc()) {
    fputcsv($output, [
        $student['mssv'],
        $student['fullname'],
        $student['birthday'],
        $student['phone'],
        $student['email'],
        $student['class'],
        $student['address']
    ]);
}

fclose($output);
exit;