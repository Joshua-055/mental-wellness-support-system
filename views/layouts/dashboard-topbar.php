<?php
declare(strict_types=1);

$navigationRole = (string) ($navigationRole ?? ($currentUser['role'] ?? 'student'));
$isStaffNavigation = in_array($navigationRole, ['staff', 'admin'], true);
$topbarTitle = (string) ($topbarTitle ?? 'Dashboard');
$displayName = escape((string) ($currentUser['full_name'] ?? ($isStaffNavigation ? 'Staff' : 'Student')));
$initial = escape(strtoupper(substr(trim((string) ($currentUser['full_name'] ?? 'U')), 0, 1)));
$settingsUrl = BASE_URL . ($isStaffNavigation ? '/staff/index.php?page=settings' : '/student/index.php?page=settings');
$spaceLabel = $isStaffNavigation ? 'Staff' : 'Student';
$profileRole = $isStaffNavigation ? 'Wellness counsellor' : 'Student';
?>
<header class="dashboard-topbar glass-surface">
    <button class="mobile-menu" type="button" aria-label="Open navigation">☰</button>
    <div class="topbar-breadcrumb"><span><?= $spaceLabel ?> space</span><strong><?= escape($topbarTitle) ?></strong></div>
    <div class="topbar-actions">
        <button class="notification-button" type="button" aria-label="Notifications"><span>♢</span><i></i></button>
        <a class="profile-chip" href="<?= $settingsUrl ?>">
            <span class="avatar<?= $isStaffNavigation ? ' staff-avatar' : '' ?>"><?= $initial ?></span>
            <span class="profile-name"><?= $displayName ?><small><?= $profileRole ?></small></span>
            <span class="chevron" aria-hidden="true">⌄</span>
        </a>
    </div>
</header>
