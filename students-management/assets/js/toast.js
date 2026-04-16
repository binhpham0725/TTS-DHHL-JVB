(function () {
    const PENDING_TOAST_KEY = 'app.pendingToast';

    function hideToast(toast) {
        if (!toast || toast.dataset.toastClosing === '1') {
            return;
        }

        toast.dataset.toastClosing = '1';
        toast.classList.remove('is-visible');
        toast.classList.add('is-hiding');

        window.setTimeout(function () {
            if (toast.parentNode) {
                toast.parentNode.removeChild(toast);
            }
        }, 260);
    }

    function activateToast(toast) {
        if (!toast) {
            return;
        }

        const duration = Number.parseInt(toast.getAttribute('data-duration') || '5000', 10);
        const finalDuration = Number.isFinite(duration) && duration >= 1000 ? duration : 5000;

        const closeButton = toast.querySelector('[data-app-toast-close]');
        if (closeButton) {
            closeButton.addEventListener('click', function () {
                hideToast(toast);
            });
        }

        window.requestAnimationFrame(function () {
            toast.classList.add('is-visible');
        });

        window.setTimeout(function () {
            hideToast(toast);
        }, finalDuration);
    }

    function ensureToastStack() {
        let stack = document.querySelector('[data-app-toast-stack]');

        if (!stack) {
            stack = document.createElement('div');
            stack.className = 'app-toast-stack';
            stack.setAttribute('data-app-toast-stack', '');
            document.body.appendChild(stack);
        }

        return stack;
    }

    function buildToast(options) {
        const toast = document.createElement('div');
        const type = ['success', 'error', 'info', 'warning'].includes(options.type) ? options.type : 'info';
        const duration = Number.isFinite(options.duration) && options.duration >= 1000 ? options.duration : 5000;
        const items = Array.isArray(options.items) ? options.items.filter(Boolean) : [];

        toast.className = 'app-toast app-toast--' + type;
        toast.setAttribute('data-app-toast', '');
        toast.setAttribute('data-duration', String(duration));
        toast.setAttribute('role', 'alert');
        toast.setAttribute('aria-live', 'assertive');

        const accent = document.createElement('div');
        accent.className = 'app-toast__accent';
        accent.setAttribute('aria-hidden', 'true');
        toast.appendChild(accent);

        const content = document.createElement('div');
        content.className = 'app-toast__content';

        if (options.title) {
            const title = document.createElement('div');
            title.className = 'app-toast__title';
            title.textContent = options.title;
            content.appendChild(title);
        }

        if (options.message) {
            const message = document.createElement('div');
            message.className = 'app-toast__message';
            message.textContent = options.message;
            content.appendChild(message);
        }

        if (items.length > 0) {
            const list = document.createElement('ul');
            list.className = 'app-toast__list';

            items.forEach(function (item) {
                const listItem = document.createElement('li');
                listItem.textContent = item;
                list.appendChild(listItem);
            });

            content.appendChild(list);
        }

        toast.appendChild(content);

        const closeButton = document.createElement('button');
        closeButton.type = 'button';
        closeButton.className = 'app-toast__close';
        closeButton.setAttribute('data-app-toast-close', '');
        closeButton.setAttribute('aria-label', 'Đóng thông báo');
        closeButton.innerHTML = '<span aria-hidden="true">&times;</span>';
        toast.appendChild(closeButton);

        return toast;
    }

    function initExistingToasts() {
        document.querySelectorAll('[data-app-toast]').forEach(function (toast) {
            if (toast.dataset.toastInitialized === '1') {
                return;
            }

            toast.dataset.toastInitialized = '1';
            activateToast(toast);
        });
    }

    function consumeQueuedToast() {
        try {
            const raw = window.sessionStorage.getItem(PENDING_TOAST_KEY);
            if (!raw) {
                return;
            }

            window.sessionStorage.removeItem(PENDING_TOAST_KEY);
            const payload = JSON.parse(raw);
            window.showAppToast(payload);
        } catch (error) {
            window.sessionStorage.removeItem(PENDING_TOAST_KEY);
            console.error('Không đọc được toast đang chờ hiển thị.', error);
        }
    }

    window.showAppToast = function (options) {
        const safeOptions = typeof options === 'object' && options !== null ? options : {};
        const stack = ensureToastStack();
        const toast = buildToast({
            type: safeOptions.type || 'info',
            title: safeOptions.title || '',
            message: safeOptions.message || '',
            items: safeOptions.items || [],
            duration: safeOptions.duration || 5000,
        });

        stack.appendChild(toast);
        activateToast(toast);
    };

    window.queueAppToast = function (options) {
        const safeOptions = typeof options === 'object' && options !== null ? options : {};

        try {
            window.sessionStorage.setItem(PENDING_TOAST_KEY, JSON.stringify({
                type: safeOptions.type || 'info',
                title: safeOptions.title || '',
                message: safeOptions.message || '',
                items: safeOptions.items || [],
                duration: safeOptions.duration || 5000,
            }));
        } catch (error) {
            console.error('Không lưu được toast đang chờ hiển thị.', error);
        }
    };

    document.addEventListener('DOMContentLoaded', function () {
        initExistingToasts();
        consumeQueuedToast();
    });
})();
