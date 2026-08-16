
// 对应你按钮上的 onclick="openRequestModal()"
function openRequestModal() {
    // 将 iframe 的 src 指向你的小窗口文件（确保文件名正确，比如 support_request_modal.php）
    document.getElementById('supportModalIframe').src = 'index.php?page=create_support_request';
    // 添加 active 类，显示弹窗
    document.getElementById('supportModalOverlay').classList.add('active');
}

// 供小窗口内的 Cancel 按钮调用的关闭函数
function closeModal() {
    document.getElementById('supportModalOverlay').classList.remove('active');
    document.getElementById('supportModalIframe').src = ''; // 清空 src，防止下次打开时闪烁旧内容
}
