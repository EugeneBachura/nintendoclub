import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: [
                    "Poppins",
                    "system-ui",
                    "-apple-system",
                    "BlinkMacSystemFont",
                ], // основной текст
                serif: ["Playfair Display", "Georgia", "Cambria"], // заголовки
            },
            colors: {
                accent: {
                    DEFAULT: "#ff3b3c",
                    hover: "#e32b2d",
                },
                background: {
                    DEFAULT: "#191919",
                    hover: "#2f2f2f",
                },
                content: {
                    DEFAULT: "#252525",
                    hover: "#2f2f2f",
                    border: "#393939",
                    table: "#252525",
                    table2: "#2f2f2f",
                },
                content_text: {
                    DEFAULT: "#dedede",
                    hover: "#ffffff",
                },
                nav: {
                    DEFAULT: "#0a0a0a",
                    hover: "#191919",
                },
                nav_text: {
                    DEFAULT: "#eeeeee",
                    hover: "#ff3b3c",
                },
                color_text: {
                    DEFAULT: "#dedede",
                    hover: "#ffffff",
                },
                dark_text: {
                    DEFAULT: "#333132",
                    hover: "#292820",
                },
                light_text: {
                    DEFAULT: "#a5a5a5",
                    hover: "#f4f6f8",
                },
                link: {
                    DEFAULT: "#ff3b3c",
                    hover: "#e32b2d",
                },
                discord: {
                    DEFAULT: "#5865f2",
                    text: "#ffffff",
                    hover: "#5b6eae",
                },
                online: {
                    DEFAULT: "#78B159",
                },
                successfully: {
                    DEFAULT: "#78B159",
                    text: "#eeeeee",
                },
                error: {
                    DEFAULT: "#ff3b3c",
                    text: "#eeeeee",
                },
                success: {
                    DEFAULT: "#78B159",
                    text: "#eeeeee",
                    hover: "#6da148",
                },
                grey: {
                    DEFAULT: "#444444",
                    text: "#dedede",
                    hover: "#323232",
                },
                warn: {
                    DEFAULT: "#ffcc00",
                    text: "#dedede",
                    hover: "#e3b22e",
                },
            },
        },
    },

    plugins: [forms],
};
