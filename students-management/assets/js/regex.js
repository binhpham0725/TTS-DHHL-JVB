document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("loginForm");
    const email = document.getElementById("email");
    const password = document.getElementById("password");
    const emailError = document.getElementById("emailError");
    const passwordError = document.getElementById("passwordError");

    if (!form || !email || !password || !emailError || !passwordError) {
        return;
    }

    const emailPattern = /^[A-Za-z0-9._%+-]+@(?:[A-Za-z0-9-]+\.)*hluv\.edu\.com\.vn$/i;

    const setFieldError = (input, errorElement, message) => {
        const group = input.closest(".input-group");

        errorElement.textContent = message;

        if (!group) {
            return;
        }

        group.classList.toggle("has-error", message !== "");
    };

    const validateEmail = () => {
        const value = email.value.trim();

        if (value === "") {
            return "Vui lòng nhập email.";
        }

        if (!emailPattern.test(value)) {
            return "Email phải có dạng ...@hluv.edu.com.vn.";
        }

        return "";
    };

    const validatePassword = () => {
        const value = password.value.trim();

        if (value === "") {
            return "Vui lòng nhập mật khẩu.";
        }

        if (value.length < 8) {
            return "Mật khẩu tối thiểu 8 ký tự.";
        }

        return "";
    };

    email.addEventListener("input", () => {
        setFieldError(email, emailError, validateEmail());
    });

    password.addEventListener("input", () => {
        setFieldError(password, passwordError, validatePassword());
    });

    form.addEventListener("submit", (event) => {
        const emailMessage = validateEmail();
        const passwordMessage = validatePassword();

        setFieldError(email, emailError, emailMessage);
        setFieldError(password, passwordError, passwordMessage);

        if (emailMessage !== "" || passwordMessage !== "") {
            event.preventDefault();
        }
    });
});
