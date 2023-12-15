import defaultTheme from 'tailwindcss/defaultTheme'
import forms from '@tailwindcss/forms'
import typography from '@tailwindcss/typography'

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [
        forms,
        typography,
        function ({ addVariant }) {
            addVariant(
                'supports-scrollbars',
                '@supports selector(::-webkit-scrollbar)',
            )
            addVariant('scrollbar', '&::-webkit-scrollbar')
            addVariant('scrollbar-track', '&::-webkit-scrollbar-track')
            addVariant('scrollbar-thumb', '&::-webkit-scrollbar-thumb')
        },
    ],
}
