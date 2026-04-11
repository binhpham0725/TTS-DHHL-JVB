/* global state quản lý animation lock và toast timing tránh spam interaction */
let isSwitching = false;
let lastToastTime = 0;
const TOAST_COOLDOWN = 3000;
/* DOM references cho tab, form và card container */
const signinTab = document.getElementById("signinTab");
const signupTab = document.getElementById("signupTab");
const signinForm = document.getElementById("signinForm");
const signupForm = document.getElementById("signupForm");
const card = document.querySelector(".card");
/* toast hiển thị message feedback với icon và progress bar */
function showToast(text, success = true) {
    let now = Date.now();
    if (now - lastToastTime < TOAST_COOLDOWN) return;

    lastToastTime = now;

    let toast = document.getElementById("toast");
    let msg = document.getElementById("toastText");
    let icon = document.getElementById("toastIcon");
    let bar = document.getElementById("toastBar");

    msg.innerText = text;
    icon.innerText = success ? "✔" : "⚠";
    bar.style.background = success ? "#6c5ce7" : "#ff6b6b";

    toast.style.display = "flex";

    bar.style.animation = "none";
    bar.offsetHeight;
    bar.style.animation = "toastProgress 3s linear forwards";

    setTimeout(() => {
        toast.style.display = "none";
    }, 3000);
}
/* animation chuyển tab giữa signin signup với lock tránh spam click */
function animateSwitch(hideForm, showForm, direction) {
    if (isSwitching) return;
    isSwitching = true;

    signinTab.disabled = true;
    signupTab.disabled = true;

    const startHeight = card.offsetHeight;

    hideForm.animate(
        [
            { transform: "translateX(0)", opacity: 1 },
            { transform: `translateX(${direction * -40}px)`, opacity: 0 }
        ],
        { duration: 300 }
    ).onfinish = () => {
        hideForm.classList.remove("active");
        showForm.classList.add("active");

        const endHeight = card.offsetHeight;

        card.style.height = startHeight + "px";

        requestAnimationFrame(() => {
            card.style.height = endHeight + "px";
        });

        setTimeout(() => {
            card.style.height = "auto";
            isSwitching = false;

            signinTab.disabled = false;
            signupTab.disabled = false;
        }, 350);

        showForm.animate(
            [
                { transform: `translateX(${direction * 40}px)`, opacity: 0 },
                { transform: "translateX(0)", opacity: 1 }
            ],
            { duration: 300 }
        );
    };
}
/* tab click handler điều hướng form tương ứng */
signinTab.onclick = () => {
    if (signinForm.classList.contains("active") || isSwitching) return;

    signinTab.classList.add("active");
    signupTab.classList.remove("active");

    animateSwitch(signupForm, signinForm, -1);
};

signupTab.onclick = () => {
    if (signupForm.classList.contains("active") || isSwitching) return;

    signupTab.classList.add("active");
    signinTab.classList.remove("active");

    animateSwitch(signinForm, signupForm, 1);
};
/* toggle password visibility cho các input password */
function togglePassword(open, closed, input) {
    open.onclick = () => {
        input.type = "text";
        open.style.display = "none";
        closed.style.display = "block";
    };

    closed.onclick = () => {
        input.type = "password";
        closed.style.display = "none";
        open.style.display = "block";
    };
}
togglePassword(
    document.getElementById("signinEyeOpen"),
    document.getElementById("signinEyeClosed"),
    document.getElementById("signinPassword")
);

togglePassword(
    document.getElementById("signupEyeOpen"),
    document.getElementById("signupEyeClosed"),
    document.getElementById("signupPassword")
);

togglePassword(
    document.getElementById("confirmEyeOpen"),
    document.getElementById("confirmEyeClosed"),
    document.getElementById("confirmPassword")
);
/* email regex validation dùng chung cho signin signup */
const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
/* signin submit xử lý validation và gọi ajax login */
signinForm.addEventListener("submit", function (e) {
    e.preventDefault();

    let email = document.getElementById("signinEmail").value.trim();
    let password = document.getElementById("signinPassword").value.trim();

    if (email === "") return showToast("Email is required", false);
    if (!emailPattern.test(email)) return showToast("Email format invalid", false);
    if (password === "") return showToast("Password required", false);
    if (password.length < 8) return showToast("Password >= 8 characters", false);

    let formData = new FormData();
    formData.append("email", email);
    formData.append("password", password);

    fetch(window.authPageConfig.loginApi, {
        method: "POST",
        body: formData
    })
        .then(res => res.text())
        .then(data => {
            if (data === "success") {
                showToast("Đăng nhập thành công", true);

                setTimeout(() => {
                    window.location.href = window.authPageConfig.studentPageUrl;
                }, 1000);
            } else if (data === "wrong_password") {
                showToast("Sai mật khẩu", false);
            } else {
                showToast("Email không tồn tại", false);
            }
        });
});
/* signup submit validate input và gửi ajax tạo tài khoản */
signupForm.addEventListener("submit", function (e) {
    e.preventDefault();

    let username = document.getElementById("signupUsername").value.trim();
    let email = document.getElementById("signupEmail").value.trim();
    let birthday = document.getElementById("birthday").value;
    let password = document.getElementById("signupPassword").value;
    let confirm = document.getElementById("confirmPassword").value;

    if (username === "") return showToast("Nhập username", false);
    if (email === "") return showToast("Nhập email", false);
    if (!emailPattern.test(email)) return showToast("Email sai định dạng", false);
    if (birthday === "") return showToast("Nhập ngày sinh", false);
    if (password === "") return showToast("Nhập mật khẩu", false);
    if (password.length < 8) return showToast("Mật khẩu >= 8 ký tự", false);
    if (confirm === "") return showToast("Xác nhận mật khẩu", false);
    if (password !== confirm) return showToast("Mật khẩu không khớp", false);

    let formData = new FormData();
    formData.append("username", username);
    formData.append("email", email);
    formData.append("password", password);
    formData.append("birthday", birthday);

    fetch(window.authPageConfig.signupApi, {
        method: "POST",
        body: formData
    })
        .then(res => res.text())
        .then(data => {
            if (data === "success") {
                showToast("Tạo tài khoản thành công", true);
                signinTab.click();
            } else if (data === "missing_username") {
                showToast("Thiếu username", false);
            } else if (data === "missing_email") {
                showToast("Thiếu email", false);
            } else if (data === "invalid_email") {
                showToast("Email sai định dạng", false);
            } else if (data === "missing_birthday") {
                showToast("Thiếu ngày sinh", false);
            } else if (data === "missing_password") {
                showToast("Thiếu mật khẩu", false);
            } else if (data === "weak_password") {
                showToast("Mật khẩu phải từ 8 ký tự", false);
            } else if (data === "email_exists") {
                showToast("Email đã tồn tại", false);
            } else {
                showToast("Không tạo được tài khoản", false);
            }
        });
});
