<?php
session_start();
require_once "../config/db.php";

$sql = "SELECT * FROM students";
$result = mysqli_query($conn, $sql);
$students = [];

while ($row = mysqli_fetch_assoc($result)) {
    $students[] = $row;
}

$search = trim($_GET['search'] ?? '');
$class = trim($_GET['class'] ?? '');
$page = max(1, (int)($_GET['page'] ?? 1));
$perPage = 10;

$allowedCourses = ['D16CNTT', 'D17CNTT', 'D18CNTT'];

if ($search !== '') {
  $students = array_filter($students, function ($student) use ($search) {
    $keyword = mb_strtolower($search);
    return
      mb_stripos($student['mssv'], $keyword) !== false ||
      mb_stripos($student['fullname'], $keyword) !== false ||
      mb_stripos($student['phone'], $keyword) !== false ||
      mb_stripos($student['email'], $keyword) !== false;
  });
}

if ($class !== '' && in_array($class, $allowedCourses, true)) {
  $students = array_filter($students, function ($student) use ($class) {
    return $student['class'] === $class;
  });
}

$students = array_values($students);
$totalStudents = count($students);
$totalPages = max(1, (int)ceil($totalStudents / $perPage));
$page = min($page, $totalPages);
$start = ($page - 1) * $perPage;
$currentStudents = array_slice($students, $start, $perPage);

function buildQuery($extra = [])
{
  $query = array_merge($_GET, $extra);
  return http_build_query(array_filter($query, function ($v) {
    return $v !== '';
  }));
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Danh sách Sinh viên</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
  <link rel="stylesheet" href="../assets/css/students.css">
  <link rel="stylesheet" href="../assets/css/nav.css">
</head>

<body>
  <div class="layout">
    <aside class="sidebar">
      <div>
        <div class="brand">
          <div class="brand-icon">
            <i class="fa-solid fa-graduation-cap"></i>
          </div>
          <h2>HLUV</h2>
        </div>

        <nav class="menu">
          <a href="index.php" class="menu-item ">
            <i class="fa-solid fa-table-cells-large"></i>
            <span>Bảng điều khiển</span>
          </a>
          <a href="listsv.php" class="menu-item active">
            <i class="fa-solid fa-users"></i>
            <span>Danh sách Sinh viên</span>
          </a>
          <a href="scores.php" class="menu-item">
            <i class="fa-solid fa-star"></i>
            <span>Quản lý Điểm</span>
          </a>
          <a href="subjects.php" class="menu-item">
            <i class="fa-solid fa-book-bookmark"></i>
            <span>Môn học</span>
          </a>
          <a href="statisticals.php" class="menu-item">
            <i class="fa-solid fa-chart-column"></i>
            <span>Thống kê & Phân tích</span>
          </a>
        </nav>
      </div>


        <div class="user-card">
          <div class="sidebar-footer">
            <div>
                <h4>GV: <?php echo isset($_SESSION['teacher_name']) ? $_SESSION['teacher_name'] : 'Chưa đăng nhập'; ?></h4>
            </div>
        </div>
      
    </aside>

    <main class="main">
      <header class="topbar">
        <div class="breadcrumb">Pages <span>/</span> <strong>Sinh viên</strong></div>
          <div class="topbar-actions">
            <button class="icon-circle">
                <i class="fa-regular fa-bell"></i>
            </button>

            <div class="profile-menu">
              <button class="icon-circle">
                      <i class="fa-solid fa-gear"></i>
              </button>

              <div class="dropdown">
                <button class="dropdown-item">
                  <span><i class="fa-solid fa-palette"></i> Chủ đề</span>
                  <i class="fa-solid fa-chevron-left"></i>
                </button>
                <button class="dropdown-item logout" onclick="logout()">
                  <span><i class="fa-solid fa-arrow-right-from-bracket"></i> Đăng xuất</span>
                </button>
              </div>
            </div>
          </div>
      </header>

      <section class="content">
        <div class="page-head">

            <h2>Danh sách Sinh viên</h2>

          <div class="page-actions">
            <button class="action-btn" id="openImportModal" type="button">
              <i class="fa-solid fa-upload"></i>
              Nhập CSV
            </button>

            <a href="/students-management/function/students/export.php" class="action-btn">
              <i class="fa-solid fa-download"></i>
              Xuất CSV
            </a>

            <button class="action-btn primary" id="openAddModal" type="button">
              <i class="fa-solid fa-user-plus"></i>
              Thêm sinh viên mới
            </button>
          </div>
        </div>
      </section>

      <section class="search-page">
          <form method="GET" class="filter-form">
            <div class="search-box">
              <i class="fa-solid fa-magnifying-glass"></i>
              <input
                type="text"
                name="search"
                value="<?= htmlspecialchars($search) ?>"
                placeholder="Tìm kiếm theo MSSV, tên, SDT hoặc Email...">
            </div>

            <div class="select-box">
              <select name="class" onchange="this.form.submit()">
                <option value="">Tất cả các khóa</option>
                <?php foreach ($allowedCourses as $item): ?>
                  <option value="<?= $item ?>" <?= $class === $item ? 'selected' : '' ?>>
                    <?= $item ?>
                  </option>
                <?php endforeach; ?>
              </select>
              <i class="fa-solid fa-chevron-down"></i>
            </div>
          </form>
      </section>  
                  
      <section class="students-panel">
        <div class="table-card">
          <table class="students-table">
            <thead>
              <tr>
                <th>MSSV</th>
                <th>HỌ VÀ TÊN</th>
                <th>NGÀY SINH</th>
                <th>SỐ ĐIỆN THOẠI</th>
                <th>EMAIL</th>
                <th>LỚP</th>
                <th>ĐỊA CHỈ</th>
                <th>THAO TÁC</th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($currentStudents)): ?>
                <tr>
                  <td colspan="8" class="empty-cell">
                    <div class="empty-box">
                      <i class="fa-regular fa-folder-open"></i>
                      <p>Chưa có sinh viên nào. Hãy thêm mới hoặc nhập từ CSV.</p>
                    </div>
                  </td>
                </tr>
              <?php else: ?>
                <?php foreach ($currentStudents as $student): ?>
                  <tr>
                    <td><?= htmlspecialchars($student['mssv']) ?></td>
                    <td><?= htmlspecialchars($student['fullname']) ?></td>
                    <td><?= htmlspecialchars($student['birthday']) ?></td>
                    <td><?= htmlspecialchars($student['phone']) ?></td>
                    <td><?= htmlspecialchars($student['email']) ?></td>
                    <td><span class="class-badge"><?= htmlspecialchars($student['class']) ?></span></td>
                    <td class="address"><?= htmlspecialchars($student['address']) ?></td>
                    <td>
                      <div class="table-actions">
                        <button
                          class="icon-action edit-btn"
                          type="button"
                          onclick="window.location.href='../function/students/edit.php?id=<?= urlencode($student['id']) ?>'"
                          title="Sửa">
                          <i class="fa-solid fa-pen"></i>
                        </button>
                        <a
                          class="icon-action delete-btn"
                          href="../function/students/del.php?id=<?= urlencode($student['id']) ?>"
                          onclick="return confirm('Bạn có chắc muốn xóa sinh viên này?')"
                          title="Xóa">
                          <i class="fa-solid fa-trash"></i>
                        </a>
                      </div>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>

          <div class="table-footer">
            <div class="pagination">
              <?php if ($page > 1): ?>
                <a class="page-btn" href="?<?= buildQuery(['page' => $page - 1]) ?>">
                  <i class="fa-solid fa-chevron-left"></i>
                </a>
              <?php else: ?>
                <span class="page-btn disabled"><i class="fa-solid fa-chevron-left"></i></span>
              <?php endif; ?>

              <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a<div>
              <?php if ($totalStudents > 0): ?>
                Hiển thị <?= $start + 1 ?> - <?= min($start + $perPage, $totalStudents) ?> trong số <?= $totalStudents ?> sinh viên
              <?php else: ?>
                Hiển thị 0 sinh viên
              <?php endif; ?>
            </div> class="page-btn <?= $i === $page ? 'active' : '' ?>" href="?<?= buildQuery(['page' => $i]) ?>">
                  <?= $i ?>
                </a>
              <?php endfor; ?>

              <?php if ($page < $totalPages): ?>
                <a class="page-btn" href="?<?= buildQuery(['page' => $page + 1]) ?>">
                  <i class="fa-solid fa-chevron-right"></i>
                </a>
              <?php else: ?>
                <span class="page-btn disabled"><i class="fa-solid fa-chevron-right"></i></span>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </section>
    </main>
  </div>

  <div class="modal" id="addStudentModal">
    <div class="modal-dialog">
      <?php include __DIR__ . '/../function/students/add.php'; ?>
    </div>
  </div>

  <div class="modal" id="importModal">
    <div class="modal-dialog small">
      <div class="modal-card">
        <div class="modal-header">
          <h3>Nhập sinh viên từ CSV</h3>
          <button type="button" class="close-modal" data-close="importModal">
            <i class="fa-solid fa-xmark"></i>
          </button>
        </div>

        <form action="../function/students/import.php" method="POST" enctype="multipart/form-data" class="student-form">
          <div class="form-group">
            <label>Chọn file CSV</label>
            <input type="file" name="csv_file" accept=".csv" required>
          </div>

          <div class="csv-note">
            <strong>Định dạng cột:</strong><br>
            MSSV | Họ và tên | Ngày sinh | Số điện thoại | Email | Lớp | Địa chỉ
          </div>

          <div class="form-actions">
            <button type="button" class="btn-light" data-close="importModal">Hủy</button>
            <button type="submit" class="btn-primary">Nhập CSV</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div class="modal" id="editStudentModal">
    <div class="modal-dialog" id="editModalContent"></div>
  </div>

  <script src="../assets/js/students.js"></script>
  <script src="../assets/js/logout.js"> </script>
</body>

</html>