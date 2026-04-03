<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo !empty($page_title) ? $page_title : "Trang chủ"; ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="<?php echo __WEB_ROOT__; ?>/public/assets/auth/css/main.css">
<body>
<div id="toast">
</div>
<div class="login-container">
    <div class="login-card">
        <div class="card-header">
            <h2>Đăng nhập</h2>
        </div>
        <div class="role-inpup">

                <div class="login-tabs">
                    <button class="active" id="btn-student" data-tag="student">
                        <i class="fa-solid fa-user"></i>
                        Sinh viên
                    </button>
                    <button id="btn-admin" data-tag="admin">
                        <i class="fa-solid fa-user-shield"></i>
                        Quản trị viên
                    </button>
            </div>
        </div>
        <form action=""  class="card-body" id="login-form">
            <input  type="hidden" id="role" name="role"  value="student">
            <div class="form-group">
                <label for="email">E-mail</label>
                <input type="text" id="email" name="email" rules="required|email"  >
                <span class="form-message"></span>
            </div>
            <div class="form-group">
                <label for="password">Mật khẩu</label>
                <input type="password" id="password" name="password" rules="required" >
                <span class="form-message"></span>
            </div>
            <div class="form-options">
                <label class="checkbox-container">
                    <input type="checkbox" name="remember">
                    <span class="checkmark"></span>
                    Nhớ đăng nhập
                </label>
                <a href="#" class="forgot-password">Quên mật khẩu?</a>
            </div>
            <button type="submit" class="btn-login">ĐĂNG NHẬP</button>
        </form>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.2.0/crypto-js.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="<?php echo __WEB_ROOT__; ?>/public/assets/auth/js/validator.js"></script>
<script src="<?php echo __WEB_ROOT__; ?>/public/assets/auth/js/main.js"></script>
</body>

</html>