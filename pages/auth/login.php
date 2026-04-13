<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login Page</title>
    <!-- css của trang login -->
    <link rel="stylesheet" href="../../assets/css/auth.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
    <!-- nền particles phía sau -->
    <canvas id="particlesCanvas"></canvas>
    <!-- card chứa form login và signup -->
    <div class="container">
        <div class="card">
            <h1 class="system-title">
                <span>Hệ thống</span>
                <span>Quản lý sinh viên</span>
            </h1>
            <div class="tabs">
                <button id="signinTab" class="active">Sign In</button>
                <button id="signupTab">Sign Up</button>
            </div>

            <div class="forms-wrapper">
                <form id="signinForm" class="form active" autocomplete="off" novalidate>
                    <input type="text" style="display:none">
                    <div class="input-box">
                        <input id="signinEmail" type="email" name="signinEmail" autocomplete="off" placeholder="Email">
                    </div>
                    <div class="input-box">
                        <input id="signinPassword" type="password" name="signinPassword" autocomplete="new-password" placeholder="Password">
                        <i class="fa-solid fa-eye" id="signinEyeOpen"></i>
                        <i class="fa-solid fa-eye-slash" id="signinEyeClosed"></i>
                    </div>
                    <button type="submit" class="submit">Sign In →</button>
                    <div class="support-box">
                        <p class="support-title">Hỗ trợ đăng nhập</p>
                        <p class="support-text">Nếu chưa có tài khoản hoặc quên mật khẩu, vui lòng liên hệ quản trị viên.</p>
                    </div>
                </form>

                <form id="signupForm" class="form" autocomplete="off">
                    <input type="text" style="display:none">
                    <div class="input-box">
                        <input id="signupUsername" type="text" name="signupUsername" autocomplete="off" placeholder="Username">
                    </div>
                    <div class="input-box">
                        <input id="signupEmail" type="email" name="signupEmail" autocomplete="off" placeholder="Email">
                    </div>
                    <div class="input-box date">
                        <input id="birthday" type="date" name="birthday" autocomplete="off">
                        <i class="fa-solid fa-cake-candles birthday-icon"></i>
                    </div>
                    <div class="input-box">
                        <input id="signupPassword" type="password" name="signupPassword" autocomplete="new-password" placeholder="Password">
                        <i class="fa-solid fa-eye" id="signupEyeOpen"></i>
                        <i class="fa-solid fa-eye-slash" id="signupEyeClosed"></i>
                    </div>
                    <div class="input-box">
                        <input id="confirmPassword" type="password" name="confirmPassword" autocomplete="new-password" placeholder="Confirm Password">
                        <i class="fa-solid fa-eye" id="confirmEyeOpen"></i>
                        <i class="fa-solid fa-eye-slash" id="confirmEyeClosed"></i>
                    </div>
                    <button type="submit" class="submit">Create Account →</button>
                    <div class="support-box">
                        <p class="support-title">Lưu ý</p>
                        <p class="support-text">Email dùng để đăng nhập phải không bị trùng với tài khoản đã có.</p>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- toast thông báo -->
    <?php require __DIR__ . '/../../components/auth-toast.php'; ?>
    <!-- config url để js gọi api đúng chỗ -->
    <script>
        window.authPageConfig = {
            loginApi: '../../api/auth/login.php',
            signupApi: '../../api/auth/signup.php',
            studentPageUrl: '../students/index.php'
        };
    </script>
    <!-- service js gọi api -->
    <script src="../../services/authService.js"></script>
    <!-- js xử lý form và hiệu ứng nền -->
    <script src="../../assets/js/auth.js"></script>
    <script src="../../assets/js/auth-particles.js"></script>
</body>
</html>
