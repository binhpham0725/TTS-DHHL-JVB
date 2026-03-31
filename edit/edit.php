<?php
/* database connection + validate id để đảm bảo request hợp lệ */
include "../database/db.php";

if(!isset($_GET['id'])) die("Missing ID");

$id = (int)$_GET['id'];

/* xử lý POST update dữ liệu sinh viên và học tập với prepared statements */
if($_SERVER["REQUEST_METHOD"]=="POST"){

    $name = $_POST['name'];
    $gender = $_POST['gender'];
    $dob = $_POST['dob'];
    $email = $_POST['email'];
    $address = $_POST['address'];

    $major = $_POST['major'];
    $course = $_POST['course'];
    $gpa = $_POST['gpa'];
    $status = $_POST['status'];
    $rank = $_POST['rank'];

    /* update bảng students */
    $stmt = $conn->prepare("
        UPDATE students SET
            ho_ten = ?,
            gioi_tinh = ?,
            ngay_sinh = ?,
            email = ?,
            dia_chi = ?
        WHERE id = ?
    ");
    $stmt->bind_param("sssssi", $name, $gender, $dob, $email, $address, $id);
    $stmt->execute();
    $stmt->close();

    /* kiểm tra tồn tại academic */
    $check = $conn->prepare("SELECT * FROM student_academic WHERE student_id = ?");
    $check->bind_param("i", $id);
    $check->execute();
    $academicResult = $check->get_result();
    $academic = $academicResult->fetch_assoc();
    $check->close();

    /* update hoặc insert academic */
    if($major || $course || $gpa || $status || $rank){

        if($academic){
            $stmt2 = $conn->prepare("
                UPDATE student_academic SET
                    chuyen_nganh = ?,
                    khoa_hoc = ?,
                    gpa = ?,
                    tinh_trang = ?,
                    xep_loai = ?
                WHERE student_id = ?
            ");
            $stmt2->bind_param("sssssi", $major, $course, $gpa, $status, $rank, $id);
            $stmt2->execute();
            $stmt2->close();
        } else {
            $stmt3 = $conn->prepare("
                INSERT INTO student_academic
                (student_id, chuyen_nganh, khoa_hoc, gpa, tinh_trang, xep_loai)
                VALUES(?, ?, ?, ?, ?, ?)
            ");
            $stmt3->bind_param("isssss", $id, $major, $course, $gpa, $status, $rank);
            $stmt3->execute();
            $stmt3->close();
        }
    }

    /* redirect sau update tránh resubmit form */
    header("Location: edit.php?id=$id&success=1");
    exit();
}

/* lấy dữ liệu sinh viên và academic để render form */
$stmt4 = $conn->prepare("SELECT * FROM students WHERE id = ?");
$stmt4->bind_param("i", $id);
$stmt4->execute();
$result = $stmt4->get_result();
$sv = $result->fetch_assoc();
$stmt4->close();

if(!$sv) die("Student not found");

$stmt5 = $conn->prepare("SELECT * FROM student_academic WHERE student_id = ?");
$stmt5->bind_param("i", $id);
$stmt5->execute();
$academicResult = $stmt5->get_result();
$academic = $academicResult->fetch_assoc();
$stmt5->close();
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Sửa sinh viên</title>

<link rel="stylesheet" href="../homepage/home.css">
<link rel="stylesheet" href="edit.css">

</head>

<body class="edit-page">

<!-- wrapper + card layout giữ form ở center màn hình -->
<div class="edit-wrapper">
<div class="edit-card">

<h2 class="edit-title">Sửa thông tin sinh viên</h2>

<form method="post">

<!-- tab switch giữa thông tin cá nhân và học tập -->
<div class="form-tabs">
    <button type="button" class="tab active" onclick="switchTab(1)">Cá nhân</button>
    <button type="button" class="tab" onclick="switchTab(2)">Học tập</button>
</div>

<!-- tab cá nhân hiển thị thông tin cơ bản sinh viên -->
<div class="tab-content active" id="tab1">

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
        <label>Ngày sinh</label>
        <input type="date" name="dob" value="<?=htmlspecialchars($sv['ngay_sinh'])?>" required>
    </div>

    <div class="edit-field">
        <label>Email</label>
        <input type="text" name="email" value="<?=htmlspecialchars($sv['email'])?>">
    </div>

    <div class="edit-field">
        <label>Địa chỉ</label>
        <input type="text" name="address" value="<?=htmlspecialchars($sv['dia_chi'])?>">
    </div>

</div>

<!-- tab học tập hiển thị thông tin academic của sinh viên -->
<div class="tab-content" id="tab2">

    <div class="edit-field">
        <label>Chuyên ngành</label>
        <input type="text" name="major" value="<?=htmlspecialchars($academic['chuyen_nganh'] ?? '')?>">
    </div>

    <div class="edit-field">
        <label>Khóa học</label>
        <input type="text" name="course" value="<?=htmlspecialchars($academic['khoa_hoc'] ?? '')?>">
    </div>

    <div class="edit-field">
        <label>GPA</label>
        <input type="number" step="0.01" name="gpa" value="<?=htmlspecialchars($academic['gpa'] ?? '')?>">
    </div>

    <div class="edit-field">
        <label>Tình trạng</label>
        <select name="status">
        <?php
        $statusList=["Năm 1","Năm 2","Năm 3","Năm 4","Đã tốt nghiệp","Khác"];
        foreach($statusList as $s){
            $selected = ($academic && $academic['tinh_trang']==$s)?"selected":"";
            echo "<option $selected>$s</option>";
        }
        ?>
        </select>
    </div>

    <div class="edit-field">
        <label>Xếp loại</label>
        <select name="rank">
        <?php
        $rankList=["Xuất sắc","Giỏi","Khá","Trung bình","Yếu"];
        foreach($rankList as $r){
            $selected = ($academic && $academic['xep_loai']==$r)?"selected":"";
            echo "<option $selected>$r</option>";
        }
        ?>
        </select>
    </div>

</div>

<button class="edit-submit" type="submit">Lưu thay đổi</button>

</form>

<a class="edit-back" href="../homepage/home.php">Quay lại</a>

</div>
</div>

<!-- toast hiển thị trạng thái update thành công -->
<div id="toast">
<span id="toastMsg"></span>
<div id="toastBar"></div>
</div>

<script src="edit.js"></script>

<?php
if(isset($_GET['success'])){
echo "<script>
showToast('Cập nhật thông tin thành công!');
</script>";
}
?>

</body>
</html>