<div class="modal-card">
    <div class="modal-header">
        <h3>Thêm sinh viên mới</h3>
        <button type="button" class="close-modal" data-close="addStudentModal">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>

    <?php if (!empty($error)): ?>
        <div class="alert-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="../function/students/add.php" class="student-form">
        <div class="form-grid">
            <div class="form-group">
                <label>MSSV</label>
                <input type="text" name="mssv" maxlength="8" placeholder="VD: 20230001"
                       value="<?= htmlspecialchars($old['mssv'] ?? '') ?>"
                       oninput="previewClass(this.value)" required>
            </div>

            <div class="form-group">
                <label>Họ và tên</label>
                <input type="text" name="fullname" value="<?= htmlspecialchars($old['fullname'] ?? '') ?>" required>
            </div>

            <div class="form-group">
                <label>Ngày sinh</label>
                <input type="date" name="birthday" value="<?= htmlspecialchars($old['birthday'] ?? '') ?>" required>
            </div>

            <div class="form-group">
                <label for="gender">Giới tính</label>
                <select name="gender" id="gender" class="form-control" required>
                    <option value="">-- Chọn giới tính --</option>
                    <option value="Nam" <?= ($old['gender'] ?? '') === 'Nam' ? 'selected' : '' ?>>Nam</option>
                    <option value="Nữ" <?= ($old['gender'] ?? '') === 'Nữ' ? 'selected' : '' ?>>Nữ</option>
                    <option value="Khác" <?= ($old['gender'] ?? '') === 'Khác' ? 'selected' : '' ?>>Khác</option>
                </select>
            </div>

            <div class="form-group">
                <label>Số điện thoại</label>
                <input type="text" name="phone" maxlength="10" value="<?= htmlspecialchars($old['phone'] ?? '') ?>" required>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" value="<?= htmlspecialchars($old['email'] ?? '') ?>" required>
            </div>

            <div class="form-group">
                <label>Lớp (tự động)</label>
                <input type="text" id="classPreview" readonly placeholder="Nhập MSSV để xác định lớp">
            </div>

            <div class="form-group">
                <label>Địa chỉ</label>
                <input type="text" name="address" value="<?= htmlspecialchars($old['address'] ?? '') ?>" required>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Lưu sinh viên</button>
        </div>
    </form>
</div>

<script>
function previewClass(mssv) {
    const year = mssv.substring(0, 4);
    const map = { '2023': 'D16CNTT', '2024': 'D17CNTT', '2025': 'D18CNTT' };
    document.getElementById('classPreview').value = map[year] || '';
}
</script>
