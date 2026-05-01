import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig(({ mode }) => {
    const isDemo = mode === "demo";

    return {
        plugins: [
            laravel({
                input: ["resources/css/app.css", "resources/js/app.js"],
                refresh: true,
            }),
        ],
        server: {
            // Saat mode demo, bind ke port 5174; local ke 5173
            host: "0.0.0.0",
            port: isDemo ? 5174 : 5173,
            hmr: {
                // Jika demo gunakan ip radmin laptop dan gunakan localhost untuk non demo
                host: isDemo ? "26.164.75.153" : "127.0.0.1",
                // Jika demo gunakan 5174 dan 5173 untuk non demo
                port: isDemo ? 5174 : 5173,
            },
        },
        define: {
            __APP_MODE__: JSON.stringify(mode),
        },
    };
});
