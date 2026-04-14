<?php
$currentUrl = $_SERVER['REQUEST_URI'];
$dashboardUrl     = __WEB_ROOT__ . "/admin/dashboard";
$studentUrl       = __WEB_ROOT__ . "/admin/student";
$roomUrl          = __WEB_ROOT__ . "/admin/room";
$roomAssignmentUrl = __WEB_ROOT__ . "/admin/roomassignment";

function isActive(string $url): string {
    $path    = parse_url($url, PHP_URL_PATH);
    $current = strtok($_SERVER['REQUEST_URI'], '?');
    return rtrim($current, '/') === rtrim($path, '/') ? 'sidebar__link--active' : '';
}

$userName  = $_SESSION['user']->email ?? 'Admin';
?>
<aside id="sideBar">
    <div class="sidebar__user">
        <h4 class="sidebar__user-name"><?= htmlspecialchars($_SESSION['user']->name ?? 'Admin') ?></h4>
        <p class="sidebar__user-email"><?= htmlspecialchars($_SESSION['user']->email ?? '') ?></p>
    </div>
    <div class="sidebar__body">
        <div class="sidebar__caption">Trang chủ</div>
        <ul class="sidebar__menu">
            <li class="sidebar__item">
                <a href="<?= $dashboardUrl ?>" class="sidebar__link <?= isActive($dashboardUrl) ?>">
                    <i class="fas fa-th-large sidebar__icon"></i>
                    <span class="sidebar__text">Dashboard</span>
                </a>
            </li>
        </ul>
        <div class="sidebar__caption">Quản lý</div>
        <ul class="sidebar__menu">
            <li class="sidebar__item">
                <a href="<?= $studentUrl ?>" class="sidebar__link <?= isActive($studentUrl) ?>">
                    <i class="fas fa-users sidebar__icon"></i>
                    <span class="sidebar__text">Sinh viên</span>
                </a>
            </li>
            <li class="sidebar__item">
                <a href="<?= $roomUrl ?>" class="sidebar__link <?= isActive($roomUrl) ?>">
                    <i class="fas fa-door-open sidebar__icon"></i>
                    <span class="sidebar__text">Phòng</span>
                </a>
            </li>
        </ul>
        <div class="sidebar__caption">Yêu cầu</div>
        <ul class="sidebar__menu">
            <li class="sidebar__item">
                <a href="<?= $roomAssignmentUrl ?>" class="sidebar__link <?= isActive($roomAssignmentUrl) ?>">
                    <i class="fas fa-clipboard-list sidebar__icon"></i>
                    <span class="sidebar__text">Đăng ký phòng</span>
                </a>
            </li>
        </ul>
        <div class="sidebar__divider"></div>
        <ul class="sidebar__menu">
            <li class="sidebar__item">
                <a href="<?= __WEB_ROOT__ ?>/auth/logout" class="sidebar__link sidebar__link--logout">
                    <i class="fas fa-sign-out-alt sidebar__icon"></i>
                    <span class="sidebar__text">Đăng xuất</span>
                </a>
            </li>
        </ul>
    </div>
</aside>