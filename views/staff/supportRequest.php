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
        <nav class="side-nav">
            <a class="side-link" href="./"><span class="nav-icon">⌂</span>Dashboard</a>
            
                <!-- Support Requests 导航：一直显示数字，如果没有就显示 0 -->
            <a class="side-link active <?= ($page === 'supportRequest') ? 'active' : '' ?>" href="index.php?page=supportRequest">
                <span class="nav-icon">◎</span>Support Requests
                <b class="side-count"><?= $unassignedRequestsCount ?? 0 ?></b>
            </a>
            
            <!-- Appointments 导航 -->
            <a class="side-link <?= ($page === 'appointment') ? 'active' : '' ?>" href="index.php?page=appointment">
                <span class="nav-icon">□</span>Appointments
                <?php if (!empty($unassignedAppointmentsCount) && $unassignedAppointmentsCount > 0): ?>
                    <b class="side-count"><?= $unassignedAppointmentsCount ?></b>
                <?php endif; ?>
            </a>

            <a class="side-link" href="#"><span class="nav-icon">◉</span>Students</a>
            <a class="side-link" href="resources.php"><span class="nav-icon">▤</span>Resources</a>
            <a class="side-link" href="#"><span class="nav-icon">⌁</span>Reports</a>
        </nav>
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
        <!-- 支持请求列表区域 (Table 布局) -->
        <div class="appointments-history-section">
            <h3 class="section-title">All Support Requests</h3>

            <!-- 👇 必须带有这两个 class，表格样式和滚动条才会生效 -->
            <div class="table-responsive-wrapper glass-surface">
                <table class="modern-table">
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>Subject & Details</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th>Assigned Staff ID & Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($supportRequests)): ?>
                            <?php foreach ($supportRequests as $req): ?>
                                <?php
                                // 1. 解析 description 中的 Subject 和 Details
                                $desc = $req['description'] ?? '';
                                $subject = 'General Request';
                                $details = $desc;

                                if (strpos($desc, '【Subject】:') !== false) {
                                    $parts = explode('【Details】:', $desc);
                                    $subject = trim(str_replace('【Subject】:', '', $parts[0]));
                                    if (isset($parts[1])) {
                                        $details = trim($parts[1]);
                                    }
                                }

                                // 2. 状态处理
                                $status = strtolower(trim($req['STATUS'] ?? 'submitted'));

                                // 3. 优先级处理
                                $priority = strtolower(trim($req['priority'] ?? 'normal'));

                                // 4. 学生名字
                                $studentName = $req['student_name'] ?? 'Unknown Student';
                                ?>
                                <tr>
                                    <!-- 学生信息 -->
                                    <td class="td-student">
                                        <strong><?= htmlspecialchars($studentName) ?></strong>
                                    </td>

                                    <!-- Subject & Details -->
                                    <td class="td-subject">
                                        <div class="subject-title">🏷️ <?= htmlspecialchars($subject) ?></div>
                                        <div class="details-text"><?= nl2br(htmlspecialchars($details)) ?></div>
                                    </td>

                                    <!-- Priority -->
                                    <td class="td-priority">
                                        <span class="priority-badge priority-<?= $priority ?>">
                                            <?= htmlspecialchars($priority) ?>
                                        </span>
                                    </td>

                                    <!-- Status -->
                                    <td class="td-status">
                                        <span class="minimal-status status-dot-<?= $status ?>">
                                            <?= htmlspecialchars($status) ?>
                                        </span>
                                    </td>

                                    <!-- Assigned Staff ID & Action (Take Case 按钮) -->
                                    <td class="td-remark-action">
                                        <div class="action-wrapper">
                                            <?php if (!empty($req['assigned_staff_id'])): ?>
                                                <span
                                                    class="staff-id-text">#<?= htmlspecialchars($req['assigned_staff_id']) ?></span>
                                            <?php else: ?>
                                                <span class="text-muted" style="margin-bottom: 8px; display: block;">NULL</span>
                                            <?php endif; ?>

                                            <!-- 如果还没有分配 staff，显示 Take Case 按钮 -->
                                            <?php if (empty($req['assigned_staff_id'])): ?>
                                                <button type="button" class="btn-assign"
                                                    onclick="takeSupportCase(<?= $req['id'] ?>)">
                                                    Take Case
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="empty-state">📭 No support requests found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</main>
<script>
    function takeSupportCase(requestId) {
        const btn = event.target;
        btn.innerText = "Processing...";
        btn.disabled = true;

        const formData = new FormData();
        formData.append('request_id', requestId);

        // 提交到后端的接单路由
        fetch('index.php?page=support_request_take', {
            method: 'POST',
            body: formData
        })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    window.location.reload(); // 成功后无感刷新页面
                } else {
                    alert(data.message || "操作失败");
                    btn.innerText = "Take Case";
                    btn.disabled = false;
                }
            })
            .catch(err => {
                console.error(err);
                btn.innerText = "Take Case";
                btn.disabled = false;
            });
    }
</script>