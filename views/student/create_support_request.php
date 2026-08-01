<!DOCTYPE html>
<html lang="zh-CN">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Support Request</title>
</head>

<body>
    <div class="request-modal">

        <!-- 调用父页面 JS 函数关闭小窗口 -->
        <button class="close-modal" type="button" onclick="parent.closeModal()">
            ×
        </button>

        <h2>Create Support Request</h2>

        <form method="POST" action="index.php?page=create_support_request" target="_parent">
            <input type="hidden" name="action" value="create_request">

            <label>Subject</label>
            <input type="text" name="title" placeholder="Enter your issue" required>

            <label>Category</label>
            <select name="category" required>
                <option value="" selected disabled>Choose category</option>
                <option value="1">Stress / Anxiety</option>
                <option value="2">Depression & Mood</option>
                <option value="3">Academic Stress</option>
                <option value="4">Relationship & Social Issues</option>
                <option value="5">Personal Growth</option>
                <option value="6">Other</option>
            </select>

            <label>Description</label>
            <textarea name="description" placeholder="Describe your problem" required></textarea>

            <!-- 按钮区 -->
            <div class="button-group">
                <!-- 取消按钮：调用父页面的 closeModal 函数 -->
                <button type="button" class="btn cancel-btn" onclick="parent.closeModal()">Cancel</button>

                <!-- 确认提交按钮：触发 form 默认的 submit 行为 -->
                <button type="submit" class="btn confirm-btn">Confirm</button>
            </div>
        </form>

    </div>
</body>

</html>