// 1. 点击表格里的 "Take Responsibility" 按钮时触发此函数
function assignAppointment(appointmentId) {
    // 这里的 page=appointment_staff_remark 正好对应你路由里的 case
    const iframeUrl = `index.php?page=appointment_staff_remark&appointment_id=${appointmentId}`;

    const iframe = document.getElementById('staffModalIframe');
    const modal = document.getElementById('staffActionModal');

    if (iframe && modal) {
        iframe.src = iframeUrl;
        modal.classList.add('active'); // 让弹窗显示
    } else {
        console.error("未找到 id='staffActionModal' 或 id='staffModalIframe' 的元素！");
    }
}

// 2. 关闭弹窗
function closeStaffModal() {
    const modal = document.getElementById('staffActionModal');
    const iframe = document.getElementById('staffModalIframe');
    if (modal) modal.classList.remove('active');
    if (iframe) iframe.src = '';
}

// 3. 子页面提交成功后的回调
function handleStaffRemarkSubmit() {
    closeStaffModal();
    window.location.reload();
}