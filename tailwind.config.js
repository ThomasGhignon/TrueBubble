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
                'main-gray': '#6B7280',
            },
            width: {
                '128': '32rem',
            },
            backgroundImage: {
                'new': "radial-gradient( circle farthest-corner at 12.3% 19.3%,  rgba(85,88,218,1) 0%, rgba(95,209,249,1) 100.2% );",
                'fake': "radial-gradient( circle farthest-corner at 50.4% 50.5%,  rgba(251,32,86,1) 0%, rgba(135,2,35,1) 90% );",
                'true': "linear-gradient( 135deg, #81FBB8 10%, #28C76F 100%);",
                'hype': "radial-gradient( circle farthest-corner at 10.2% 55.8%,  rgba(252,37,103,1) 0%, rgba(250,38,151,1) 46.2%, rgba(186,8,181,1) 90.1% );",
            },
        },
    },
    plugins: [
        require('flowbite/plugin')
    ]
}