/* global state quản lý lock submit toast navigation tránh spam interaction */
let toastLock = false;
let submitLock = false;
let navLock = false;
/* toast hiển thị message và optional redirect sau delay */
function showToast(message, redirect = false) {
    if (toastLock) return;
    toastLock = true;

    const toast = document.getElementById("toast");
    const msg = document.getElementById("toastMsg");
    const bar = document.getElementById("toastBar");

    msg.textContent = message;
    toast.style.display = "block";

    bar.style.animation = "none";
    bar.offsetHeight;
    bar.style.animation = "toastProgress 1.5s linear forwards";

    setTimeout(() => {
        toast.style.display = "none";
        toastLock = false;
    }, 1500);

    if (redirect) {
        navLock = true;
        setTimeout(() => {
            window.location = "./index.php";
        }, 1500);
    }
}
/* dom ready setup form submit lock back button và navigation control */
document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector("form");
    const submitBtn = document.querySelector(".edit-submit");
    const back = document.querySelector(".edit-back");

    /* submit lock tránh double submit */
    if (form) {
        form.addEventListener("submit", function (e) {
            if (submitLock) {
                e.preventDefault();
                return;
            }

            submitLock = true;
            navLock = true;

            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.style.opacity = "0.6";
                submitBtn.style.cursor = "not-allowed";
            }
        });
    }

    /* back button trigger toast rồi redirect */
    if (back) {
        back.addEventListener("click", function (e) {
            if (navLock) {
                e.preventDefault();
                return;
            }

            e.preventDefault();
            navLock = true;
            showToast("Đang quay lại trang chủ sau vài giây...", true);
        });
    }

    /* block toàn bộ click khi đang chuyển navigation */
    document.addEventListener(
        "click",
        function (e) {
            if (navLock) {
                e.preventDefault();
                e.stopPropagation();
            }
        },
        true
    );

    /* xử lý sau khi update thành công disable submit và auto redirect */
    if (window.location.search.includes("success=1")) {
        submitLock = true;
        navLock = true;

        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.style.opacity = "0.6";
            submitBtn.style.cursor = "not-allowed";
        }

        showToast("Cập nhật thông tin thành công");

        setTimeout(() => {
            window.location = "./index.php";
        }, 1500);
    }
});
/* tab switch giữa form cá nhân và học tập */
function switchTab(tab) {
    if (navLock) return;

    document.querySelectorAll(".tab-content").forEach(t => t.classList.remove("active"));
    document.querySelectorAll(".tab").forEach(b => b.classList.remove("active"));

    document.getElementById("tab" + tab).classList.add("active");
    document.querySelectorAll(".tab")[tab - 1].classList.add("active");
}
