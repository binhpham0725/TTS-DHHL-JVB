let toastLock = false;
let submitLock = false;
let navLock = false;

/* ================= TOAST ================= */

function showToast(message, redirect = false) {
    if (toastLock) return;
    toastLock = true;

    const toast = document.getElementById("toast");
    const msg = document.getElementById("toastMsg");
    const bar = document.getElementById("toastBar");

    msg.textContent = message;
    toast.style.display = "block";

    /* reset animation */
    bar.style.animation = "none";
    bar.offsetHeight;
    bar.style.animation = "toastProgress 3s linear forwards";

    /* auto hide */
    setTimeout(() => {
        toast.style.display = "none";
        toastLock = false;
    }, 3000);

    /* redirect */
    if (redirect) {
        navLock = true;
        setTimeout(() => {
            window.location = "home.php";
        }, 3000);
    }
}

/* ================= DOM ================= */

document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector("form");
    const submitBtn = document.querySelector(".edit-submit");
    const back = document.querySelector(".edit-back");

    /* ===== SUBMIT LOCK ===== */
    if (form) {
        form.addEventListener("submit", function (e) {
            if (submitLock) {
                e.preventDefault();
                return;
            }

            submitLock = true;
            navLock = true;

            /* disable button */
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.style.opacity = "0.6";
                submitBtn.style.cursor = "not-allowed";
            }
        });
    }

    /* ===== BACK BUTTON ===== */
    if (back) {
        back.addEventListener("click", function (e) {
            if (navLock) {
                e.preventDefault();
                return;
            }

            e.preventDefault();
            navLock = true;
            showToast("Đang quay lại trang chủ sau 3 giây...", true);
        });
    }

    /* ===== GLOBAL CLICK BLOCK ===== */
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

    /* ===== AFTER SUCCESS ===== */
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
            window.location = "home.php";
        }, 3000);
    }
});

/* ================= TAB ================= */

function switchTab(tab) {
    if (navLock) return;

    document.querySelectorAll(".tab-content").forEach((t) => t.classList.remove("active"));
    document.querySelectorAll(".tab").forEach((b) => b.classList.remove("active"));

    document.getElementById("tab" + tab).classList.add("active");
    document.querySelectorAll(".tab")[tab - 1].classList.add("active");
}