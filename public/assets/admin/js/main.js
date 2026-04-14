$(function () {
  console.log($(location).attr("href"));
  var studentPagination = $("#student-pagination");
  var currentPage = 1;
  const rommTableElement = $("#table-rooms");
  const studentLimitSelect = $("#student-limit-select");
  const seachStudent = $("#search-student");
  const toastElement = $("#toast");
  const allBtnOpenModal = $(".btn--open__model");
  var $navBar = $("nav");
  var $sideBar = $("aside");
  setActiveSideBar();
  loadStudents();
  handelOpenModal(allBtnOpenModal);

  studentLimitSelect.on("change", function () {
    loadStudents();
  });
  seachStudent.on("keyup", function () {
    var keyWord = seachStudent.val();
    handleSeachStudent(keyWord);
  });
  // HẾT
  function setActiveSideBar() {
    var sideBarLink = $("#sideBar a");
    var currentUrl = window.location.href;
    if (sideBarLink) {
      $.each(sideBarLink, function (index, link) {
        var link = $(link);
        if (link.attr("href") == currentUrl) {
          link.addClass("sidebar__link--active");
        }
      });
    }
  }
  function handleSeachStudent(keyWord) {
    if (keyWord != "") {
      var url = `student/search/${keyWord}`;
      fetch(url)
        .then(function (response) {
          return response.json();
        })
        .then(function (data) {
          var limit = studentLimitSelect.val();
        });
    } else {
      loadStudents();
    }
  }

  // Sử lý logic mở modal
  function handelOpenModal(allBtnOpenModal) {
    if (allBtnOpenModal) {
      $.each(allBtnOpenModal, function (index, btnOpenModal) {
        btnOpenModal = $(btnOpenModal);
        if (btnOpenModal) {
          btnOpenModal.on("click", function () {
            const modal = btnOpenModal.attr("data-target");
            const modalElement = $(modal);
            const modalContainer = modalElement.find(".modal-container");
            const modalContainerElement = $(modalContainer);
            if (modalContainerElement) {
              modalContainer.on("click", function (even) {
                even.stopPropagation();
              });
            }
            if (modalElement) {
              showModal(modalElement);
              modalElement.on("click", function () {
                hiddenModal(modalElement);
              });
              var btnCloseModal = modalElement.find(".modal-close");
              if (btnCloseModal) {
                btnCloseModal.on("click", function () {
                  hiddenModal(modalElement);
                });
              }
              //     Lấy ra các form  trong modal và xử lý các form đó
              const form = modalElement.find("form");
              const formElement = $(form);

              if (formElement) {
                const dataId = formElement.attr("id");
                switch (dataId) {
                  case "formCapNhatSV":
                    const formElemet = $("#formCapNhatSV");
                    handelFormCreateSV(modalElement, formElement);
                    break;
                }
              }
            }
          });
        }
      });
    }
  }
  // Xử lý form trong modal
  function handelFormCreateSV(modalElement, formElement) {
    if (formElement) {
      Validator(formElement, {
        onSubmit: function (data) {
          var url = "student/addStudent";
          fetch(url, {
            method: "POST",
            headers: {
              "Content-Type": "application/json", // Sửa thành Object ở đây
            },
            body: JSON.stringify(data),
          })
            .then(function (response) {
              return response.json();
            })
            .then(function (data) {
              var status = data["status"];
              var message = data["message"];
              if (status == "success") {
                toast({
                  title: "Thành công",
                  message: `Thêm sinh viên thành công`,
                  type: "error",
                  duration: 3000,
                });
                loadStudents();
                setTimeout(() => {
                  hiddenModal(modalElement);
                }, 3000);
              }
              if (status == "error") {
                toast({
                  title: "Thất bại",
                  message: `Đã xảy ra lỗi khi thêm sinh viên ${message}`,
                  type: "error",
                  duration: 3000,
                });
              }
            });
        },
      });
    }
  }
  function showModal(modalElement) {
    if (modalElement) {
      modalElement.addClass("open");
    }
  }
  function hiddenModal(modalElement) {
    if (modalElement) {
      modalElement.removeClass("open");
    }
  }

  function loadStudentPagination(data) {
    let url = "student/getAllStudents";

    fetch(url)
      .then(function (response) {
        return response.json();
      })
      .then(function (datas) {
        var limit = parseInt(studentLimitSelect.val());
        var totalPage = Math.ceil(datas.length / limit);
        var htmls = [];
        if (totalPage > 1) {
          console.log(limit, totalPage);
          for (i = 1; i <= totalPage; i += 1) {
            if (i == currentPage) {
              var html = `<button  class="pagination-item pagination-item--active" data-page="${i}">${i}</button>`;
            } else {
              var html = `<button  class="pagination-item" data-page="${i}">${i}</button>`;
            }
            htmls.push(html);
          }
        } else {
        }

        studentPagination.html(
          `<ul class="pagination-list" >${htmls.join("")}</ul>`,
        );
        var btns = studentPagination.find("button");
        $.each(btns, function (i, btn) {
          var btn = $(btn);
          btn.on("click", function () {
            currentPage = btn.attr("data-page");
            loadStudents();
          });
        });
      })
      .catch(function (error) {});
  }
  //        Gắn sự kiện khi thay đổi số lượng hiển thị sinh viên

  //  PHẦN HIỂN THỊ DỮ LIỆU LÊN BẢNG SINH VIÊN
  function renderStudentTable(data) {
    console.log(data);
    var tableStudent = $("#table-students");
    var bodyTableStudent = tableStudent.find("tbody");
    var htmls = [];
    $.each(data, function ($index, $item) {
      html = `
            <tr>
                    <td>${$item["id"]}</td>
                    <td class="text-bold">${$item["mssv"]}</td>
                    <td class="text-nowrap">${$item["name"]}</td>
                    <td class="text-nowrap">${$item["birthday"]}</td>
                    <td>${$item["gender"]}</td>
                    <td>${$item["cccd"]}</td>
                    <td>${$item["email"]}</td>
                    <td>${$item["phone"]}</td>
                    <td class="text-muted">${$item["address"]}</td>
                    <td class="text-center">
                        <div class="action-group">
                            <btn  href="#" class="btn-icon btn-icon--edit btn-edit__student" title="Sửa"  student-id=${$item["id"]}>
                                <i class="fas fa-edit"></i>
                            </btn>
                            <button class="btn-icon btn-icon--delete btn-delete" title="Xóa" student-id=${$item["id"]}>
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>`;
      htmls.push(html);
    });
    var htmls = htmls.join("");
    bodyTableStudent.html(htmls);

    var listBtnEdit = bodyTableStudent.find(".btn-edit__student");
    var listBtnDelete = bodyTableStudent.find(".btn-delete");
    $.each(listBtnDelete, function (index, item) {
      var studentId = $(this).attr("student-id");
      handleDeleteStudent(studentId);
    });
    $.each(listBtnEdit, function (index, item) {
      var btnEdit = $(item);
      btnEdit.on("click", function () {
        var studentId = $(this).attr("student-id");
        console.log("ID sinh viên được chọn: " + studentId);
        handelUpdateStudent(studentId);
      });
    });
  }
  function handleDeleteStudent(sutdentId) {
    url = `student/delete/${sutdentId}`;
    fetch(url)
      .then(function (response) {
        return response.json();
      })
      .then(function (data) {
        if (data.status == "success") {
          toast({
            title: "Thành công",
            message: data.message,
            type: "success",
          });
        }
      });
  }
  function handelUpdateStudent(studentId) {
    const modalStudent = $("#model-student");
    url = `student/getStudentById/${studentId}`;
    fetch(url)
      .then(function (response) {
        return response.json();
      })
      .then(function (data) {
        if (modalStudent) {
          const form = $("#formCapNhatSV");
          form.find('input[name="id"]').val(data.id);
          form.find('input[name="mssv"]').val(data.mssv);
          form.find('input[name="name"]').val(data.name);
          form.find('input[name="birthday"]').val(data.birthday);
          form.find('input[name="email"]').val(data.email);
          form.find('input[name="cccd"]').val(data.cccd);
          form.find('input[name="phone"]').val(data.phone);
          form.find('input[name="address"]').val(data.address);
          var password = form.find('input[name="password"]');
          password.parent().remove();
          form.find('select[name="gender"]').val(data.gender);
          $("#model-student").css("display", "flex");
          Validator(form, {
            onSubmit: function (data) {
              var dataStudent = {
                id: data["id"],
                mssv: data["mssv"],
                name: data["name"],
                gender: data["gender"],
                birthday: data["birthday"],
                cccd: data["cccd"],
                email: data["email"],
                phone: data["phone"],
                address: data["address"],
              };
              url = "student/update";
              fetch(url, {
                method: "POST",
                headers: {
                  "Content-Type": "application/json",
                  Accept: "application/json",
                },
                body: JSON.stringify(dataStudent),
              })
                .then(function (response) {
                  return response.json();
                })
                .then(function (data) {
                  console.log(data.status);
                  if ((data.status = "success")) {
                    toast({
                      title: "Thành công",
                      message: data.message,
                      type: "success",
                      duration: 3000,
                    });
                    modalStudent.hide();
                    loadStudents();
                  }
                });
            },
          });
        }
      });
  }

  function loadStudents() {
    var limit = studentLimitSelect.val();

    var offSet = (currentPage - 1) * limit;
    const url = `student/getStudent/${limit}/${offSet}`;
    console.log(url);
    fetch(url)
      .then((response) => {
        if (!response.ok) {
          throw new Error("Lỗi kết nối mạng");
        }
        return response.json();
      })
      .then((data) => {
        renderStudentTable(data);
      })
      .catch((error) => {
        console.error(error);
      });
    loadStudentPagination();
  }
});
