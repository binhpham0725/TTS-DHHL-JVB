document.addEventListener('DOMContentLoaded', function () {
  const addModal = document.getElementById('addStudentModal');
  const importModal = document.getElementById('importModal');
  const editModal = document.getElementById('editStudentModal');
  const editModalContent = document.getElementById('editModalContent');

  const openAddBtn = document.getElementById('openAddModal');
  const openImportBtn = document.getElementById('openImportModal');

  if (openAddBtn && addModal) {
    openAddBtn.addEventListener('click', function (e) {
      e.preventDefault();
      addModal.classList.add('show');
    });
  }

  if (openImportBtn && importModal) {
    openImportBtn.addEventListener('click', function (e) {
      e.preventDefault();
      importModal.classList.add('show');
    });
  }

  document.addEventListener('click', function (e) {
    const closeBtn = e.target.closest('[data-close]');
    if (closeBtn) {
      e.preventDefault();
      const modalId = closeBtn.getAttribute('data-close');
      const modal = document.getElementById(modalId);
      if (modal) modal.classList.remove('show');
      return;
    }

    if (e.target.classList.contains('modal')) {
      e.target.classList.remove('show');
      return;
    }

    const editBtn = e.target.closest('.edit-btn');
    if (editBtn) {
      e.preventDefault();

      const id = editBtn.getAttribute('data-id');
      if (!id) return;

      if (!editModal || !editModalContent) {
        console.error('Không tìm thấy edit modal hoặc editModalContent');
        return;
      }

      editModalContent.innerHTML = '<p>Đang tải...</p>';

      fetch('/students-management/students/edit.php?id=' + encodeURIComponent(id), {
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
          editModal.classList.add('show');
        })
        .catch(function (error) {
          console.error('Lỗi load form edit:', error);
          editModalContent.innerHTML = '<p>Không tải được form sửa.</p>';
          editModal.classList.add('show');
        });
    }
  });
});