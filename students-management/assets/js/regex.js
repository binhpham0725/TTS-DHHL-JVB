const form = document.getElementById("loginForm");
        const email = document.getElementById("email");
        const password = document.getElementById("password");
        const emailError = document.getElementById("emailError");
        const passwordError = document.getElementById("passwordError");

        const emailRegex = /^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.hluv\.edu\.com\.vn$/;

        form.addEventListener("submit", function(e) {
            let ok = true;

            emailError.textContent = "";
            passwordError.textContent = "";

            if (!emailRegex.test(email.value.trim())) {
                emailError.textContent = "Email phải có dạng ...@...hluv.edu.com.vn";
                ok = false;
            }

            if (password.value.trim().length < 8) {
                passwordError.textContent = "Mật khẩu tối thiểu 8 ký tự";
                ok = false;
            }

            if (!ok) e.preventDefault();
        });