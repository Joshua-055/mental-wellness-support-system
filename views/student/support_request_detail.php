<?php
$displayName = escape((string) ($currentUser['full_name'] ?? 'Student'));
$initial = escape(strtoupper(substr(trim((string) ($currentUser['full_name'] ?? 'S')), 0, 1)));

// 因为存进数据库时，我们把 Subject 和 Details 拼在一起了，现在要把它们拆开显示
$fullDescription = $request['description'] ?? '';
$subject = 'Support Request';
$details = $fullDescription;

if (strpos($fullDescription, '【Subject】:') !== false && strpos($fullDescription, '【Details】:') !== false) {
    $parts = explode('【Details】:', $fullDescription);
    $subjectPart = str_replace('【Subject】:', '', $parts[0]);
    $subject = trim($subjectPart);
    $details = trim($parts[1] ?? '');
}

$statusClass = strtolower(str_replace(' ', '-', $request['STATUS']));
?>

<main class="student-app">
    <div class="dashboard-glow glow-one" aria-hidden="true"></div>
    <div class="dashboard-glow glow-two" aria-hidden="true"></div>

    <aside class="student-sidebar glass-surface" aria-label="Student navigation">
        <a class="brand dashboard-brand" href="../index.php"><span class="brand-mark"
                aria-hidden="true"><span></span><span></span><span></span></span><span>mindful</span></a>
        <nav class="side-nav">
            <a class="side-link" href="index.php?page=dashboard"><span class="nav-icon">⌂</span>Dashboard</a>
            <a class="side-link" href="checkin.php"><span class="nav-icon">♡</span>Wellness Check-In</a>
            <a class="side-link" href="checkin-history.php"><span class="nav-icon">◷</span>History</a>
            <a class="side-link" href="resources.php"><span class="nav-icon">▤</span>Resources</a>
            <a class="side-link" href="index.php?page=appointments"><span class="nav-icon">□</span>Appointments</a>
            <a class="side-link active" href="index.php?page=support_request"><span class="nav-icon">◎</span>Support
                Requests</a>
        </nav>
        <div class="sidebar-bottom">
            <a class="side-link" href="#"><span class="nav-icon">⚙</span>Settings</a>
            <div class="sidebar-care">
                <span class="care-spark">✦</span>
                <p>Need to talk?</p>
                <a href="support-request.php">Get support <span>→</span></a>
            </div>
            <a class="side-link" href="<?= BASE_URL ?>/auth/logout.php"><span class="nav-icon">↗</span>Log out</a>
        </div>
    </aside>

    <section class="dashboard-content">
        <header class="dashboard-topbar glass-surface">
            <button class="mobile-menu" type="button" aria-label="Open navigation">☰</button>
            <div class="topbar-breadcrumb"><span>Student space</span><strong>Support Requests</strong></div>
            <div class="topbar-actions">
                <button class="notification-button" type="button"
                    aria-label="You have 2 notifications"><span>♢</span><i></i></button>
                <a class="profile-chip" href="#"><span class="avatar"><?= $initial ?></span><span
                        class="profile-name"><?= $displayName ?> <small>Student</small></span><span
                        class="chevron">⌄</span></a>
            </div>
        </header>

        <div class="support-request-page">
            <div class="support-header">
                <div>
                    <!-- 返回按钮 -->
                    <a href="index.php?page=support_request"
                        style="color: #666; text-decoration: none; font-size: 14px;">
                        ← Back to Requests
                    </a>
                    <h1 style="margin-top: 10px;">Request #<?= htmlspecialchars((string) $request['id']) ?></h1>
                </div>
            </div>

            <div class="request-section"
                style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
                <!-- 状态与分类 -->
                <div
                    style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #eee; padding-bottom: 15px; margin-bottom: 15px;">
                    <div>
                        <span style="font-size: 13px; color: #888;">Category</span><br>
                        <strong><?= htmlspecialchars($request['category_name'] ?? 'Uncategorized') ?></strong>
                    </div>
                    <div>
                        <span style="font-size: 13px; color: #888;">Status</span><br>
                        <span class="status <?= $statusClass ?>">
                            ● <?= htmlspecialchars($request['STATUS']) ?>
                        </span>
                    </div>
                    <div style="text-align: right;">
                        <span style="font-size: 13px; color: #888;">Submitted On</span><br>
                        <strong><?= date('d M Y, h:i A', strtotime($request['created_at'])) ?></strong>
                    </div>
                </div>

                <!-- 标题与详情 -->
                <div style="margin-top: 20px;">
                    <h3 style="color: #333; margin-bottom: 15px; font-size: 20px;">
                        <?= htmlspecialchars($subject) ?>
                    </h3>

                    <div
                        style="background: #f9fafb; padding: 20px; border-radius: 8px; font-size: 15px; color: #444; line-height: 1.6; white-space: pre-wrap;">
                        <?= htmlspecialchars($details) ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>