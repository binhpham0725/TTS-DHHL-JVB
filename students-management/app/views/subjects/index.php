<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách môn học</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/nav.css">
    <link rel="stylesheet" href="../assets/css/subjects.css">
</head>
<body>
    <div class="container-fluid px-0">
        <div class="mobile-sidebar-backdrop"></div>
        <div class="row g-0 layout bootstrap-layout">
        <aside class="col-12 col-lg-3 col-xl-2 sidebar-col">
            <div class="sidebar">
            <div class="sidebar-top">
                <div class="brand">
                    <div class="brand-icon">
                        <i class="fa-solid fa-graduation-cap"></i>
                    </div>
                    <h2>HLUV</h2>
                </div>

                <nav class="menu">
                    <a href="index.php" class="menu-item">
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
                    <a href="subjects.php" class="menu-item active">
                        <i class="fa-solid fa-book-bookmark"></i>
                        <span>Môn học</span>
                    </a>
                </nav>
            </div>

            <div class="user-card">
                <div class="sidebar-footer">
                    <div>
                        <h4>GV: <?= htmlspecialchars($teacherName) ?></h4>
                    </div>
                </div>
            </div>
            </div>
        </aside>

        <main class="col-12 col-lg-9 col-xl-10 main-col">
            <div class="main">
            <header class="topbar">
                <button class="icon-circle mobile-menu-toggle" type="button" aria-label="Mo menu">
                    <i class="fa-solid fa-bars"></i>
                </button>
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
                            <button class="dropdown-item logout" onclick="logout()">
                                <span><i class="fa-solid fa-arrow-right-from-bracket"></i> Đăng xuất</span>
                            </button>
                        </div>
                    </div>
                </div>
            </header>

            <section class="subjects-page">
                <div class="subjects-head">
                    <div>
                        <h2>Môn học đang giảng dạy</h2>
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
                    <?php
                    $icons = [
                        ['fa-solid fa-laptop-code', 'tag-mix', 'Môn học'],
                        ['fa-solid fa-database', 'tag-theory', 'Môn học'],
                        ['fa-solid fa-code-branch', 'tag-mix', 'Môn học'],
                        ['fa-solid fa-globe', 'tag-practice', 'Môn học'],
                    ];
                    $i = 0;
                    ?>

                    <div class="subjects-grid">
                        <?php foreach ($subjects as $item): ?>
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
                                        <h3><?= htmlspecialchars($item['subject_name']) ?></h3>

                                        <div class="subject-meta">
                                            <div class="meta-row">
                                                <span>Mã môn:</span>
                                                <strong><?= htmlspecialchars($item['subject_code'] ?? '') ?></strong>
                                            </div>

                                            <div class="meta-row">
                                                <span>Số tín chỉ:</span>
                                                <strong><?= (int)($item['credits'] ?? 3) ?> Tín chỉ</strong>
                                            </div>

                                            <div class="meta-row">
                                                <span>Chuyên cần:</span>
                                                <strong><?= (int)($item['attendance_weight'] ?? 10) ?>%</strong>
                                            </div>

                                            <div class="meta-row">
                                                <span>Giữa kỳ / Cuối kỳ:</span>
                                                <strong><?= (int)($item['midterm_weight'] ?? 30) ?>% / <?= (int)($item['final_weight'] ?? 60) ?>%</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="subject-actions">
                                    <button
                                        type="button"
                                        class="btn-detail"
                                        onclick="openModal(
                                            '<?= htmlspecialchars($item['subject_name'], ENT_QUOTES) ?>',
                                            '<?= htmlspecialchars($item['subject_code'] ?? '', ENT_QUOTES) ?>',
                                            '<?= (int)($item['credits'] ?? 3) ?>',
                                            '<?= htmlspecialchars($item['description'] ?? 'Chưa có mô tả', ENT_QUOTES) ?>',
                                            '<?= (int)($item['attendance_weight'] ?? 10) ?>',
                                            '<?= (int)($item['midterm_weight'] ?? 30) ?>',
                                            '<?= (int)($item['final_weight'] ?? 60) ?>'
                                        )"
                                    >
                                        <i class="fa-solid fa-eye"></i>
                                        <span>Xem chi tiết</span>
                                    </button>

                                    <a href="../function/subjects/edit.php?id=<?= $item['id'] ?>" class="btn-edit" title="Sửa">
                                        <i class="fa-solid fa-pen"></i>
                                    </a>

                                    <a href="../function/subjects/del.php?id=<?= $item['id'] ?>" class="btn-delete" title="Xóa" onclick="return confirm('Bạn có chắc muốn xóa môn học này?')">
                                        <i class="fa-solid fa-trash"></i>
                                    </a>
                                </div>
                            </div>

                            <?php $i++; ?>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </section>
            </div>
        </main>
        </div>
    </div>

    <div class="subject-modal" id="subjectModal">
        <div class="subject-modal-box">
            <div class="subject-modal-header">
                <h3 id="m_name">Chi tiết môn học</h3>
                <button class="subject-modal-close" type="button" onclick="closeModal()">
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
                <button class="btn-close-modal" type="button" onclick="closeModal()">Đóng</button>
            </div>
        </div>
    </div>

    <script src="../assets/js/layout.js"></script>
    <script src="../assets/js/subjects.js"></script>
    <script src="../assets/js/logout.js"></script>
</body>
</html>
