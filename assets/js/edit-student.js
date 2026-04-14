/* global state quản lý lock submit toast navigation tránh spam interaction */
let toastLock = false;
let submitLock = false;
let navLock = false;
const EDIT_THEME_STORAGE_KEY = "student_app_theme";

/* toast hiển thị message và optional redirect sau delay */
function showToast(message, isSuccess = true, redirect = false) {
    if (toastLock) return;
    toastLock = true;

    const toast = document.getElementById("toast");
    const msg = document.getElementById("toastMsg");
    const bar = document.getElementById("toastBar");

    msg.textContent = message;
    toast.classList.toggle("toast-error", !isSuccess);
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

function applyEditTheme(theme) {
    document.body.setAttribute("data-theme", theme);
    const toggleButton = document.querySelector(".edit-theme-toggle");
    if (toggleButton) {
        toggleButton.textContent = theme === "dark" ? "Dark mode" : "Light mode";
    }
}

function toggleEditTheme() {
    const currentTheme = document.body.getAttribute("data-theme") === "dark" ? "dark" : "light";
    const nextTheme = currentTheme === "dark" ? "light" : "dark";
    localStorage.setItem(EDIT_THEME_STORAGE_KEY, nextTheme);
    applyEditTheme(nextTheme);
}

/* dom ready setup form submit lock back button và navigation control */
document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector("form");
    const submitBtn = document.querySelector(".edit-submit");
    const back = document.querySelector(".edit-back");
    const pageState = window.editPageState || { success: false, errorMessage: "" };

    applyEditTheme(localStorage.getItem(EDIT_THEME_STORAGE_KEY) || "light");

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
            showToast("Đang quay lại trang chủ sau vài giây...", true, true);
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

    /* sau redirect từ backend thì hiện toast đúng theo success/error */
    if (pageState.success) {
        submitLock = true;
        navLock = true;

        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.style.opacity = "0.6";
            submitBtn.style.cursor = "not-allowed";
        }

        showToast("Cập nhật thông tin thành công", true);

        setTimeout(() => {
            window.location = "./index.php";
        }, 1500);
    } else if (pageState.errorMessage) {
        showToast(pageState.errorMessage, false);
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
