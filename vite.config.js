import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
    plugins: [
        laravel({
            input: ["resources/css/app.css", "resources/js/app.js"],
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
