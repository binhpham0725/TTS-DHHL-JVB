<?php
include "db.php";

if(!isset($_GET['id'])) die("Missing ID");

$id = (int)$_GET['id'];

/* ===== HANDLE POST (PRG) ===== */
if($_SERVER["REQUEST_METHOD"]=="POST"){

    $name=$_POST['name'];
    $gender=$_POST['gender'];
    $dob=$_POST['dob'];
    $email=$_POST['email'];
    $address=$_POST['address'];

    $major=$_POST['major'];
    $course=$_POST['course']; // NEW
    $gpa=$_POST['gpa'];
    $status=$_POST['status'];
    $rank=$_POST['rank'];

    /* update students */
    $conn->query("UPDATE students 
        SET ho_ten='$name',
            gioi_tinh='$gender',
            ngay_sinh='$dob',
            email='$email',
            dia_chi='$address'
        WHERE id=$id");

    /* check academic */
    $check = $conn->query("SELECT * FROM student_academic WHERE student_id=$id");
    $academic = $check->fetch_assoc();

    /* update / insert academic */
    if($major || $course || $gpa || $status || $rank){ // INCLUDE $course

        if($academic){
            $conn->query("UPDATE student_academic SET
                chuyen_nganh='$major',
                khoa_hoc='$course',
                gpa='$gpa',
                tinh_trang='$status',
                xep_loai='$rank'
                WHERE student_id=$id");
        }else{
            $conn->query("INSERT INTO student_academic
                (student_id,chuyen_nganh,khoa_hoc,gpa,tinh_trang,xep_loai)
                VALUES('$id','$major','$course','$gpa','$status','$rank')");
        }
    }

    /* ===== REDIRECT (FIX LOOP) ===== */
    header("Location: edit.php?id=$id&success=1");
    exit();
}

/* ===== GET DATA ===== */

/* cá nhân */
$result = $conn->query("SELECT * FROM students WHERE id=$id");
$sv = $result->fetch_assoc();

if(!$sv) die("Student not found");

/* học tập */
$academicResult = $conn->query("SELECT * FROM student_academic WHERE student_id=$id");
$academic = $academicResult->fetch_assoc();
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

<!-- TABS -->
<div class="form-tabs">
    <button type="button" class="tab active" onclick="switchTab(1)">Cá nhân</button>
    <button type="button" class="tab" onclick="switchTab(2)">Học tập</button>
</div>

<!-- TAB 1 -->
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

<!-- TAB 2 -->
<div class="tab-content" id="tab2">

    <div class="edit-field">
        <label>Chuyên ngành</label>
        <input type="text" name="major" value="<?=htmlspecialchars($academic['chuyen_nganh'] ?? '')?>">
    </div>

    <!-- NEW ROW -->
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

<a class="edit-back" href="home.php">Quay lại</a>

</div>
</div>

<!-- TOAST -->
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