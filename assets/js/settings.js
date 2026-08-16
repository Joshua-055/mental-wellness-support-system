document.addEventListener('DOMContentLoaded', () => {
    const root = document.documentElement;
    const options = document.querySelectorAll('[data-theme-choice]');

    const applyTheme = (theme) => {
        const selectedTheme = theme === 'dark' ? 'dark' : 'light';
        root.dataset.theme = selectedTheme;

        try {
            localStorage.setItem('mindful-theme', selectedTheme);
        } catch (error) {
            // The theme still applies for this page if storage is unavailable.
        }

        options.forEach((option) => {
            const selected = option.dataset.themeChoice === selectedTheme;
            option.classList.toggle('selected', selected);
            option.setAttribute('aria-pressed', String(selected));
        });
    };

    options.forEach((option) => {
        option.addEventListener('click', () => applyTheme(option.dataset.themeChoice));
    });

    applyTheme(root.dataset.theme);
});
