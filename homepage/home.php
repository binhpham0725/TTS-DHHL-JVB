<?php
include "../database/db.php";

$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$start = ($page - 1) * $limit;

$limit = (int)$limit;
$start = (int)$start;

$view = isset($_GET['view']) ? $_GET['view'] : "personal";

if ($view == "academic") {
    $allowed = [
        "ho_ten" => "s.ho_ten",
        "chuyen_nganh" => "a.chuyen_nganh",
        "khoa_hoc" => "a.khoa_hoc",
        "gpa" => "a.gpa",
        "tinh_trang" => "a.tinh_trang",
        "xep_loai" => "a.xep_loai"
    ];

    $sortKey = $_GET['sort'] ?? "ho_ten";
    $sort = isset($allowed[$sortKey]) ? $allowed[$sortKey] : "s.id";
} else {
    $allowed = ["ho_ten", "gioi_tinh", "ngay_sinh", "email", "dia_chi"];
    $sort = isset($_GET['sort']) && in_array($_GET['sort'], $allowed) ? $_GET['sort'] : "id";
}

$order = isset($_GET['order']) && $_GET['order'] == "desc" ? "DESC" : "ASC";

$totalResult = $conn->query("SELECT COUNT(*) as total FROM students");
$total = $totalResult->fetch_assoc()['total'];
$totalPages = ceil($total / $limit);

if ($view == "academic") {
    $result = $conn->query("
        SELECT 
            s.id, 
            s.ho_ten, 
            COALESCE(a.chuyen_nganh,'') as chuyen_nganh,
            COALESCE(a.khoa_hoc,'') as khoa_hoc,
            COALESCE(a.gpa,'') as gpa,
            COALESCE(a.tinh_trang,'') as tinh_trang,
            COALESCE(a.xep_loai,'') as xep_loai
        FROM students s
        LEFT JOIN student_academic a ON s.id = a.student_id
        ORDER BY $sort $order
        LIMIT $start, $limit
    ");
} else {
    $result = $conn->query("
        SELECT * FROM students
        ORDER BY $sort $order
        LIMIT $start, $limit
    ");
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Homepage</title>
    <link rel="stylesheet" href="home.css">
</head>
<body>

<h2>Danh sách sinh viên</h2>
<div class="student-count">
    <b>Tổng số sinh viên: <span id="studentCount">0</span></b>
</div>

<div class="top-bar">
    <div class="top-actions">
        <button onclick="openForm()">Đăng ký</button>
        <button onclick="logout()">Logout</button>
        <input id="search" placeholder="Tìm kiếm..." onkeyup="liveSearch()">
        <select id="viewMode" onchange="changeView()">
            <option value="personal" <?= ($view == "personal") ? "selected" : "" ?>>Thông tin cá nhân</option>
            <option value="academic" <?= ($view == "academic") ? "selected" : "" ?>>Thông tin học tập</option>
        </select>
    </div>

    <div class="bulk-actions">
        <button id="bulkDeleteBtn" onclick="confirmBulkDelete()" style="display:none;">
            Xóa nhiều dữ liệu?
        </button>
    </div>
</div>

<table>
    <thead>
        <?php if ($view == "academic") { ?>
            <tr>
                <th>STT</th>
                <th onclick="sortTable('ho_ten')">Họ tên</th>
                <th onclick="sortTable('chuyen_nganh')">Chuyên ngành</th>
                <th onclick="sortTable('khoa_hoc')">Khóa học</th>
                <th onclick="sortTable('gpa')">GPA</th>
                <th onclick="sortTable('tinh_trang')">Tình trạng</th>
                <th onclick="sortTable('xep_loai')">Xếp loại</th>
                <th class="action-col">Thao tác</th>
            </tr>
        <?php } else { ?>
            <tr>
                <th>STT</th>
                <th onclick="sortTable('ho_ten')">Họ tên</th>
                <th onclick="sortTable('gioi_tinh')">Giới tính</th>
                <th onclick="sortTable('ngay_sinh')">Ngày sinh</th>
                <th onclick="sortTable('email')">Email</th>
                <th onclick="sortTable('dia_chi')">Địa chỉ</th>
                <th class="action-col">Thao tác</th>
            </tr>
        <?php } ?>
    </thead>
    <tbody id="tableBody">
        <?php
        $stt = $start + 1;
        while ($row = $result->fetch_assoc()) {
            if ($view == "academic") {
                echo "<tr>
                    <td>{$stt}</td>
                    <td>{$row['ho_ten']}</td>
                    <td>{$row['chuyen_nganh']}</td>
                    <td>{$row['khoa_hoc']}</td>
                    <td>{$row['gpa']}</td>
                    <td>{$row['tinh_trang']}</td>
                    <td>{$row['xep_loai']}</td>
                    <td class='action-cell'>
                        <button onclick='confirmEdit({$row['id']})'>Sửa</button>
                        <button onclick='confirmDelete({$row['id']})'>Xóa</button>
                        <input type='checkbox' class='row-check' value='{$row['id']}' onchange='toggleBulkDeleteButton()'>
                    </td>
                </tr>";
            } else {
                $dob = $row['ngay_sinh'] ? date("d/m/Y", strtotime($row['ngay_sinh'])) : '';
                echo "<tr>
                    <td>{$stt}</td>
                    <td>{$row['ho_ten']}</td>
                    <td>{$row['gioi_tinh']}</td>
                    <td>{$dob}</td>
                    <td>{$row['email']}</td>
                    <td>{$row['dia_chi']}</td>
                    <td class='action-cell'>
                        <button onclick='confirmEdit({$row['id']})'>Sửa</button>
                        <button onclick='confirmDelete({$row['id']})'>Xóa</button>
                        <input type='checkbox' class='row-check' value='{$row['id']}' onchange='toggleBulkDeleteButton()'>
                    </td>
                </tr>";
            }
            $stt++;
        }
        ?>
    </tbody>
</table>

<div class="pagination">
    <?php
    for ($i = 1; $i <= $totalPages; $i++) {
        if ($i == $page) {
            echo "<strong>$i</strong> ";
        } else {
            echo "<a href='#' onclick='goPage($i)'>$i</a> ";
        }
    }
    ?>
</div>

<!-- TOASTS -->
<div id="logoutToast" class="toast">
    <span>✔ Logout thành công</span>
    <div class="toastBar green" id="logoutBar"></div>
</div>

<div id="deleteToast" class="toast">
    <span>✔ Đã xóa</span>
    <div class="toastBar red" id="deleteBar"></div>
</div>

<div id="loadingToast" class="toast">
    <span id="loadingText">⏳ Đang xử lý...</span>
    <div class="toastBar blue" id="loadingBar"></div>
</div>

<div id="pageToast" class="toast">
    <span id="pageText">⏳ Đang lấy dữ liệu trang...</span>
    <div class="toastBar purple" id="pageBar"></div>
</div>

<div id="registerToast" class="toast">
    <span>✔ Đăng ký thành công</span>
    <div class="toastBar green" id="registerBar"></div>
</div>

<!-- DELETE DIALOG -->
<div id="deleteOverlay">
    <div id="deleteDialog">
        <div id="deleteHeader">Xóa</div>
        <div id="deleteContent">Xác nhận xóa thông tin sinh viên?</div>
        <div id="deleteActions">
            <button id="confirmDeleteBtn">Xóa</button>
            <button id="cancelDeleteBtn">Hủy</button>
        </div>
    </div>
</div>

<!-- BULK DELETE DIALOG -->
<div id="bulkDeleteOverlay">
    <div id="bulkDeleteDialog">
        <div id="bulkDeleteHeader">Xóa nhiều</div>
        <div id="bulkDeleteContent">Xác nhận xóa các sinh viên đã chọn?</div>
        <div id="bulkDeleteActions">
            <button id="confirmBulkDeleteBtn">Xóa</button>
            <button id="cancelBulkDeleteBtn">Hủy</button>
        </div>
    </div>
</div>

<!-- EDIT DIALOG -->
<div id="editOverlay">
    <div id="editDialog">
        <div id="editHeader">Chỉnh sửa</div>
        <div id="editContent">Xác nhận chỉnh sửa?</div>
        <div id="editActions">
            <button id="confirmEditBtn">Sửa</button>
            <button id="cancelEditBtn">Hủy</button>
        </div>
    </div>
</div>

<!-- FORM -->
<div id="formOverlay">
    <div id="formBox">
        <div class="form-tabs">
            <button type="button" class="tab active" onclick="switchTab(1)">Cá nhân</button>
            <button type="button" class="tab" onclick="switchTab(2)">Học tập</button>
        </div>

        <form id="studentForm">

            <!-- Cá nhân tab -->
            <div class="tab-content active" id="tab1">
                <div class="form-row">
                    <input name="name" placeholder="Họ tên" required style="width:90%;">
                </div>
                <div class="form-row" style="display:flex; gap:6px;">
                    <select name="gender" required style="flex:1;">
                        <option value="">Giới tính</option>
                        <option>Nam</option>
                        <option>Nữ</option>
                    </select>
                    <input type="date" name="dob" required style="flex:1;">
                </div>
                <div class="form-row">
                    <input name="email" placeholder="Email" style="width:90%;">
                </div>
                <div class="form-row">
                    <input name="address" placeholder="Địa chỉ" style="width:90%;">
                </div>
                <div class="form-row form-buttons">
                    <button type="submit">Lưu</button>
                    <button type="button" onclick="closeForm()">Hủy</button>
                </div>
            </div>

            <!-- Học tập tab -->
            <div class="tab-content" id="tab2">
                <div class="form-row" style="display:flex; gap:6px;">
                    <input name="major" placeholder="Chuyên ngành" style="flex:7;">
                    <input name="course" placeholder="Khóa học" style="flex:3;">
                </div>
                <div class="form-row">
                    <input type="number" step="0.01" name="gpa" placeholder="GPA" style="width:90%;">
                </div>
                <div class="form-row" style="display:flex; gap:6px;">
                    <select name="rank" style="flex:1;">
                        <option>Xuất sắc</option>
                        <option>Giỏi</option>
                        <option>Khá</option>
                        <option>Trung bình</option>
                        <option>Yếu</option>
                    </select>
                    <select name="status" style="flex:1;">
                        <option>Năm 1</option>
                        <option>Năm 2</option>
                        <option>Năm 3</option>
                        <option>Năm 4</option>
                        <option>Đã tốt nghiệp</option>
                        <option>Khác</option>
                    </select>
                </div>
                <div class="form-row form-buttons">
                    <button type="submit">Lưu</button>
                    <button type="button" onclick="closeForm()">Hủy</button>
                </div>
            </div>

        </form>
    </div>
</div>

<script src="home.js"></script>
</body>
</html>