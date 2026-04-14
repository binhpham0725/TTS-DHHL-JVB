$(document).ready(function () {
  var current_page = 1;
  var modalAdd    = $("#modal-add-student");
  var modalEdit   = $("#modal-edit-student");
  var modalDelete = $("#modal-delete-student");
  var formAdd     = $("#form-add-student");
  var formEdit    = $("#form-edit-student");

  getListData(current_page);

  // Filter
  $("#keyword, #per_page").on("input change", function () {
    current_page = 1;
    getListData(current_page);
  });

  // Mở modal thêm
  $("#btn-open-add").on("click", function () {
    formAdd[0].reset();
    modalAdd.addClass("open");
  });

  // Đóng modal
  $(document).on("click", ".modal-close", function () {
    $(this).closest(".modal").removeClass("open");
  });

  // =====================
  // LOAD TABLE
  // =====================
  function getListData(page) {
    var payload = {
      page:     page,
      per_page: parseInt($("#per_page").val()) || 10,
      keyword:  $("#keyword").val().trim(),
    };
    fetch("student/getListData", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(payload),
    })
      .then((r) => r.json())
      .then((res) => {
        if (res.status === "success") {
          renderTable(res.data);
          renderPagination(res.total_page);
        }
      });
  }

  function renderTable(data) {
    var tbody = $("#table-students tbody");
    if (!data || data.length === 0) {
      tbody.html('<tr><td colspan="10" class="text-center">Không có dữ liệu</td></tr>');
      return;
    }
    var per_page = parseInt($("#per_page").val()) || 10;
    var offset   = (current_page - 1) * per_page;
    var rows = data.map(function (item, i) {
      return `<tr>
        <td class="text-center">${offset + i + 1}</td>
        <td class="text-bold">${item.mssv}</td>
        <td class="text-nowrap">${item.name}</td>
        <td class="text-center">${item.birthday ?? ""}</td>
        <td class="text-center">${parseInt(item.gender) === 1 ? "Nam" : "Nữ"}</td>
        <td class="text-center">${item.cccd}</td>
        <td>${item.email}</td>
        <td class="text-center">${item.phone}</td>
        <td>${item.address}</td>
        <td class="text-center">
          <div class="action-group">
            <button class="btn-icon btn-icon--edit btn-edit" title="Sửa" data-id="${item.id}">
              <i class="fas fa-edit"></i>
            </button>
            <button class="btn-icon btn-icon--delete btn-delete" title="Xóa" data-id="${item.id}" data-name="${item.name}">
              <i class="fas fa-trash"></i>
            </button>
          </div>
        </td>
      </tr>`;
    });
    tbody.html(rows.join(""));

    // Sửa
    tbody.find(".btn-edit").off("click").on("click", function () {
      var id = $(this).data("id");
      openEditModal(id);
    });

    // Xóa
    tbody.find(".btn-delete").off("click").on("click", function () {
      var id   = $(this).data("id");
      var name = $(this).data("name");
      $("#delete-student-id").val(id);
      $("#delete-student-name").text(name);
      modalDelete.addClass("open");
    });
  }

  // =====================
  // PAGINATION
  // =====================
  function renderPagination(totalPage) {
    var container = $(".pagination-list");
    if (totalPage <= 1) { container.html(""); return; }

    var sublink = 3, html = "";
    if (current_page > 1)
      html += `<li><a href="#" class="page-btn" data-page="${current_page - 1}">Prev</a></li>`;

    var start = Math.max(1, current_page - Math.floor(sublink / 2));
    var end   = Math.min(totalPage, start + sublink - 1);
    if (end - start + 1 < sublink) start = Math.max(1, end - sublink + 1);

    if (start > 1) {
      html += `<li><a href="#" class="page-btn" data-page="1">1</a></li>`;
      if (start > 2) html += `<li><span>...</span></li>`;
    }
    for (var i = start; i <= end; i++)
      html += `<li><a href="#" class="page-btn${i === current_page ? " active" : ""}" data-page="${i}">${i}</a></li>`;
    if (end < totalPage) {
      if (end < totalPage - 1) html += `<li><span>...</span></li>`;
      html += `<li><a href="#" class="page-btn" data-page="${totalPage}">${totalPage}</a></li>`;
    }
    if (current_page < totalPage)
      html += `<li><a href="#" class="page-btn" data-page="${current_page + 1}">Next</a></li>`;

    container.html(html);
    container.find(".page-btn").off("click").on("click", function (e) {
      e.preventDefault();
      var p = parseInt($(this).data("page"));
      if (p !== current_page) { current_page = p; getListData(current_page); }
    });
  }

  // =====================
  // THÊM SINH VIÊN
  // =====================
  Validator(formAdd, {
    onSubmit: function (data) {
      fetch("student/addStudent", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(data),
      })
        .then((r) => r.json())
        .then((res) => {
          if (res.status === "success") {
            toast({ title: "Thành công", message: res.message, type: "success", duration: 3000 });
            modalAdd.removeClass("open");
            formAdd[0].reset();
            current_page = 1;
            getListData(current_page);
          } else {
            toast({ title: "Thất bại", message: res.message, type: "error", duration: 3000 });
          }
        });
    },
  });

  // =====================
  // SỬA SINH VIÊN
  // =====================
  function openEditModal(id) {
    fetch(`student/getStudentById/${id}`)
      .then((r) => r.json())
      .then((s) => {
        formEdit.find('[name="id"]').val(s.id);
        formEdit.find('[name="mssv"]').val(s.mssv);
        formEdit.find('[name="name"]').val(s.name);
        formEdit.find('[name="birthday"]').val(s.birthday);
        formEdit.find('[name="gender"]').val(s.gender);
        formEdit.find('[name="cccd"]').val(s.cccd);
        formEdit.find('[name="email"]').val(s.email);
        formEdit.find('[name="phone"]').val(s.phone);
        formEdit.find('[name="address"]').val(s.address);
        modalEdit.addClass("open");
      });
  }

  Validator(formEdit, {
    onSubmit: function (data) {
      fetch("student/update", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(data),
      })
        .then((r) => r.json())
        .then((res) => {
          if (res.status === "success") {
            toast({ title: "Thành công", message: res.message, type: "success", duration: 3000 });
            modalEdit.removeClass("open");
            getListData(current_page);
          } else {
            toast({ title: "Thất bại", message: res.message, type: "error", duration: 3000 });
          }
        });
    },
  });

  // =====================
  // XÓA SINH VIÊN
  // =====================
  $("#btn-confirm-delete").on("click", function () {
    var id = $("#delete-student-id").val();
    fetch(`student/delete/${id}`)
      .then((r) => r.json())
      .then((res) => {
        if (res.status === "success") {
          toast({ title: "Thành công", message: res.message, type: "success", duration: 3000 });
          modalDelete.removeClass("open");
          getListData(current_page);
        } else {
          toast({ title: "Thất bại", message: res.message, type: "error", duration: 3000 });
        }
      });
  });
});
