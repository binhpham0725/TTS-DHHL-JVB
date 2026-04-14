function toast({ title = "", message = "", type = "info", duration = 3000 }) {
  const main = $("#toast");
  if (main) {
    const icons = {
      success: "fa-circle-check",
      info: "fa-circle-info",
      warning: "fa-triangle-exclamation",
      error: "fa-circle-xmark",
    };
    const htmls = `
            <div class="toast__icon">
                <i class="fa-solid ${icons[type]}"></i>
            </div>
            <div class="toast__body">
                <h3 class="toast__title">${title}</h3>
                <p class="toast__msg">${message}</p>
            </div>
            <div class="toast__close">
                <i class="fa-solid fa-xmark"></i>
            </div>
        `;
    const toast = $("<div></div>");
    toast
      .delay(duration) // Đợi 3s (giống tham số 3s trong CSS của bạn)
      .animate(
        {
          opacity: 0,
        },
        1000, // Chạy hiệu ứng trong 1s
        function () {
          $(this).remove(); // Xóa chính nó
        },
      );
    toast.html(htmls);
    toast.addClass("toast");
    toast.addClass(`toast--${type}`);
    main.append(toast);
  }
}
