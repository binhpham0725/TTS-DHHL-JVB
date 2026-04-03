<?php
session_start();
require_once "../../config/db.php";
require_once "../../reports/average.php";

$subject_id = (int)($_POST['subject_id'] ?? 0);
$class = trim($_POST['class'] ?? '');
$scoreData = $_POST['scores'] ?? [];

if ($subject_id <= 0) {
    header("Location: ../../interface/scores.php?msg=error_subject");
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

$attendanceWeight = (int)$subject['attendance_weight'];
$midtermWeight = (int)$subject['midterm_weight'];
$finalWeight = (int)$subject['final_weight'];

foreach ($scoreData as $student_id => $scores) {
    $student_id = (int)$student_id;

    $attendance = round((float)($scores['attendance_score'] ?? 0), 1);
    $midterm = round((float)($scores['midterm_score'] ?? 0), 1);
    $final = round((float)($scores['final_score'] ?? 0), 1);

    $attendance = max(0, min(10, $attendance));
    $midterm = max(0, min(10, $midterm));
    $final = max(0, min(10, $final));

    $total = calculateAverage(
        $attendance,
        $midterm,
        $final,
        $attendanceWeight,
        $midtermWeight,
        $finalWeight
    );

    $checkSql = "SELECT id FROM scores WHERE student_id = ? AND subject_id = ?";
    $checkStmt = mysqli_prepare($conn, $checkSql);
    mysqli_stmt_bind_param($checkStmt, "ii", $student_id, $subject_id);
    mysqli_stmt_execute($checkStmt);
    $checkResult = mysqli_stmt_get_result($checkStmt);

    if ($row = mysqli_fetch_assoc($checkResult)) {
        $score_id = (int)$row['id'];

        $updateSql = "UPDATE scores
                      SET attendance_score = ?, midterm_score = ?, final_score = ?, scores = ?
                      WHERE id = ?";
        $updateStmt = mysqli_prepare($conn, $updateSql);
        mysqli_stmt_bind_param($updateStmt, "ddddi", $attendance, $midterm, $final, $total, $score_id);
        mysqli_stmt_execute($updateStmt);
    } else {
        $insertSql = "INSERT INTO scores (student_id, subject_id, attendance_score, midterm_score, final_score, scores)
                      VALUES (?, ?, ?, ?, ?, ?)";
        $insertStmt = mysqli_prepare($conn, $insertSql);
        mysqli_stmt_bind_param($insertStmt, "iidddd", $student_id, $subject_id, $attendance, $midterm, $final, $total);
        mysqli_stmt_execute($insertStmt);
    }
}

header("Location: ../../interface/scores.php?subject_id={$subject_id}&class=" . urlencode($class) . "&msg=saved");
exit;