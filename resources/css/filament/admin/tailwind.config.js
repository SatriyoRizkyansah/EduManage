import preset from "../../../../vendor/filament/filament/tailwind.config.preset";

export default {
    presets: [preset],
    // content: [
    //     "./app/Filament/**/*.php",
    //     "./resources/views/filament/**/*.blade.php",
    //     "./vendor/filament/**/*.blade.php",
    //     "./resources/**/*.blade.php",
    //     "./resources/**/*.js",
    //     "./resources/**/*.vue",
    // ],
    content: [
        "./app/Filament/**/*.php",
        "./resources/views/filament/**/*.blade.php",
        "./vendor/filament/**/*.blade.php",
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {},
    },
    plugins: [],
};

// module.exports = {
//     content: ["./src/**/*.{html,js}"],
//     theme: {
//         extend: {},
//     },
//     plugins: [],
// };
