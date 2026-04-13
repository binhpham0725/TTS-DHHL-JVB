<?php
require_once __DIR__ . '/../../core/StudentService.php';
/* lấy sinh viên theo id để đổ dữ liệu vào form */
$studentId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$student = getStudentById($studentId);
$academic = getStudentAcademicByStudentId($studentId);
if (!$student) {
    die('Student not found');
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Sửa sinh viên</title>
    <!-- css chung và css riêng của trang edit -->
    <link rel="stylesheet" href="../../assets/css/students.css">
    <link rel="stylesheet" href="../../assets/css/edit-student.css">
</head>
<body class="edit-page">
    <div class="edit-wrapper">
        <div class="edit-card">
            <h2 class="edit-title">Sửa thông tin sinh viên</h2>
            <!-- form sửa đầy đủ thông tin sinh viên -->
            <form method="post" action="../../api/students/update.php?id=<?= $studentId ?>">
                <div class="form-tabs">
                    <button type="button" class="tab active" onclick="switchTab(1)">Cá nhân</button>
                    <button type="button" class="tab" onclick="switchTab(2)">Học tập</button>
                </div>

                <div class="tab-content active" id="tab1">
                    <div class="edit-field">
                        <label>Họ tên</label>
                        <input type="text" name="name" value="<?= escapeValue($student['ho_ten']) ?>" required>
                    </div>
                    <div class="edit-field">
                        <label>Giới tính</label>
                        <select name="gender">
                            <option value="Nam" <?= $student['gioi_tinh'] === 'Nam' ? 'selected' : '' ?>>Nam</option>
                            <option value="Nữ" <?= $student['gioi_tinh'] === 'Nữ' ? 'selected' : '' ?>>Nữ</option>
                        </select>
                    </div>
                    <div class="edit-field">
                        <label>Ngày sinh</label>
                        <input type="date" name="dob" value="<?= escapeValue($student['ngay_sinh']) ?>" required>
                    </div>
                    <div class="edit-field">
                        <label>Email</label>
                        <input type="text" name="email" value="<?= escapeValue($student['email']) ?>">
                    </div>
                    <div class="edit-field">
                        <label>Địa chỉ</label>
                        <input type="text" name="address" value="<?= escapeValue($student['dia_chi']) ?>">
                    </div>
                </div>

                <div class="tab-content" id="tab2">
                    <div class="edit-field">
                        <label>Chuyên ngành</label>
                        <input type="text" name="major" value="<?= escapeValue($academic['chuyen_nganh'] ?? '') ?>">
                    </div>
                    <div class="edit-field">
                        <label>Khóa học</label>
                        <input type="text" name="course" value="<?= escapeValue($academic['khoa_hoc'] ?? '') ?>">
                    </div>
                    <div class="edit-field">
                        <label>GPA</label>
                        <input type="number" step="0.01" max="4" name="gpa" value="<?= escapeValue($academic['gpa'] ?? '') ?>">
                    </div>
                    <div class="edit-field">
                        <label>Tình trạng</label>
                        <select name="status">
                            <?php foreach (['Năm 1', 'Năm 2', 'Năm 3', 'Năm 4', 'Đã tốt nghiệp', 'Khác'] as $statusOption) { ?>
                                <option value="<?= escapeValue($statusOption) ?>" <?= (($academic['tinh_trang'] ?? '') === $statusOption) ? 'selected' : '' ?>>
                                    <?= escapeValue($statusOption) ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="edit-field">
                        <label>Xếp loại</label>
                        <select name="rank">
                            <?php foreach (['Xuất sắc', 'Giỏi', 'Khá', 'Trung bình', 'Yếu'] as $rankOption) { ?>
                                <option value="<?= escapeValue($rankOption) ?>" <?= (($academic['xep_loai'] ?? '') === $rankOption) ? 'selected' : '' ?>>
                                    <?= escapeValue($rankOption) ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                </div>

                <button class="edit-submit" type="submit">Lưu thay đổi</button>
            </form>

            <a class="edit-back" href="./index.php">Quay lại</a>
        </div>
    </div>
    <!-- toast báo cập nhật thành công -->
    <div id="toast">
        <span id="toastMsg"></span>
        <div id="toastBar"></div>
    </div>
    <script src="../../assets/js/edit-student.js"></script>
    <?php if (isset($_GET['success'])) { ?>
        <script>
            showToast('Cập nhật thông tin thành công');
        </script>
    <?php } elseif (isset($_GET['error'])) { ?>
        <script>
            showToast('<?= escapeValue(match ($_GET['error']) {
                'missing_name' => 'Thiếu họ tên',
                'invalid_gender' => 'Giới tính không hợp lệ',
                'missing_dob' => 'Thiếu ngày sinh',
                'invalid_email' => 'Email không hợp lệ',
                'invalid_status' => 'Tình trạng không hợp lệ',
                'invalid_rank' => 'Xếp loại không hợp lệ',
                'invalid_gpa' => 'GPA không hợp lệ',
                'gpa_too_high' => 'GPA không được lớn hơn 4.0',
                default => 'Cập nhật thất bại'
            }) ?>');
        </script>
    <?php } ?>
</body>
</html>
