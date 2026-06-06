import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',

    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Plus Jakarta Sans', ...defaultTheme.fontFamily.sans],
                serif: ['Source Serif 4', ...defaultTheme.fontFamily.serif],
                article: ['Source Serif 4', 'Georgia', ...defaultTheme.fontFamily.serif],
            },
            colors: {
                brand: {
                    green: '#3D9A62',
                    yellow: '#C99718',
                    red: '#C94545',
                },
            },
        },
    },

    plugins: [forms],
};
