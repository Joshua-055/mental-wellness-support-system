<?php
// 接收主页面通过 URL 传过来的日期和时间
$date = isset($_GET['date']) ? htmlspecialchars($_GET['date']) : '未选择日期';
$time = isset($_GET['time']) ? htmlspecialchars($_GET['time']) : '未选择时间';
?>
<!DOCTYPE html>
<html lang="zh-CN">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>确认预约信息</title>
</head>

<body>

    <div class="modal-card" id="appointmentData" data-date="<?= htmlspecialchars($date) ?>"
        data-time="<?= htmlspecialchars($time) ?>">
        <h3 class="modal-title">核对预约时间</h3>
        <p class="modal-desc">请确认以下选择是否正确：</p>

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

        <div class="form-group">
            <!-- 👇 去掉(可选)，加上红色星号 -->
            <label for="supportRequestId">选择你要预约的 Request Subject <span style="color: #ff3b30;">*</span></label>
            <select id="supportRequestId" onchange="toggleReasonBox()">
                <option value="">-- 请选择关联请求 --</option>

                <?php if (isset($rawRequests) && is_array($rawRequests)): ?>
                    <?php foreach ($rawRequests as $req): ?>
                        <?php
                        $desc = $req['description'] ?? '';
                        $subject = 'Support Request';

                        if (strpos($desc, '【Subject】:') !== false) {
                            $parts = explode('【Details】:', $desc);
                            $subject = trim(str_replace('【Subject】:', '', $parts[0]));
                        }
                        ?>
                        <option value="<?= $req['id'] ?>">
                            Subject: <?= htmlspecialchars($subject) ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>

            </select>
            <!-- 👇 新增的隐藏报错提示字 (Subject 专属) -->
            <div id="subjectError" style="color: #ff3b30; font-size: 13px; margin-top: 8px; display: none;">请选择要预约的
                Request Subject！</div>
        </div>

        <div class="form-group" id="reasonGroup">
            <label for="reason">预约原因 <span style="color: #ff3b30;">*</span></label>
            <textarea id="reason" placeholder="请简述您的预约原因..."></textarea>
            <!-- 👇 新增的隐藏报错提示字 -->
            <div id="reasonError" style="color: #ff3b30; font-size: 13px; margin-top: 8px; display: none;">请填写预约原因！
            </div>
        </div>

        <div class="btn-group">
            <button type="button" class="btn btn-cancel" onclick="parent.closeModal()">取消</button>
            <button type="button" class="btn btn-confirm" onclick="submitToParent()">确认预约</button>
        </div>
    </div>

    <script>

    </script>
</body>

</html>