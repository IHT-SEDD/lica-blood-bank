import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import { globSync } from "glob";

export default defineConfig({
    assetsInclude: ["**/*.woff", "**/*.woff2"],
    plugins: [
        laravel({
            input: [
                "resources/css/app.css",
                "resources/scss/app.scss",

                "resources/js/app.js",
                "resources/js/config.js",

                ...globSync("resources/js/pages/**/*.js"),
                ...globSync("resources/js/utility/**/*.js"),
            ],
            refresh: true,
        }),
    ],
    server: {
        // Bind host ke 0.0.0.0 agar bisa diakses dari jaringan lokal, bukan hanya localhost
        host: "0.0.0.0",
        port: 5173,
        hmr: {
            host: "localhost",
            port: 5173,
        },
    },
});
