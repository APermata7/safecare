const defaultTheme = require('tailwindcss/defaultTheme');
const colors = require('tailwindcss/colors');

module.exports = {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: {
                    DEFAULT: '#537D5D',
                    50: '#F2F7F3',
                    100: '#E5EFE7',
                    200: '#C1D9C6',
                    300: '#9DC3A5',
                    400: '#78AD84',
                    500: '#537D5D',
                    600: '#43644B',
                    700: '#324B38',
                    800: '#223226',
                    900: '#111913',
                },
                secondary: {
                    DEFAULT: '#73946B',
                    50: '#F5F8F4',
                    100: '#EBF1EA',
                    200: '#CEDDCA',
                    300: '#B1C9AA',
                    400: '#94B58A',
                    500: '#73946B',
                    600: '#5C7656',
                    700: '#455840',
                    800: '#2E3B2B',
                    900: '#171D15',
                },
                accent: {
                    DEFAULT: '#9EBC8A',
                    50: '#F7FAF5',
                    100: '#EFF5EB',
                    200: '#D8E7CE',
                    300: '#C1D9B1',
                    400: '#AACB94',
                    500: '#9EBC8A',
                    600: '#7E966E',
                    700: '#5F7153',
                    800: '#3F4B37',
                    900: '#20261C',
                },
            },
            boxShadow: {
                card: '0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.02)',
                panel: '0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.01)',
            },
            transitionProperty: {
                'colors': 'background-color, border-color, color, fill, stroke',
            },
            transitionDuration: {
                '200': '200ms',
                '300': '300ms',
            },
        },
    },

    plugins: [
        require('@tailwindcss/forms'),
        require('@tailwindcss/typography'),
        require('@tailwindcss/aspect-ratio'),
    ],
};