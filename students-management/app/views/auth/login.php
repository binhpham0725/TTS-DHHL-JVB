<?php
$error = $error ?? '';
$oldEmail = $oldEmail ?? '';
$fieldErrors = is_array($fieldErrors ?? null) ? $fieldErrors : [];
$baseUrl = defined('APP_BASE') ? APP_BASE : '/' . basename(dirname(__DIR__, 3));
$texts = app_text_group('auth');

if ($error !== '' && !isset($fieldErrors['password'])) {
    $fieldErrors['password'] = $error;
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($texts['page_title']) ?></title>
    <link rel="stylesheet" href="<?= htmlspecialchars($baseUrl) ?>/assets/css/login.css">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>
    <div class="login-page">
        <div class="overlay"></div>
        <div class="login-box">
            <h2><?= htmlspecialchars($texts['heading']) ?></h2>

            <form id="loginForm" action="<?= htmlspecialchars($baseUrl) ?>/auth/login.php" method="POST" novalidate>
                <div class="input-group <?= isset($fieldErrors['email']) ? 'has-error' : '' ?>">
                    <label for="email">
                        <?= htmlspecialchars($texts['email_label']) ?>
                        <span class="required-mark" aria-hidden="true">*</span>
                    </label>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        placeholder="<?= htmlspecialchars($texts['email_placeholder']) ?>"
                        value="<?= htmlspecialchars($oldEmail) ?>"
                        autocomplete="username"
                    >
                    <small id="emailError" class="field-error"><?= htmlspecialchars($fieldErrors['email'] ?? '') ?></small>
                </div>

                <div class="input-group <?= isset($fieldErrors['password']) ? 'has-error' : '' ?>">
                    <label for="password">
                        <?= htmlspecialchars($texts['password_label']) ?>
                        <span class="required-mark" aria-hidden="true">*</span>
                    </label>
                    <input
                        id="password"
                        type="password"
                        name="password"
                        placeholder="<?= htmlspecialchars($texts['password_placeholder']) ?>"
                        autocomplete="current-password"
                    >
                    <small id="passwordError" class="field-error"><?= htmlspecialchars($fieldErrors['password'] ?? '') ?></small>
                </div>

                <button type="submit" class="login-btn"><?= htmlspecialchars($texts['submit']) ?></button>
            </form>
        </div>
    </div>
    <script src="<?= htmlspecialchars($baseUrl) ?>/assets/js/regex.js"></script>
</body>
</html>
