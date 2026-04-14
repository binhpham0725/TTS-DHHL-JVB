<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Hệ thống Quản lý Sinh viên</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"/>
    <link rel="stylesheet" href="../assets/css/style.css"/>
    <link rel="stylesheet" href="../assets/css/nav.css"/>
</head>
<body>
    <div class="container-fluid px-0">
        <div class="mobile-sidebar-backdrop"></div>
        <div class="row g-0 layout bootstrap-layout">
        <aside class="col-12 col-lg-3 col-xl-2 sidebar-col">
            <div class="sidebar">
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
            </nav>

            <div class="sidebar-footer">
                <h4>GV: <?= htmlspecialchars($teacherName) ?></h4>
            </div>
            </div>
        </aside>

        <main class="col-12 col-lg-9 col-xl-10 main-col">
            <div class="main">
            <header class="topbar">
                <button class="icon-circle mobile-menu-toggle" type="button" aria-label="Mo menu">
                    <i class="fa-solid fa-bars"></i>
                </button>
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
                            <button class="dropdown-item logout" onclick="logout()">
                                <span><i class="fa-solid fa-arrow-right-from-bracket"></i> Đăng xuất</span>
                            </button>
                        </div>
                    </div>
                </div>
            </header>

            <section class="dashboard-header">
                <div class="page-head">
                    <h2>Tổng quan</h2>
                </div>
            </section>

            <section class="dashboard-filter">
                <div class="filter-tabs">
                    <?php
                    $tabs = ['all' => 'Toàn khóa', 'D16CNTT' => 'D16CNTT', 'D17CNTT' => 'D17CNTT', 'D18CNTT' => 'D18CNTT'];
                    foreach ($tabs as $val => $label):
                    ?>
                        <a href="?class=<?= $val ?>" class="stat-tab <?= $activeClass === $val ? 'active' : '' ?>">
                            <?= $label ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </section>

            <section class="dashboard-analytics">
                <div class="charts-row">
                    <div class="chart-card">
                        <div class="chart-card-header">
                            <h4>Phân bổ Xếp loại học lực</h4>
                        </div>
                        <div class="chart-doughnut-wrap">
                            <canvas id="chartRanking"></canvas>
                            <div class="chart-center-label">
                                <span id="chartCenterPct">-</span>
                                <small>tổng quát</small>
                            </div>
                        </div>
                        <div class="chart-legend" id="chartLegend"></div>
                    </div>

                    <div class="chart-card">
                        <div class="chart-card-header">
                            <h4>GPA trung bình theo môn</h4>
                        </div>
                        <canvas id="chartGpaBar"></canvas>
                    </div>
                </div>
            </section>
            </div>
        </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="../assets/js/layout.js"></script>
    <script src="../assets/js/logout.js"></script>
    <script src="../assets/js/statisticals.js"></script>
</body>
</html>
