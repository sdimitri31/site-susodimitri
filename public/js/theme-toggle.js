document.addEventListener('DOMContentLoaded', (event) => {
    const themeToggleBtn = document.getElementById('themeToggle');
    const body = document.getElementById('body');
    const themeIcon = document.getElementById('themeIcon');

    const updateThemeIcon = (theme) => {
        if (theme === 'light') {
            themeIcon.classList.remove('fa-sun');
            themeIcon.classList.add('fa-moon');
        } else {
            themeIcon.classList.remove('fa-moon');
            themeIcon.classList.add('fa-sun');
        }
    };

    themeToggleBtn.addEventListener('click', () => {
        let currentTheme = body.getAttribute('data-bs-theme');
        let newTheme = currentTheme === 'light' ? 'dark' : 'light';
        body.setAttribute('data-bs-theme', newTheme);
        localStorage.setItem('theme', newTheme);
        updateThemeIcon(newTheme);
    });

    // Load saved theme from localStorage
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme) {
        body.setAttribute('data-bs-theme', savedTheme);
        updateThemeIcon(savedTheme);
    } else {
        const prefersDarkScheme = window.matchMedia('(prefers-color-scheme: dark)').matches;
        const initialTheme = prefersDarkScheme ? 'dark' : 'light';
        body.setAttribute('data-bs-theme', initialTheme);
        updateThemeIcon(initialTheme);
    }
});