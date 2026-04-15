<?php
$common = app_text_group('common');
$nav = app_text_group('nav');
$texts = app_text_group('subjects');
$meta = $texts['meta'];
$actions = $texts['actions'];
$messages = $texts['messages'];
$modal = $texts['modal'];
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
                    <h2><?= htmlspecialchars($common['brand']) ?></h2>
                </div>

                <nav class="menu">
                    <a href="index.php" class="menu-item">
                        <i class="fa-solid fa-table-cells-large"></i>
                        <span><?= htmlspecialchars($nav['dashboard']) ?></span>
                    </a>
                    <a href="listsv.php" class="menu-item">
                        <i class="fa-solid fa-users"></i>
                        <span><?= htmlspecialchars($nav['students']) ?></span>
                    </a>
                    <a href="scores.php" class="menu-item">
                        <i class="fa-solid fa-star"></i>
                        <span><?= htmlspecialchars($nav['scores']) ?></span>
                    </a>
                    <a href="subjects.php" class="menu-item active">
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
                    <button class="icon-circle" aria-label="<?= htmlspecialchars($common['bell_aria']) ?>">
                        <i class="fa-regular fa-bell"></i>
                    </button>

                    <div class="profile-menu">
                        <button class="icon-circle" aria-label="<?= htmlspecialchars($common['settings_aria']) ?>">
                            <i class="fa-solid fa-gear"></i>
                        </button>

                        <div class="dropdown">
                            <button class="dropdown-item logout" onclick="logout()">
                                <span><i class="fa-solid fa-arrow-right-from-bracket"></i> <?= htmlspecialchars($common['logout']) ?></span>
                            </button>
                        </div>
                    </div>
                </div>
            </header>

            <section class="subjects-page">
                <div class="subjects-head">
                    <div>
                        <h2><?= htmlspecialchars($texts['heading']) ?></h2>
                        <p><?= htmlspecialchars($texts['subtitle']) ?></p>
                    </div>

                    <a href="../function/subjects/add.php" class="btn-add-subject">
                        <i class="fa-solid fa-plus"></i>
                        <span><?= htmlspecialchars($texts['add_new']) ?></span>
                    </a>
                </div>

                <?php if (isset($_GET['msg'])): ?>
                    <div class="empty-state" style="margin-bottom: 18px; padding: 18px 20px;">
                        <p style="margin:0; text-align:left;">
                            <?php
                            switch ($_GET['msg']) {
                                case 'add_success':
                                    echo htmlspecialchars($messages['add_success']);
                                    break;
                                case 'edit_success':
                                    echo htmlspecialchars($messages['edit_success']);
                                    break;
                                case 'del_success':
                                    echo htmlspecialchars($messages['del_success']);
                                    break;
                                case 'not_found':
                                    echo htmlspecialchars($messages['not_found']);
                                    break;
                                case 'error_delete':
                                    echo htmlspecialchars($messages['error_delete']);
                                    break;
                                default:
                                    echo htmlspecialchars($common['action_processed']);
                                    break;
                            }
                            ?>
                        </p>
                    </div>
                <?php endif; ?>

                <?php if (empty($subjects)): ?>
                    <div class="empty-state">
                        <i class="fa-solid fa-book-open"></i>
                        <h3><?= htmlspecialchars($texts['empty_title']) ?></h3>
                        <p><?= htmlspecialchars($texts['empty_description']) ?></p>

                        <a href="../function/subjects/add.php" class="btn-add-subject empty-btn">
                            <i class="fa-solid fa-plus"></i>
                            <span><?= htmlspecialchars($texts['add_new']) ?></span>
                        </a>
                    </div>
                <?php else: ?>
                    <?php
                    $icons = [
                        ['fa-solid fa-laptop-code', 'tag-mix', $texts['badge']],
                        ['fa-solid fa-database', 'tag-theory', $texts['badge']],
                        ['fa-solid fa-code-branch', 'tag-mix', $texts['badge']],
                        ['fa-solid fa-globe', 'tag-practice', $texts['badge']],
                    ];
                    $i = 0;
                    ?>

                    <div class="subjects-grid">
                        <?php foreach ($subjects as $item): ?>
                            <?php $currentIcon = $icons[$i % count($icons)]; ?>

                            <div class="subject-card-v2">
                                <div class="subject-top">
                                    <div class="subject-banner <?= $currentIcon[1] ?>">
                                        <span class="subject-badge"><?= htmlspecialchars($currentIcon[2]) ?></span>

                                        <div class="subject-icon">
                                            <i class="<?= $currentIcon[0] ?>"></i>
                                        </div>
                                    </div>

                                    <div class="subject-content">
                                        <h3><?= htmlspecialchars($item['subject_name']) ?></h3>

                                        <div class="subject-meta">
                                            <div class="meta-row">
                                                <span><?= htmlspecialchars($meta['code']) ?></span>
                                                <strong><?= htmlspecialchars($item['subject_code'] ?? '') ?></strong>
                                            </div>

                                            <div class="meta-row">
                                                <span><?= htmlspecialchars($meta['credits']) ?></span>
                                                <strong><?= (int)($item['credits'] ?? 3) ?> <?= htmlspecialchars($meta['credit_suffix']) ?></strong>
                                            </div>

                                            <div class="meta-row">
                                                <span><?= htmlspecialchars($meta['attendance']) ?></span>
                                                <strong><?= (int)($item['attendance_weight'] ?? 10) ?>%</strong>
                                            </div>

                                            <div class="meta-row">
                                                <span><?= htmlspecialchars($meta['mid_final']) ?></span>
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
                                            '<?= htmlspecialchars($item['description'] ?? $modal['no_description'], ENT_QUOTES) ?>',
                                            '<?= (int)($item['attendance_weight'] ?? 10) ?>',
                                            '<?= (int)($item['midterm_weight'] ?? 30) ?>',
                                            '<?= (int)($item['final_weight'] ?? 60) ?>'
                                        )"
                                    >
                                        <i class="fa-solid fa-eye"></i>
                                        <span><?= htmlspecialchars($actions['view_detail']) ?></span>
                                    </button>

                                    <a href="../function/subjects/edit.php?id=<?= $item['id'] ?>" class="btn-edit" title="<?= htmlspecialchars($actions['edit']) ?>">
                                        <i class="fa-solid fa-pen"></i>
                                    </a>

                                    <a href="../function/subjects/del.php?id=<?= $item['id'] ?>" class="btn-delete" title="<?= htmlspecialchars($actions['delete']) ?>" onclick="return confirm('<?= htmlspecialchars($actions['confirm_delete'], ENT_QUOTES) ?>')">
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
                <h3 id="m_name"><?= htmlspecialchars($modal['title']) ?></h3>
                <button class="subject-modal-close" type="button" onclick="closeModal()">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <div class="subject-modal-body">
                <div class="subject-modal-grid">
                    <div class="subject-modal-left">
                        <h4><?= htmlspecialchars($modal['detail_info']) ?></h4>

                        <div class="info-row">
                            <div class="info-item">
                                <span><?= htmlspecialchars($modal['code']) ?></span>
                                <strong id="m_code"></strong>
                            </div>

                            <div class="info-item">
                                <span><?= htmlspecialchars($modal['credits']) ?></span>
                                <strong id="m_credits"></strong>
                            </div>
                        </div>

                        <div class="desc-section">
                            <h4><?= htmlspecialchars($modal['description']) ?></h4>
                            <div class="desc-box" id="m_desc"></div>
                        </div>
                    </div>

                    <div class="subject-modal-right">
                        <h4><?= htmlspecialchars($modal['score_weights']) ?></h4>

                        <div class="score-item">
                            <div class="score-top">
                                <span><?= htmlspecialchars($modal['attendance']) ?></span>
                                <strong id="m_att"></strong>
                            </div>
                            <div class="score-bar">
                                <div class="score-fill" id="bar_att"></div>
                            </div>
                        </div>

                        <div class="score-item">
                            <div class="score-top">
                                <span><?= htmlspecialchars($modal['midterm']) ?></span>
                                <strong id="m_mid"></strong>
                            </div>
                            <div class="score-bar">
                                <div class="score-fill" id="bar_mid"></div>
                            </div>
                        </div>

                        <div class="score-item">
                            <div class="score-top">
                                <span><?= htmlspecialchars($modal['final']) ?></span>
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
                <button class="btn-close-modal" type="button" onclick="closeModal()"><?= htmlspecialchars($common['close']) ?></button>
            </div>
        </div>
    </div>

    <script>
        window.APP_TEXTS = window.APP_TEXTS || {};
        window.APP_TEXTS.common = <?= json_encode([
            'logout_confirm' => app_text('common.logout_confirm'),
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;
        window.APP_TEXTS.subjects = <?= json_encode(app_text_group('subjects.js'), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;
    </script>
    <script src="../assets/js/layout.js"></script>
    <script src="../assets/js/subjects.js"></script>
    <script src="../assets/js/logout.js"></script>
</body>
</html>
