<?php
$toasts = is_array($toasts ?? null) ? $toasts : [];
$allowedTypes = ['success', 'error', 'info', 'warning'];
$normalizedToasts = [];

foreach ($toasts as $toast) {
    if (!is_array($toast)) {
        continue;
    }

    $message = trim((string)($toast['message'] ?? ''));
    $title = trim((string)($toast['title'] ?? ''));
    $items = is_array($toast['items'] ?? null) ? $toast['items'] : [];
    $items = array_values(array_filter(array_map(static function ($item) {
        return trim((string)$item);
    }, $items), static function ($item) {
        return $item !== '';
    }));

    if ($message === '' && $title === '' && $items === []) {
        continue;
    }

    $type = (string)($toast['type'] ?? 'info');
    if (!in_array($type, $allowedTypes, true)) {
        $type = 'info';
    }

    $duration = (int)($toast['duration'] ?? 5000);
    if ($duration < 1000) {
        $duration = 5000;
    }

    $normalizedToasts[] = [
        'type' => $type,
        'title' => $title,
        'message' => $message,
        'items' => $items,
        'duration' => $duration,
    ];
}

if ($normalizedToasts === []) {
    return;
}
?>
<div class="app-toast-stack" data-app-toast-stack>
    <?php foreach ($normalizedToasts as $toast): ?>
        <div
            class="app-toast app-toast--<?= htmlspecialchars($toast['type']) ?>"
            data-app-toast
            data-duration="<?= (int)$toast['duration'] ?>"
            role="alert"
            aria-live="assertive"
        >
            <div class="app-toast__accent" aria-hidden="true"></div>

            <div class="app-toast__content">
                <?php if ($toast['title'] !== ''): ?>
                    <div class="app-toast__title"><?= htmlspecialchars($toast['title']) ?></div>
                <?php endif; ?>

                <?php if ($toast['message'] !== ''): ?>
                    <div class="app-toast__message"><?= htmlspecialchars($toast['message']) ?></div>
                <?php endif; ?>

                <?php if ($toast['items'] !== []): ?>
                    <ul class="app-toast__list">
                        <?php foreach ($toast['items'] as $item): ?>
                            <li><?= htmlspecialchars($item) ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>

            <button type="button" class="app-toast__close" data-app-toast-close aria-label="Đóng thông báo">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endforeach; ?>
</div>
