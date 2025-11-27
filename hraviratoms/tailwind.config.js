/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/views/**/*.blade.php",
        "./resources/js/**/*.{vue,js,ts,jsx,tsx}",
    ],
    theme: {
        extend: {
            colors: {
                leaf: {
                    DEFAULT: '#5CA371',     // primary
                    soft: '#A7D3B5',        // light buttons/labels
                    deep: '#447D56',        // dark accents
                    bg: '#FAF8F4',          // light background (ivory)
                },
                gold: {
                    light: '#ECDDBB',
                    DEFAULT: '#D9C79E',
                    dark: '#A9966C',
                },
                love: {
                    blush: '#F6ECE8',
                },
            },
            fontFamily: {
                logo: ['"Playfair Display"', 'serif'],
                display: ['"Cormorant Garamond"', 'serif'],
                body: ['Inter', 'system-ui', 'sans-serif'],
                script: ['"Dancing Script"', 'cursive'],
            },
        },
    },
    plugins: [
        require('@tailwindcss/forms'),
    ],
}
