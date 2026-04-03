<?php
session_start();
require_once "../../config/db.php";

$subject_id = (int)($_GET['subject_id'] ?? 0);
$class = trim($_GET['class'] ?? '');

if ($subject_id <= 0) {
    header("Location: ../../interface/scores.php?msg=error_subject");
    exit;
}

$sql = "
    SELECT
        st.mssv,
        st.fullname,
        st.class,
        COALESCE(sc.attendance_score, 0) AS attendance_score,
        COALESCE(sc.midterm_score, 0) AS midterm_score,
        COALESCE(sc.final_score, 0) AS final_score,
        COALESCE(sc.scores, 0) AS total_score
    FROM students st
    LEFT JOIN scores sc
        ON sc.student_id = st.id
        AND sc.subject_id = ?
    WHERE (? = '' OR st.class = ?)
    ORDER BY st.mssv ASC
";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "iss", $subject_id, $class, $class);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$filename = "scores_subject_" . $subject_id . "_" . date("Ymd_His") . ".csv";

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=' . $filename);

$output = fopen('php://output', 'w');
fputs($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

fputcsv($output, ['MSSV', 'Họ và tên', 'Lớp', 'Chuyên cần', 'Giữa kỳ', 'Cuối kỳ', 'Trung bình']);

while ($row = mysqli_fetch_assoc($result)) {
    fputcsv($output, [
        $row['mssv'],
        $row['fullname'],
        $row['class'],
        $row['attendance_score'],
        $row['midterm_score'],
        $row['final_score'],
        $row['total_score']
    ]);
}

fclose($output);
exit;