import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: ['./src/**/*.{vue,js,ts}',
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
        './public/index.html',
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['poppins', 'ui-sans-serif', 'system-ui']
            },
            fontWeight: {
                thin: 100,
                extralight: 200,
                light: 300
            },

            animation: {
                'pulse-slow': 'pulse-slow 6s ease-in-out infinite',
            },
            keyframes: {
                'pulse-slow': {
                    '0%, 100%': {opacity: '0.5'},
                    '50%': {opacity: '0.8'},
                },
            },
            colors: {
                primary: {
                    DEFAULT: '#0E9C9A',        // Lagos Lagoon Teal (deep teal)
                    light: '#3AB0AC',          // lighter teal variant
                    dark: '#0B7A78',           // darker teal variant
                },
                secondary: {
                    DEFAULT: '#E67E22',        // Sunset Ochre (warm orange)
                    light: '#F2994A',          // lighter orange
                    dark: '#B05E17',           // darker orange
                },
                tertiary: {
                    DEFAULT: '#F5F0E6',        // Baobab Cream (warm off-white)
                    light: '#FAF7F2',          // lighter off-white
                    dark: '#D9D5CB',           // darker off-white / beige
                },
            },
        }
    },
    plugins: [
        require('@tailwindcss/forms'), forms
    ]
}
