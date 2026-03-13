<?php
include "db.php";

if(!isset($_GET['id'])) die("Missing ID");

$id = (int)$_GET['id'];

$result = $conn->query("SELECT * FROM students WHERE id=$id");
$sv = $result->fetch_assoc();

if(!$sv) die("Student not found");

$success = false;

if($_SERVER["REQUEST_METHOD"]=="POST"){

$name=$_POST['name'];
$gender=$_POST['gender'];
$age=$_POST['age'];
$email=$_POST['email'];
$address=$_POST['address'];

$conn->query("UPDATE students 
SET ho_ten='$name',
gioi_tinh='$gender',
tuoi='$age',
email='$email',
dia_chi='$address'
WHERE id=$id");

$success = true;
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Sửa sinh viên</title>

<link rel="stylesheet" href="home.css">
<link rel="stylesheet" href="edit.css">

</head>

<body class="edit-page">

<div class="edit-wrapper">

<div class="edit-card">

<h2 class="edit-title">Sửa thông tin sinh viên</h2>

<form method="post">

<div class="edit-field">
<label>Họ tên</label>
<input type="text" name="name" value="<?=htmlspecialchars($sv['ho_ten'])?>" required>
</div>

<div class="edit-field">
<label>Giới tính</label>
<select name="gender">
<option value="Nam" <?=$sv['gioi_tinh']=="Nam"?"selected":""?>>Nam</option>
<option value="Nữ" <?=$sv['gioi_tinh']=="Nữ"?"selected":""?>>Nữ</option>
</select>
</div>

<div class="edit-field">
<label>Tuổi</label>
<input type="number" name="age" value="<?=$sv['tuoi']?>" required>
</div>

<div class="edit-field">
<label>Email</label>
<input type="text" name="email" value="<?=htmlspecialchars($sv['email'])?>">
</div>

<div class="edit-field">
<label>Địa chỉ</label>
<input type="text" name="address" value="<?=htmlspecialchars($sv['dia_chi'])?>">
</div>

<button class="edit-submit" type="submit">Lưu thay đổi</button>

</form>

<a class="edit-back" href="home.php">Quay lại</a>

</div>

</div>

<div id="toast">
<span id="toastMsg"></span>
<div id="toastBar"></div>
</div>

<script src="edit.js"></script>

<?php
if($success){
echo "<script>
showToast('Cập nhật thông tin thành công, đang quay lại trang chủ...', true);
</script>";
}
?>

</body>
</html>