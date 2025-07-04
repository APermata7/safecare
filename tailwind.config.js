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
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                'primary-green': '#537D5D',
                'secondary-green': '#73946B',
                'light-green': '#9EBC8A',
                'beige-tone': '#D2D0A0',

                olive: {
                    DEFAULT: '#73946B',
                    hover: '#5e7b58',
                    active: '#4b6246',
                    ring: '#a3bda1',
                },
            },
        },
    },

    plugins: [forms],
};
