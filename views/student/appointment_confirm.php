<?php
// 接收主页面通过 URL 传过来的日期和时间（暂不存数据库）
$date = isset($_GET['date']) ? htmlspecialchars($_GET['date']) : '未选择日期';
$time = isset($_GET['time']) ? htmlspecialchars($_GET['time']) : '未选择时间';
?>
<!DOCTYPE html>
<html lang="zh-CN">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>确认预约信息</title>
    <style>
       
    </style>
</head>

<body>

    <div class="modal-card">
        <h3 class="modal-title">核对预约时间</h3>
        <p class="modal-desc">请确认以下选择是否正确：</p>

        <!-- 展示通过 URL 拿到的日期和时间 -->
        <div class="summary-box">
            <div class="summary-item">
                <span class="label">📅 预约日期</span>
                <span class="value"><?= $date ?></span>
            </div>
            <div class="summary-item">
                <span class="label">⏰ 预约时间</span>
                <span class="value"><?= $time ?></span>
            </div>
        </div>

        <!-- 两个按钮 -->
        <div class="btn-group">
            <!-- 点取消：关闭小窗口 -->
            <button type="button" class="btn btn-cancel" onclick="parent.closeModal()">取消</button>

            <!-- 点确认：将选择的日期时间传递给父页面处理 -->
            <button type="button" class="btn btn-confirm"
                onclick="parent.handleFinalSubmit('<?= $date ?>', '<?= $time ?>')">确认</button>
        </div>
    </div>

</body>

</html>