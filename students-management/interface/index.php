<?php
session_start();
require_once "../config/db.php";

if (!isset($_SESSION['teacher_id'])) {
    header("Location: ../auth/login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Hệ thống Quản lý Sinh viên</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"/>
    <link rel="stylesheet" href="../assets/css/style.css" />
    <link rel="stylesheet" href="../assets/css/nav.css" />
</head>
<body>
    <div class="layout">
        <aside class="sidebar">
        <div class="brand">
            <div class="brand-icon">
            <i class="fa-solid fa-graduation-cap"></i>
            </div>
            <h2>HLUV</h2>
        </div>

        <nav class="menu">
            <a href="index.php" class="menu-item active">
                <i class="fa-solid fa-table-cells-large"></i>
                <span>Bảng điều khiển</span>
            </a>
            <a href="listsv.php" class="menu-item">
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
                <span>Thống kê &amp; Phân tích</span>
            </a>
        </nav>

        <div class="sidebar-footer">
            <div>
                <h4>GV: <?php echo isset($_SESSION['teacher_name']) ? $_SESSION['teacher_name'] : 'Chưa đăng nhập'; ?></h4>
            </div>
        </div>
        </aside>

        <main class="main">
            <header class="topbar">
                <h1>Hệ thống Quản lý Sinh viên</h1>

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
                        <span><i class="fa-solid fa-arrow-right-from-bracket"></i><a href="logout.php">Đăng xuất</a></span>
                    </button>
                </div>
              </div>
            </div>
        </header>

        <section class="content">
            <h2>Tổng quan Hệ thống</h2> 
        </section>

        <section class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon blue">
                    <i class="fa-solid fa-users"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-label">TỔNG SINH VIÊN</span>
                        <h3>90</h3>
                    <div class="divider"></div>
                    <ul class="stat-list">
                        <li><span>D16 CNTT</span><strong>21</strong></li>
                        <li><span>D17 CNTT</span><strong>35</strong></li>
                        <li><span>D18 CNTT</span><strong>34</strong></li>
                    </ul>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon yellow">
                    <i class="fa-regular fa-star"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-label">ĐIỂM TRUNG BÌNH</span>
                    <h3>3.42</h3>
                    <p class="subtext">Tính trên toàn hệ thống</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon green">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-label">TỶ LỆ ĐẠT</span>
                    <h3></h3>
                    <div class="progress">
                     <span></span>
                    </div>
                    <p class="subtext">Dựa trên kết quả môn học hiện tại</p>
                </div>
            </div>

        </section>

<!--         chưa sửa
        <section class="students-panel">
            <div class="students-panel__header">
                <h2>Hoạt động Sinh viên Gần đây</h2>

                <div class="search-box">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input
                    type="text"
                    placeholder="Tìm kiếm theo tên, MSSV hoặc khóa học"
                    />
                </div>
            </div>

            <div class="students-table-wrap">
            <table class="students-table">
                <thead>
                <tr>
                    <th>TÊN SINH VIÊN</th>
                    <th>MSSV</th>
                    <th>KHÓA</th>
                    <th>MÔN HỌC</th>
                    <th>TRẠNG THÁI</th>
                    <th>THAO TÁC</th>
                </tr>
                </thead>

                <tbody>
                    <?php foreach ($students as $student): ?>
                        <tr>
                            <td><?= htmlspecialchars($student['fullname']) ?></td>
                            <td><?= htmlspecialchars($student['mssv']) ?></td>
                            <td><?= htmlspecialchars($student['class']) ?></td>
                            <td><?= htmlspecialchars($student['phone']) ?></td>
                            <td><?= htmlspecialchars($student['email']) ?></td>
                            <td>
                                <a href="../function/students/edit.php $student['id'] ?>">Sửa</a>
                                <a href="../function/students/del.php $student['id'] ?>" onclick="return confirm('Xóa sinh viên này?')">Xóa</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

                <div class="students-panel__footer">
                    <p>Hiển thị 4 trong số # sinh viên</p>

                    <div class="pagination">
                        <button class="page-btn page-btn--disabled">Trước</button>
                        <button class="page-btn">Sau</button>
                    </div>
                </div>
        </section> -->
        </main>
    </div>
    <script src="../assets/js/logout.js"></script>

</body>
</html>