/**
 * Template Name: Simple - Admin & Dashboard Template
 * By (Author): Coderthemes
 * Module/App (File Name): Config
 * Version: 3.0.0
 */

(function () {
    const html = document.documentElement;
    const storageKey = "__LICA_BLOOD_BANK_CONFIG__";

    let savedConfig = null;
    try {
        savedConfig = JSON.parse(sessionStorage.getItem(storageKey));
    } catch (e) {}

    // ---- Profil sidenav per halaman ----
    const sidenavProfiles = {
        blood_transfusion: {
            color: "light", // Sidebar color: light, dark, or gray
            size: "collapse", // Sidebar size: default, collapse, or offcanvas
            user: false, // Show user info in sidebar: true or false
        },
        dev_playground: {
            color: "light", // Sidebar color: light, dark, or gray
            size: "default", // Sidebar size: default, collapse, or offcanvas
            user: true, // Show user info in sidebar: true or false
        },
        other: {
            color: "light", // Sidebar color: light, dark, or gray
            size: "default", // Sidebar size: default, collapse, or offcanvas
            user: true, // Show user info in sidebar: true or false
        }, // fallback
    };

    // Urutan penting: yang pertama cocok akan dipakai
    const sidenavRoutes = [
        {
            key: "blood_transfusion",
            test: (p) => p.startsWith("/blood-transfusion"),
        },
        { key: "dev_playground", test: (p) => p.startsWith("/playground") },
    ];

    const resolveSidenavKey = (path = window.location.pathname) =>
        (sidenavRoutes.find((r) => r.test(path)) || { key: "other" }).key;

    // ---- Default config ----
    const defaultConfig = {
        skin: "shadcn", // Theme skin: default, two, three, four, five, six
        monochrome: false, // Enable monochrome mode
        theme: "light", // App theme: light or dark
        sidenavs: sidenavProfiles,
        topbar: {
            color: "light", // Topbar color: light, dark, or gray
        },
        layout: {
            position: "fixed", // Layout position: fixed or scrollable
        },
    };

    // Build config dari atribut HTML (selain sidenav)
    const htmlConfig = {
        skin: html.getAttribute("data-skin") || defaultConfig.skin,
        monochrome:
            html.classList.contains("monochrome") || defaultConfig.monochrome,
        theme: html.getAttribute("data-bs-theme") || defaultConfig.theme,
        sidenavs: structuredClone(sidenavProfiles),
        topbar: {
            color:
                html.getAttribute("data-topbar-color") ||
                defaultConfig.topbar.color,
        },
        layout: {
            position:
                html.getAttribute("data-layout-position") ||
                defaultConfig.layout.position,
        },
    };

    window.defaultConfig = structuredClone(htmlConfig);

    // Merge config tersimpan dengan default (aman untuk format lama / key baru)
    let config = structuredClone(htmlConfig);
    if (savedConfig && typeof savedConfig === "object") {
        const {
            sidenavs: savedSidenavs,
            sidenav: _legacy,
            ...rest
        } = savedConfig;
        config = { ...config, ...rest };
        config.topbar = { ...htmlConfig.topbar, ...(rest.topbar || {}) };
        config.layout = { ...htmlConfig.layout, ...(rest.layout || {}) };
        config.sidenavs = {};
        for (const key of Object.keys(sidenavProfiles)) {
            config.sidenavs[key] = {
                ...sidenavProfiles[key],
                ...((savedSidenavs && savedSidenavs[key]) || {}),
            };
        }
    }
    window.config = config;

    // Profil aktif untuk halaman ini
    const sidenavKey = resolveSidenavKey();
    window.sidenavKey = sidenavKey;
    window.resolveSidenavKey = resolveSidenavKey;
    const sidenav = config.sidenavs[sidenavKey];

    // ---- Terapkan atribut segera ----
    html.setAttribute("data-skin", config.skin);
    html.setAttribute("data-bs-theme", config.theme);
    html.setAttribute("data-sidenav-color", sidenav.color);
    html.setAttribute("data-topbar-color", config.topbar.color);
    html.setAttribute("data-layout-position", config.layout.position);
    html.classList.toggle("monochrome", config.monochrome);

    let size = sidenav.size;
    if (window.innerWidth <= 1140) size = "offcanvas";
    html.setAttribute("data-sidenav-size", size);

    if (sidenav.user === true) {
        html.setAttribute("data-sidenav-user", "true");
    } else {
        html.removeAttribute("data-sidenav-user");
    }
})();
