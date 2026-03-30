const signinTab = document.getElementById("signinTab");
const signupTab = document.getElementById("signupTab");

const signinForm = document.getElementById("signinForm");
const signupForm = document.getElementById("signupForm");

const card = document.querySelector(".card");

let isSwitching = false;

/* ================= TOAST (FIX RATE LIMIT) ================= */

let lastToastTime = 0;
const TOAST_COOLDOWN = 3000; // đúng bằng duration

function showToast(text, success = true) {
    let now = Date.now();

    /* ignore nếu spam */
    if (now - lastToastTime < TOAST_COOLDOWN) {
        return;
    }

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

/* ================= TAB SWITCH ================= */

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
        {
            duration: 300
        }
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
            {
                duration: 300
            }
        );
    };
}

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

/* ================= PASSWORD TOGGLE ================= */

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

/* ================= EMAIL REGEX ================= */

const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

/* ================= SIGN IN ================= */

signinForm.addEventListener("submit", function (e) {
    e.preventDefault();

    let email = document.getElementById("signinEmail").value.trim();
    let password = document.getElementById("signinPassword").value.trim();

    if (email === "") {
        showToast("Email is required", false);
        return;
    }

    if (!emailPattern.test(email)) {
        showToast("Email format invalid", false);
        return;
    }

    if (password === "") {
        showToast("Password required", false);
        return;
    }

    if (password.length < 8) {
        showToast("Password >= 8 characters", false);
        return;
    }

    let formData = new FormData();
    formData.append("email", email);
    formData.append("password", password);

    fetch("login.php", {
        method: "POST",
        body: formData
    })
        .then(res => res.text())
        .then(data => {
            if (data === "success") {
                showToast("Đăng nhập thành công", true);

                setTimeout(() => {
                    window.location.href = "../homepage/home.php";
                }, 1000);
            } else if (data === "wrong_password") {
                showToast("Sai mật khẩu", false);
            } else {
                showToast("Email không tồn tại", false);
            }
        });
});

/* ================= SIGN UP ================= */

signupForm.addEventListener("submit", function (e) {
    e.preventDefault();

    let username = document.getElementById("signupUsername").value.trim();
    let email = document.getElementById("signupEmail").value.trim();
    let birthday = document.getElementById("birthday").value;
    let password = document.getElementById("signupPassword").value;
    let confirm = document.getElementById("confirmPassword").value;

    if (username === "") {
        showToast("Nhập username", false);
        return;
    }

    if (email === "") {
        showToast("Nhập email", false);
        return;
    }

    if (!emailPattern.test(email)) {
        showToast("Email sai định dạng", false);
        return;
    }

    if (birthday === "") {
        showToast("Nhập ngày sinh", false);
        return;
    }

    if (password === "") {
        showToast("Nhập mật khẩu", false);
        return;
    }

    if (password.length < 8) {
        showToast("Mật khẩu >= 8 ký tự", false);
        return;
    }

    if (confirm === "") {
        showToast("Xác nhận mật khẩu", false);
        return;
    }

    if (password !== confirm) {
        showToast("Mật khẩu không khớp", false);
        return;
    }

    let formData = new FormData();

    formData.append("username", username);
    formData.append("email", email);
    formData.append("password", password);
    formData.append("birthday", birthday);

    fetch("signup.php", {
        method: "POST",
        body: formData
    })
        .then(res => res.text())
        .then(data => {
            if (data === "success") {
                showToast("Tạo tài khoản thành công", true);
                signinTab.click();
            } else if (data === "email_exists") {
                showToast("Email đã tồn tại", false);
            } else {
                showToast("Không tạo được tài khoản", false);
            }
        });
});