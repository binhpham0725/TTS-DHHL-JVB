document.addEventListener('DOMContentLoaded', function () {
    const addModal = document.getElementById('addStudentModal');
    const importModal = document.getElementById('importModal');
    const editModal = document.getElementById('editStudentModal');
    const editModalContent = document.getElementById('editModalContent');
    const texts = (window.APP_TEXTS && window.APP_TEXTS.students) || {};

    const openAddBtn = document.getElementById('openAddModal');
    const openImportBtn = document.getElementById('openImportModal');

    function openModal(modal) {
        if (modal) {
            modal.classList.add('show');
        }
    }

    function closeModal(modal) {
        if (modal) {
            modal.classList.remove('show');
        }
    }

    function closeAllModals() {
        closeModal(addModal);
        closeModal(importModal);
        closeModal(editModal);
    }

    function loadEditForm(studentId) {
        if (!editModal || !editModalContent) {
            console.error(texts.missing_modal || 'Không tìm thấy modal sửa hoặc vùng chứa nội dung.');
            return;
        }

        editModalContent.innerHTML = '<p>' + (texts.loading || 'Đang tải...') + '</p>';
        openModal(editModal);

        fetch('../function/students/edit.php?id=' + encodeURIComponent(studentId), {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
            .then(function (response) {
                if (!response.ok) {
                    throw new Error('HTTP error: ' + response.status);
                }
                return response.text();
            })
            .then(function (html) {
                editModalContent.innerHTML = html;
            })
            .catch(function (error) {
                console.error(texts.load_error || 'Không tải được form sửa.', error);
                editModalContent.innerHTML = '<p>' + (texts.load_error || 'Không tải được form sửa.') + '</p>';
            });
    }

    if (openAddBtn) {
        openAddBtn.addEventListener('click', function (e) {
            e.preventDefault();
            openModal(addModal);
        });
    }

    if (openImportBtn) {
        openImportBtn.addEventListener('click', function (e) {
            e.preventDefault();
            openModal(importModal);
        });
    }

    document.addEventListener('click', function (e) {
        const closeBtn = e.target.closest('[data-close]');
        if (closeBtn) {
            e.preventDefault();
            const modalId = closeBtn.getAttribute('data-close');
            const modal = document.getElementById(modalId);
            closeModal(modal);
            return;
        }

        const editBtn = e.target.closest('.edit-btn');
        if (editBtn) {
            e.preventDefault();
            const id = editBtn.getAttribute('data-id');

            if (!id) {
                console.error(texts.missing_student_id || 'Không tìm thấy data-id của sinh viên.');
                return;
            }

            loadEditForm(id);
            return;
        }

        if (e.target.classList.contains('modal')) {
            closeModal(e.target);
        }
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            closeAllModals();
        }
    });

    // Xử lý submit form edit bằng delegation vì form được nạp động qua AJAX.
    document.addEventListener('submit', function (e) {
        const form = e.target.closest('#editStudentForm');
        if (!form) {
            return;
        }

        e.preventDefault();

        const errorBox = document.getElementById('editError');
        errorBox.style.display = 'none';
        errorBox.textContent = '';

        const formData = new FormData(form);

        fetch('../function/students/edit.php', {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            body: formData
        })
            .then(function (response) {
                return response.json();
            })
            .then(function (data) {
                if (data.success) {
                    closeModal(editModal);
                    location.reload();
                } else {
                    errorBox.textContent = data.message || texts.update_failed || 'Cập nhật thất bại.';
                    errorBox.style.display = 'block';
                }
            })
            .catch(function (error) {
                console.error(texts.unexpected_error || 'Có lỗi xảy ra, vui lòng thử lại.', error);
                errorBox.textContent = texts.unexpected_error || 'Có lỗi xảy ra, vui lòng thử lại.';
                errorBox.style.display = 'block';
            });
    });
});
