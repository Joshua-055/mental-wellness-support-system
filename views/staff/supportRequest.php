<?php
$displayName = escape((string) ($currentUser['full_name'] ?? 'Staff'));
$firstName = escape(explode(' ', trim((string) ($currentUser['full_name'] ?? 'Staff')))[0]);
$initial = escape(strtoupper(substr(trim((string) ($currentUser['full_name'] ?? 'S')), 0, 1)));
?>
<main class="staff-app">
    <div class="staff-glow staff-glow-blue" aria-hidden="true"></div>
    <div class="staff-glow staff-glow-green" aria-hidden="true"></div>
    <aside class="staff-sidebar glass-surface" aria-label="Staff navigation">
        <a class="brand dashboard-brand" href="../index.php"><span class="brand-mark"
                aria-hidden="true"><span></span><span></span><span></span></span><span>mindful</span></a>
        <p class="staff-space-label">STAFF SPACE</p>
        <nav class="side-nav"><a class="side-link" href="./"><span class="nav-icon">⌂</span>Dashboard</a><a
                class="side-link active" href="index.php?page=supportRequest"><span class="nav-icon">◎</span>Support Requests
                <b class="side-count">12</b></a><a class="side-link" href="index.php?page=appointment"><span
                    class="nav-icon">□</span>Appointments</a><a class="side-link" href="#"><span
                    class="nav-icon">◉</span>Students</a><a class="side-link" href="resources.php"><span
                    class="nav-icon">▤</span>Resources</a><a class="side-link" href="#"><span
                    class="nav-icon">⌁</span>Reports</a></nav>
        <div class="sidebar-bottom"><a class="side-link" href="#"><span class="nav-icon">⚙</span>Settings</a><a
                class="side-link staff-logout" href="<?= BASE_URL ?>/auth/logout.php"><span class="nav-icon">↗</span>Log
                out</a></div>
    </aside>
    <section class="staff-content">
        <header class="dashboard-topbar glass-surface"><button class="mobile-menu" type="button"
                aria-label="Open navigation">☰</button>
            <div class="topbar-breadcrumb"><span>Staff space</span><strong>Support request</strong></div>
            <div class="topbar-actions"><button class="notification-button" type="button"
                    aria-label="You have 4 notifications"><span>♢</span><i></i></button><a class="profile-chip"
                    href="#"><span class="avatar staff-avatar"><?= $initial ?></span><span
                        class="profile-name"><?= $displayName ?> <small>Wellness counsellor</small></span><span
                        class="chevron">⌄</span></a></div>
        </header>
    </section>
</main>