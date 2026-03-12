<?php
include "db.php";

$result = $conn->query("SELECT * FROM students");
?>

<!DOCTYPE html>
<html>

<head>
<meta charset="UTF-8">
<title>Homepage</title>
<link rel="stylesheet" href="home.css">
</head>

<body>

<h2>Danh sách học sinh</h2>

<div>

<input id="name" placeholder="Họ và tên">

<select id="gender">
<option value="">Giới tính</option>
<option value="Nam">Nam</option>
<option value="Nữ">Nữ</option>
</select>

<input id="age" type="number" placeholder="Tuổi">

<input id="email" placeholder="Email">

<input id="address" placeholder="Địa chỉ">

<button onclick="register()">Đăng ký</button>
<button onclick="logout()">Logout</button>

</div>

<p id="message"></p>

<table>

<thead>
<tr>
<th>STT</th>
<th>Họ tên</th>
<th>Giới tính</th>
<th>Tuổi</th>
<th>Email</th>
<th>Địa chỉ</th>
<th>Xóa</th>
</tr>
</thead>

<tbody id="tableBody">

<?php
$stt = 1;
while($row = $result->fetch_assoc()){
echo "<tr>
<td>".$stt++."</td>
<td>".$row['ho_ten']."</td>
<td>".$row['gioi_tinh']."</td>
<td>".$row['tuoi']."</td>
<td>".$row['email']."</td>
<td>".$row['dia_chi']."</td>
<td><button onclick='confirmDelete(".$row['id'].")'>Xóa</button></td>
</tr>";
}
?>

</tbody>

</table>

<div id="logoutPopup">
<span>✅</span> Logout thành công, chuyển hướng về trang đăng nhập sau 3 giây...
<div id="logoutBar"></div>
</div>

<div id="deleteOverlay">

<div id="deleteDialog">

<div id="deleteHeader">
Xác nhận xóa
</div>

<div id="deleteContent">
Bạn có chắc muốn xóa sinh viên này không?
</div>

<div id="deleteActions">
<button id="confirmDeleteBtn">Xóa</button>
<button id="cancelDeleteBtn">Hủy</button>
</div>

</div>

</div>

<script src="home.js"></script>

</body>
</html>