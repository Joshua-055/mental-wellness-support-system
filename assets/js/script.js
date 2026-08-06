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
        excellent: 'Wonderful. Continue to your full check-in.',
        good: 'Glad to hear it. Continue to your full check-in.',
        neutral: 'Feeling neutral is completely okay. Continue to your full check-in.',
        stressed: 'It sounds like a lot. Continue so you can reflect or request support.',
        overwhelmed: 'You do not have to carry everything alone. Continue to your full check-in.'
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
            window.location.href = `index.php?page=checkin&mood=${encodeURIComponent(option.dataset.mood)}`;
        });
    });
});
