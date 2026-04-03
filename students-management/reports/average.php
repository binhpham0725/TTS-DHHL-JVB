<?php

if (!function_exists('calculateAverage')) {
    function calculateAverage($attendance, $midterm, $final, $attendanceWeight, $midtermWeight, $finalWeight) {
        $attendance = max(0, min(10, (float)$attendance));
        $midterm = max(0, min(10, (float)$midterm));
        $final = max(0, min(10, (float)$final));

        $average =
            ($attendance * $attendanceWeight / 100) +
            ($midterm * $midtermWeight / 100) +
            ($final * $finalWeight / 100);

        return round($average, 2);
    }
}

if (!function_exists('getResultStatus')) {
    function getResultStatus($average) {
        return $average >= 5 ? 'Đạt' : 'Thi lại';
    }
}

if (!function_exists('getStatusClass')) {
    function getStatusClass($average) {
        return $average >= 5 ? 'dat' : 'thilai';
    }
}

if (!function_exists('getRank')) {
    function getRank($average) {
        if ($average >= 9) return 'Xuất sắc';
        if ($average >= 8) return 'Giỏi';
        if ($average >= 6.5) return 'Khá';
        if ($average >= 5) return 'Trung bình';
        return 'Yếu';
    }
}