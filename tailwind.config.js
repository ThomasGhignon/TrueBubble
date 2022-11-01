/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "./assets/**/*.js",
        "./templates/**/*.html.twig",
    ],
    theme: {
        extend: {
            boxShadow: {
                '3xl': '0 4px 6px -1px rgb(0 0 0 / 0.01), 0 2px 5px -2px rgb(0 0 0 / 0.1)',
            },
            colors: {
                'main': '#ebebeb',
            },
        },
    },
    plugins: [],
}