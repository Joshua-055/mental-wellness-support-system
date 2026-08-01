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
            <a class="side-link active" href="index.php?page=appointments"><span
                    class="nav-icon">□</span>Appointments</a>
            <a class="side-link" href="index.php?page=support_request"><span class="nav-icon">◎</span>Support Requests</a>
        </nav>
        <div class="sidebar-bottom">
            <a class="side-link" href="#"><span class="nav-icon">⚙</span>Settings</a>
            <div class="sidebar-care">
                <span class="care-spark">✦</span>
                <p>Need to talk?</p>
                <a href="index.php?page=support_request">Get support <span>→</span></a>
            </div>
            <a class="side-link" href="<?= BASE_URL ?>/auth/logout.php"><span class="nav-icon">↗</span>Log out</a>
        </div>
    </aside>

    <section class="dashboard-content">
        <header class="dashboard-topbar glass-surface">
            <button class="mobile-menu" type="button" aria-label="Open navigation">☰</button>
            <div class="topbar-breadcrumb"><span>Student space</span><strong>Appointment</strong></div>
            <div class="topbar-actions">
                <button class="notification-button" type="button"
                    aria-label="You have 2 notifications"><span>♢</span><i></i></button>
                <a class="profile-chip" href="#"><span class="avatar"><?= $initial ?></span><span
                        class="profile-name"><?= $displayName ?> <small>Student</small></span><span
                        class="chevron">⌄</span></a>
            </div>
        </header>

        <!-- 中间预约容器 -->
        <div class="appointment-center-wrapper">
            <div class="appointment-picker-container glass-surface">
                <!-- 左侧：动态日历 -->
                <div class="picker-section calendar-section">
                    <h3 class="picker-title">选择日期</h3>
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
                    <h3 class="picker-title">选择时间</h3>

                    <div class="time-wheel-wrapper">
                        <div class="time-selection-overlay"></div>
                        <div class="time-wheel" id="timeWheel">
                            <!-- JS 会在这里自动渲染时间滚轮 -->
                        </div>
                    </div>

                    <button type="button" class="confirm-btn" id="confirmBtn">确认预约时间</button>
                </div>
            </div>
        </div>
    </section>
</main>

 <!-- Appointment Confirm Modal -->
    <div class="appointment-modal" id="appointmentModal">
        <div class="modal-overlay" onclick="closeModal()"></div>

        <div class="modal-content">
            <iframe
                id="modalIframe"
                src=""
                frameborder="0">
            </iframe>
        </div>
    </div>