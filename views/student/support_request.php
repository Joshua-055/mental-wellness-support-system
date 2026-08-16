<?php
$displayName = escape((string) ($currentUser['full_name'] ?? 'Student'));
$firstName = escape(explode(' ', trim((string) ($currentUser['full_name'] ?? 'Student')))[0]);
$initial = escape(strtoupper(substr(trim((string) ($currentUser['full_name'] ?? 'S')), 0, 1)));
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
                                    <p>Created: <?= date('d M Y, h:i A', strtotime($request['created_at'])) ?></p>
                                    <?php if (in_array($request['STATUS'], ['resolved', 'closed'], true)): ?>
                                        <p>Completed: <?= date('d M Y, h:i A', strtotime($request['updated_at'])) ?></p>
                                    <?php endif; ?>
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
