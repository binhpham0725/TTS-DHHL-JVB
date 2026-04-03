<?php
require_once __DIR__ . '/../../config/db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mssv = trim($_POST['mssv'] ?? '');
    $fullname = trim($_POST['fullname'] ?? '');
    $birthday = trim($_POST['birthday'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $class = trim($_POST['class'] ?? '');
    $address = trim($_POST['address'] ?? '');

    if ($mssv && $fullname && $birthday && $phone && $email && $class && $address) {
        $mssv = mysqli_real_escape_string($conn, $mssv);
        $fullname = mysqli_real_escape_string($conn, $fullname);
        $birthday = mysqli_real_escape_string($conn, $birthday);
        $phone = mysqli_real_escape_string($conn, $phone);
        $email = mysqli_real_escape_string($conn, $email);
        $class = mysqli_real_escape_string($conn, $class);
        $address = mysqli_real_escape_string($conn, $address);

        $sql = "INSERT INTO students (mssv, fullname, birthday, phone, email, class, address)
                VALUES ('$mssv', '$fullname', '$birthday','$phone', '$email', '$class', '$address')";

        if (mysqli_query($conn, $sql)) {
            header("Location: ../../interface/listsv.php");
            exit;
        } else {
            $error = "Thêm sinh viên thất bại.";
        }
    } else {
        $error = "Vui lòng nhập đầy đủ thông tin.";
    }
}
?>

<div class="modal-card">
  <div class="modal-header">
    <h3>Thêm sinh viên mới</h3>
    <button type="button" class="close-modal" data-close="addStudentModal">
      <i class="fa-solid fa-xmark"></i>
    </button>
  </div>

  <?php if ($error): ?>
    <div class="alert-error"><?= $error ?></div>
  <?php endif; ?>

  <form method="POST" class="student-form">
    <div class="form-grid">
      <div class="form-group">
        <label>MSSV</label>
        <input type="text" name="mssv" required>
      </div>

      <div class="form-group">
        <label>Họ và tên</label>
        <input type="text" name="fullname" required>
      </div>

      <div class="form-group">
        <label>Ngày sinh</label>
        <input type="date" name="birthday" required>
      </div>

      <div class="form-group">
        <label>Số điện thoại</label>
        <input type="text" name="phone" required>
      </div>

      <div class="form-group">
        <label>Email</label>
        <input type="email" name="email" required>
      </div>

      <div class="form-group">
        <label>Lớp</label>
        <select name="class" required>
          <option value="">Chọn Lớp</option>
          <option value="D16CNTT">D16CNTT</option>
          <option value="D17CNTT">D17CNTT</option>
          <option value="D18CNTT">D18CNTT</option>
        </select>
      </div>

      <div class="form-group form-group-full">
        <label>Địa chỉ</label>
        <input type="text" name="address" required>
      </div>
    </div>

    <div class="form-actions">
      <button type="button" class="btn btn-light close-modal" data-close="addStudentModal">Hủy</button>
      <button type="submit" class="btn btn-primary">Lưu sinh viên</button>
    </div>
  </form>
</div>