<?php
$displayName = escape((string) ($currentUser['full_name'] ?? 'Student'));
$firstName = escape(explode(' ', trim((string) ($currentUser['full_name'] ?? 'Student')))[0]);
$initial = escape(strtoupper(substr(trim((string) ($currentUser['full_name'] ?? 'S')), 0, 1)));
?>
<main class="student-app">
    <div class="dashboard-glow glow-one" aria-hidden="true"></div>
    <div class="dashboard-glow glow-two" aria-hidden="true"></div>

    <?php $activePage = 'appointments'; require APP_ROOT . '/views/layouts/app-sidebar.php'; ?>

    <section class="dashboard-content">
        <?php $topbarTitle = 'Appointment'; require APP_ROOT . '/views/layouts/dashboard-topbar.php'; ?>

        <!-- 中间预约容器 -->
        <div class="appointment-center-wrapper">
            <div class="appointment-picker-container glass-surface">
                <!-- 左侧：动态日历 -->
                <div class="picker-section calendar-section">
                    <h3 class="picker-title">Select Date</h3>
                    <div class="calendar-header">
                        <button type="button" class="cal-nav" id="prevMonth">&lt;</button>
                        <span class="current-month-year" id="monthYearTitle"></span>
                        <button type="button" class="cal-nav" id="nextMonth">&gt;</button>
                    </div>

                    <div class="calendar-grid" id="calendarGrid">
                        <!-- JS 会在这里自动渲染日历 -->
                    </div>
                </div>

                <!-- 右侧：动态滚轮时间选择器 -->
                <div class="picker-section time-section">
                    <h3 class="picker-title">Select Time</h3>

                    <div class="time-wheel-wrapper">
                        <div class="time-selection-overlay"></div>
                        <div class="time-wheel" id="timeWheel">
                            <!-- JS 会在这里自动渲染时间滚轮 -->
                        </div>
                    </div>

                    <button type="button" class="confirm-btn" id="confirmBtn">Confirm</button>
                </div>
            </div>
        </div>

        <!-- 预约记录展示区 (表格布局) -->
            <div class="appointments-history-section">
                <h3 class="section-title">My appointment record</h3>

                <div class="table-responsive-wrapper glass-surface">
                    <table class="modern-table">
                        <thead>
                            <tr>
                                <th>Subject</th>
                                <th>Date & Time</th>
                                <th>Reason</th>
                                <th>Staff Remark</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($appointments)): ?>
                                <?php foreach ($appointments as $appointment): ?>
                                    <?php
                                    // 1. 提取 Subject
                                    $subject = 'General Appointment';
                                    $desc = $appointment['support_description'] ?? '';
                                    if (strpos($desc, '【Subject】:') !== false) {
                                        $parts = explode('【Details】:', $desc);
                                        $subject = trim(str_replace('【Subject】:', '', $parts[0]));
                                    }

                                    // 2. 状态处理
                                    $status = strtolower(trim($appointment['STATUS'] ?? 'pending'));
                                    ?>
                                    <tr>
                                        <td class="td-subject">
                                            <strong><?= htmlspecialchars($subject) ?></strong>
                                        </td>
                                        <td class="td-datetime">
                                            <div class="date-text">📅 <?= htmlspecialchars($appointment['appointment_date']) ?>
                                            </div>
                                            <div class="time-text">⏰ <?= htmlspecialchars($appointment['appointment_time']) ?>
                                            </div>
                                        </td>
                                        <td class="td-reason">
                                            <?= nl2br(htmlspecialchars($appointment['reason'])) ?>
                                        </td>
                                        <td class="td-remark">
                                            <?php if (!empty($appointment['staff_remark'])): ?>
                                                <span
                                                    class="remark-text"><?= nl2br(htmlspecialchars($appointment['staff_remark'])) ?></span>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="td-status">
                                            <span class="minimal-status status-dot-<?= $status ?>">
                                                <?= htmlspecialchars($status) ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="empty-state">📭 No appointments found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
    </section>
</main>

<!-- Appointment Confirm Modal -->
<div class="appointment-modal" id="appointmentModal">
    <div class="modal-overlay" onclick="closeModal()"></div>

    <div class="modal-content">
        <iframe id="modalIframe" src="" frameborder="0">
        </iframe>
    </div>
</div>
