<?php
require_once __DIR__ . '/../../core/StudentService.php';
header('Content-Type: application/json; charset=UTF-8');
/* nhận payload sửa nhanh từ bảng */
$studentId = isset($_POST['id']) ? (int) $_POST['id'] : 0;
$view = (isset($_POST['view']) && $_POST['view'] === 'academic') ? 'academic' : 'personal';
$payload = json_decode($_POST['payload'] ?? '{}', true);
if (!is_array($payload)) {
    echo json_encode(['status' => 'error', 'message' => 'Dữ liệu không hợp lệ'], JSON_UNESCAPED_UNICODE);
    exit;
}
echo json_encode(handleInlineStudentUpdate($studentId, $view, $payload), JSON_UNESCAPED_UNICODE);
