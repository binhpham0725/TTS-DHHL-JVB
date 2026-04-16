<?php

function getResultStatus($avg)
{
    return $avg >= 5 ? app_text('helpers.result.pass') : app_text('helpers.result.retake');
}

function getStatusClass($avg)
{
    if ($avg >= 8) {
        return 'badge-good';
    }

    if ($avg >= 5) {
        return 'badge-avg';
    }

    return 'badge-bad';
}

function getRank($avg)
{
    if ($avg >= 9) {
        return app_text('helpers.rank.excellent');
    }

    if ($avg >= 8) {
        return app_text('helpers.rank.good');
    }

    if ($avg >= 6.5) {
        return app_text('helpers.rank.fair');
    }

    if ($avg >= 5) {
        return app_text('helpers.rank.average');
    }

    return app_text('helpers.rank.weak');
}

function getRankClass($avg)
{
    if ($avg >= 9) {
        return 'rank-excellent';
    }

    if ($avg >= 8) {
        return 'rank-good';
    }

    if ($avg >= 6.5) {
        return 'rank-fair';
    }

    if ($avg >= 5) {
        return 'rank-average';
    }

    return 'rank-weak';
}

function calculateAverage($attendance, $midterm, $final, $attendanceWeight = 10, $midtermWeight = 30, $finalWeight = 60)
{
    // Chuẩn hóa điểm đầu vào về khoảng 0-10 trước khi áp dụng trọng số.
    $attendance = max(0, min(10, (float)$attendance));
    $midterm = max(0, min(10, (float)$midterm));
    $final = max(0, min(10, (float)$final));

    return round(
        ($attendance * $attendanceWeight / 100) +
        ($midterm * $midtermWeight / 100) +
        ($final * $finalWeight / 100),
        2
    );
}

function buildQuery($extra = [])
{
    // Giữ lại toàn bộ bộ lọc hiện tại khi đổi trang, export file hoặc quay lại danh sách.
    $query = array_merge($_GET, $extra);
    return http_build_query(array_filter($query, fn($value) => $value !== ''));
}

function getClassFromMssv($mssv)
{
    // Quy ước nội bộ: 4 số đầu của MSSV dùng để suy ra khóa/lớp mặc định.
    $yearMap = [
        '2023' => 'D16CNTT',
        '2024' => 'D17CNTT',
        '2025' => 'D18CNTT',
    ];

    $year = substr($mssv, 0, 4);
    return $yearMap[$year] ?? null;
}
