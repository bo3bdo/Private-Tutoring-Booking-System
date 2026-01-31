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
            boxShadow: {
                'soft': '0 2px 15px -3px rgba(0, 0, 0, 0.07), 0 10px 20px -2px rgba(0, 0, 0, 0.04)',
                'elevated': '0 10px 40px -10px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05)',
                'inner-glow': 'inset 0 2px 4px 0 rgba(255, 255, 255, 0.06)',
            },
            animation: {
                'pulse-soft': 'pulse-soft 2s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                'bounce-subtle': 'bounce-subtle 1s ease-in-out infinite',
                'gradient-shift': 'gradient-shift 3s ease infinite',
            },
            keyframes: {
                'pulse-soft': {
                    '0%, 100%': { opacity: '1' },
                    '50%': { opacity: '0.7' },
                },
                'bounce-subtle': {
                    '0%, 100%': { transform: 'translateY(0)' },
                    '50%': { transform: 'translateY(-3px)' },
                },
                'gradient-shift': {
                    '0%': { backgroundPosition: '0% 50%' },
                    '50%': { backgroundPosition: '100% 50%' },
                    '100%': { backgroundPosition: '0% 50%' },
                },
            },
            transitionDuration: {
                '400': '400ms',
            },
        },
    },

    plugins: [forms],
};
