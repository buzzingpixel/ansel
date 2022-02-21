/* eslint-disable global-require */

module.exports = {
    content: [
        './assets/react/**/*.jsx',
        './cms/**/*.{html,svg,template.php,twig}',
        './src/**/*.{html,svg,template.php,twig}',
    ],
    theme: {
        extend: {
            colors: {},
        },
    },
    plugins: [
        require('@tailwindcss/aspect-ratio'),
        require('@tailwindcss/forms'),
    ],
};
