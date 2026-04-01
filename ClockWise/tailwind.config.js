const defaultTheme = require('tailwindcss/defaultTheme');
const forms = require('@tailwindcss/forms');

module.exports = {
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
            boxShadow: {
                glass: '0 8px 32px rgba(31, 38, 135, 0.12), inset 0 1px 0 rgba(255, 255, 255, 0.45)',
                'glow-indigo': '0 0 40px -8px rgba(99, 102, 241, 0.45), 0 12px 40px -12px rgba(139, 92, 246, 0.35)',
                'glow-rose': '0 0 32px -6px rgba(244, 63, 94, 0.4)',
            },
            backgroundImage: {
                'mesh-app':
                    'linear-gradient(160deg, #e8f0ff 0%, #ede9fe 28%, #fce7f3 55%, #fae8ff 78%, #e0f2fe 100%)',
            },
        },
    },

    plugins: [forms],
};
