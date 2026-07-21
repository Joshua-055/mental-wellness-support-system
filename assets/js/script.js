document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('#loginForm');
    const password = document.querySelector('#password');
    const toggle = document.querySelector('.password-toggle');
    const message = document.querySelector('#formMessage');

    if (toggle && password) {
        toggle.addEventListener('click', () => {
            const showPassword = password.type === 'password';
            password.type = showPassword ? 'text' : 'password';
            toggle.setAttribute('aria-pressed', String(showPassword));
            toggle.setAttribute('aria-label', showPassword ? 'Hide password' : 'Show password');
        });
    }

    if (form) {
        form.addEventListener('submit', (event) => {
            if (!form.checkValidity()) {
                event.preventDefault();
                message.textContent = 'Please enter your university email and password.';
                form.querySelector(':invalid')?.focus();
            }
        });
    }

    const moodOptions = document.querySelectorAll('.mood-option');
    const moodFeedback = document.querySelector('.mood-feedback');
    const moodMessages = {
        Excellent: 'Wonderful. Capture what is making today feel bright.',
        Good: 'Glad to hear it. Keep making room for what supports you.',
        Neutral: 'Feeling neutral is completely okay. You are doing well by checking in.',
        Stressed: 'It sounds like a lot. Try a small pause, or explore support when you are ready.',
        Overwhelmed: 'You do not have to carry everything alone. Support is available when you need it.'
    };

    moodOptions.forEach((option) => {
        option.addEventListener('click', () => {
            moodOptions.forEach((item) => {
                item.classList.remove('selected');
                item.setAttribute('aria-pressed', 'false');
            });
            option.classList.add('selected');
            option.setAttribute('aria-pressed', 'true');
            if (moodFeedback) moodFeedback.textContent = moodMessages[option.dataset.mood];
        });
    });
});
