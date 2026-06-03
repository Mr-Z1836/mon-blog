const storageKey = 'theme';

export function getPreferredTheme() {
    const stored = localStorage.getItem(storageKey);

    if (stored === 'dark' || stored === 'light') {
        return stored;
    }

    return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
}

export function applyTheme(theme) {
    document.documentElement.classList.toggle('dark', theme === 'dark');
    localStorage.setItem(storageKey, theme);
}

export function initTheme() {
    applyTheme(getPreferredTheme());
}

export function toggleTheme() {
    const next = document.documentElement.classList.contains('dark') ? 'light' : 'dark';
    applyTheme(next);

    return next;
}
