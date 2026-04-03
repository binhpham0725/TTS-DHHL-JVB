<?php
session_start();
require_once "../../config/db.php";
require_once "../../reports/average.php";

$subject_id = (int)($_POST['subject_id'] ?? 0);
$class = trim($_POST['class'] ?? '');

if ($subject_id <= 0) {
    header("Location: ../../interface/scores.php?msg=error_subject");
    exit;
}

if (!isset($_FILES['csv_file']) || $_FILES['csv_file']['error'] !== 0) {
    header("Location: ../../interface/scores.php?subject_id={$subject_id}&class=" . urlencode($class) . "&msg=error_file");
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

$file = fopen($_FILES['csv_file']['tmp_name'], 'r');
if (!$file) {
    header("Location: ../../interface/scores.php?subject_id={$subject_id}&class=" . urlencode($class) . "&msg=error_file");
    exit;
}

$firstRow = true;
$imported = 0;
$skipped = 0;

while (($data = fgetcsv($file, 1000, ",")) !== false) {
    if ($firstRow) {
        $firstRow = false;
        if (isset($data[0]) && mb_strtolower(trim($data[0]), 'UTF-8') === 'mssv') {
            continue;
        }
    }

    if (count($data) < 4) {
        $skipped++;
        continue;
    }

    $mssv = trim($data[0]);
    $attendance = round((float)($data[1] ?? 0), 1);
    $midterm = round((float)($data[2] ?? 0), 1);
    $final = round((float)($data[3] ?? 0), 1);

    $attendance = max(0, min(10, $attendance));
    $midterm = max(0, min(10, $midterm));
    $final = max(0, min(10, $final));

    $studentStmt = mysqli_prepare($conn, "SELECT id, class FROM students WHERE mssv = ?");
    mysqli_stmt_bind_param($studentStmt, "s", $mssv);
    mysqli_stmt_execute($studentStmt);
    $studentRes = mysqli_stmt_get_result($studentStmt);
    $student = mysqli_fetch_assoc($studentRes);

    if (!$student) {
        $skipped++;
        continue;
    }

    if ($class !== '' && $student['class'] !== $class) {
        $skipped++;
        continue;
    }

    $student_id = (int)$student['id'];

    $total = calculateAverage(
        $attendance,
        $midterm,
        $final,
        (int)$subject['attendance_weight'],
        (int)$subject['midterm_weight'],
        (int)$subject['final_weight']
    );

    $checkStmt = mysqli_prepare($conn, "SELECT id FROM scores WHERE student_id = ? AND subject_id = ?");
    mysqli_stmt_bind_param($checkStmt, "ii", $student_id, $subject_id);
    mysqli_stmt_execute($checkStmt);
    $checkRes = mysqli_stmt_get_result($checkStmt);

    if ($row = mysqli_fetch_assoc($checkRes)) {
        $score_id = (int)$row['id'];

        $updateStmt = mysqli_prepare(
            $conn,
            "UPDATE scores
             SET attendance_score = ?, midterm_score = ?, final_score = ?, scores = ?
             WHERE id = ?"
        );
        mysqli_stmt_bind_param($updateStmt, "ddddi", $attendance, $midterm, $final, $total, $score_id);
        mysqli_stmt_execute($updateStmt);
    } else {
        $insertStmt = mysqli_prepare(
            $conn,
            "INSERT INTO scores (student_id, subject_id, attendance_score, midterm_score, final_score, scores)
             VALUES (?, ?, ?, ?, ?, ?)"
        );
        mysqli_stmt_bind_param($insertStmt, "iidddd", $student_id, $subject_id, $attendance, $midterm, $final, $total);
        mysqli_stmt_execute($insertStmt);
    }

    $imported++;
}

fclose($file);

header("Location: ../../interface/scores.php?subject_id={$subject_id}&class=" . urlencode($class) . "&msg=import_success&imported={$imported}&skipped={$skipped}");
exit;