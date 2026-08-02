function toggleReasonBox() {
    const selectBox = document.getElementById('supportRequestId');
    const reasonGroup = document.getElementById('reasonGroup');
    const reasonInput = document.getElementById('reason');

    if (selectBox.value !== "") {
        reasonGroup.style.display = 'block';
    } else {
        reasonGroup.style.display = 'none';
        reasonInput.value = "";
    }
}

function submitToParent() {
    const date = '<?= $date ?>';
    const time = '<?= $time ?>';
    const supportRequestId = document.getElementById('supportRequestId').value;
    const reason = document.getElementById('reason').value;

    if (supportRequestId !== "" && reason.trim() === "") {
        alert("请填写预约原因！");
        document.getElementById('reason').focus();
        return;
    }

    parent.handleFinalSubmit(date, time, supportRequestId, reason);
}