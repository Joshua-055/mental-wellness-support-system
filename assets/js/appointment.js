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