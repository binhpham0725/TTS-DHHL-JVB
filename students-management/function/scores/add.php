<?php
require_once __DIR__ . '/../../app/bootstrap.php';

Session::start();
Auth::requireLogin();

$conn = app_db();
$subjectId = (int)($_POST['subject_id'] ?? 0);
$class = trim($_POST['class'] ?? '');
$studentId = (int)($_POST['student_id'] ?? 0);

$attendance = max(0, min(10, round((float)($_POST['attendance_score'] ?? 0), 1)));
$midterm = max(0, min(10, round((float)($_POST['midterm_score'] ?? 0), 1)));
$final = max(0, min(10, round((float)($_POST['final_score'] ?? 0), 1)));

if ($subjectId <= 0 || $studentId <= 0) {
    header('Location: ../../interface/scores.php?subject_id=' . $subjectId . '&class=' . urlencode($class) . '&msg=error_add');
    exit;
}

$subject = (new SubjectModel($conn))->find($subjectId);
if ($subject === null) {
    header('Location: ../../interface/scores.php?msg=error_subject');
    exit;
}

$checkStmt = $conn->prepare('SELECT id FROM scores WHERE student_id = ? AND subject_id = ?');
$checkStmt->bind_param('ii', $studentId, $subjectId);
$checkStmt->execute();
$checkRes = $checkStmt->get_result();

if ($checkRes->fetch_assoc()) {
    header('Location: ../../interface/scores.php?subject_id=' . $subjectId . '&class=' . urlencode($class) . '&msg=exists');
    exit;
}

$total = calculateAverage(
    $attendance,
    $midterm,
    $final,
    (int)$subject['attendance_weight'],
    (int)$subject['midterm_weight'],
    (int)$subject['final_weight']
);

$insertStmt = $conn->prepare(
    'INSERT INTO scores (student_id, subject_id, attendance_score, midterm_score, final_score, scores)
     VALUES (?, ?, ?, ?, ?, ?)'
);
$insertStmt->bind_param('iidddd', $studentId, $subjectId, $attendance, $midterm, $final, $total);
$insertStmt->execute();

header('Location: ../../interface/scores.php?subject_id=' . $subjectId . '&class=' . urlencode($class) . '&msg=add_success');
exit;
