<?php
$displayName = escape((string) ($currentUser['full_name'] ?? 'Student'));
$initial = escape(strtoupper(substr(trim((string) ($currentUser['full_name'] ?? 'S')), 0, 1)));

// 拆分 Subject 和 Details
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
                    <a href="index.php?page=support_request" class="detail-back-btn">
                        ← Back to Requests
                    </a>
                    <h1 class="detail-page-title">Request #<?= htmlspecialchars((string) $request['id']) ?></h1>
                </div>
            </div>

            <!-- 主卡片容器 -->
            <div class="request-detail-card">
                <!-- 顶部元数据栏 -->
                <div class="detail-meta-grid">
                    <div class="meta-item">
                        <span class="meta-label">Category</span>
                        <div class="meta-value"><?= htmlspecialchars($request['category_name'] ?? 'Uncategorized') ?></div>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">Status</span>
                        <div class="meta-value">
                            <span class="status-badge status-<?= $statusClass ?>">
                                ● <?= htmlspecialchars($request['STATUS']) ?>
                            </span>
                        </div>
                    </div>
                    <div class="meta-item text-right">
                        <span class="meta-label">Submitted On</span>
                        <div class="meta-value"><?= date('d M Y, h:i A', strtotime($request['created_at'])) ?></div>
                        <?php if (in_array($request['STATUS'], ['resolved', 'closed'], true)): ?>
                            <span class="meta-label" style="margin-top: 6px;">Completed On</span>
                            <div class="meta-value"><?= date('d M Y, h:i A', strtotime($request['updated_at'])) ?></div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- 核心内容区 -->
                <div class="detail-main-content">
                    <h3 class="detail-subject-heading"><?= htmlspecialchars($subject) ?></h3>

                    <div class="detail-description-box">
                        <?= htmlspecialchars($details) ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>