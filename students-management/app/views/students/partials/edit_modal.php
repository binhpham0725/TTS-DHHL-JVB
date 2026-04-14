<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="../../assets/css/students.css">

<div class="modal-card">
    <div class="modal-header">
        <h3>Chỉnh sửa sinh viên</h3>
        <button type="button" class="close-modal" data-close="editStudentModal">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>

    <div class="alert-error" id="editError" style="display:none;"></div>

    <form id="editStudentForm" class="student-form">
        <input type="hidden" name="id" value="<?= htmlspecialchars($student['id']) ?>">

        <div class="form-grid">
            <div class="form-group">
                <label>MSSV</label>
                <input type="text" name="mssv" value="<?= htmlspecialchars($student['mssv']) ?>" required>
            </div>

            <div class="form-group">
                <label>Họ và tên</label>
                <input type="text" name="fullname" value="<?= htmlspecialchars($student['fullname']) ?>" required>
            </div>

            <div class="form-group">
                <label>Ngày sinh</label>
                <input type="date" name="birthday" value="<?= htmlspecialchars($student['birthday']) ?>" required>
            </div>

            <div class="form-group">
                <label for="gender">Giới tính</label>
                <select name="gender" id="gender" class="form-control" required>
                    <option value="">-- Chọn giới tính --</option>
                    <option value="Nam" <?= $student['gender'] === 'Nam' ? 'selected' : '' ?>>Nam</option>
                    <option value="Nữ" <?= $student['gender'] === 'Nữ' ? 'selected' : '' ?>>Nữ</option>
                    <option value="Khác" <?= $student['gender'] === 'Khác' ? 'selected' : '' ?>>Khác</option>
                </select>
            </div>

            <div class="form-group">
                <label>Số điện thoại</label>
                <input type="text" name="phone" value="<?= htmlspecialchars($student['phone']) ?>" maxlength="10" required>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" value="<?= htmlspecialchars($student['email']) ?>" required>
            </div>

            <div class="form-group">
                <label>Lớp</label>
                <select name="class" required>
                    <option value="">Chọn Lớp</option>
                    <?php foreach ($allowedCourses as $course): ?>
                        <option value="<?= $course ?>" <?= $student['class'] === $course ? 'selected' : '' ?>>
                            <?= $course ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Địa chỉ</label>
                <input type="text" name="address" value="<?= htmlspecialchars($student['address']) ?>" required>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
        </div>
    </form>
</div>
