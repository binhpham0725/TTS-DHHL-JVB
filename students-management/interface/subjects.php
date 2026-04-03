<?php
session_start();
require_once "../config/db.php";

$sql = "SELECT * FROM subject ORDER BY id DESC";
$result = mysqli_query($conn, $sql);

$subjects = [];
while ($row = mysqli_fetch_assoc($result)) {
    $subjects[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách môn học</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/nav.css">
    <link rel="stylesheet" href="../assets/css/subjects.css">
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
            <a href="subjects.php" class="menu-item active">
              <i class="fa-solid fa-book-bookmark"></i>
              <span>Môn học</span>
            </a>
            <a href="statisticals.php" class="menu-item">
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
            <div class="breadcrumb">Pages <span>/</span> <strong>Môn học</strong></div>
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

        <section class="subjects-page">
            <div class="subjects-header">
                <div>
                    <h1>Môn học đang giảng dạy</h1>
                    <p>Quản lý các học phần phụ trách trong học kỳ hiện tại</p>
                </div>

                <a href="../function/subjects/add.php" class="btn-add-subject">
                    <i class="fa-solid fa-plus"></i>
                    <span>Thêm môn học mới</span>
                </a>
            </div>

            <?php if (empty($subjects)): ?>
                <div class="empty-state">
                    <i class="fa-solid fa-book-open"></i>
                    <h3>Chưa có môn học nào</h3>
                    <p>Hãy thêm môn học đầu tiên để bắt đầu quản lý.</p>
                    <a href="../function/subjects/add.php" class="btn-add-subject empty-btn">
                        <i class="fa-solid fa-plus"></i>
                        <span>Thêm môn học</span>
                    </a>
                </div>
            <?php else: ?>
                <div class="subjects-grid">
                    <?php
                    $icons = [
                        ['fa-solid fa-laptop-code', 'tag-mix', 'Môn học'],
                        ['fa-solid fa-database', 'tag-theory', 'Môn học'],
                        ['fa-solid fa-code-branch', 'tag-mix', 'Môn học'],
                        ['fa-solid fa-globe', 'tag-practice', 'Môn học'],
                    ];
                    $i = 0;
                    ?>

                    <?php foreach ($subjects as $subject): ?>
                        <?php $currentIcon = $icons[$i % count($icons)]; ?>
                        <div class="subject-card-v2">
                            <div class="subject-top">
                                <div class="subject-banner <?= $currentIcon[1] ?>">
                                    <span class="subject-badge"><?= $currentIcon[2] ?></span>
                                    <div class="subject-icon">
                                        <i class="<?= $currentIcon[0] ?>"></i>
                                    </div>
                                </div>

                                <div class="subject-content">
                                    <h3><?= htmlspecialchars($subject['subject_name']) ?></h3>

                                    <div class="subject-meta">
                                        <div class="meta-row">
                                            <span>Mã môn:</span>
                                            <strong><?= htmlspecialchars($subject['subject_code'] ?? '') ?></strong>
                                        </div>
                                        <div class="meta-row">
                                            <span>Số tín chỉ:</span>
                                            <strong><?= (int)($subject['credits'] ?? 3) ?> Tín chỉ</strong>
                                        </div>
                                        <div class="meta-row">
                                            <span>Chuyên cần:</span>
                                            <strong><?= (int)($subject['attendance_weight'] ?? 10) ?>%</strong>
                                        </div>
                                        <div class="meta-row">
                                            <span>Giữa kỳ / Cuối kỳ:</span>
                                            <strong>
                                                <?= (int)($subject['midterm_weight'] ?? 30) ?>% /
                                                <?= (int)($subject['final_weight'] ?? 60) ?>%
                                            </strong>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="subject-actions">
                                <button
                                    class="btn-detail"
                                    onclick="openModal(
                                        '<?= htmlspecialchars($subject['subject_name'], ENT_QUOTES) ?>',
                                        '<?= htmlspecialchars($subject['subject_code'] ?? '', ENT_QUOTES) ?>',
                                        '<?= (int)($subject['credits'] ?? 3) ?>',
                                        '<?= htmlspecialchars($subject['description'] ?? 'Chưa có mô tả', ENT_QUOTES) ?>',
                                        '<?= (int)($subject['attendance_weight'] ?? 10) ?>',
                                        '<?= (int)($subject['midterm_weight'] ?? 30) ?>',
                                        '<?= (int)($subject['final_weight'] ?? 60) ?>'
                                    )"
                                >
                                    <i class="fa-solid fa-eye"></i>
                                    <span>Xem chi tiết</span>
                                </button>

                                <a href="../function/subjects/edit.php?id=<?= $subject['id'] ?>" class="btn-edit" title="Sửa">
                                    <i class="fa-solid fa-pen"></i>
                                </a>

                                <a href="../function/subjects/del.php?id=<?= $subject['id'] ?>"
                                   class="btn-delete"
                                   title="Xóa"
                                   onclick="return confirm('Bạn có chắc muốn xóa môn học này?')">
                                    <i class="fa-solid fa-trash"></i>
                                </a>
                            </div>
                        </div>
                        <?php $i++; ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
      </main>
    </div>

    <div class="subject-modal" id="subjectModal">
    <div class="subject-modal-box">
        <div class="subject-modal-header">
            <h3 id="m_name">Chi tiết môn học</h3>
            <button class="subject-modal-close" onclick="closeModal()">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <div class="subject-modal-body">
            <div class="subject-modal-grid">
                <div class="subject-modal-left">
                    <h4>THÔNG TIN CHI TIẾT</h4>

                    <div class="info-row">
                        <div class="info-item">
                            <span>MÃ MÔN</span>
                            <strong id="m_code"></strong>
                        </div>
                        <div class="info-item">
                            <span>SỐ TÍN CHỈ</span>
                            <strong id="m_credits"></strong>
                        </div>
                    </div>

                    <div class="desc-section">
                        <h4>MÔ TẢ MÔN HỌC</h4>
                        <div class="desc-box" id="m_desc"></div>
                    </div>
                </div>

                <div class="subject-modal-right">
                    <h4>TỶ TRỌNG ĐIỂM</h4>

                    <div class="score-item">
                        <div class="score-top">
                            <span>Chuyên cần</span>
                            <strong id="m_att"></strong>
                        </div>
                        <div class="score-bar">
                            <div class="score-fill" id="bar_att"></div>
                        </div>
                    </div>

                    <div class="score-item">
                        <div class="score-top">
                            <span>Kiểm tra giữa kỳ</span>
                            <strong id="m_mid"></strong>
                        </div>
                        <div class="score-bar">
                            <div class="score-fill" id="bar_mid"></div>
                        </div>
                    </div>

                    <div class="score-item">
                        <div class="score-top">
                            <span>Thi cuối kỳ</span>
                            <strong id="m_final"></strong>
                        </div>
                        <div class="score-bar">
                            <div class="score-fill" id="bar_final"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="subject-modal-footer">
            <button class="btn-close-modal" onclick="closeModal()">Đóng</button>
        </div>
    </div>
</div>

<script src="../assets/js/subjects.js"></script>
</body>
</html>