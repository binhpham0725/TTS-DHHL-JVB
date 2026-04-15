<?php $modalTexts = app_text_group('students.add_modal'); ?>
<div class="modal-card">
    <div class="modal-header">
        <h3><?= htmlspecialchars($modalTexts['title']) ?></h3>
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
                <input type="text" name="mssv" maxlength="8" placeholder="<?= htmlspecialchars($modalTexts['mssv_placeholder']) ?>"
                       value="<?= htmlspecialchars($old['mssv'] ?? '') ?>"
                       oninput="previewClass(this.value)" required>
            </div>

            <div class="form-group">
                <label><?= htmlspecialchars($modalTexts['fullname']) ?></label>
                <input type="text" name="fullname" value="<?= htmlspecialchars($old['fullname'] ?? '') ?>" required>
            </div>

            <div class="form-group">
                <label><?= htmlspecialchars($modalTexts['birthday']) ?></label>
                <input type="date" name="birthday" value="<?= htmlspecialchars($old['birthday'] ?? '') ?>" required>
            </div>

            <div class="form-group">
                <label for="gender"><?= htmlspecialchars($modalTexts['gender']) ?></label>
                <select name="gender" id="gender" class="form-control" required>
                    <option value=""><?= htmlspecialchars($modalTexts['gender_placeholder']) ?></option>
                    <option value="Nam" <?= ($old['gender'] ?? '') === 'Nam' ? 'selected' : '' ?>><?= htmlspecialchars($modalTexts['gender_male']) ?></option>
                    <option value="Nữ" <?= ($old['gender'] ?? '') === 'Nữ' ? 'selected' : '' ?>><?= htmlspecialchars($modalTexts['gender_female']) ?></option>
                    <option value="Khác" <?= ($old['gender'] ?? '') === 'Khác' ? 'selected' : '' ?>><?= htmlspecialchars($modalTexts['gender_other']) ?></option>
                </select>
            </div>

            <div class="form-group">
                <label><?= htmlspecialchars($modalTexts['phone']) ?></label>
                <input type="text" name="phone" maxlength="10" value="<?= htmlspecialchars($old['phone'] ?? '') ?>" required>
            </div>

            <div class="form-group">
                <label><?= htmlspecialchars($modalTexts['email']) ?></label>
                <input type="email" name="email" value="<?= htmlspecialchars($old['email'] ?? '') ?>" required>
            </div>

            <div class="form-group">
                <label><?= htmlspecialchars($modalTexts['class_preview']) ?></label>
                <input type="text" id="classPreview" readonly placeholder="<?= htmlspecialchars($modalTexts['class_preview_placeholder']) ?>">
            </div>

            <div class="form-group">
                <label><?= htmlspecialchars($modalTexts['address']) ?></label>
                <input type="text" name="address" value="<?= htmlspecialchars($old['address'] ?? '') ?>" required>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary"><?= htmlspecialchars($modalTexts['submit']) ?></button>
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
