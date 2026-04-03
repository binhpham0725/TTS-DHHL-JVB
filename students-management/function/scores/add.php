<?php
session_start();
require_once "../../config/db.php";
require_once "../../reports/average.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../../interface/scores.php");
    exit;
}

$subject_id = (int)($_POST['subject_id'] ?? 0);
$class = trim($_POST['class'] ?? '');
$student_id = (int)($_POST['student_id'] ?? 0);

$attendance_score = round((float)($_POST['attendance_score'] ?? 0), 1);
$midterm_score = round((float)($_POST['midterm_score'] ?? 0), 1);
$final_score = round((float)($_POST['final_score'] ?? 0), 1);

$attendance_score = max(0, min(10, $attendance_score));
$midterm_score = max(0, min(10, $midterm_score));
$final_score = max(0, min(10, $final_score));

if ($subject_id <= 0 || $student_id <= 0) {
    header("Location: ../../interface/scores.php?subject_id={$subject_id}&class=" . urlencode($class) . "&msg=error_add");
    exit;
}

$subjectStmt = mysqli_prepare($conn, "SELECT attendance_weight, midterm_weight, final_weight FROM subject WHERE id = ?");
mysqli_stmt_bind_param($subjectStmt, "i", $subject_id);
mysqli_stmt_execute($subjectStmt);
$subjectRes = mysqli_stmt_get_result($subjectStmt);
$subject = mysqli_fetch_assoc($subjectRes);

if (!$subject) {
    header("Location: ../../interface/scores.php?msg=error_subject");
    exit;
}

$checkStmt = mysqli_prepare($conn, "SELECT id FROM scores WHERE student_id = ? AND subject_id = ?");
mysqli_stmt_bind_param($checkStmt, "ii", $student_id, $subject_id);
mysqli_stmt_execute($checkStmt);
$checkRes = mysqli_stmt_get_result($checkStmt);

if (mysqli_num_rows($checkRes) > 0) {
    header("Location: ../../interface/scores.php?subject_id={$subject_id}&class=" . urlencode($class) . "&msg=exists");
    exit;
}

$total = calculateAverage(
    $attendance_score,
    $midterm_score,
    $final_score,
    (int)$subject['attendance_weight'],
    (int)$subject['midterm_weight'],
    (int)$subject['final_weight']
);

$insertStmt = mysqli_prepare(
    $conn,
    "INSERT INTO scores (student_id, subject_id, attendance_score, midterm_score, final_score, scores)
     VALUES (?, ?, ?, ?, ?, ?)"
);
mysqli_stmt_bind_param(
    $insertStmt,
    "iidddd",
    $student_id,
    $subject_id,
    $attendance_score,
    $midterm_score,
    $final_score,
    $total
);
mysqli_stmt_execute($insertStmt);

header("Location: ../../interface/scores.php?subject_id={$subject_id}&class=" . urlencode($class) . "&msg=add_success");
exit;