import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './vendor/mckenziearts/laravel-notify/resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Roboto', 'Cairo', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                'dark-blue': {
                    50: '#e6f0ff',
                    100: '#b3d9ff',
                    200: '#80c2ff',
                    300: '#4dabff',
                    400: '#1a94ff',
                    500: '#0066cc',
                    600: '#0052a3',
                    700: '#003d7a',
                    800: '#002952',
                    900: '#001429',
                },
            },
            borderRadius: {
                'button': '0.75rem',
            },
        },
    },

    plugins: [forms],
};
