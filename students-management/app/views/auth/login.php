<?php
$error = $error ?? '';
$oldEmail = $oldEmail ?? '';
$baseUrl = defined('APP_BASE') ? APP_BASE : '/' . basename(dirname(__DIR__, 3));
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập</title>
    <link rel="stylesheet" href="<?= htmlspecialchars($baseUrl) ?>/assets/css/login.css">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>
    <div class="login-page">
        <div class="overlay"></div>
        <div class="login-box">
            <h2>Dang nhap</h2>
            <?php if ($error !== ''): ?>
                <div class="error-text"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form action="<?= htmlspecialchars($baseUrl) ?>/auth/login.php" method="POST">
                <div class="input-group">
                    <input type="text" name="email" placeholder="Email" value="<?= htmlspecialchars($oldEmail) ?>" required>
                </div>

                <div class="input-group">
                    <input type="password" name="password" placeholder="Password" required>
                </div>

                <div class="options">
                    <label class="remember">
                        <input type="checkbox" name="remember">
                        Ghi lai
                    </label>
                    <a href="#" class="forgot">Quên mật khẩu?</a>
                </div>

                <button type="submit" class="login-btn">Đăng nhập</button>
            </form>
        </div>
    </div>
    <script src="<?= htmlspecialchars($baseUrl) ?>/assets/js/regex.js"></script>
</body>
</html>
