<?php
/* database connection + pagination setup để phân trang và query dữ liệu sinh viên */
include "../database/db.php";

function esc($value) {
    return htmlspecialchars((string)($value ?? ''), ENT_QUOTES, 'UTF-8');
}

/* inline update giới hạn field nhẹ */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'inline_update') {
    header('Content-Type: application/json; charset=UTF-8');

    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    $view = isset($_POST['view']) && $_POST['view'] === 'academic' ? 'academic' : 'personal';
    $payload = json_decode($_POST['payload'] ?? '{}', true);

    if ($id <= 0 || !is_array($payload)) {
        echo json_encode([
            "status" => "error",
            "message" => "Dữ liệu không hợp lệ"
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    if ($view === 'personal') {
        $email = trim($payload['email'] ?? '');
        $diaChi = trim($payload['dia_chi'] ?? '');

        if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode([
                "status" => "error",
                "message" => "Email không hợp lệ"
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }

        $stmt = $conn->prepare("
            UPDATE students
            SET email = ?, dia_chi = ?
            WHERE id = ?
        ");
        $stmt->bind_param("ssi", $email, $diaChi, $id);

        if ($stmt->execute()) {
            echo json_encode([
                "status" => "success",
                "data" => [
                    "email" => $email,
                    "dia_chi" => $diaChi
                ]
            ], JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode([
                "status" => "error",
                "message" => "Không thể cập nhật dữ liệu"
            ], JSON_UNESCAPED_UNICODE);
        }
        exit;
    }

    $chuyenNganh = trim($payload['chuyen_nganh'] ?? '');
    $tinhTrang = trim($payload['tinh_trang'] ?? '');
    $xepLoai = trim($payload['xep_loai'] ?? '');

    $validTinhTrang = ["Năm 1", "Năm 2", "Năm 3", "Năm 4", "Đã tốt nghiệp", "Khác"];
    $validXepLoai = ["Xuất sắc", "Giỏi", "Khá", "Trung bình", "Yếu"];

    if ($tinhTrang !== '' && !in_array($tinhTrang, $validTinhTrang, true)) {
        echo json_encode([
            "status" => "error",
            "message" => "Tình trạng không hợp lệ"
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    if ($xepLoai !== '' && !in_array($xepLoai, $validXepLoai, true)) {
        echo json_encode([
            "status" => "error",
            "message" => "Xếp loại không hợp lệ"
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    $check = $conn->prepare("SELECT student_id FROM student_academic WHERE student_id = ?");
    $check->bind_param("i", $id);
    $check->execute();
    $exists = $check->get_result()->num_rows > 0;
    $check->close();

    if ($exists) {
        $stmt = $conn->prepare("
            UPDATE student_academic
            SET chuyen_nganh = ?, tinh_trang = ?, xep_loai = ?
            WHERE student_id = ?
        ");
        $stmt->bind_param("sssi", $chuyenNganh, $tinhTrang, $xepLoai, $id);
    } else {
        $emptyCourse = '';
        $emptyGpa = null;
        $stmt = $conn->prepare("
            INSERT INTO student_academic (student_id, chuyen_nganh, khoa_hoc, gpa, tinh_trang, xep_loai)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param("issdss", $id, $chuyenNganh, $emptyCourse, $emptyGpa, $tinhTrang, $xepLoai);
    }

    if ($stmt->execute()) {
        echo json_encode([
            "status" => "success",
            "data" => [
                "chuyen_nganh" => $chuyenNganh,
                "tinh_trang" => $tinhTrang,
                "xep_loai" => $xepLoai
            ]
        ], JSON_UNESCAPED_UNICODE);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Không thể cập nhật dữ liệu"
        ], JSON_UNESCAPED_UNICODE);
    }
    exit;
}

$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$start = ($page - 1) * $limit;

$limit = (int)$limit;
$start = (int)$start;

/* view mode xác định hiển thị personal hoặc academic data */
$view = isset($_GET['view']) ? $_GET['view'] : "personal";
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

/* sorting logic với whitelist để tránh SQL injection và vague column */
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
    $allowed = [
        "ho_ten" => "ho_ten",
        "gioi_tinh" => "gioi_tinh",
        "ngay_sinh" => "ngay_sinh",
        "email" => "email",
        "dia_chi" => "dia_chi"
    ];
    $sortKey = $_GET['sort'] ?? "ho_ten";
    $sort = isset($allowed[$sortKey]) ? $allowed[$sortKey] : "id";
}

/* order direction xử lý asc desc để đảo chiều sort */
$order = isset($_GET['order']) && $_GET['order'] == "desc" ? "DESC" : "ASC";

/* export csv giữ nguyên state hiện tại */
if (isset($_GET['export']) && $_GET['export'] === 'csv') {
    $filename = 'students_' . $view . '_' . date('Ymd_His') . '.csv';

    header('Content-Type: text/csv; charset=UTF-8');
    header('Content-Disposition: attachment; filename="' . $filename . '"');

    $output = fopen('php://output', 'w');

    fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

    if ($view == "academic") {
        fputcsv($output, ["STT", "Họ tên", "Chuyên ngành", "Khóa học", "GPA", "Tình trạng", "Xếp loại"]);

        $exportResult = $conn->query("
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
        ");

        $stt = 1;
        while ($row = $exportResult->fetch_assoc()) {
            fputcsv($output, [
                $stt,
                $row['ho_ten'],
                $row['chuyen_nganh'],
                $row['khoa_hoc'],
                $row['gpa'],
                $row['tinh_trang'],
                $row['xep_loai']
            ]);
            $stt++;
        }
    } else {
        fputcsv($output, ["STT", "Họ tên", "Giới tính", "Ngày sinh", "Email", "Địa chỉ"]);

        $exportResult = $conn->query("
            SELECT * FROM students
            ORDER BY $sort $order
        ");

        $stt = 1;
        while ($row = $exportResult->fetch_assoc()) {
            $dob = $row['ngay_sinh'] ? date("d/m/Y", strtotime($row['ngay_sinh'])) : '';
            fputcsv($output, [
                $stt,
                $row['ho_ten'],
                $row['gioi_tinh'],
                $dob,
                $row['email'],
                $row['dia_chi']
            ]);
            $stt++;
        }
    }

    fclose($output);
    exit;
}

/* tổng số sinh viên để tính pagination page number */
$totalResult = $conn->query("SELECT COUNT(*) as total FROM students");
$total = $totalResult->fetch_assoc()['total'];
$totalPages = ceil($total / $limit);

/* query dữ liệu tùy theo view mode personal hoặc academic */
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

<!-- page header hiển thị title và tổng số sinh viên realtime -->
<h2>Danh sách sinh viên</h2>
<div class="student-count">
    <b>Tổng số sinh viên: <span id="studentCount">0</span></b>
</div>

<!-- top bar chứa action chính như register logout search và view mode -->
<div class="top-bar">
    <div class="top-actions">
        <button onclick="openForm()">Đăng ký</button>
        <button onclick="logout()">Logout</button>
        <button onclick="exportCSV()">Export CSV</button>
        <input id="search" placeholder="Tìm kiếm..." value="<?= esc($search) ?>">
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

<!-- table hiển thị dữ liệu sinh viên với sorting column và action -->
<div class="table-wrapper">
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

    <!-- tbody render từng dòng sinh viên dynamic từ database -->
    <tbody id="tableBody">
        <?php
        $stt = $start + 1;
        while ($row = $result->fetch_assoc()) {
            if ($view == "academic") {
                echo "<tr data-id='" . esc($row['id']) . "' data-view='academic'>
                    <td>{$stt}</td>
                    <td>" . esc($row['ho_ten']) . "</td>
                    <td data-field='chuyen_nganh' data-value='" . esc($row['chuyen_nganh']) . "'>" . esc($row['chuyen_nganh']) . "</td>
                    <td>" . esc($row['khoa_hoc']) . "</td>
                    <td>" . esc($row['gpa']) . "</td>
                    <td data-field='tinh_trang' data-value='" . esc($row['tinh_trang']) . "'>" . esc($row['tinh_trang']) . "</td>
                    <td data-field='xep_loai' data-value='" . esc($row['xep_loai']) . "'>" . esc($row['xep_loai']) . "</td>
                    <td class='action-cell'>
                        <button type='button' onclick='startInlineEdit(this)'>Sửa nhanh</button>
                        <button onclick='confirmEdit({$row['id']})'>Sửa</button>
                        <button onclick='confirmDelete({$row['id']})'>Xóa</button>
                        <input type='checkbox' class='row-check' value='" . esc($row['id']) . "' onchange='toggleBulkDeleteButton()'>
                    </td>
                </tr>";
            } else {
                $dob = $row['ngay_sinh'] ? date("d/m/Y", strtotime($row['ngay_sinh'])) : '';
                echo "<tr data-id='" . esc($row['id']) . "' data-view='personal'>
                    <td>{$stt}</td>
                    <td>" . esc($row['ho_ten']) . "</td>
                    <td>" . esc($row['gioi_tinh']) . "</td>
                    <td>" . esc($dob) . "</td>
                    <td data-field='email' data-value='" . esc($row['email']) . "'>" . esc($row['email']) . "</td>
                    <td data-field='dia_chi' data-value='" . esc($row['dia_chi']) . "'>" . esc($row['dia_chi']) . "</td>
                    <td class='action-cell'>
                        <button type='button' onclick='startInlineEdit(this)'>Sửa nhanh</button>
                        <button onclick='confirmEdit({$row['id']})'>Sửa</button>
                        <button onclick='confirmDelete({$row['id']})'>Xóa</button>
                        <input type='checkbox' class='row-check' value='" . esc($row['id']) . "' onchange='toggleBulkDeleteButton()'>
                    </td>
                </tr>";
            }
            $stt++;
        }
        ?>
    </tbody>
</table>
</div>

<!-- pagination hiển thị page number và navigation giữa các trang -->
<div class="pagination">
    <?php
    for ($i = 1; $i <= $totalPages; $i++) {
        if ($i == $page) {
            echo "<strong>$i</strong> ";
        } else {
            echo "<a href='#' onclick='goPage($i); return false;'>$i</a> ";
        }
    }
    ?>
</div>

<!-- toast notifications hiển thị trạng thái thao tác async của user -->
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
    <span id="pageText">⏳ Đang tải dữ liệu...</span>
    <div class="toastBar purple" id="pageBar"></div>
</div>

<div id="registerToast" class="toast">
    <span>✔ Đăng ký thành công</span>
    <div class="toastBar green" id="registerBar"></div>
</div>

<div id="inlineSuccessToast" class="toast">
    <span>✔ Cập nhật nhanh thành công</span>
    <div class="toastBar green" id="inlineSuccessBar"></div>
</div>

<div id="inlineErrorToast" class="toast">
    <span>✖ Dữ liệu không hợp lệ hoặc cập nhật thất bại</span>
    <div class="toastBar red" id="inlineErrorBar"></div>
</div>

<!-- delete dialog dùng confirm xóa từng student -->
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

<!-- bulk delete dialog dùng xóa nhiều student cùng lúc -->
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

<!-- edit dialog xác nhận trước khi chuyển sang trang edit -->
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

<!-- form overlay dùng tạo sinh viên mới với tab cá nhân và học tập -->
<div id="formOverlay">
    <div id="formBox">
        <div class="form-tabs">
            <button type="button" class="tab active" onclick="switchTab(1)">Cá nhân</button>
            <button type="button" class="tab" onclick="switchTab(2)">Học tập</button>
        </div>

        <form id="studentForm">

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