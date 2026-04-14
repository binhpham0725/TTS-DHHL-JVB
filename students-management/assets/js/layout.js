document.addEventListener('DOMContentLoaded', function () {
    const sidebar = document.querySelector('.sidebar-col');
    const toggleButtons = document.querySelectorAll('.mobile-menu-toggle');
    const backdrop = document.querySelector('.mobile-sidebar-backdrop');

    if (!sidebar || toggleButtons.length === 0 || !backdrop) {
        return;
    }

    function openMenu() {
        sidebar.classList.add('is-open');
        document.body.classList.add('mobile-menu-open');
    }

    function closeMenu() {
        sidebar.classList.remove('is-open');
        document.body.classList.remove('mobile-menu-open');
    }

    toggleButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            if (sidebar.classList.contains('is-open')) {
                closeMenu();
            } else {
                openMenu();
            }
        });
    });

    backdrop.addEventListener('click', closeMenu);

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            closeMenu();
        }
    });

    window.addEventListener('resize', function () {
        if (window.innerWidth >= 992) {
            closeMenu();
        }
    });
});
