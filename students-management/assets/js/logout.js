function logout() {
    const commonTexts = (window.APP_TEXTS && window.APP_TEXTS.common) || {};

    function doLogout() {
        localStorage.removeItem('token');
        localStorage.removeItem('user');
        sessionStorage.clear();
        window.location.href = '../auth/logout.php';
    }

    if (typeof window.showAppConfirm === 'function') {
        window.showAppConfirm({
            title: commonTexts.confirm_title || 'Xác nhận thao tác',
            message: commonTexts.logout_confirm || 'Bạn có chắc muốn đăng xuất?',
            confirmText: 'Đăng xuất',
            cancelText: commonTexts.confirm_cancel || 'Hủy',
            variant: 'danger'
        }).then(function (accepted) {
            if (accepted) {
                doLogout();
            }
        });

        return;
    }

    const ok = confirm(commonTexts.logout_confirm || 'Bạn có chắc muốn đăng xuất?');
    if (!ok) {
        return;
    }

    doLogout();
}
