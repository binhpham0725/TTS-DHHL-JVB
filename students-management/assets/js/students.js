document.addEventListener("DOMContentLoaded", () => {
    const addModal = document.getElementById("addStudentModal");
    const importModal = document.getElementById("importModal");
    const editModal = document.getElementById("editStudentModal");
    const editModalContent = document.getElementById("editModalContent");
    const openAddBtn = document.getElementById("openAddModal");
    const openImportBtn = document.getElementById("openImportModal");
    const addForm = document.getElementById("addStudentForm");
    const addAlert = addModal ? addModal.querySelector(".alert-error") : null;
    const texts = (window.APP_TEXTS && window.APP_TEXTS.students) || {};

    const addValidationTexts = (() => {
        if (!addForm || !addForm.dataset.validation) {
            return {};
        }

        try {
            return JSON.parse(addForm.dataset.validation);
        } catch (error) {
            console.error("Không đọc được cấu hình validation của form thêm sinh viên.", error);
            return {};
        }
    })();

    const classMap = {
        "2023": "D16CNTT",
        "2024": "D17CNTT",
        "2025": "D18CNTT"
    };

    function pushToast(message, type = "info") {
        if (typeof window.showAppToast === "function") {
            window.showAppToast({
                message,
                type,
                duration: 5000
            });
            return true;
        }

        return false;
    }

    function openModal(modal) {
        if (modal) {
            modal.classList.add("show");
        }
    }

    function closeModal(modal) {
        if (modal) {
            modal.classList.remove("show");
        }
    }

    function closeAllModals() {
        closeModal(addModal);
        closeModal(importModal);
        closeModal(editModal);
    }

    function getAddField(name) {
        return addForm ? addForm.querySelector(`[name="${name}"]`) : null;
    }

    function getFieldGroup(field) {
        return field ? field.closest(".form-group") : null;
    }

    function getFieldErrorBox(field) {
        const group = getFieldGroup(field);
        return group ? group.querySelector(".field-error") : null;
    }

    function setFieldError(field, message) {
        if (!field) {
            return;
        }

        const group = getFieldGroup(field);
        const errorBox = getFieldErrorBox(field);

        if (group) {
            group.classList.add("has-error");
        }

        field.setAttribute("aria-invalid", "true");

        if (errorBox) {
            errorBox.textContent = message;
            errorBox.style.display = "block";
        }
    }

    function clearFieldError(field) {
        if (!field) {
            return;
        }

        const group = getFieldGroup(field);
        const errorBox = getFieldErrorBox(field);

        if (group) {
            group.classList.remove("has-error");
        }

        field.setAttribute("aria-invalid", "false");

        if (errorBox) {
            errorBox.textContent = "";
            errorBox.style.display = "none";
        }
    }

    function clearAddErrors() {
        if (!addForm) {
            return;
        }

        addForm.querySelectorAll("input, select").forEach((field) => {
            clearFieldError(field);
        });
    }

    function previewClass(mssv) {
        const preview = document.getElementById("classPreview");
        const year = String(mssv).substring(0, 4);

        if (preview) {
            preview.value = classMap[year] || "";
        }
    }

    function resetAddModalState() {
        if (!addForm) {
            return;
        }

        addForm.reset();

        addForm.querySelectorAll("input, select").forEach((field) => {
            if (field.tagName === "SELECT") {
                field.selectedIndex = 0;
            } else {
                field.value = "";
            }

            clearFieldError(field);
        });

        if (addAlert) {
            addAlert.style.display = "none";
        }

        previewClass("");
    }

    function validateAddForm() {
        if (!addForm) {
            return true;
        }

        clearAddErrors();

        const mssvField = getAddField("mssv");
        const fullnameField = getAddField("fullname");
        const birthdayField = getAddField("birthday");
        const genderField = getAddField("gender");
        const phoneField = getAddField("phone");
        const emailField = getAddField("email");
        const addressField = getAddField("address");

        const mssv = (mssvField ? mssvField.value : "").trim();
        const fullname = (fullnameField ? fullnameField.value : "").trim();
        const birthday = (birthdayField ? birthdayField.value : "").trim();
        const gender = (genderField ? genderField.value : "").trim();
        const phone = (phoneField ? phoneField.value : "").trim();
        const email = (emailField ? emailField.value : "").trim();
        const address = (addressField ? addressField.value : "").trim();
        const today = new Date().toISOString().slice(0, 10);
        const allowedGender = ["Nam", "Nữ", "Khác"];

        let firstInvalidField = null;

        function markInvalid(field, message) {
            if (!field) {
                return;
            }

            if (!firstInvalidField) {
                firstInvalidField = field;
            }

            setFieldError(field, message);
        }

        if (mssv === "") {
            markInvalid(mssvField, addValidationTexts.mssv_required || "Vui lòng nhập MSSV.");
        } else if (!/^\d{8}$/.test(mssv)) {
            markInvalid(mssvField, addValidationTexts.invalid_mssv || "MSSV phải gồm đúng 8 chữ số.");
        } else if (!classMap[mssv.substring(0, 4)]) {
            markInvalid(mssvField, addValidationTexts.invalid_year || "4 số đầu của MSSV chưa thuộc nhóm lớp đang hỗ trợ.");
        }

        if (fullname === "") {
            markInvalid(fullnameField, addValidationTexts.fullname_required || "Vui lòng nhập họ và tên.");
        }

        if (birthday === "") {
            markInvalid(birthdayField, addValidationTexts.birthday_required || "Vui lòng chọn ngày sinh.");
        } else if (!/^\d{4}-\d{2}-\d{2}$/.test(birthday) || Number.isNaN(Date.parse(birthday))) {
            markInvalid(birthdayField, addValidationTexts.invalid_birthday || "Ngày sinh không hợp lệ.");
        } else if (birthday > today) {
            markInvalid(birthdayField, addValidationTexts.future_birthday || "Ngày sinh không được lớn hơn ngày hiện tại.");
        }

        if (gender === "") {
            markInvalid(genderField, addValidationTexts.gender_required || "Vui lòng chọn giới tính.");
        } else if (!allowedGender.includes(gender)) {
            markInvalid(genderField, addValidationTexts.invalid_gender || "Giới tính không hợp lệ.");
        }

        if (phone === "") {
            markInvalid(phoneField, addValidationTexts.phone_required || "Vui lòng nhập số điện thoại.");
        } else if (!/^0\d{9}$/.test(phone)) {
            markInvalid(phoneField, addValidationTexts.invalid_phone || "Số điện thoại phải gồm 10 chữ số và bắt đầu bằng số 0.");
        }

        if (email === "") {
            markInvalid(emailField, addValidationTexts.email_required || "Vui lòng nhập email.");
        } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            markInvalid(emailField, addValidationTexts.invalid_email || "Email không hợp lệ.");
        }

        if (address === "") {
            markInvalid(addressField, addValidationTexts.address_required || "Vui lòng nhập địa chỉ.");
        }

        if (firstInvalidField) {
            firstInvalidField.focus();
            return false;
        }

        return true;
    }

    function loadEditForm(studentId) {
        if (!editModal || !editModalContent) {
            console.error(texts.missing_modal || "Không tìm thấy modal sửa hoặc vùng chứa nội dung.");
            return;
        }

        editModalContent.innerHTML = `<p>${texts.loading || "Đang tải..."}</p>`;
        openModal(editModal);

        fetch(`../function/students/edit.php?id=${encodeURIComponent(studentId)}`, {
            method: "GET",
            headers: {
                "X-Requested-With": "XMLHttpRequest"
            }
        })
            .then((response) => {
                if (!response.ok) {
                    throw new Error(`HTTP error: ${response.status}`);
                }

                return response.text();
            })
            .then((html) => {
                editModalContent.innerHTML = html;
            })
            .catch((error) => {
                const message = texts.load_error || "Không tải được form sửa.";
                console.error(message, error);
                editModalContent.innerHTML = `<p>${message}</p>`;
                pushToast(message, "error");
            });
    }

    if (openAddBtn) {
        openAddBtn.addEventListener("click", (event) => {
            event.preventDefault();
            resetAddModalState();
            openModal(addModal);
        });
    }

    if (openImportBtn) {
        openImportBtn.addEventListener("click", (event) => {
            event.preventDefault();
            openModal(importModal);
        });
    }

    if (addForm) {
        const mssvField = getAddField("mssv");

        if (mssvField) {
            previewClass(mssvField.value);
            mssvField.addEventListener("input", (event) => {
                previewClass(event.target.value);
            });
        }

        addForm.addEventListener("submit", (event) => {
            if (!validateAddForm()) {
                event.preventDefault();
                openModal(addModal);
            }
        });

        addForm.querySelectorAll("input, select").forEach((field) => {
            const eventName = field.tagName === "SELECT" ? "change" : "input";
            field.addEventListener(eventName, () => {
                clearFieldError(field);
            });
        });
    }

    if (addModal && addModal.dataset.autoOpen === "1") {
        openModal(addModal);
    }

    document.addEventListener("click", (event) => {
        const closeBtn = event.target.closest("[data-close]");
        if (closeBtn) {
            event.preventDefault();
            const modalId = closeBtn.getAttribute("data-close");
            const modal = document.getElementById(modalId);
            closeModal(modal);
            if (modal === addModal) {
                resetAddModalState();
            }
            return;
        }

        const editBtn = event.target.closest(".edit-btn");
        if (editBtn) {
            event.preventDefault();
            const id = editBtn.getAttribute("data-id");

            if (!id) {
                console.error(texts.missing_student_id || "Không tìm thấy data-id của sinh viên.");
                return;
            }

            loadEditForm(id);
            return;
        }

        if (event.target.classList.contains("modal")) {
            closeModal(event.target);
            if (event.target === addModal) {
                resetAddModalState();
            }
        }
    });

    document.addEventListener("keydown", (event) => {
        if (event.key === "Escape") {
            if (addModal && addModal.classList.contains("show")) {
                resetAddModalState();
            }
            closeAllModals();
        }
    });

    document.addEventListener("submit", (event) => {
        const form = event.target.closest("#editStudentForm");
        if (!form) {
            return;
        }

        event.preventDefault();

        const errorBox = document.getElementById("editError");
        if (errorBox) {
            errorBox.style.display = "none";
            errorBox.textContent = "";
        }

        const formData = new FormData(form);

        fetch("../function/students/edit.php", {
            method: "POST",
            headers: { "X-Requested-With": "XMLHttpRequest" },
            body: formData
        })
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    if (typeof window.queueAppToast === "function") {
                        window.queueAppToast({
                            type: "success",
                            message: data.message || "Chỉnh sửa sinh viên thành công.",
                            duration: 5000
                        });
                    }

                    closeModal(editModal);
                    location.reload();
                    return;
                }

                const message = data.message || texts.update_failed || "Cập nhật thất bại.";
                if (!pushToast(message, "error") && errorBox) {
                    errorBox.textContent = message;
                    errorBox.style.display = "block";
                }
            })
            .catch((error) => {
                const message = texts.unexpected_error || "Có lỗi xảy ra, vui lòng thử lại.";
                console.error(message, error);
                if (!pushToast(message, "error") && errorBox) {
                    errorBox.textContent = message;
                    errorBox.style.display = "block";
                }
            });
    });
});
