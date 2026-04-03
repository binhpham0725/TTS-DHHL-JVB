function logout() {
    const ok = confirm("Bạn có chắc muốn đăng xuất?");
    if (!ok) return;

    localStorage.removeItem("token");
    localStorage.removeItem("user");
    sessionStorage.clear();
    window.location.href = "login.php";
}
