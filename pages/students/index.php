<?php
require_once __DIR__ . '/../../services/StudentService.php';
/* lấy trạng thái view, search và phân trang */
$view = (isset($_GET['view']) && $_GET['view'] === 'academic') ? 'academic' : 'personal';
$search = trim($_GET['search'] ?? '');
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$pageData = getStudentListViewData($view, $page, 10);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Danh sách sinh viên</title>
    <link rel="stylesheet" href="../../assets/css/students.css">
</head>
<body>
    <h2>Danh sách sinh viên</h2>
    <div class="student-count">
        <b>Tổng số sinh viên: <span id="studentCount">0</span></b>
    </div>
    <!-- thanh action chính -->
    <div class="top-bar">
        <div class="top-actions">
            <button type="button" onclick="openForm()">Đăng ký</button>
            <button type="button" onclick="logout()">Logout</button>
            <button type="button" onclick="exportCSV()">Export CSV</button>
            <input id="search" placeholder="Tìm kiếm..." value="<?= escapeValue($search) ?>">
            <select id="viewMode" onchange="changeView()">
                <option value="personal" <?= $view === 'personal' ? 'selected' : '' ?>>Thông tin cá nhân</option>
                <option value="academic" <?= $view === 'academic' ? 'selected' : '' ?>>Thông tin học tập</option>
            </select>
        </div>

        <div class="bulk-actions">
            <button id="bulkDeleteBtn" type="button" onclick="confirmBulkDelete()" style="display:none;">
                Xóa nhiều dữ liệu?
            </button>
        </div>
    </div>
    <!-- bảng sinh viên -->
    <?php require __DIR__ . '/../../components/student-table.php'; ?>
    <!-- phân trang -->
    <div class="pagination">
        <?php for ($pageNumber = 1; $pageNumber <= $pageData['total_pages']; $pageNumber++) { ?>
            <?php if ($pageNumber === $pageData['page']) { ?>
                <strong><?= $pageNumber ?></strong>
            <?php } else { ?>
                <a href="#" onclick="goPage(<?= $pageNumber ?>); return false;"><?= $pageNumber ?></a>
            <?php } ?>
        <?php } ?>
    </div>
    <!-- các phần giao diện tách riêng để page đỡ dài -->
    <?php require __DIR__ . '/../../components/student-toasts.php'; ?>
    <?php require __DIR__ . '/../../components/student-dialogs.php'; ?>
    <?php require __DIR__ . '/../../components/student-create-modal.php'; ?>
    <!-- config url để js gọi api và chuyển trang -->
    <script>
        window.studentPageConfig = {
            loginPageUrl: '../auth/login.php',
            editPageUrl: './edit.php',
            createApi: '../../api/students/create.php',
            deleteApi: '../../api/students/delete.php',
            countApi: '../../api/students/count.php',
            inlineUpdateApi: '../../api/students/inline-update.php',
            exportApi: '../../api/students/export.php'
        };
    </script>
    <!-- js xử lý tương tác của trang danh sách -->
    <script src="../../assets/js/students-page.js"></script>
</body>
</html>
