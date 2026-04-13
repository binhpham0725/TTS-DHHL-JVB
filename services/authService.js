/* service gọi api cho login và signup */
function loginAccount(email, password) {
    const formData = new FormData();
    formData.append("email", email);
    formData.append("password", password);

    return fetch(window.authPageConfig.loginApi, {
        method: "POST",
        body: formData
    }).then(res => res.text());
}

function signupAccount(payload) {
    const formData = new FormData();
    formData.append("username", payload.username);
    formData.append("email", payload.email);
    formData.append("password", payload.password);
    formData.append("birthday", payload.birthday);

    return fetch(window.authPageConfig.signupApi, {
        method: "POST",
        body: formData
    }).then(res => res.text());
}
