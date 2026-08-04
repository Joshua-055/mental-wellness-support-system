<?php
$displayName = escape((string) ($currentUser['full_name'] ?? 'Student'));
$firstName = escape(explode(' ', trim((string) ($currentUser['full_name'] ?? 'Student')))[0]);
$initial = escape(strtoupper(substr(trim((string) ($currentUser['full_name'] ?? 'S')), 0, 1)));
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
            <a class="side-link" href="<?= BASE_URL ?>/student/index.php?page=settings"><span class="nav-icon">⚙</span>Settings</a>
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
                <a class="profile-chip" href="<?= BASE_URL ?>/student/index.php?page=settings"><span class="avatar"><?= $initial ?></span><span
                        class="profile-name"><?= $displayName ?> <small>Student</small></span><span
                        class="chevron">⌄</span></a>
            </div>
        </header>

        <div class="support-request-page">
            <div class="support-header">
                <div>
                    <h1>Support Requests</h1>
                    <p>
                        Submit a request and get help from our support team.
                    </p>
                </div>
                <button class="create-request-btn" onclick="openRequestModal()">
                    + Create Request
                </button>
            </div>
            <div class="request-section">
                <h2>My Requests</h2>

                <!-- 👇 新加的这一层：用来做外面那个完整的大 Box -->
                <div class="request-box-container">

                    <!-- 👇 这一层专门用来限制高度和滚动 -->
                    <div class="request-list-wrapper">

                        <?php foreach ($requests as $request): ?>
                            <?php
                            // 处理标题和状态（保留你之前的代码）
                            $subject = 'Support Request';
                            if (strpos($request['description'], '【Subject】:') !== false) {
                                $lines = explode("\n", $request['description']);
                                $subject = trim(str_replace('【Subject】:', '', $lines[0]));
                            }
                            $statusClass = strtolower(str_replace(' ', '-', $request['STATUS']));
                            ?>

                            <!-- 这里是你截图里漂亮的卡片 -->
                            <div class="request-card">
                                <div class="request-info">
                                    <h3><?= htmlspecialchars($subject) ?></h3>
                                    <p><strong>Category:</strong>
                                        <?= htmlspecialchars($request['category_name'] ?? 'Uncategorized') ?></p>
                                    <p>Submitted: <?= date('d M Y', strtotime($request['created_at'])) ?></p>
                                </div>

                                <div class="request-status">
                                    <span class="status <?= $statusClass ?>">
                                        ● <?= htmlspecialchars($request['STATUS']) ?>
                                    </span>
                                    <a href="index.php?page=support_request_detail&id=<?= $request['id'] ?>">
                                        View Details →
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>

                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<!-- 弹窗遮罩和 iframe 容器 -->
<div class="modal-overlay" id="supportModalOverlay">
    <div class="modal-card-wrapper">
        <iframe id="supportModalIframe" src="" frameborder="0"></iframe>
    </div>
</div>
