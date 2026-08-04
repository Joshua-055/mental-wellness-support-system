function toggleReasonBox() {
    const selectBox = document.getElementById('supportRequestId');
    const reasonGroup = document.getElementById('reasonGroup');
    const reasonInput = document.getElementById('reason');
    const subjectError = document.getElementById('subjectError');

    // 每次更改选项时，清除红框报错样式
    selectBox.style.borderColor = '#e2e8f0';
    if (subjectError) subjectError.style.display = 'none';

    if (selectBox.value !== "") {
        reasonGroup.style.display = 'block';
    } else {
        reasonGroup.style.display = 'none';
        reasonInput.value = "";
    }
}

// 转换时间格式 (12小时制转24小时制)
function convertTo24Hour(time12h) {
    const [time, modifier] = time12h.split(' ');
    let [hours, minutes] = time.split(':');

    if (hours === '12') {
        hours = '00';
    }

    if (modifier === 'PM') {
        hours = String(parseInt(hours, 10) + 12);
    }

    return `${hours.padStart(2, '0')}:${minutes}:00`;
}

function submitToParent() {
    // 1. 获取并转换日期时间
    const appointmentData = document.getElementById('appointmentData');
    const date = appointmentData.dataset.date;
    const displayTime = appointmentData.dataset.time;
    const time = convertTo24Hour(displayTime); // 转换为 24 小时制存入数据库

    // 2. 获取用户输入的值
    const selectBox = document.getElementById('supportRequestId');
    const supportRequestId = selectBox.value;
    const reasonInput = document.getElementById('reason');
    const reason = reasonInput.value;

    // 3. 获取报错提示元素
    const subjectError = document.getElementById('subjectError');
    const reasonError = document.getElementById('reasonError');

    let hasError = false;

    // 重置所有的报错红框和提示
    selectBox.style.borderColor = '#e2e8f0';
    reasonInput.style.borderColor = '#e2e8f0';
    if (subjectError) subjectError.style.display = 'none';
    if (reasonError) reasonError.style.display = 'none';

    // 校验 Subject (没有选直接报红)
    if (supportRequestId === "") {
        if (subjectError) subjectError.style.display = 'block';
        selectBox.style.borderColor = '#ff3b30';
        hasError = true;
    }

    // 校验 Reason (哪怕 Subject 选了，Reason 没填也报红)
    if (supportRequestId !== "" && reason.trim() === "") {
        if (reasonError) reasonError.style.display = 'block';
        reasonInput.style.borderColor = '#ff3b30';
        hasError = true;
    }

    // 如果存在任何没填的项目，阻止提交
    if (hasError) {
        return;
    }

    // ✅ 验证通过：让按钮变成 "提交中..." 并禁用
    document.querySelector('.btn-confirm').innerText = "提交中...";
    document.querySelector('.btn-confirm').disabled = true;

    // 调用主页面的方法，把处理好的 24 小时制时间传给后端
    parent.handleFinalSubmit(date, time, supportRequestId, reason);
}