<?php
$modalTexts = app_text_group('students.add_modal');
$fieldErrors = is_array($fieldErrors ?? null) ? $fieldErrors : [];
$validationTexts = [
    'mssv_required' => 'Vui lòng nhập MSSV.',
    'invalid_mssv' => 'MSSV phải gồm đúng 8 chữ số.',
    'invalid_year' => '4 số đầu của MSSV chưa thuộc nhóm lớp đang hỗ trợ.',
    'fullname_required' => 'Vui lòng nhập họ và tên.',
    'birthday_required' => 'Vui lòng chọn ngày sinh.',
    'invalid_birthday' => 'Ngày sinh không hợp lệ.',
    'future_birthday' => 'Ngày sinh không được lớn hơn ngày hiện tại.',
    'gender_required' => 'Vui lòng chọn giới tính.',
    'invalid_gender' => 'Giới tính không hợp lệ.',
    'phone_required' => 'Vui lòng nhập số điện thoại.',
    'invalid_phone' => 'Số điện thoại phải gồm 10 chữ số và bắt đầu bằng số 0.',
    'email_required' => 'Vui lòng nhập email.',
    'invalid_email' => 'Email không hợp lệ.',
    'address_required' => 'Vui lòng nhập địa chỉ.',
];
?>
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

    <form
        id="addStudentForm"
        method="POST"
        action="../function/students/add.php"
        class="student-form"
        novalidate
        data-validation="<?= htmlspecialchars(json_encode($validationTexts, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), ENT_QUOTES) ?>"
    >
        <div class="form-grid">
            <div class="form-group<?= !empty($fieldErrors['mssv']) ? ' has-error' : '' ?>">
                <label for="studentMssv">MSSV <span class="required-mark" aria-hidden="true">*</span></label>
                <input
                    type="text"
                    id="studentMssv"
                    name="mssv"
                    maxlength="8"
                    inputmode="numeric"
                    placeholder="<?= htmlspecialchars($modalTexts['mssv_placeholder']) ?>"
                    value="<?= htmlspecialchars($old['mssv'] ?? '') ?>"
                    oninput="previewClass(this.value)"
                    aria-invalid="<?= !empty($fieldErrors['mssv']) ? 'true' : 'false' ?>"
                    required
                >
                <div class="field-error"<?= empty($fieldErrors['mssv']) ? ' style="display:none;"' : '' ?>>
                    <?= htmlspecialchars($fieldErrors['mssv'] ?? '') ?>
                </div>
            </div>

            <div class="form-group<?= !empty($fieldErrors['fullname']) ? ' has-error' : '' ?>">
                <label for="studentFullname"><?= htmlspecialchars($modalTexts['fullname']) ?> <span class="required-mark" aria-hidden="true">*</span></label>
                <input
                    type="text"
                    id="studentFullname"
                    name="fullname"
                    maxlength="100"
                    value="<?= htmlspecialchars($old['fullname'] ?? '') ?>"
                    aria-invalid="<?= !empty($fieldErrors['fullname']) ? 'true' : 'false' ?>"
                    required
                >
                <div class="field-error"<?= empty($fieldErrors['fullname']) ? ' style="display:none;"' : '' ?>>
                    <?= htmlspecialchars($fieldErrors['fullname'] ?? '') ?>
                </div>
            </div>

            <div class="form-group<?= !empty($fieldErrors['birthday']) ? ' has-error' : '' ?>">
                <label for="studentBirthday"><?= htmlspecialchars($modalTexts['birthday']) ?></label>
                <input
                    type="date"
                    id="studentBirthday"
                    name="birthday"
                    value="<?= htmlspecialchars($old['birthday'] ?? '') ?>"
                    max="<?= date('Y-m-d') ?>"
                    aria-invalid="<?= !empty($fieldErrors['birthday']) ? 'true' : 'false' ?>"
                    required
                >
                <div class="field-error"<?= empty($fieldErrors['birthday']) ? ' style="display:none;"' : '' ?>>
                    <?= htmlspecialchars($fieldErrors['birthday'] ?? '') ?>
                </div>
            </div>

            <div class="form-group<?= !empty($fieldErrors['gender']) ? ' has-error' : '' ?>">
                <label for="studentGender"><?= htmlspecialchars($modalTexts['gender']) ?></label>
                <select
                    name="gender"
                    id="studentGender"
                    class="form-control"
                    aria-invalid="<?= !empty($fieldErrors['gender']) ? 'true' : 'false' ?>"
                    required
                >
                    <option value=""><?= htmlspecialchars($modalTexts['gender_placeholder']) ?></option>
                    <option value="Nam" <?= ($old['gender'] ?? '') === 'Nam' ? 'selected' : '' ?>><?= htmlspecialchars($modalTexts['gender_male']) ?></option>
                    <option value="Nữ" <?= ($old['gender'] ?? '') === 'Nữ' ? 'selected' : '' ?>><?= htmlspecialchars($modalTexts['gender_female']) ?></option>
                    <option value="Khác" <?= ($old['gender'] ?? '') === 'Khác' ? 'selected' : '' ?>><?= htmlspecialchars($modalTexts['gender_other']) ?></option>
                </select>
                <div class="field-error"<?= empty($fieldErrors['gender']) ? ' style="display:none;"' : '' ?>>
                    <?= htmlspecialchars($fieldErrors['gender'] ?? '') ?>
                </div>
            </div>

            <div class="form-group<?= !empty($fieldErrors['phone']) ? ' has-error' : '' ?>">
                <label for="studentPhone"><?= htmlspecialchars($modalTexts['phone']) ?> <span class="required-mark" aria-hidden="true">*</span></label>
                <input
                    type="text"
                    id="studentPhone"
                    name="phone"
                    maxlength="10"
                    inputmode="numeric"
                    value="<?= htmlspecialchars($old['phone'] ?? '') ?>"
                    aria-invalid="<?= !empty($fieldErrors['phone']) ? 'true' : 'false' ?>"
                    required
                >
                <div class="field-error"<?= empty($fieldErrors['phone']) ? ' style="display:none;"' : '' ?>>
                    <?= htmlspecialchars($fieldErrors['phone'] ?? '') ?>
                </div>
            </div>

            <div class="form-group<?= !empty($fieldErrors['email']) ? ' has-error' : '' ?>">
                <label for="studentEmail"><?= htmlspecialchars($modalTexts['email']) ?></label>
                <input
                    type="email"
                    id="studentEmail"
                    name="email"
                    maxlength="100"
                    value="<?= htmlspecialchars($old['email'] ?? '') ?>"
                    aria-invalid="<?= !empty($fieldErrors['email']) ? 'true' : 'false' ?>"
                    required
                >
                <div class="field-error"<?= empty($fieldErrors['email']) ? ' style="display:none;"' : '' ?>>
                    <?= htmlspecialchars($fieldErrors['email'] ?? '') ?>
                </div>
            </div>

            <div class="form-group">
                <label for="classPreview"><?= htmlspecialchars($modalTexts['class_preview']) ?></label>
                <input
                    type="text"
                    id="classPreview"
                    readonly
                    placeholder="<?= htmlspecialchars($modalTexts['class_preview_placeholder']) ?>"
                >
            </div>

            <div class="form-group<?= !empty($fieldErrors['address']) ? ' has-error' : '' ?>">
                <label for="studentAddress"><?= htmlspecialchars($modalTexts['address']) ?></label>
                <input
                    type="text"
                    id="studentAddress"
                    name="address"
                    maxlength="255"
                    value="<?= htmlspecialchars($old['address'] ?? '') ?>"
                    aria-invalid="<?= !empty($fieldErrors['address']) ? 'true' : 'false' ?>"
                    required
                >
                <div class="field-error"<?= empty($fieldErrors['address']) ? ' style="display:none;"' : '' ?>>
                    <?= htmlspecialchars($fieldErrors['address'] ?? '') ?>
                </div>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary"><?= htmlspecialchars($modalTexts['submit']) ?></button>
        </div>
    </form>
</div>

<script>
function previewClass(mssv) {
    const year = String(mssv).substring(0, 4);
    const map = { '2023': 'D16CNTT', '2024': 'D17CNTT', '2025': 'D18CNTT' };
    const preview = document.getElementById('classPreview');
    if (preview) {
        preview.value = map[year] || '';
    }
}

document.addEventListener('DOMContentLoaded', function () {
    const mssvInput = document.getElementById('studentMssv');
    if (mssvInput) {
        previewClass(mssvInput.value);
    }
});
</script>
