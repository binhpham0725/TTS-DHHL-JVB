<?php
session_start();
require_once "../config/db.php";

if (isset($_SESSION['teacher_id'])) {
    header("Location: ../interface/index.php");
    exit();
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"] ?? "");
    $password = trim($_POST["password"] ?? "");

    if (empty($email) || empty($password)) {
        $error = "Vui lòng nhập đầy đủ email và mật khẩu";
    } else {
        $stmt = $conn->prepare("SELECT id, name, email, password FROM Teacher WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            if ($password === $row["password"]) {
                $_SESSION["teacher_id"] = $row["id"];
                $_SESSION["teacher_name"] = $row["name"];
                $_SESSION["teacher_email"] = $row["email"];

                header("Location: ../interface/index.php");
                exit();
            } else {
                $error = "Sai email hoặc mật khẩu";
            }
        } else {
            $error = "Sai email hoặc mật khẩu";
        }

        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập</title>
    <link rel="stylesheet" href="../assets/css/login.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

</head>
<body>
    <div class="login-page">
    
        <div class="overlay"></div>

        <div class="login-box">
            <h2>Đăng nhập</h2>
            <?php if (!empty($error)) : ?>
                <div class="error-text"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <form action="" method="POST">
                <div class="input-group">
                    <input type="text" name="email" placeholder="Email" required>
                </div>

                <div class="input-group">
                    <input type="password" name="password" placeholder="Password" required>
                </div>

                <div class="options">
                    <label class="remember">
                        <input type="checkbox" name="remember">
                        Ghi lại
                    </label>
                
                    <a href="#" class="forgot">Quên mật khẩu?</a>
                </div>

                <button type="submit" class="login-btn">Đăng nhập</button>
                </p>
            </form>
        </div>
    </div>
    <script src="../assets/js/regex.js"></script>
</body>
</html>