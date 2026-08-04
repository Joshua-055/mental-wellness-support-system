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

    <?php $activePage = 'support_request'; require APP_ROOT . '/views/layouts/app-sidebar.php'; ?>

    <section class="dashboard-content">
        <?php $topbarTitle = 'Support Requests'; require APP_ROOT . '/views/layouts/dashboard-topbar.php'; ?>

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
