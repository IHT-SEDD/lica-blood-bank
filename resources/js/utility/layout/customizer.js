export class LayoutCustomizer {
    constructor() {
        this.html = document.documentElement;
        this.config = {};
    }

    init() {
        this.initConfig();
        this.monochromeMode();
        this.initSwitchListener();
        this.initWindowSize();
        this._adjustLayout();
        this.setSwitchFromConfig();
        this.openCustomizer(); // demo only
    }

    initConfig() {
        this.defaultConfig = JSON.parse(JSON.stringify(window.defaultConfig));
        this.config = JSON.parse(JSON.stringify(window.config));
        this.setSwitchFromConfig();
    }

    // demo only
    isFirstVisit() {
        const visited = localStorage.getItem("__user_has_visited__");
        if (!visited) {
            localStorage.setItem("__user_has_visited__", "true");
            return true;
        }
        return false;
    }

    // demo only
    openCustomizer() {
        const layoutCustomizer = document.getElementById(
            "theme-settings-offcanvas",
        );
        if (layoutCustomizer && this.isFirstVisit()) {
            const offcanvas = new bootstrap.Offcanvas(layoutCustomizer);
            setTimeout(() => {
                offcanvas.show();
            }, 1000);
        }
    }

    monochromeMode() {
        const toggleBtn = document.getElementById("monochrome-mode");
        if (toggleBtn) {
            toggleBtn.addEventListener("click", () => {
                this.config.monochrome = !this.config.monochrome;

                if (this.config.monochrome) {
                    this.html.classList.add("monochrome");
                } else {
                    this.html.classList.remove("monochrome");
                }

                // persist config
                this.setSwitchFromConfig();
            });
        }
    }

    changeSkin(skin) {
        this.config.skin = skin;
        this.html.setAttribute("data-skin", skin);
        this.setSwitchFromConfig();
    }

    changeSidenavColor(color) {
        this.config.sidenav.color = color;
        this.html.setAttribute("data-sidenav-color", color);
        this.setSwitchFromConfig();
    }

    changeSidenavSize(size, save = true) {
        this.html.setAttribute("data-sidenav-size", size);
        if (save) {
            this.config.sidenav.size = size;
            this.setSwitchFromConfig();
        }
    }

    changeLayoutPosition(position) {
        this.config.layout.position = position;
        this.html.setAttribute("data-layout-position", position);
        this.setSwitchFromConfig();
    }

    changeTheme(color) {
        this.config.theme = color;
        this.html.setAttribute("data-bs-theme", color);
        this.setSwitchFromConfig();
    }

    changeTopbarColor(color) {
        this.config.topbar.color = color;
        this.html.setAttribute("data-topbar-color", color);
        this.setSwitchFromConfig();
    }

    changeSidenavUser(showUser) {
        this.config.sidenav.user = showUser;
        if (showUser) {
            this.html.setAttribute("data-sidenav-user", showUser);
        } else {
            this.html.removeAttribute("data-sidenav-user");
        }
        this.setSwitchFromConfig();
    }

    resetTheme() {
        this.config = JSON.parse(JSON.stringify(window.defaultConfig));
        this.changeSkin(this.config.skin);
        this.changeSidenavColor(this.config.sidenav.color);
        this.changeSidenavSize(this.config.sidenav.size);
        this.changeTheme(this.config.theme);
        this.changeLayoutPosition(this.config.layout.position);
        this.changeTopbarColor(this.config.topbar.color);
        this.changeSidenavUser(this.config.sidenav.user);
        this._adjustLayout();
    }

    setSwitchFromConfig() {
        const config = this.config;

        sessionStorage.setItem("__SIMPLE_CONFIG__", JSON.stringify(config));

        document
            .querySelectorAll(".right-bar input[type=checkbox]")
            .forEach((cb) => (cb.checked = false));

        const select = (name, val) =>
            document.querySelector(`input[name="${name}"][value="${val}"]`);
        const toggle = (sel, state) => {
            const el = document.querySelector(sel);
            if (el) el.checked = state;
        };

        toggle('input[name="sidebar-user"]', config.sidenav.user === true);

        [
            ["data-skin", config.skin],
            ["data-bs-theme", config.theme],
            ["data-layout-position", config.layout.position],
            ["data-topbar-color", config.topbar.color],
            ["data-sidenav-color", config.sidenav.color],
            ["data-sidenav-size", config.sidenav.size],
        ].forEach(([name, val]) => {
            const el = select(name, val);
            if (el) el.checked = true;
        });
    }

    initSwitchListener() {
        const bindChange = (selector, handler) => {
            document
                .querySelectorAll(selector)
                .forEach((input) =>
                    input.addEventListener("change", () => handler(input)),
                );
        };

        // Bind skin related buttons
        document
            .querySelectorAll("button[data-skin]")
            .forEach((btn) =>
                btn.addEventListener("click", () =>
                    this.changeSkin(btn.getAttribute("data-skin")),
                ),
            );

        // Bind theme and layout related radios
        // bindChange('input[name="data-skin"]', input => this.changeSkin(input.value));
        bindChange('input[name="data-sidenav-color"]', (input) =>
            this.changeSidenavColor(input.value),
        );
        bindChange('input[name="data-sidenav-size"]', (input) =>
            this.changeSidenavSize(input.value),
        );
        bindChange('input[name="data-bs-theme"]', (input) =>
            this.changeTheme(input.value),
        );
        bindChange('input[name="data-layout-position"]', (input) =>
            this.changeLayoutPosition(input.value),
        );
        bindChange('input[name="data-topbar-color"]', (input) =>
            this.changeTopbarColor(input.value),
        );
        bindChange('input[name="sidebar-user"]', (input) =>
            this.changeSidenavUser(input.checked),
        );

        const themeToggle = document.getElementById("light-dark-mode");
        if (themeToggle) {
            themeToggle.addEventListener("click", () => {
                const newTheme =
                    this.config.theme === "light" ? "dark" : "light";
                this.changeTheme(newTheme);
            });
        }

        const resetBtn = document.querySelector("#reset-layout");
        if (resetBtn) {
            resetBtn.addEventListener("click", () => this.resetTheme());
        }

        const closeBtn = document.querySelector(".button-close-offcanvas");
        if (closeBtn) {
            closeBtn.addEventListener("click", () => {
                this.html.classList.remove("sidebar-enable");
                this.hideBackdrop();
            });
        }

        document.querySelectorAll(".button-collapse-toggle").forEach((el) => {
            el.addEventListener("click", () => {
                const current = this.html.getAttribute("data-sidenav-size");

                if (current === "offcanvas") {
                    this.showBackdrop();
                    this.html.classList.toggle("sidebar-enable");
                    return;
                }

                this.changeSidenavSize(
                    { default: "collapse", collapse: "default" }[current],
                    true,
                );
            });
        });
    }

    showBackdrop() {
        const backdrop = document.createElement("div");

        const getScrollbarWidth = () => {
            const container = document.createElement("div");
            container.style.visibility = "hidden";
            container.style.overflow = "scroll";
            container.style.width = "100px";
            container.style.height = "100px";
            document.body.appendChild(container);

            const inner = document.createElement("div");
            inner.style.width = "100%";
            container.appendChild(inner);

            const scrollbarWidth =
                container.offsetWidth - container.clientWidth;

            document.body.removeChild(container);
            return scrollbarWidth;
        };

        backdrop.id = "custom-backdrop";
        backdrop.className = "offcanvas-backdrop fade show";
        document.body.appendChild(backdrop);
        document.body.style.overflow = "hidden";
        document.body.style.paddingRight = `${getScrollbarWidth()}px`;

        backdrop.addEventListener("click", () => {
            this.html.classList.remove("sidebar-enable");
            this.hideBackdrop();
        });
    }

    hideBackdrop() {
        const backdrop = document.getElementById("custom-backdrop");
        if (backdrop) {
            document.body.removeChild(backdrop);
            document.body.style.overflow = "";
            document.body.style.paddingRight = "";
        }
    }

    _adjustLayout() {
        const width = window.innerWidth;
        const size = this.config.sidenav.size;

        if (width <= 1199) {
            this.changeSidenavSize("offcanvas", false);
        } else if (this._isSidenavCollapsePage()) {
            this.changeSidenavSize("collapse", false);
        } else {
            this.changeSidenavSize(size);
        }
    }

    initWindowSize() {
        window.addEventListener("resize", () => this._adjustLayout());
    }

    // Custom sidenav size
    _isSidenavCollapsePage() {
        const sidenavCollapsePage = [
            window.location.pathname.startsWith(
                "/inventory/blood-stock/detail/",
            ),
            window.location.pathname.startsWith("/blood-transfusion/"),
        ];
        return sidenavCollapsePage;
    }
}