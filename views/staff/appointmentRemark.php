<?php
$appointmentId = isset($_GET['appointment_id']) ? (int) $_GET['appointment_id'] : 0;
?>
<!DOCTYPE html>
<html lang="zh-CN">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enter Your Remark</title>
</head>

<body>
    <div class="modal-card">
        <h3 class="modal-title">Handle This Appointment</h3>
        <p class="modal-desc">Please enter your staff remark: </p>

        <div class="form-group">
            <label for="staffRemark">Staff Remark</label>
            <textarea id="staffRemark" placeholder="Please enter your follow-up remarks or notes..."></textarea>
            <div id="remarkError" style="color: #ff3b30; font-size: 13px; margin-top: 6px; display: none;">Please fill in the remarks.
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