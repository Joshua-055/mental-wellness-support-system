function submitRemark(appointmentId) {
    const remarkInput = document.getElementById('staffRemark');
    const remark = remarkInput.value;
    const remarkError = document.getElementById('remarkError');

    remarkError.style.display = 'none';
    remarkInput.style.borderColor = '#e2e8f0';

    if (remark.trim() === "") {
        remarkError.style.display = 'block';
        remarkInput.style.borderColor = '#ff3b30';
        remarkInput.focus();
        return;
    }

    const btn = document.querySelector('.btn-confirm');
    btn.innerText = "Submitting...";
    btn.disabled = true;

    const formData = new FormData();
    formData.append('appointment_id', appointmentId);
    formData.append('staff_remark', remark);

    fetch('index.php?page=appointment_take_save', {
        method: 'POST',
        body: formData
    })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                parent.handleStaffRemarkSubmit();
            } else {
                alert(data.message || "保存失败");
                btn.innerText = "Submit";
                btn.disabled = false;
            }
        })
        .catch(err => {
            console.error(err);
            btn.innerText = "Submit";
            btn.disabled = false;
        });
}