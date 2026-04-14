<?php
require_once __DIR__ . "/../../config/db.php";

function getAllSubjects($conn) {
    $subjects = [];
    $sql = "SELECT id, subject_code, subject_name FROM subject ORDER BY subject_name ASC";
    $result = mysqli_query($conn, $sql);

    while ($row = mysqli_fetch_assoc($result)) {
        $subjects[] = $row;
    }

    return $subjects;
}

function getAllClasses($conn) {
    $classes = [];
    $sql = "SELECT DISTINCT class FROM students WHERE class IS NOT NULL AND class != '' ORDER BY class ASC";
    $result = mysqli_query($conn, $sql);

    while ($row = mysqli_fetch_assoc($result)) {
        $classes[] = $row['class'];
    }

    return $classes;
}

function getSubjectInfo($conn, $subject_id) {
    $stmt = mysqli_prepare($conn, "SELECT * FROM subject WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $subject_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_assoc($result);
}

function getScoreRows($conn, $subject_id, $selected_class = '') {
    $rows = [];

    $sql = "
        SELECT
            st.id AS student_id,
            st.mssv,
            st.fullname,
            st.class,
            sc.id AS score_id,
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
    mysqli_stmt_bind_param($stmt, "iss", $subject_id, $selected_class, $selected_class);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }

    return $rows;
}

function getStudentsNotInSubject($conn, $subject_id, $selected_class = '') {
    $students = [];

    $sql = "
        SELECT st.id, st.mssv, st.fullname, st.class
        FROM students st
        WHERE st.id NOT IN (
            SELECT student_id FROM scores WHERE subject_id = ?
        )
        AND (? = '' OR st.class = ?)
        ORDER BY st.mssv ASC
    ";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "iss", $subject_id, $selected_class, $selected_class);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    while ($row = mysqli_fetch_assoc($result)) {
        $students[] = $row;
    }

    return $students;
}