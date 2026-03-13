<?php
include "db.php";

/* pagination */
$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if($page < 1) $page = 1;
$start = ($page-1)*$limit;

/* sorting */
$allowed = ["ho_ten","gioi_tinh","tuoi","email","dia_chi"];
$sort = isset($_GET['sort']) && in_array($_GET['sort'],$allowed) ? $_GET['sort'] : "id";
$order = isset($_GET['order']) && $_GET['order']=="desc" ? "DESC":"ASC";

/* total rows */
$totalResult = $conn->query("SELECT COUNT(*) as total FROM students");
$total = $totalResult->fetch_assoc()['total'];
$totalPages = ceil($total/$limit);

/* main query */
$result = $conn->query("SELECT * FROM students ORDER BY $sort $order LIMIT $start,$limit");
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Homepage</title>
<link rel="stylesheet" href="home.css">
</head>

<body>

<h2>Danh sách sinh viên</h2>

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

<input id="search" placeholder="Tìm kiếm..." onkeyup="liveSearch()">

</div>

<table>

<thead>
<tr>
<th>STT</th>
<th onclick="sortTable('ho_ten')">Họ tên</th>
<th onclick="sortTable('gioi_tinh')">Giới tính</th>
<th onclick="sortTable('tuoi')">Tuổi</th>
<th onclick="sortTable('email')">Email</th>
<th onclick="sortTable('dia_chi')">Địa chỉ</th>
<th>Thao tác</th>
</tr>
</thead>

<tbody id="tableBody">

<?php
$stt = $start + 1;

while($row = $result->fetch_assoc()){
echo "<tr>
<td>".$stt++."</td>
<td>".$row['ho_ten']."</td>
<td>".$row['gioi_tinh']."</td>
<td>".$row['tuoi']."</td>
<td>".$row['email']."</td>
<td>".$row['dia_chi']."</td>
<td>
<button onclick='confirmEdit(".$row['id'].")'>Sửa</button>
<button onclick='confirmDelete(".$row['id'].")'>Xóa</button>
</td>
</tr>";
}
?>

</tbody>
</table>

<div class="pagination">
<?php
for($i=1;$i<=$totalPages;$i++){
if($i==$page){
echo "<strong>$i</strong> ";
}else{
echo "<a href='#' onclick='goPage($i)'>$i</a> ";
}
}
?>
</div>

<!-- toasts -->

<div id="logoutToast" class="toast">
<span>✔ Logout thành công, chuyển hướng về trang đăng nhập...</span>
<div class="toastBar green" id="logoutBar"></div>
</div>

<div id="deleteToast" class="toast">
<span>✔ Đã xóa sinh viên</span>
<div class="toastBar red" id="deleteBar"></div>
</div>

<div id="loadingToast" class="toast">
<span id="loadingText">⏳ Đang lấy dữ liệu...</span>
<div class="toastBar blue" id="loadingBar"></div>
</div>

<div id="pageToast" class="toast">
<span id="pageText">⏳ Đang lấy dữ liệu...</span>
<div class="toastBar purple" id="pageBar"></div>
</div>

<div id="registerToast" class="toast">
<span>✔ Đăng ký thành công</span>
<div class="toastBar green" id="registerBar"></div>
</div>

<!-- delete dialog -->

<div id="deleteOverlay">
<div id="deleteDialog">

<div id="deleteHeader">Xác nhận xóa</div>

<div id="deleteContent">
Bạn có chắc muốn xóa sinh viên này không?
</div>

<div id="deleteActions">
<button id="confirmDeleteBtn">Xóa</button>
<button id="cancelDeleteBtn">Hủy</button>
</div>

</div>
</div>

<!-- edit dialog -->

<div id="editOverlay">
<div id="editDialog">

<div id="editHeader">Xác nhận chỉnh sửa</div>

<div id="editContent">
Bạn có muốn sửa thông tin sinh viên này?
</div>

<div id="editActions">
<button id="confirmEditBtn">Sửa</button>
<button id="cancelEditBtn">Hủy</button>
</div>

</div>
</div>

<script src="home.js"></script>

</body>
</html>