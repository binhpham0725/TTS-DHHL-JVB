function logout() {
    const commonTexts = (window.APP_TEXTS && window.APP_TEXTS.common) || {};
    const ok = confirm(commonTexts.logout_confirm || 'Bạn có chắc muốn đăng xuất?');
    if (!ok) {
        return;
    }

    localStorage.removeItem('token');
    localStorage.removeItem('user');
    sessionStorage.clear();
    window.location.href = '../auth/logout.php';
}
