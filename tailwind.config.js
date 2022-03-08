/* eslint-disable global-require */

module.exports = {
    prefix: 'ansel_',
    content: [
        './assets/js/**/*.jsx',
        './assets/js/**/*.tsx',
        './cms/**/*.{html,svg,template.php,twig}',
        './src/**/*.{html,svg,template.php,twig}',
    ],
    theme: {
        extend: {
            colors: {},
            screens: {
                '3xl': '1750px',
            },
        },
    },
    plugins: [
        require('@tailwindcss/aspect-ratio'),
        require('@tailwindcss/forms'),
    ],
};
