$(document).ready(function () {
  var modal      = $("#modal-room");
  var formRoom   = $("#form-room");
  var modalDelete = $("#modal-delete-room");
  var isEditMode = false;

  loadTable();

  // Tìm kiếm
  $("#keyword-room").on("input", function () { loadTable(); });

  // Xuất CSV
  $("#btnExport").on("click", function () {
    $("#table-rooms").table2csv({ filename: "danh-sach-phong.csv", excludeColumns: ".no_export" });
  });

  // Mở modal thêm
  $("#btn-open-add-room").on("click", function () {
    isEditMode = false;
    formRoom[0].reset();
    formRoom.find('[name="id"]').val("");
    $("#wrap-room-status").hide();
    modal.find(".modal-title").text("Thêm phòng");
    loadRoomTypes(null);
    modal.addClass("open");
  });

  // Đóng modal
  $(document).on("click", ".modal-close", function () {
    $(this).closest(".modal").removeClass("open");
  });

  // =====================
  // LOAD BẢNG
  // =====================
  function loadTable() {
    var keyword = $("#keyword-room").val().toLowerCase();
    fetch("room/getRooms")
      .then((r) => r.json())
      .then((data) => {
        if (keyword) {
          data = data.filter((r) => r.room_name.toLowerCase().includes(keyword));
        }
        renderTable(data);
      });
  }

  function renderTable(data) {
    var tbody = $("#table-rooms tbody");
    if (!data || data.length === 0) {
      tbody.html('<tr><td colspan="7" class="text-center">Không có dữ liệu</td></tr>');
      return;
    }
    var rows = data.map(function (room, i) {
      var statusBadge = room.status == 1
        ? '<span class="badge badge--success">Hoạt động</span>'
        : '<span class="badge badge--danger">Sửa chữa</span>';
      var genderText = parseInt(room.gender) === 1 ? "Nam" : "Nữ";
      return `<tr>
        <td class="text-center">${i + 1}</td>
        <td class="text-bold text-center">${room.room_name}</td>
        <td class="text-center">${room.type_name}</td>
        <td class="text-center">${genderText}</td>
        <td class="text-center">${room.current_number}</td>
        <td class="text-center">${statusBadge}</td>
        <td class="text-center no_export">
          <div class="action-group">
            <button class="btn-icon btn-icon--edit btn-edit-room" title="Sửa" data-id="${room.id}">
              <i class="fas fa-edit"></i>
            </button>
            <button class="btn-icon btn-icon--delete btn-delete-room" title="Xóa" data-id="${room.id}" data-name="${room.room_name}">
              <i class="fas fa-trash"></i>
            </button>
          </div>
        </td>
      </tr>`;
    });
    tbody.html(rows.join(""));

    tbody.find(".btn-edit-room").off("click").on("click", function () {
      openEditModal($(this).data("id"));
    });

    tbody.find(".btn-delete-room").off("click").on("click", function () {
      $("#delete-room-id").val($(this).data("id"));
      $("#delete-room-name").text($(this).data("name"));
      modalDelete.addClass("open");
    });
  }

  // =====================
  // LOAD LOẠI PHÒNG
  // =====================
  function loadRoomTypes(selectedId) {
    return fetch("roomtype/list")
      .then((r) => r.json())
      .then((data) => {
        var options = data.map((t) => `<option value="${t.id}">${t.type_name}</option>`);
        $("#room_type").html(options.join(""));
        if (selectedId) $("#room_type").val(String(selectedId));
      });
  }

  // =====================
  // SỬA PHÒNG
  // =====================
  function openEditModal(id) {
    fetch(`room/details/${id}`)
      .then((r) => r.json())
      .then((res) => {
        if (res.status !== "success") return;
        var room = res.data_room;
        isEditMode = true;
        formRoom.find('[name="id"]').val(room.id);
        formRoom.find('[name="room_name"]').val(room.room_name);
        formRoom.find('[name="gender"]').val(String(room.gender));
        formRoom.find('[name="room_status"]').val(String(room.status));
        $("#wrap-room-status").show();
        modal.find(".modal-title").text("Cập nhật phòng");
        loadRoomTypes(room.room_type_id).then(() => {
          modal.addClass("open");
        });
      });
  }

  // =====================
  // SUBMIT FORM (THÊM + SỬA)
  // =====================
  Validator(formRoom, {
    onSubmit: function (data) {
      var url    = isEditMode ? "room/update" : "room/add";
      var payload = isEditMode
        ? { id: data.id, room_name: data.room_name, room_type: data.room_type, room_status: data.room_status, gender: data.gender }
        : { room_name: data.room_name, room_type_id: data.room_type, gender: data.gender };

      fetch(url, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(payload),
      })
        .then((r) => r.json())
        .then((res) => {
          if (res.status === "success") {
            toast({ title: "Thành công", message: res.message, type: "success", duration: 3000 });
            modal.removeClass("open");
            formRoom[0].reset();
            loadTable();
          } else {
            toast({ title: "Thất bại", message: res.message, type: "error", duration: 3000 });
          }
        });
    },
  });

  // =====================
  // XÓA PHÒNG
  // =====================
  $("#btn-confirm-delete-room").on("click", function () {
    var id = $("#delete-room-id").val();
    fetch(`room/delete/${id}`)
      .then((r) => r.json())
      .then((res) => {
        if (res.status === "success") {
          toast({ title: "Thành công", message: res.message, type: "success", duration: 3000 });
          modalDelete.removeClass("open");
          loadTable();
        } else {
          toast({ title: "Thất bại", message: res.message, type: "error", duration: 3000 });
        }
      });
  });
});
