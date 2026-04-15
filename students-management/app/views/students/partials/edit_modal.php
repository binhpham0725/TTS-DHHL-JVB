<?php $editTexts = app_text_group('students.edit_modal'); $addTexts = app_text_group('students.add_modal'); ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="../../assets/css/students.css">

<div class="modal-card">
    <div class="modal-header">
        <h3><?= htmlspecialchars($editTexts['title']) ?></h3>
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
                <label><?= htmlspecialchars($addTexts['fullname']) ?></label>
                <input type="text" name="fullname" value="<?= htmlspecialchars($student['fullname']) ?>" required>
            </div>

            <div class="form-group">
                <label><?= htmlspecialchars($addTexts['birthday']) ?></label>
                <input type="date" name="birthday" value="<?= htmlspecialchars($student['birthday']) ?>" required>
            </div>

            <div class="form-group">
                <label for="gender"><?= htmlspecialchars($addTexts['gender']) ?></label>
                <select name="gender" id="gender" class="form-control" required>
                    <option value=""><?= htmlspecialchars($addTexts['gender_placeholder']) ?></option>
                    <option value="Nam" <?= $student['gender'] === 'Nam' ? 'selected' : '' ?>><?= htmlspecialchars($addTexts['gender_male']) ?></option>
                    <option value="Nữ" <?= $student['gender'] === 'Nữ' ? 'selected' : '' ?>><?= htmlspecialchars($addTexts['gender_female']) ?></option>
                    <option value="Khác" <?= $student['gender'] === 'Khác' ? 'selected' : '' ?>><?= htmlspecialchars($addTexts['gender_other']) ?></option>
                </select>
            </div>

            <div class="form-group">
                <label><?= htmlspecialchars($addTexts['phone']) ?></label>
                <input type="text" name="phone" value="<?= htmlspecialchars($student['phone']) ?>" maxlength="10" required>
            </div>

            <div class="form-group">
                <label><?= htmlspecialchars($addTexts['email']) ?></label>
                <input type="email" name="email" value="<?= htmlspecialchars($student['email']) ?>" required>
            </div>

            <div class="form-group">
                <label><?= htmlspecialchars($editTexts['class']) ?></label>
                <select name="class" required>
                    <option value=""><?= htmlspecialchars($editTexts['class_placeholder']) ?></option>
                    <?php foreach ($allowedCourses as $course): ?>
                        <option value="<?= $course ?>" <?= $student['class'] === $course ? 'selected' : '' ?>>
                            <?= $course ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label><?= htmlspecialchars($addTexts['address']) ?></label>
                <input type="text" name="address" value="<?= htmlspecialchars($student['address']) ?>" required>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary"><?= htmlspecialchars($editTexts['submit']) ?></button>
        </div>
    </form>
</div>
