<?php
$displayName = escape((string) ($currentUser['full_name'] ?? 'Staff'));
$firstName = escape(explode(' ', trim((string) ($currentUser['full_name'] ?? 'Staff')))[0]);
$initial = escape(strtoupper(substr(trim((string) ($currentUser['full_name'] ?? 'S')), 0, 1)));
?>
<main class="staff-app">
    <div class="staff-glow staff-glow-blue" aria-hidden="true"></div>
    <div class="staff-glow staff-glow-green" aria-hidden="true"></div>
    <?php $navigationRole = 'staff'; $activePage = 'supportRequest'; require APP_ROOT . '/views/layouts/app-sidebar.php'; ?>
    <section class="staff-content">
        <?php $topbarTitle = 'Support Requests'; require APP_ROOT . '/views/layouts/dashboard-topbar.php'; ?>
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
                                        <small class="request-time">Created <?= htmlspecialchars(date('d M Y, h:i A', strtotime($req['created_at']))) ?></small>
                                        <?php if (in_array($status, ['resolved', 'closed'], true)): ?><small class="request-time completed-time">Completed <?= htmlspecialchars(date('d M Y, h:i A', strtotime($req['updated_at']))) ?></small><?php endif; ?>
                                    </td>

                                    <!-- Assigned Staff ID & Action (Take Case 按钮) -->
                                    <td class="td-remark-action">
                                        <div class="action-wrapper">
                                            <?php if (!empty($req['assigned_staff_id'])): ?>
                                                <span class="staff-id-text"><?= htmlspecialchars($req['assigned_staff_name'] ?? 'Assigned staff') ?></span>
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
                                            <?php if ((int) ($req['assigned_staff_id'] ?? 0) === (int) $currentUser['id'] && !in_array($status, ['resolved', 'closed'], true)): ?>
                                                <button type="button" class="btn-complete" onclick="completeSupportCase(event, <?= (int) $req['id'] ?>)">Complete</button>
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

    function completeSupportCase(event, requestId) {
        const btn = event.currentTarget;
        btn.innerText = 'Completing...';
        btn.disabled = true;
        const formData = new FormData();
        formData.append('request_id', requestId);
        formData.append('csrf_token', '<?= escape(csrfToken()) ?>');
        fetch('index.php?page=support_request_complete', { method: 'POST', body: formData })
            .then(async response => ({ ok: response.ok, data: await response.json() }))
            .then(({ ok, data }) => {
                if (ok && data.success) window.location.reload();
                else throw new Error(data.message || 'Unable to complete this request.');
            })
            .catch(error => {
                alert(error.message);
                btn.innerText = 'Complete';
                btn.disabled = false;
            });
    }
</script>
