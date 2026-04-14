<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nhập điểm học phần</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/nav.css">
    <link rel="stylesheet" href="../assets/css/scores.css">
</head>
<body>
<div class="container-fluid px-0">
    <div class="mobile-sidebar-backdrop"></div>
    <div class="row g-0 layout bootstrap-layout">
    <aside class="col-12 col-lg-3 col-xl-2 sidebar-col">
        <div class="sidebar">
        <div>
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
                <a href="scores.php" class="menu-item active">
                    <i class="fa-solid fa-star"></i>
                    <span>Quản lý Điểm</span>
                </a>
                <a href="subjects.php" class="menu-item">
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

        <section class="content">
            <div class="page-head">
                <div>
                    <h2>Nhập điểm học phần</h2>
                    <p>
                        <?php if ($subjectInfo): ?>
                            Lớp: <?= $selectedClass !== '' ? htmlspecialchars($selectedClass) : 'Tất cả' ?>
                            - Mã môn: <?= htmlspecialchars($subjectInfo['subject_code']) ?>
                            - Môn học: <?= htmlspecialchars($subjectInfo['subject_name']) ?>
                        <?php else: ?>
                            Chọn môn học và lớp để nhập hoặc export điểm
                        <?php endif; ?>
                    </p>
                </div>

                <div class="page-actions">
                    <form action="../function/scores/import.php" method="POST" enctype="multipart/form-data" class="inline-form">
                        <input type="hidden" name="subject_id" value="<?= $selectedSubject ?>">
                        <input type="hidden" name="class" value="<?= htmlspecialchars($selectedClass) ?>">
                        <label class="action-btn">
                            <i class="fa-solid fa-file-import"></i>
                            <span>Nhập từ CSV</span>
                            <input type="file" name="csv_file" accept=".csv" hidden onchange="this.form.submit()">
                        </label>
                    </form>

                    <a class="action-btn <?= $selectedSubject <= 0 ? 'disabled' : '' ?>"
                       href="<?= $selectedSubject > 0 ? '../function/scores/export.php?subject_id=' . $selectedSubject . '&class=' . urlencode($selectedClass) : '#' ?>">
                        <i class="fa-solid fa-file-export"></i>
                        <span>Xuất CSV</span>
                    </a>

                    <button class="action-btn primary" form="scoreForm" type="submit">
                        <i class="fa-solid fa-floppy-disk"></i>
                        <span>Lưu thay đổi</span>
                    </button>
                </div>
            </div>
        </section>

        <?php if (isset($_GET['msg'])): ?>
            <div class="flash-msg">
                <?php
                switch ($_GET['msg']) {
                    case 'saved':
                        echo 'Đã lưu điểm thành công.';
                        break;
                    case 'del_success':
                        echo 'Đã xóa sinh viên khỏi bảng điểm.';
                        break;
                    case 'error_subject':
                        echo 'Vui lòng chọn môn học hợp lệ.';
                        break;
                    case 'import_success':
                        echo 'Import CSV thành công.';
                        if (isset($_GET['imported'])) {
                            echo ' Đã nhập: ' . (int)$_GET['imported'] . ' dòng.';
                        }
                        if (isset($_GET['skipped'])) {
                            echo ' Bỏ qua: ' . (int)$_GET['skipped'] . ' dòng.';
                        }
                        break;
                    case 'error_file':
                        echo 'File CSV không hợp lệ hoặc không thể đọc.';
                        break;
                    default:
                        echo 'Thao tác thành công.';
                        break;
                }
                ?>
            </div>
        <?php endif; ?>

        <form method="GET" class="score-filters">
            <div class="select-box">
                <select name="subject_id" class="filter-select" required>
                    <option value="">Chọn Môn học</option>
                    <?php foreach ($subjects as $item): ?>
                        <option value="<?= $item['id'] ?>" <?= $selectedSubject === (int)$item['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($item['subject_name']) ?> (<?= htmlspecialchars($item['subject_code']) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="select-box">
                <select name="class" class="filter-select">
                    <option value="">Tất cả các khóa</option>
                    <?php foreach ($classes as $className): ?>
                        <option value="<?= htmlspecialchars($className) ?>" <?= $selectedClass === $className ? 'selected' : '' ?>>
                            <?= htmlspecialchars($className) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" class="filter-submit">Lọc dữ liệu</button>
        </form>

        <form id="scoreForm" action="../function/scores/save.php" method="POST">
            <input type="hidden" name="subject_id" value="<?= $selectedSubject ?>">
            <input type="hidden" name="class" value="<?= htmlspecialchars($selectedClass) ?>">
            <input type="hidden" name="page" value="<?= $page ?>">

            <section class="scores-panel">
                <div class="score-table-wrap">
                    <table class="score-table">
                        <thead>
                            <tr>
                                <th>MSSV</th>
                                <th>Họ và tên</th>
                                <th>Chuyên cần</th>
                                <th>Giữa kỳ</th>
                                <th>Cuối kỳ</th>
                                <th>Trung bình</th>
                                <th>Đánh giá</th>
                                <th>Xếp loại</th>
                                <th>Chức năng</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if ($selectedSubject <= 0): ?>
                            <tr>
                                <td colspan="9" class="empty-cell">Vui lòng chọn môn học để hiển thị danh sách điểm.</td>
                            </tr>
                        <?php elseif (empty($scoreRows)): ?>
                            <tr>
                                <td colspan="9" class="empty-cell">Không có sinh viên phù hợp.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($scoreRows as $row): ?>
                                <?php
                                    $avg = (float)$row['total_score'];
                                    $statusText = getResultStatus($avg);
                                    $statusClass = getStatusClass($avg);
                                    $rank = getRank($avg);
                                ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['mssv']) ?></td>
                                    <td class="name-cell"><?= htmlspecialchars($row['fullname']) ?></td>
                                    <td>
                                        <input type="number" step="0.1" min="0" max="10" name="scores[<?= $row['student_id'] ?>][attendance_score]" value="<?= htmlspecialchars((string)$row['attendance_score']) ?>" class="score-input">
                                    </td>
                                    <td>
                                        <input type="number" step="0.1" min="0" max="10" name="scores[<?= $row['student_id'] ?>][midterm_score]" value="<?= htmlspecialchars((string)$row['midterm_score']) ?>" class="score-input">
                                    </td>
                                    <td>
                                        <input type="number" step="0.1" min="0" max="10" name="scores[<?= $row['student_id'] ?>][final_score]" value="<?= htmlspecialchars((string)$row['final_score']) ?>" class="score-input">
                                    </td>
                                    <td class="avg-cell"><?= number_format($avg, 1) ?></td>
                                    <td>
                                        <span class="status-badge <?= $statusClass ?>">
                                            <?= $statusText ?>
                                        </span>
                                    </td>
                                    <td class="rank-cell"><?= $rank ?></td>
                                    <td>
                                        <?php if (!empty($row['score_id'])): ?>
                                            <a class="delete-score-btn" href="../function/scores/del.php?id=<?= $row['score_id'] ?>&subject_id=<?= $selectedSubject ?>&class=<?= urlencode($selectedClass) ?>&page=<?= $page ?>" onclick="return confirm('Bạn có chắc muốn xóa sinh viên này khỏi bảng điểm?')">
                                                Xóa
                                            </a>
                                        <?php else: ?>
                                            <span class="no-action">Chưa có</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <?php if ($selectedSubject > 0): ?>
                    <div class="table-footer">
                        <div class="table-info">
                            <?php if ($totalStudents > 0): ?>
                                Hiển thị <?= $offset + 1 ?> - <?= min($offset + $perPage, $totalStudents) ?> trong số <?= $totalStudents ?> sinh viên
                            <?php else: ?>
                                Hiển thị 0 sinh viên
                            <?php endif; ?>
                        </div>

                        <?php if ($totalPages > 1): ?>
                            <div class="pagination">
                                <?php if ($page > 1): ?>
                                    <a class="page-btn" href="?<?= buildQuery(['page' => $page - 1]) ?>">
                                        <i class="fa-solid fa-chevron-left"></i>
                                    </a>
                                <?php else: ?>
                                    <span class="page-btn disabled">
                                        <i class="fa-solid fa-chevron-left"></i>
                                    </span>
                                <?php endif; ?>

                                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                    <a class="page-btn <?= $i === $page ? 'active' : '' ?>" href="?<?= buildQuery(['page' => $i]) ?>">
                                        <?= $i ?>
                                    </a>
                                <?php endfor; ?>

                                <?php if ($page < $totalPages): ?>
                                    <a class="page-btn" href="?<?= buildQuery(['page' => $page + 1]) ?>">
                                        <i class="fa-solid fa-chevron-right"></i>
                                    </a>
                                <?php else: ?>
                                    <span class="page-btn disabled">
                                        <i class="fa-solid fa-chevron-right"></i>
                                    </span>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </section>
        </form>
        </div>
    </main>
    </div>
</div>

<script src="../assets/js/layout.js"></script>
<script src="../assets/js/logout.js"></script>
</body>
</html>
