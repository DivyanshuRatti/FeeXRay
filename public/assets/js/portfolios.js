const themeButton = document.getElementById('portfolio-theme');
const themeIcon = themeButton.querySelector('span');

function updateThemeButton() {
    const dark = document.documentElement.dataset.theme === 'dark';
    themeButton.setAttribute('aria-pressed', String(dark));
    themeIcon.textContent = dark ? '☀' : '☾';
}

themeButton.addEventListener('click', () => {
    const theme = document.documentElement.dataset.theme === 'dark' ? 'light' : 'dark';
    document.documentElement.dataset.theme = theme;
    try {
        // Use the same saved choice as Dashboard and Settings.
        localStorage.setItem('feexray-theme', theme);
    } catch {}
    updateThemeButton();
});

updateThemeButton();
themeButton.hidden = false;
