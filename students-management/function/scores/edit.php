<?php
require_once __DIR__ . '/../../app/bootstrap.php';

Session::start();
Auth::requireLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . app_url('interface/scores.php'));
    exit;
}

$conn = app_db();
$id = (int)($_POST['id'] ?? 0);
$subjectId = (int)($_POST['subject_id'] ?? 0);
$class = trim($_POST['class'] ?? '');

$attendance = max(0, min(10, round((float)($_POST['attendance_score'] ?? 0), 1)));
$midterm = max(0, min(10, round((float)($_POST['midterm_score'] ?? 0), 1)));
$final = max(0, min(10, round((float)($_POST['final_score'] ?? 0), 1)));

if ($id <= 0 || $subjectId <= 0) {
    header('Location: ' . app_url('interface/scores.php?msg=error_edit'));
    exit;
}

$subject = (new SubjectModel($conn))->find($subjectId);
if ($subject === null) {
    header('Location: ' . app_url('interface/scores.php?msg=error_subject'));
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

$stmt = $conn->prepare(
    'UPDATE scores SET attendance_score = ?, midterm_score = ?, final_score = ?, scores = ? WHERE id = ?'
);
$stmt->bind_param('ddddi', $attendance, $midterm, $final, $total, $id);
$stmt->execute();
$stmt->close();

header('Location: ' . app_url('interface/scores.php?subject_id=' . $subjectId . '&class=' . urlencode($class) . '&msg=edit_success'));
exit;
