function logout() {
    const ok = confirm("Ban co chac muon dang xuat?");
    if (!ok) return;

    localStorage.removeItem("token");
    localStorage.removeItem("user");
    sessionStorage.clear();
    window.location.href = "../auth/logout.php";
}
