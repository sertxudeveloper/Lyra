module.exports = {
    content: [
        './resources/views/**/*.{html,php}',
        './resources/js/**/*.{vue,js}',
    ],
    safelist: [
        'text-left',
        'text-center',
        'text-right',
        'text-justify',
    ],
    darkMode: 'class', // or 'media' or 'class'
    theme: {
        extend: {},
    },
    variants: {
        extend: {},
    },
    plugins: [
        require('@tailwindcss/forms')({
            strategy: 'class',
        })
    ],
}
