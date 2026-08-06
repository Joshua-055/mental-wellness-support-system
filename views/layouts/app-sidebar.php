<?php
declare(strict_types=1);

$navigationRole = (string) ($navigationRole ?? ($currentUser['role'] ?? 'student'));
$isStaffNavigation = in_array($navigationRole, ['staff', 'admin'], true);
$activePage = (string) ($activePage ?? 'dashboard');
$sidebarClass = (string) ($sidebarClass ?? ($isStaffNavigation ? 'staff-sidebar' : 'student-sidebar'));
$sidebarLabel = $isStaffNavigation ? 'Staff navigation' : 'Student navigation';
$homeUrl = BASE_URL . ($isStaffNavigation ? '/staff/' : '/student/');

$studentItems = [
    ['key' => 'dashboard', 'icon' => '⌂', 'label' => 'Dashboard', 'url' => BASE_URL . '/student/'],
    ['key' => 'checkin', 'icon' => '♡', 'label' => 'Wellness Check-In', 'url' => BASE_URL . '/student/index.php?page=checkin'],
    ['key' => 'checkin_history', 'icon' => '◷', 'label' => 'History', 'url' => BASE_URL . '/student/index.php?page=checkin_history'],
    ['key' => 'resources', 'icon' => '▤', 'label' => 'Resources', 'url' => BASE_URL . '/student/index.php?page=resources'],
    ['key' => 'appointments', 'icon' => '□', 'label' => 'Appointments', 'url' => BASE_URL . '/student/index.php?page=appointments'],
    ['key' => 'support_request', 'icon' => '◎', 'label' => 'Support Requests', 'url' => BASE_URL . '/student/index.php?page=support_request'],
];

$staffItems = [
    ['key' => 'dashboard', 'icon' => '⌂', 'label' => 'Dashboard', 'url' => BASE_URL . '/staff/'],
    ['key' => 'supportRequest', 'icon' => '◎', 'label' => 'Support Requests', 'url' => BASE_URL . '/staff/index.php?page=supportRequest', 'badge' => $unassignedRequestsCount ?? null],
    ['key' => 'appointment', 'icon' => '□', 'label' => 'Appointments', 'url' => BASE_URL . '/staff/index.php?page=appointment', 'badge' => $unassignedAppointmentsCount ?? null],
    ['key' => 'students', 'icon' => '◉', 'label' => 'Students', 'url' => BASE_URL . '/staff/index.php?page=students'],
    ['key' => 'resources', 'icon' => '▤', 'label' => 'Resources', 'url' => BASE_URL . '/staff/index.php?page=resources'],
    ['key' => 'reports', 'icon' => '⌁', 'label' => 'Reports', 'url' => null],
];

$navigationItems = $isStaffNavigation ? $staffItems : $studentItems;
$settingsUrl = BASE_URL . ($isStaffNavigation ? '/staff/index.php?page=settings' : '/student/index.php?page=settings');
?>
<aside class="<?= escape($sidebarClass) ?> glass-surface" aria-label="<?= escape($sidebarLabel) ?>">
    <a class="brand dashboard-brand" href="<?= $homeUrl ?>">
        <span class="brand-mark" aria-hidden="true"><span></span><span></span><span></span></span>
        <span>mindful</span>
    </a>

    <?php if ($isStaffNavigation): ?>
        <p class="staff-space-label">STAFF SPACE</p>
    <?php endif; ?>

    <nav class="side-nav">
        <?php foreach ($navigationItems as $item): ?>
            <?php
            $isActive = $activePage === $item['key'];
            $isDisabled = $item['url'] === null;
            $classes = 'side-link' . ($isActive ? ' active' : '') . ($isDisabled ? ' is-disabled' : '');
            ?>
            <a
                class="<?= $classes ?>"
                href="<?= $isDisabled ? '#' : $item['url'] ?>"
                <?= $isActive ? 'aria-current="page"' : '' ?>
                <?= $isDisabled ? 'aria-disabled="true" tabindex="-1" title="Coming soon"' : '' ?>
            >
                <span class="nav-icon" aria-hidden="true"><?= $item['icon'] ?></span>
                <?= escape($item['label']) ?>
                <?php if (isset($item['badge']) && (int) $item['badge'] >= 0): ?>
                    <b class="side-count"><?= (int) $item['badge'] ?></b>
                <?php endif; ?>
            </a>
        <?php endforeach; ?>
    </nav>

    <div class="sidebar-bottom">
        <a class="side-link<?= $activePage === 'settings' ? ' active' : '' ?>" href="<?= $settingsUrl ?>"<?= $activePage === 'settings' ? ' aria-current="page"' : '' ?>>
            <span class="nav-icon" aria-hidden="true">⚙</span>Settings
        </a>
        <a class="side-link<?= $isStaffNavigation ? ' staff-logout' : '' ?>" href="<?= BASE_URL ?>/auth/logout.php">
            <span class="nav-icon" aria-hidden="true">↗</span>Log out
        </a>
    </div>
</aside>
