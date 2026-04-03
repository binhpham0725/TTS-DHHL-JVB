<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý điểm</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
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
              <a href="listsv.php" class="menu-item ">
                <i class="fa-solid fa-users"></i>
                <span>Danh sách Sinh viên</span>
              </a>
              <a href="scores.php" class="menu-item ">
                <i class="fa-solid fa-star"></i>
                <span>Quản lý Điểm</span>
              </a>
              <a href="subjects.php" class="menu-item ">
                <i class="fa-solid fa-book-bookmark"></i>
                <span>Môn học</span>
              </a>
              <a href="statisticals.php" class="menu-item active">
                <i class="fa-solid fa-chart-column"></i>
                <span>Thống kê &amp; Phân tích</span>
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
          <div class="breadcrumb">Pages <span>/</span> <strong>Thống kê &amp; Phân tích</strong></div>
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
      </main>
    </div>
    <script></script>
</body>
</html>