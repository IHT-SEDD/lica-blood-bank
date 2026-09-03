import { defineConfig, loadEnv } from "vite";
import laravel from "laravel-vite-plugin";
import { globSync } from "glob";

export default defineConfig(({ mode }) => {
    // ---------- Load environment variables
    const env = loadEnv(mode, process.cwd(), "");

    // ---------- Validate ALWAYS-required environment variables
    const requiredEnv = ["APP_VERSION", "VITE_APP_HOST", "VITE_APP_PORT"];
    const missingEnv = requiredEnv.filter((key) => !env[key]);
    if (missingEnv.length > 0) {
        throw new Error(
            `Missing required environment variables: ${missingEnv.join(", ")}`,
        );
    }

    // ---------- Parse & validate server port
    const serverPort = Number(env.VITE_APP_PORT);
    if (
        !Number.isInteger(serverPort) ||
        serverPort <= 0 ||
        serverPort > 65535
    ) {
        throw new Error(
            `VITE_APP_PORT wajib memiliki angka port yang valid. Yang diterima: ${env.VITE_APP_PORT}`,
        );
    }

    // ---------- HMR is OPTIONAL group
    // Kosong semua -> pakai default Vite (cocok untuk dev di laptop lokal).
    // Diisi semua -> pakai custom HMR (untuk dev server yang di-expose ke LAN).
    const hmrKeys = ["VITE_HMR_HOST", "VITE_HMR_PORT", "VITE_HMR_PROTOCOL"];
    const hmrProvided = hmrKeys.filter((key) => env[key]);

    let hmrConfig = undefined;

    if (hmrProvided.length > 0) {
        // Kalau salah satu diisi, semua wajib diisi (biar tidak setengah-setengah)
        const hmrMissing = hmrKeys.filter((key) => !env[key]);
        if (hmrMissing.length > 0) {
            throw new Error(
                `Jika salah satu variabel HMR diisi, semua wajib diisi. Yang belum diisi: ${hmrMissing.join(", ")}`,
            );
        }

        const hmrPort = Number(env.VITE_HMR_PORT);
        if (!Number.isInteger(hmrPort) || hmrPort <= 0 || hmrPort > 65535) {
            throw new Error(
                `VITE_HMR_PORT wajib memiliki angka port yang valid. Yang diterima: ${env.VITE_HMR_PORT}`,
            );
        }

        if (!["ws", "wss"].includes(env.VITE_HMR_PROTOCOL)) {
            throw new Error(
                `VITE_HMR_PROTOCOL wajib "ws" atau "wss". Yang diterima: ${env.VITE_HMR_PROTOCOL}`,
            );
        }

        if (env.VITE_HMR_HOST === "0.0.0.0") {
            throw new Error(
                `VITE_HMR_HOST tidak boleh "0.0.0.0". Gunakan IP LAN server (misal 192.168.1.50) yang bisa dijangkau komputer client.`,
            );
        }

        hmrConfig = {
            host: env.VITE_HMR_HOST,
            port: hmrPort,
            protocol: env.VITE_HMR_PROTOCOL,
        };
    }
    // ---------- Kalau hmrProvided.length === 0, hmrConfig tetap undefined
    // -> Vite pakai default behavior-nya sendiri, cukup untuk dev lokal biasa.

    // -------------------- VITE CONFIGURATIONS --------------------
    return {
        define: {
            __APP_VERSION__: JSON.stringify(env.APP_VERSION),
        },
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
        // -------------------- SERVER --------------------
        server: {
            host: env.VITE_APP_HOST,
            port: serverPort,
            strictPort: true,
            cors: true,
            allowedHosts: ["lica-blood-bank.public:8080"],
            watch: {
                ignored: ["**/storage/framework/views/**"],
            },
            headers: {
                "X-Content-Type-Options": "nosniff",
            },
            hmr: hmrConfig, // undefined di lokal, custom object di server
        },
    };
});
