import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            colors: {
                primary: '#4CAF50',
                secondary: '#A7C7C7',
                accent: '#8BC34A',
                surface: '#F5F7F6',
                ink: '#2E2E2E',
                footer: '#0B1D26',
                'tint-green': '#EAF6EC',
                divider: '#E5E7EB',
            },
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
                serif: ['Cormorant Garamond', 'Georgia', ...defaultTheme.fontFamily.serif],
            },
            boxShadow: {
                soft: '0 4px 24px rgba(46, 46, 46, 0.08)',
                card: '0 8px 30px rgba(46, 46, 46, 0.06)',
                depth: '0 10px 25px rgba(0, 0, 0, 0.08)',
                'depth-hover': '0 14px 30px rgba(0, 0, 0, 0.12)',
            },
            borderRadius: {
                card: '16px',
                btn: '10px',
                badge: '8px',
            },
        },
    },

    plugins: [forms],
};
