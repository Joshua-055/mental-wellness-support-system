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
            <a class="side-link" href="support-request.php"><span class="nav-icon">◎</span>Support Requests</a>
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

<!-- 注意：script 里面放的是 JS 逻辑，不是 HTML！ -->
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // --- 1. 动态日历逻辑 ---
        let currentDate = new Date();
        let selectedDateStr = '';

        const calendarGrid = document.getElementById('calendarGrid');
        const monthYearTitle = document.getElementById('monthYearTitle');
        const prevBtn = document.getElementById('prevMonth');
        const nextBtn = document.getElementById('nextMonth');

        function renderCalendar(date) {
            calendarGrid.innerHTML = '';

            // 渲染星期头
            const weekDays = ['日', '一', '二', '三', '四', '五', '六'];
            weekDays.forEach(day => {
                const headDiv = document.createElement('div');
                headDiv.className = 'cal-day-head';
                headDiv.textContent = day;
                calendarGrid.appendChild(headDiv);
            });

            const year = date.getFullYear();
            const month = date.getMonth();

            monthYearTitle.textContent = `${year}年 ${month + 1}月`;

            const firstDayOfWeek = new Date(year, month, 1).getDay();
            const daysInMonth = new Date(year, month + 1, 0).getDate();
            const daysInPrevMonth = new Date(year, month, 0).getDate();

            // 填充上个月末尾天数
            for (let i = firstDayOfWeek - 1; i >= 0; i--) {
                const span = document.createElement('span');
                span.className = 'cal-date muted';
                span.textContent = daysInPrevMonth - i;
                calendarGrid.appendChild(span);
            }

            // 渲染当月天数
            for (let day = 1; day <= daysInMonth; day++) {
                const span = document.createElement('span');
                span.className = 'cal-date';
                span.textContent = day;

                const formattedDate = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;

                if (!selectedDateStr && day === new Date().getDate() && month === new Date().getMonth()) {
                    span.classList.add('active');
                    selectedDateStr = formattedDate;
                } else if (selectedDateStr === formattedDate) {
                    span.classList.add('active');
                }

                span.addEventListener('click', () => {
                    document.querySelectorAll('.cal-date').forEach(el => el.classList.remove('active'));
                    span.classList.add('active');
                    selectedDateStr = formattedDate;
                });

                calendarGrid.appendChild(span);
            }
        }

        prevBtn.addEventListener('click', () => {
            currentDate.setMonth(currentDate.getMonth() - 1);
            renderCalendar(currentDate);
        });

        nextBtn.addEventListener('click', () => {
            currentDate.setMonth(currentDate.getMonth() + 1);
            renderCalendar(currentDate);
        });

        renderCalendar(currentDate);

        // --- 2. 动态时间滚轮逻辑 ---
        const timeWheel = document.getElementById('timeWheel');
        let selectedTimeStr = '12:00 PM';

        function generateTimeSlots(startHour, endHour, intervalMinutes = 60) {
            const slots = [];
            let current = new Date();
            current.setHours(startHour, 0, 0, 0);
            const end = new Date();
            end.setHours(endHour, 0, 0, 0);

            while (current <= end) {
                let hours = current.getHours();
                let minutes = current.getMinutes();
                let ampm = hours >= 12 ? 'PM' : 'AM';

                hours = hours % 12;
                hours = hours ? hours : 12;

                let strTime = `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')} ${ampm}`;
                slots.push(strTime);

                current.setMinutes(current.getMinutes() + intervalMinutes);
            }
            return slots;
        }

        function renderTimeWheel() {
            timeWheel.innerHTML = '';
            const timeList = generateTimeSlots(0, 23, 60);

            timeList.forEach((time, index) => {
                const div = document.createElement('div');
                div.className = 'time-item';
                div.textContent = time;

                if (time === '12:00 PM') {
                    div.classList.add('active');
                    selectedTimeStr = time;

                    setTimeout(() => {
                        div.scrollIntoView({
                            behavior: 'auto',
                            block: 'center'
                        });
                    }, 0);
                } else {
                    div.classList.add('muted');
                }

                div.addEventListener('click', () => {
                    document.querySelectorAll('.time-item').forEach(el => {
                        el.classList.remove('active');
                        el.classList.add('muted');
                    });
                    div.classList.add('active');
                    div.classList.remove('muted');
                    selectedTimeStr = time;
                    div.scrollIntoView({ behavior: 'smooth', block: 'center' });
                });

                timeWheel.appendChild(div);
            });
        }

        renderTimeWheel();

        // 当点击“确认预约时间”按钮时，将选中的日期和时间通过 URL 传递给 appointment_confirm.php
        document.getElementById('confirmBtn').addEventListener('click', () => {
            // 检查是否已选择日期和时间
            if (!selectedDateStr || !selectedTimeStr) {
                alert('请先选择日期和时间！');
                return;
            }

            // 1. 拼接带参数的 URL 指向 appointment_confirm.php
           const iframeUrl = `index.php?page=appointment_confirm&date=${encodeURIComponent(selectedDateStr)}&time=${encodeURIComponent(selectedTimeStr)}`;
            // 2. 将 URL 传给 iframe 并显示弹窗
            document.getElementById('modalIframe').src = iframeUrl;
            document.getElementById('appointmentModal').classList.add('active');
        });

        window.closeModal = function () {
            document.getElementById('appointmentModal')
                .classList.remove('active');

            document.getElementById('modalIframe').src = '';
        };


        window.handleFinalSubmit = function (date, time) {

            alert(
                "预约成功！\n\n" +
                "日期：" + date +
                "\n时间：" + time
            );

            closeModal();
        };
    });
</script>