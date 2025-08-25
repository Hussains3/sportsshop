import './bootstrap';
import Alpine from 'alpinejs';
import { initializeAlerts } from './utils';

// Dark mode handler
const setupTheme = () => {
    const theme = localStorage.getItem('theme') || 'system';
    const isDarkMode = theme === 'dark' ||
        (theme === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches);

    if (isDarkMode) {
        document.documentElement.classList.add('dark');
    } else {
        document.documentElement.classList.remove('dark');
    }
};

// Watch for system theme changes
window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', setupTheme);

// Initialize theme on page load
document.addEventListener('DOMContentLoaded', setupTheme);

window.Alpine = Alpine;

// Add dark mode handler to Alpine.js
Alpine.store('theme', {
    mode: localStorage.getItem('theme') || 'system',

    init() {
        setupTheme();
    },

    setMode(newMode) {
        this.mode = newMode;
        if (newMode === 'system') {
            localStorage.removeItem('theme');
        } else {
            localStorage.setItem('theme', newMode);
        }
        setupTheme();
    }
});

Alpine.start();

// Initialize custom functionality
document.addEventListener('DOMContentLoaded', function() {
    initializeAlerts();
});
