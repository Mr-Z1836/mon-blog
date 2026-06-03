import Alpine from 'alpinejs';
import { getPreferredTheme, initTheme, toggleTheme } from './theme';

initTheme();

window.Alpine = Alpine;

Alpine.data('themeToggle', () => ({
    dark: getPreferredTheme() === 'dark',
    toggle() {
        this.dark = toggleTheme() === 'dark';
    },
}));

Alpine.start();
