<div class="admin-container">
    <header class="page-header">
        <h2 class="page-header__title">Dashboard</h2>
        <span class="text-muted"><?= date('d/m/Y') ?></span>
    </header>

    <!-- STAT CARDS -->
    <div class="dashboard-cards">
        <div class="stat-card stat-card--blue">
            <div class="stat-card__icon"><i class="fas fa-users"></i></div>
            <div class="stat-card__info">
                <p class="stat-card__label">Tổng sinh viên</p>
                <h3 class="stat-card__value"><?= $total_students ?></h3>
            </div>
        </div>
        <div class="stat-card stat-card--green">
            <div class="stat-card__icon"><i class="fas fa-user-check"></i></div>
            <div class="stat-card__info">
                <p class="stat-card__label">Đang ở KTX</p>
                <h3 class="stat-card__value"><?= $students_living ?></h3>
            </div>
        </div>
        <div class="stat-card stat-card--indigo">
            <div class="stat-card__icon"><i class="fas fa-door-open"></i></div>
            <div class="stat-card__info">
                <p class="stat-card__label">Tổng phòng</p>
                <h3 class="stat-card__value"><?= $total_rooms ?></h3>
            </div>
        </div>
        <div class="stat-card stat-card--orange">
            <div class="stat-card__icon"><i class="fas fa-clipboard-list"></i></div>
            <div class="stat-card__info">
                <p class="stat-card__label">Chờ duyệt</p>
                <h3 class="stat-card__value"><?= $pending ?></h3>
            </div>
        </div>
    </div>

    <div class="dashboard-grid">
        <!-- BẢNG TÌNH TRẠNG PHÒNG -->
        <div class="data-card">
            <h3 class="dashboard-section__title">Tình trạng phòng</h3>
            <table class="data-table" style="margin-top: 1rem">
                <thead>
                    <tr>
                        <th>Phòng</th>
                        <th>Loại</th>
                        <th>Giới tính</th>
                        <th>Sĩ số</th>
                        <th>Tỉ lệ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($room_stats as $room): ?>
                    <tr>
                        <td class="text-bold text-center"><?= htmlspecialchars($room['room_name']) ?></td>
                        <td class="text-center"><?= htmlspecialchars($room['type_name']) ?></td>
                        <td class="text-center"><?= $room['gender'] == 1 ? 'Nam' : 'Nữ' ?></td>
                        <td class="text-center"><?= $room['current_number'] ?>/<?= $room['max_people'] ?></td>
                        <td style="min-width: 120px">
                            <div class="progress-bar">
                                <div class="progress-bar__fill <?= $room['percent'] >= 100 ? 'progress-bar__fill--full' : ($room['percent'] >= 70 ? 'progress-bar__fill--warn' : '') ?>"
                                     style="width: <?= min($room['percent'], 100) ?>%">
                                </div>
                            </div>
                            <span class="progress-label"><?= $room['percent'] ?>%</span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- BẢNG YÊU CẦU GẦN ĐÂY -->
        <div class="data-card">
            <h3 class="dashboard-section__title">Yêu cầu đăng ký gần đây</h3>
            <table class="data-table" style="margin-top: 1rem">
                <thead>
                    <tr>
                        <th>Sinh viên</th>
                        <th>MSSV</th>
                        <th>Loại phòng</th>
                        <th>Ngày nộp</th>
                        <th>Trạng thái</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recent_assignments as $item): ?>
                    <?php
                        $statusMap = [0 => ['label' => 'Chờ duyệt', 'class' => 'badge--pending'],
                                      1 => ['label' => 'Đã duyệt',  'class' => 'badge--success'],
                                      2 => ['label' => 'Từ chối',   'class' => 'badge--danger']];
                        $s = $statusMap[(int)$item['status']];
                    ?>
                    <tr>
                        <td class="text-bold"><?= htmlspecialchars($item['student_name']) ?></td>
                        <td class="text-center"><?= htmlspecialchars($item['mssv']) ?></td>
                        <td class="text-center"><?= htmlspecialchars($item['type_name']) ?></td>
                        <td class="text-center"><?= date('d/m/Y', strtotime($item['created_date'])) ?></td>
                        <td class="text-center"><span class="badge <?= $s['class'] ?>"><?= $s['label'] ?></span></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div style="margin-top: 1rem; text-align: right">
                <a href="<?= __WEB_ROOT__ ?>/admin/roomassignment" class="btn" style="font-size: 0.85rem; padding: 0.4rem 0.9rem">
                    Xem tất cả <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
</div>
