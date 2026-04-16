<?php
$common = app_text_group('common');
$nav = app_text_group('nav');
$texts = app_text_group('students');
$headers = $texts['headers'];
$messages = $texts['messages'];
$actions = $texts['actions'];
$toasts = [];

if (!empty($addError)) {
    $toasts[] = [
        'type' => 'error',
        'message' => $addError,
    ];
}

if (isset($_GET['msg'])) {
    $toastType = 'info';
    $toastMessage = $common['action_processed'];

    switch ($_GET['msg']) {
        case 'add_success':
        case 'edit_success':
        case 'delete_success':
            $toastType = 'success';
            $toastMessage = $messages[$_GET['msg']];
            break;
        case 'import_success':
            $toastType = 'success';
            $toastMessage = $messages['import_success'];
            if (isset($_GET['imported'])) {
                $toastMessage .= ' ' . app_text('students.messages.imported_count', ['count' => (int)$_GET['imported']]);
            }
            if (isset($_GET['skipped'])) {
                $toastMessage .= ' ' . app_text('students.messages.skipped_count', ['count' => (int)$_GET['skipped']]);
            }
            if (!empty($_GET['reason'])) {
                $toastMessage .= ' ' . app_text('students.messages.first_reason', ['reason' => (string)$_GET['reason']]);
            }
            break;
        case 'error_file':
        case 'delete_error':
            $toastType = 'error';
            $toastMessage = $messages[$_GET['msg']];
            break;
    }

    $toasts[] = [
        'type' => $toastType,
        'message' => $toastMessage,
    ];
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($texts['page_title']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/toast.css">
    <link rel="stylesheet" href="../assets/css/confirm.css">
    <link rel="stylesheet" href="../assets/css/students.css">
    <link rel="stylesheet" href="../assets/css/nav.css">
</head>
<body>
    <?php require dirname(__DIR__) . '/partials/toast.php'; ?>
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
                    <h2><?= htmlspecialchars($common['brand']) ?></h2>
                </div>

                <nav class="menu">
                    <a href="index.php" class="menu-item">
                        <i class="fa-solid fa-table-cells-large"></i>
                        <span><?= htmlspecialchars($nav['dashboard']) ?></span>
                    </a>
                    <a href="listsv.php" class="menu-item active">
                        <i class="fa-solid fa-users"></i>
                        <span><?= htmlspecialchars($nav['students']) ?></span>
                    </a>
                    <a href="scores.php" class="menu-item">
                        <i class="fa-solid fa-star"></i>
                        <span><?= htmlspecialchars($nav['scores']) ?></span>
                    </a>
                    <a href="subjects.php" class="menu-item">
                        <i class="fa-solid fa-book-bookmark"></i>
                        <span><?= htmlspecialchars($nav['subjects']) ?></span>
                    </a>
                </nav>
            </div>

            <div class="user-card">
                <div class="sidebar-footer">
                    <div>
                        <h4><?= htmlspecialchars($common['teacher_prefix']) ?> <?= htmlspecialchars($teacherName) ?></h4>
                    </div>
                </div>
            </div>
            </div>
        </aside>

        <main class="col-12 col-lg-9 col-xl-10 main-col">
        <div class="main">
            <header class="topbar">
                <button class="icon-circle mobile-menu-toggle" type="button" aria-label="<?= htmlspecialchars($common['open_menu']) ?>">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <div class="breadcrumb"><?= htmlspecialchars($common['pages']) ?> <span>/</span> <strong><?= htmlspecialchars($texts['breadcrumb']) ?></strong></div>
                <div class="topbar-actions">
                    <button class="icon-circle" type="button" aria-label="<?= htmlspecialchars($common['bell_aria']) ?>">
                        <i class="fa-regular fa-bell"></i>
                    </button>

                    <div class="profile-menu">
                        <button class="icon-circle" type="button" aria-label="<?= htmlspecialchars($common['settings_aria']) ?>">
                            <i class="fa-solid fa-gear"></i>
                        </button>

                        <div class="dropdown">
                            <button class="dropdown-item logout" type="button" onclick="logout()">
                                <span><i class="fa-solid fa-arrow-right-from-bracket"></i> <?= htmlspecialchars($common['logout']) ?></span>
                            </button>
                        </div>
                    </div>
                </div>
            </header>

            <section class="content">
                <div class="page-head">
                    <h2><?= htmlspecialchars($texts['heading']) ?></h2>

                    <div class="page-actions">
                        <button class="action-btn" id="openImportModal" type="button">
                            <i class="fa-solid fa-upload"></i>
                            <?= htmlspecialchars($texts['import_csv']) ?>
                        </button>

                        <a href="<?= $exportUrl ?>" class="action-btn">
                            <i class="fa-solid fa-download"></i>
                            <?= htmlspecialchars($texts['export_csv']) ?>
                        </a>

                        <button class="action-btn primary" id="openAddModal" type="button">
                            <i class="fa-solid fa-user-plus"></i>
                            <?= htmlspecialchars($texts['add_new']) ?>
                        </button>
                    </div>
                </div>
            </section>

            <section class="search-page">
                <form method="GET" class="filter-form">
                    <div class="search-box">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="<?= htmlspecialchars($texts['search_placeholder']) ?>">
                    </div>

                    <div class="select-box">
                        <select name="class" onchange="this.form.submit()">
                            <option value=""><?= htmlspecialchars($common['all_classes']) ?></option>
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
                                <th><?= htmlspecialchars($headers['mssv']) ?></th>
                                <th><?= htmlspecialchars($headers['fullname']) ?></th>
                                <th><?= htmlspecialchars($headers['birthday']) ?></th>
                                <th><?= htmlspecialchars($headers['gender']) ?></th>
                                <th><?= htmlspecialchars($headers['phone']) ?></th>
                                <th><?= htmlspecialchars($headers['email']) ?></th>
                                <th><?= htmlspecialchars($headers['class']) ?></th>
                                <th><?= htmlspecialchars($headers['address']) ?></th>
                                <th><?= htmlspecialchars($headers['actions']) ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($currentStudents)): ?>
                                <tr>
                                    <td colspan="9" class="empty-cell">
                                        <div class="empty-box">
                                            <i class="fa-regular fa-folder-open"></i>
                                            <p><?= htmlspecialchars($texts['empty']) ?></p>
                                        </div>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($currentStudents as $student): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($student['mssv']) ?></td>
                                        <td><?= htmlspecialchars($student['fullname']) ?></td>
                                        <td><?= htmlspecialchars($student['birthday']) ?></td>
                                        <td><?= htmlspecialchars($student['gender']) ?></td>
                                        <td><?= htmlspecialchars($student['phone']) ?></td>
                                        <td><?= htmlspecialchars($student['email']) ?></td>
                                        <td><span class="class-badge"><?= htmlspecialchars($student['class']) ?></span></td>
                                        <td class="address"><?= htmlspecialchars($student['address']) ?></td>
                                        <td>
                                            <div class="table-actions">
                                                <button class="icon-action edit-btn" type="button" data-id="<?= htmlspecialchars($student['id']) ?>" title="<?= htmlspecialchars($actions['edit']) ?>">
                                                    <i class="fa-solid fa-pen"></i>
                                                </button>

                                                <a
                                                    class="icon-action delete-btn"
                                                    href="../function/students/del.php?id=<?= urlencode($student['id']) ?>"
                                                    data-app-confirm="1"
                                                    data-confirm-message="<?= htmlspecialchars($texts['confirm_delete'], ENT_QUOTES) ?>"
                                                    data-confirm-accept="Xóa"
                                                    data-confirm-cancel="Hủy"
                                                    data-confirm-variant="danger"
                                                    title="<?= htmlspecialchars($actions['delete']) ?>"
                                                >
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
                        <div>
                            <?php if ($totalStudents > 0): ?>
                                <?= htmlspecialchars(app_text('students.showing_range', [
                                    'from' => $start + 1,
                                    'to' => min($start + $perPage, $totalStudents),
                                    'total' => $totalStudents,
                                ])) ?>
                            <?php else: ?>
                                <?= htmlspecialchars($texts['showing_zero']) ?>
                            <?php endif; ?>
                        </div>

                        <div class="pagination">
                            <?php if ($page > 1): ?>
                                <a class="page-btn" href="?<?= buildQuery(['page' => $page - 1]) ?>">
                                    <i class="fa-solid fa-chevron-left"></i>
                                </a>
                            <?php else: ?>
                                <span class="page-btn disabled"><i class="fa-solid fa-chevron-left"></i></span>
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
                                <span class="page-btn disabled"><i class="fa-solid fa-chevron-right"></i></span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        </main>
        </div>
    </div>

    <div class="modal" id="addStudentModal" data-auto-open="<?= (!empty($addError) || !empty($addFieldErrors) || !empty($addOld)) ? '1' : '0' ?>">
        <div class="modal-dialog">
            <?php
            $error = '';
            $fieldErrors = $addFieldErrors;
            $old = $addOld;
            require __DIR__ . '/partials/add_modal.php';
            ?>
        </div>
    </div>

    <div class="modal" id="importModal">
        <div class="modal-dialog small">
            <div class="modal-card">
                <div class="modal-header">
                    <h3><?= htmlspecialchars($texts['import_modal']['title']) ?></h3>
                    <button type="button" class="close-modal" data-close="importModal">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <form action="../function/students/import.php" method="POST" enctype="multipart/form-data" class="student-form">
                    <div class="form-group">
                        <label><?= htmlspecialchars($texts['import_modal']['choose_file']) ?></label>
                        <input type="file" name="csv_file" accept=".csv" required>
                    </div>

                    <div class="csv-note">
                        <strong><?= htmlspecialchars($texts['import_modal']['columns_label']) ?></strong><br>
                        <?= htmlspecialchars($texts['import_modal']['columns_value']) ?>
                    </div>

                    <div class="form-actions">
                        <button type="button" class="btn-light" data-close="importModal"><?= htmlspecialchars($texts['import_modal']['cancel']) ?></button>
                        <button type="submit" class="btn-primary"><?= htmlspecialchars($texts['import_modal']['submit']) ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal" id="editStudentModal">
        <div class="modal-dialog" id="editModalContent"></div>
    </div>

    <script>
        window.APP_TEXTS = window.APP_TEXTS || {};
        window.APP_TEXTS.common = <?= json_encode([
            'logout_confirm' => app_text('common.logout_confirm'),
            'confirm_title' => 'Xác nhận thao tác',
            'confirm_accept' => 'Đồng ý',
            'confirm_cancel' => 'Hủy',
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;
        window.APP_TEXTS.students = <?= json_encode(app_text_group('students.js'), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;
    </script>
    <script src="../assets/js/toast.js"></script>
    <script src="../assets/js/confirm.js"></script>
    <script src="../assets/js/students.js"></script>
    <script src="../assets/js/layout.js"></script>
    <script src="../assets/js/logout.js"></script>
</body>
</html>
