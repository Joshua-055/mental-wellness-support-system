<?php
$appointmentId = isset($_GET['appointment_id']) ? (int) $_GET['appointment_id'] : 0;
?>
<!DOCTYPE html>
<html lang="zh-CN">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>填写处理意见</title>
</head>

<body>
    <div class="modal-card">
        <h3 class="modal-title">负责此预约</h3>
        <p class="modal-desc">请填写您的处理备注 (Staff Remark)：</p>

        <div class="form-group">
            <label for="staffRemark">Staff Remark</label>
            <textarea id="staffRemark" placeholder="请输入跟进意见或备注..."></textarea>
            <div id="remarkError" style="color: #ff3b30; font-size: 13px; margin-top: 6px; display: none;">请填写备注内容！
            </div>
        </div>

        <div class="btn-group">
            <button type="button" class="btn btn-cancel" onclick="parent.closeStaffModal()">Cancel</button>
            <button type="button" class="btn btn-confirm" onclick="submitRemark(<?= $appointmentId ?>)">Submit</button>
        </div>
    </div>

    <script>
       
    </script>
</body>

</html>