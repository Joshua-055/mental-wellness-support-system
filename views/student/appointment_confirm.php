<?php
// 接收主页面通过 URL 传过来的日期和时间
$date = isset($_GET['date']) ? htmlspecialchars($_GET['date']) : 'No Date Selected';
$time = isset($_GET['time']) ? htmlspecialchars($_GET['time']) : 'No Time Selected';
?>
<!DOCTYPE html>
<html lang="zh-CN">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm Appointment Details</title>
</head>

<body>

    <div class="modal-card" id="appointmentData" data-date="<?= htmlspecialchars($date) ?>"
        data-time="<?= htmlspecialchars($time) ?>">
        <h3 class="modal-title">Review Appointment Time</h3>
        <p class="modal-desc">Please confirm that the following details are correct:</p>

        <div class="summary-box">
            <div class="summary-item">
                <span class="label">📅 Appointment Date</span>
                <span class="value"><?= $date ?></span>
            </div>
            <div class="summary-item">
                <span class="label">⏰ Appointment Time</span>
                <span class="value"><?= $time ?></span>
            </div>
        </div>

        <div class="form-group">
            <!-- 👇 去掉(可选)，加上红色星号 -->
            <label for="supportRequestId">Select a Request Subject <span style="color: #ff3b30;">*</span></label>
            <select id="supportRequestId" onchange="toggleReasonBox()">
                <option value="">-- Please select a request --</option>

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
            <div id="subjectError" style="color: #ff3b30; font-size: 13px; margin-top: 8px; display: none;">Please select an appointment
                Request Subject！</div>
        </div>

        <div class="form-group" id="reasonGroup">
            <label for="reason">Reason for Appointment <span style="color: #ff3b30;">*</span></label>
            <textarea id="reason" placeholder="Please briefly describe the reason for your appointment..."></textarea>
            <!-- 👇 新增的隐藏报错提示字 -->
            <div id="reasonError" style="color: #ff3b30; font-size: 13px; margin-top: 8px; display: none;">Please enter the reason for your appointment!
            </div>
        </div>

        <div class="btn-group">
            <button type="button" class="btn btn-cancel" onclick="parent.closeModal()">Cancel</button>
            <button type="button" class="btn btn-confirm" onclick="submitToParent()">Confirm Appointment</button>
        </div>
    </div>

    <script>

    </script>
</body>

</html>