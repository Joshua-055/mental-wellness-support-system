<?php
$displayName = escape((string) ($currentUser['full_name'] ?? 'Staff'));
$firstName = escape(explode(' ', trim((string) ($currentUser['full_name'] ?? 'Staff')))[0]);
$initial = escape(strtoupper(substr(trim((string) ($currentUser['full_name'] ?? 'S')), 0, 1)));
?>
<main class="staff-app">
    <div class="staff-glow staff-glow-blue" aria-hidden="true"></div>
    <div class="staff-glow staff-glow-green" aria-hidden="true"></div>
    <?php $navigationRole = 'staff'; $activePage = 'appointment'; require APP_ROOT . '/views/layouts/app-sidebar.php'; ?>
    <section class="staff-content">
        <?php $topbarTitle = 'Appointment'; require APP_ROOT . '/views/layouts/dashboard-topbar.php'; ?>
        <!-- 员工视角的全部预约记录 (Table 布局) -->
        <div class="appointments-history-section">
            <h3 class="section-title">All Student Appointments</h3>
            
            <div class="table-responsive-wrapper glass-surface">
                <table class="modern-table">
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>Subject</th>
                            <th>Date & Time</th>
                            <th>Reason</th>
                            <th>Staff ID</th> <!-- 👇 新增这列 -->
                            <th>Status</th>
                            <th>Staff Remark & Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            // 🚀 获取当前的系统时间戳
                            $currentTimestamp = time(); 
                        ?>
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
                                    
                                    // 3. 学生名字
                                    $studentName = $appointment['student_name'] ?? 'Unknown Student';
                                    
                                    // 4. ⏳ 判断是否已经过期
                                    $appointmentTimestamp = strtotime($appointment['appointment_date'] . ' ' . $appointment['appointment_time']);
                                    $isPast = $appointmentTimestamp < $currentTimestamp;
                                    
                                    // 如果过期，给这一行加上一个特殊的 class
                                    $rowClass = $isPast ? 'is-past-appointment' : '';
                                ?>
                                <!-- 👇 tr 动态输出过期 class -->
                                <tr class="<?= $rowClass ?>">
                                    <!-- 学生信息 -->
                                    <td class="td-student">
                                        <strong><?= htmlspecialchars($studentName) ?></strong>
                                    </td>
                                    
                                    <!-- Subject -->
                                    <td class="td-subject">
                                        <?= htmlspecialchars($subject) ?>
                                    </td>
                                    
                                    <!-- 日期和时间 -->
                                    <td class="td-datetime">
                                        <div class="date-text">📅 <?= htmlspecialchars($appointment['appointment_date']) ?></div>
                                        <div class="time-text">⏰ <?= htmlspecialchars($appointment['appointment_time']) ?></div>
                                    </td>
                                    
                                    <!-- 预约原因 -->
                                    <td class="td-reason">
                                        <?= nl2br(htmlspecialchars($appointment['reason'])) ?>
                                    </td>

                                    <!-- Staff ID -->
                                    <td class="td-staff-id">
                                        <?php if (!empty($appointment['staff_id'])): ?>
                                            <span style="font-weight: 600;"><?= htmlspecialchars($appointment['staff_name'] ?? 'Assigned staff') ?></span>
                                        <?php else: ?>
                                            <span class="text-muted" style="font-style: italic;">NULL</span>
                                        <?php endif; ?>
                                    </td>
                                    
                                    <!-- 状态 -->
                                    <td class="td-status">
                                        <span class="minimal-status status-dot-<?= $status ?>">
                                            <?= htmlspecialchars($status) ?>
                                        </span>
                                    </td>
                                    
                                    <!-- Remark & Action -->
                                    <td class="td-remark-action">
                                        <div class="action-wrapper">
                                            <?php if (!empty($appointment['staff_remark'])): ?>
                                                <span class="remark-text"><?= nl2br(htmlspecialchars($appointment['staff_remark'])) ?></span>
                                            <?php else: ?>
                                                <span class="text-muted" style="margin-bottom: 8px; display: block;">No remark</span>
                                            <?php endif; ?>
                                            
                                            <!-- 如果还没有分配 staff，显示按钮 -->
                                            <?php if (empty($appointment['staff_id'])): ?>
                                                <button type="button" class="btn-assign" <?= $isPast ? 'disabled' : '' ?> onclick="assignAppointment(<?= $appointment['id'] ?>)">
                                                    Take Responsibility
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="empty-state">📭 No appointments found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</main>

<!-- Staff Action Modal (弹窗组件容器) -->
<div class="appointment-modal" id="staffActionModal">
    <div class="modal-overlay" onclick="closeStaffModal()"></div>
    <div class="modal-content">
        <iframe id="staffModalIframe" src="" frameborder="0"></iframe>
    </div>
</div>

