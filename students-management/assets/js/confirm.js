(function () {
    let activeResolver = null;

    function getCommonTexts() {
        return (window.APP_TEXTS && window.APP_TEXTS.common) || {};
    }

    function ensureConfirmDialog() {
        let overlay = document.getElementById('appConfirmOverlay');
        if (overlay) {
            return overlay;
        }

        overlay = document.createElement('div');
        overlay.id = 'appConfirmOverlay';
        overlay.className = 'app-confirm-overlay';
        overlay.innerHTML = [
            '<div class="app-confirm-dialog" role="dialog" aria-modal="true" aria-labelledby="appConfirmTitle" aria-describedby="appConfirmMessage">',
            '  <div class="app-confirm-header">',
            '    <div class="app-confirm-title-wrap">',
            '      <div class="app-confirm-icon"><i class="fa-solid fa-circle-exclamation"></i></div>',
            '      <h3 class="app-confirm-title" id="appConfirmTitle"></h3>',
            '    </div>',
            '    <button type="button" class="app-confirm-close" data-confirm-close aria-label="Đóng hộp xác nhận">',
            '      <i class="fa-solid fa-xmark"></i>',
            '    </button>',
            '  </div>',
            '  <div class="app-confirm-body">',
            '    <p class="app-confirm-message" id="appConfirmMessage"></p>',
            '  </div>',
            '  <div class="app-confirm-actions">',
            '    <button type="button" class="app-confirm-btn app-confirm-btn--cancel" data-confirm-cancel></button>',
            '    <button type="button" class="app-confirm-btn app-confirm-btn--confirm" data-confirm-accept></button>',
            '  </div>',
            '</div>'
        ].join('');

        document.body.appendChild(overlay);

        overlay.addEventListener('click', function (event) {
            if (event.target === overlay || event.target.closest('[data-confirm-close]') || event.target.closest('[data-confirm-cancel]')) {
                closeConfirm(false);
                return;
            }

            if (event.target.closest('[data-confirm-accept]')) {
                closeConfirm(true);
            }
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' && overlay.classList.contains('is-open')) {
                closeConfirm(false);
            }
        });

        return overlay;
    }

    function openConfirm(options) {
        const commonTexts = getCommonTexts();
        const overlay = ensureConfirmDialog();
        const dialog = overlay.querySelector('.app-confirm-dialog');
        const titleNode = overlay.querySelector('#appConfirmTitle');
        const messageNode = overlay.querySelector('#appConfirmMessage');
        const cancelButton = overlay.querySelector('[data-confirm-cancel]');
        const confirmButton = overlay.querySelector('[data-confirm-accept]');

        titleNode.textContent = options.title || commonTexts.confirm_title || 'Xác nhận thao tác';
        messageNode.textContent = options.message || '';
        cancelButton.textContent = options.cancelText || commonTexts.confirm_cancel || 'Hủy';
        confirmButton.textContent = options.confirmText || commonTexts.confirm_accept || 'Đồng ý';

        dialog.classList.toggle('is-danger', options.variant === 'danger');
        overlay.classList.add('is-open');
        document.body.classList.add('app-confirm-open');
        confirmButton.focus();

        return new Promise(function (resolve) {
            activeResolver = resolve;
        });
    }

    function closeConfirm(accepted) {
        const overlay = document.getElementById('appConfirmOverlay');
        if (overlay) {
            overlay.classList.remove('is-open');
        }

        document.body.classList.remove('app-confirm-open');

        if (activeResolver) {
            activeResolver(Boolean(accepted));
            activeResolver = null;
        }
    }

    function handleConfirmedAction(element) {
        if (!element) {
            return;
        }

        const href = element.getAttribute('href');
        if (href) {
            window.location.href = href;
            return;
        }

        if (typeof element.click === 'function') {
            element.click();
        }
    }

    window.showAppConfirm = function (options) {
        const safeOptions = typeof options === 'object' && options !== null ? options : {};
        return openConfirm({
            title: safeOptions.title || '',
            message: safeOptions.message || '',
            confirmText: safeOptions.confirmText || '',
            cancelText: safeOptions.cancelText || '',
            variant: safeOptions.variant || 'default'
        });
    };

    document.addEventListener('click', function (event) {
        const trigger = event.target.closest('[data-app-confirm]');
        if (!trigger) {
            return;
        }

        event.preventDefault();
        const confirmedElement = trigger;

        openConfirm({
            title: trigger.getAttribute('data-confirm-title') || '',
            message: trigger.getAttribute('data-confirm-message') || '',
            confirmText: trigger.getAttribute('data-confirm-accept') || '',
            cancelText: trigger.getAttribute('data-confirm-cancel') || '',
            variant: trigger.getAttribute('data-confirm-variant') || 'default'
        }).then(function (accepted) {
            if (accepted) {
                handleConfirmedAction(confirmedElement);
            }
        });
    });
})();
