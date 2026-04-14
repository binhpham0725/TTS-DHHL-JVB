<?php
require_once __DIR__ . '/../../app/bootstrap.php';

Session::start();
Auth::requireLogin();

$conn = app_db();
$subjectId = (int)($_POST['subject_id'] ?? 0);
$class = trim($_POST['class'] ?? '');

if ($subjectId <= 0) {
    header('Location: ../../interface/scores.php?msg=error_subject');
    exit;
}

if (!isset($_FILES['csv_file']) || $_FILES['csv_file']['error'] !== UPLOAD_ERR_OK) {
    header('Location: ../../interface/scores.php?subject_id=' . $subjectId . '&class=' . urlencode($class) . '&msg=error_file');
    exit;
}

$subject = (new SubjectModel($conn))->find($subjectId);
if ($subject === null) {
    header('Location: ../../interface/scores.php?msg=error_subject');
    exit;
}

$file = fopen($_FILES['csv_file']['tmp_name'], 'r');
if (!$file) {
    header('Location: ../../interface/scores.php?subject_id=' . $subjectId . '&class=' . urlencode($class) . '&msg=error_file');
    exit;
}

$firstRow = true;
$imported = 0;
$skipped = 0;

while (($data = fgetcsv($file, 1000, ',')) !== false) {
    if ($firstRow) {
        $firstRow = false;
        if (isset($data[0]) && trim(mb_strtolower($data[0])) === 'mã môn') {
            continue;
        }
    }

    if (count($data) < 7) {
        $skipped++;
        continue;
    }

    $mssv = trim($data[1] ?? '');
    $attendance = max(0, min(10, round((float)($data[4] ?? 0), 1)));
    $midterm = max(0, min(10, round((float)($data[5] ?? 0), 1)));
    $final = max(0, min(10, round((float)($data[6] ?? 0), 1)));

    $studentStmt = $conn->prepare('SELECT id, class FROM students WHERE mssv = ? LIMIT 1');
    $studentStmt->bind_param('s', $mssv);
    $studentStmt->execute();
    $studentRes = $studentStmt->get_result();
    $student = $studentRes->fetch_assoc();
    $studentStmt->close();

    if ($student === null) {
        $skipped++;
        continue;
    }

    if ($class !== '' && $student['class'] !== $class) {
        $skipped++;
        continue;
    }

    $studentId = (int)$student['id'];
    $total = calculateAverage(
        $attendance,
        $midterm,
        $final,
        (int)$subject['attendance_weight'],
        (int)$subject['midterm_weight'],
        (int)$subject['final_weight']
    );

    $checkStmt = $conn->prepare('SELECT id FROM scores WHERE student_id = ? AND subject_id = ?');
    $checkStmt->bind_param('ii', $studentId, $subjectId);
    $checkStmt->execute();
    $checkRes = $checkStmt->get_result();
    $existing = $checkRes->fetch_assoc();
    $checkStmt->close();

    if ($existing) {
        $scoreId = (int)$existing['id'];
        $stmt = $conn->prepare('UPDATE scores SET attendance_score = ?, midterm_score = ?, final_score = ?, scores = ? WHERE id = ?');
        $stmt->bind_param('ddddi', $attendance, $midterm, $final, $total, $scoreId);
    } else {
        $stmt = $conn->prepare(
            'INSERT INTO scores (student_id, subject_id, attendance_score, midterm_score, final_score, scores)
             VALUES (?, ?, ?, ?, ?, ?)'
        );
        $stmt->bind_param('iidddd', $studentId, $subjectId, $attendance, $midterm, $final, $total);
    }

    $stmt->execute();
    $stmt->close();
    $imported++;
}

fclose($file);

header('Location: ../../interface/scores.php?subject_id=' . $subjectId . '&class=' . urlencode($class) . '&msg=import_success&imported=' . $imported . '&skipped=' . $skipped);
exit;
