$(document).ready(function () {
  var table = $("#table");
  var current_page = 1;
  const formAssign = $("#form-assign-room");
  const formReject = $("#form-reject-room");
  const modalAssign = $("#modal-assign-room");
  const modalReject = $("#modal-reject-room");
  getListData(current_page);

  // Reset về trang 1 khi filter thay đổi
  $("#keyword, #status, #time_start, #per_page").on("change input", function () {
    current_page = 1;
    getListData(current_page);
  });

  function getListData(page) {
    const data = {
      page: page ?? 1,
      per_page: parseInt($("#per_page").val()) || 20,
      keyword: $("#keyword").val() ?? "",
      status: $("#status").val() ?? "",
      time_start: $("#time_start").val() ?? "",
    };
    var url = "roomassignment/getListData";
    fetch(url, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(data),
    })
      .then(function (resphonse) {
        return resphonse.json();
      })
      .then(function (data) {
        if ((data.status = "success")) {
          loadTable(data.data);
          renderPagination(data.total_page);
        }
      });
  }

  function loadTable(data) {
    var tbody = table.find("tbody"); // Thay bằng ID thực tế của em

    if (data && data.length > 0) {
      var htmls = [];
      $.each(data, function (index, item) {
        // Xử lý giá trị mặc định cho ngày tháng
        var check_in = item.check_in || "Chưa xác định";
        var check_out = item.check_out || "Chưa xác định";

        var statusText = "";
        var htmlAction = "";

        // Ép kiểu sang Int để so sánh switch case chuẩn xác
        switch (parseInt(item.status)) {
          case 1:
            statusText = "Đã duyệt";
            htmlAction = `<td class="text-center"><i class="fas fa-grin-stars fa-2x text-success"></i></td>`;
            break;
          case 2:
            statusText = "Từ chối";
            htmlAction = `<td class="text-center"><i class="fas fa-frown fa-2x text-danger"></i></td>`;
            break;
          case 0:
          default:
            statusText = "Chưa duyệt";
            htmlAction = `
                        <td class="text-center">
                            <div class="action-group">
                                <button class="btn-icon btn-approve" title="Duyệt" data-id="${item.id}">
                                    <i class="fas fa-check-circle fa-2x text-success"></i>
                                </button>
                                <button class="btn-icon btn-danger btn-reject" title="Từ chối" data-id="${item.id}">
                                    <i class="fas fa-times-circle fa-2x text-danger"></i>
                                </button>
                            </div>
                        </td>`;
            break;
        }

        var html = `
                <tr>
                    <td class="text-center">${item.id}</td>
                    <td class="text-bold text-center">${item.student_name}</td>
                    <td class="text-center">${item.type_name}</td>
                    <td class="text-center">${item.created_date}</td>
                    <td class="text-center">${check_in}</td>
                    <td class="text-center">${check_out}</td>
                    <td class="text-center">${statusText}</td>
                    ${htmlAction}
                </tr>`;

        htmls.push(html);
      });

      tbody.html(htmls.join(""));
      tbody
        .find(".btn-reject")
        .off("click")
        .on("click", function () {
          var dataId = $(this).attr("data-id");
          showModalReject();
          handleReject(dataId);
        });

      tbody
        .find(".btn-approve")
        .off("click")
        .on("click", function () {
          var dataId = $(this).attr("data-id");
          showModalAssign();
          hadleApprove(dataId);
        });
    } else {
      // Hiển thị thông báo nếu không có dữ liệu (UX tốt)
      tbody.html(
        '<tr><td colspan="8" class="text-center">Không tìm thấy dữ liệu phù hợp</td></tr>',
      );
    }
  }
  function renderPagination(totalItems) {
    var perPage = parseInt($("#per_page").val()) || 20;
    var totalPage = Math.ceil(totalItems / perPage);
    var paginationContainer = $(".pagination-list");
    var html = "";
    var sublink = 3;

    if (totalPage <= 1) {
      paginationContainer.html("");
      return;
    }

    // Prev
    if (current_page > 1) {
      html += `<li><a href="#" class="page-btn" data-page="${current_page - 1}">Prev</a></li>`;
    }

    var start = Math.max(1, current_page - Math.floor(sublink / 2));
    var end = Math.min(totalPage, start + sublink - 1);
    if (end - start + 1 < sublink) {
      start = Math.max(1, end - sublink + 1);
    }

    // Trang đầu + ...
    if (start > 1) {
      html += `<li><a href="#" class="page-btn" data-page="1">1</a></li>`;
      if (start > 2) html += `<li><span>...</span></li>`;
    }

    // Các trang chính
    for (var i = start; i <= end; i++) {
      html += `<li><a href="#" class="page-btn${i === current_page ? " active" : ""}" data-page="${i}">${i}</a></li>`;
    }

    // ... + Trang cuối
    if (end < totalPage) {
      if (end < totalPage - 1) html += `<li><span>...</span></li>`;
      html += `<li><a href="#" class="page-btn" data-page="${totalPage}">${totalPage}</a></li>`;
    }

    // Next
    if (current_page < totalPage) {
      html += `<li><a href="#" class="page-btn" data-page="${current_page + 1}">Next</a></li>`;
    }

    paginationContainer.html(html);

    paginationContainer.find(".page-btn").off("click").on("click", function (e) {
      e.preventDefault();
      var page = parseInt($(this).data("page"));
      if (page !== current_page) {
        current_page = page;
        getListData(current_page);
      }
    });
  }
  function showModalReject() {
    if (!modalReject.hasClass("open")) {
      modalReject.addClass("open");
    }
  }
  function hiddenModalReject() {
    if (modalReject && modalReject.length > 0) {
      modalReject.removeClass("open");
      const form = modalReject.find("form");
      if (form.length > 0) {
        form[0].reset();
      }
    }
  }
  function handleReject(dataId) {
    formReject.find(`input[name="id"`).val(dataId);
    Validator(formReject, {
      onSubmit: function (data) {
        console.log(data);
        fetch("roomassignment/reject", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify(data),
        })
          .then(function (resphonse) {
            return resphonse.json();
          })
          .then(function (data) {
            if (data.status == "success") {
              toast({
                title: "Thành công",
                message: data.message,
                type: "success",
                duration: 3000,
              });
              hiddenModalReject();
              getListData(current_page);
            } else {
              toast({
                title: "Thất bại",
                message: data.message,
                type: "error",
                duration: 3000,
              });
            }
          })
          .catch(function (error) {
            toast({
              title: "Thất bại",
              message: "Đã xảy ra lỗi vui lòng thử lại sau",
              type: "error",
              duration: 3000,
            });
          });
      },
    });
  }
  function hadleApprove(dataId) {
    modalAssign
      .find(".btn-close")
      .off("click")
      .on("click", function () {
        hadleCloseModal();
      });
    redenrModalApprove(dataId);
    if (typeof Validator === "function") {
      Validator(formAssign, {
        onSubmit: function (data) {
          fetch("roomassignment/approve", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(data),
          })
            .then((res) => res.json())
            .then((data) => {
              if (data.status == "success") {
                toast({
                  title: "Thành công",
                  message: data.message,
                  type: "success",
                });
                hadleCloseModal();
                getListData(current_page);
              } else {
                toast({
                  title: "Thất bại",
                  message: data.message,
                  type: "error",
                });
              }
            });
        },
      });
    }
  }
  function hadleCloseModal() {
    modalAssign.removeClass("open");
  }

  function showModalAssign() {
    modalAssign.addClass("open");
  }

  function redenrModalApprove(id) {
    fetch(`roomassignment/detail/${id}`)
      .then((response) => response.json())
      .then((data) => {
        formAssign.find("input[name='id']").val(data.id);
        formAssign.find("#student-name").val(data.name || data.student_name);
        formAssign.find("#room-type-name").val(data.type_name);
        redenrSelectRoom(data.room_type_id, data.gender);
      });
  }
  function redenrSelectRoom(roomTypeId, gender) {
    fetch(`roomassignment/getAvailableRooms/${roomTypeId}/${gender}`)
      .then((response) => response.json())
      .then((data) => {
        var select = $("#select-room-id");
        var htmls = data.map(
          (item) => `<option value="${item.id}">${item.room_name}</option>`,
        );
        select.html(htmls.join());
      });
  }
});
