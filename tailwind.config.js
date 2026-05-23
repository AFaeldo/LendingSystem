/** @type {import('tailwindcss').Config} */

export default {
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.ts',
    ],

    theme: {
        extend: {

            fontFamily: {
                sans: [
                    'Instrument Sans',
                    'ui-sans-serif',
                    'system-ui',
                    'sans-serif',
                ],
            },
            boxShadow: {
                card: '0 20px 50px rgba(0,0,0,0.15)',
            },

            colors: {
                brand: {
                    50: '#fdf6f0',
                    100: '#f6e7d7',
                    200: '#f2d6b3',
                    300: '#e8c29a',
                    400: '#d9a36f',
                    500: '#c98a4a',
                    600: '#b87333',
                    700: '#8a5222',
                    800: '#6e401b',
                    900: '#4a2b12',
                },
            }

        },
    },

    plugins: [],
}
