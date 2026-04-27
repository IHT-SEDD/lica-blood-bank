/**
 * Template Name: Simple - Responsive Admin & Dashboard Template
 * By (Author): Coderthemes
 * Module/App (File Name): Main App JS File
 * Version: 3.0.0
 */

//
// ------------------------------ Required main scripts ------------------------------
//
import $ from "jquery";
window.$ = $;
window.jQuery = $;

import bootstrap from "bootstrap/dist/js/bootstrap";
window.bootstrap = bootstrap;

import { createIcons, icons } from "lucide";
import { Chart } from "chart.js/auto";

import ChartDataLabels from "chartjs-plugin-datalabels";
Chart.register(ChartDataLabels);

import "simplebar";
import flatpickr from "flatpickr";

// Import library datatable bootstrap 5 utama
import DataTable from "datatables.net-bs5";
import "datatables.net-bs5";

// Import library datatable buttons
import "datatables.net-buttons";
import "datatables.net-buttons-bs5";
import "datatables.net-buttons/js/buttons.html5.js";
import "datatables.net-buttons/js/buttons.print.js";

// Import library datatable responsive
import "datatables.net-responsive-bs5";
import "datatables.net-responsive";

// Import library datatable select
import "datatables.net-select";
import "datatables.net-select-bs5";

import "jszip/dist/jszip.min.js";
import "pdfmake/build/pdfmake.js";
import "pdfmake/build/vfs_fonts.js";

// Import choices select
import Choices from "choices.js";

// Import notyf
import { Notyf } from "notyf";
window.notyf = new Notyf({
    duration: 1000,
    position: {
        x: "right",
        y: "top",
    },
});

// Common
class App {
    init() {
        this.initComponents();
        this.initPreloader();
        this.initPortletCard();
        this.initMultiDropdown();
        // this.initFormValidation();
        this.initCounter();
        this.initToggle();
        this.initPassword();
        this.initDismissible();
        this.initSidenav();
        this.initTitleTextAnimation();
    }

    // Bootstrap Components
    initComponents() {
        createIcons({ icons });

        // Popovers
        document
            .querySelectorAll('[data-bs-toggle="popover"]')
            .forEach((el) => {
                new bootstrap.Popover(el);
            });

        // Tooltips
        document
            .querySelectorAll('[data-bs-toggle="tooltip"]')
            .forEach((el) => {
                new bootstrap.Tooltip(el);
            });

        // Offcanvas
        document.querySelectorAll(".offcanvas").forEach((el) => {
            new bootstrap.Offcanvas(el);
        });

        // Toasts
        document.querySelectorAll(".toast").forEach((el) => {
            new bootstrap.Toast(el);
        });
    }

    // Preloader
    initPreloader() {
        window.addEventListener("load", () => {
            const status = document.getElementById("status");
            const preloader = document.getElementById("preloader");
            if (status) status.style.display = "none";
            if (preloader) {
                setTimeout(() => (preloader.style.display = "none"), 350);
            }
        });
    }

    // Portlet Widget (Card Reload, Collapse, and Delete)
    initPortletCard() {
        // Handle card close
        $('[data-action="card-close"]').on("click", function (event) {
            event.preventDefault();

            const $card = $(this).closest(".card");
            $card.fadeOut(300, function () {
                $card.remove();
            });
        });

        // Handle card toggle
        $('[data-action="card-toggle"]').on("click", function (event) {
            event.preventDefault();

            const $card = $(this).closest(".card");
            const $icon = $(this).find("i").eq(0);
            const $body = $card.find(".card-body");
            const $footer = $card.find(".card-footer");

            $body.slideToggle(300);
            $footer.slideToggle(200);
            $icon.toggleClass("ti-chevron-up ti-chevron-down");
            $card.toggleClass("card-collapse");
        });

        // Handle card refresh
        const refreshButtons = document.querySelectorAll(
            '[data-action="card-refresh"]',
        );
        if (refreshButtons) {
            refreshButtons.forEach(function (button) {
                button.addEventListener("click", function (event) {
                    event.preventDefault();

                    const card = event.target.closest(".card");
                    const cardBody = card.querySelector(".card-body");

                    // Ensure .card-body has relative positioning
                    cardBody.style.position = "relative";

                    let overlay = cardBody.querySelector(".card-overlay");
                    if (!overlay) {
                        overlay = document.createElement("div");
                        overlay.classList.add("card-overlay");

                        const spinner = document.createElement("div");
                        spinner.classList.add("spinner-border", "text-primary");

                        overlay.appendChild(spinner);
                        cardBody.appendChild(overlay);
                    }

                    overlay.style.display = "flex";

                    setTimeout(function () {
                        overlay.style.display = "none";
                    }, 1500);
                });
            });
        }

        // Handle code preview collapse
        $('[data-action="code-collapse"]').on("click", function (event) {
            event.preventDefault();

            const $card = $(this).closest(".card");
            const $icon = $(this).find("i").eq(0);
            const $codeBody = $card.find(".code-body");

            $codeBody.slideToggle(300);
            $icon.toggleClass("ti-chevron-up ti-chevron-down");
        });
    }

    //  Multi Dropdown
    initMultiDropdown() {
        $(".dropdown-menu a.dropdown-toggle").on("click", function () {
            const dropdown = $(this).next(".dropdown-menu");
            const otherDropdown = $(this)
                .parent()
                .parent()
                .find(".dropdown-menu")
                .not(dropdown);
            otherDropdown.removeClass("show");
            otherDropdown.parent().find(".dropdown-toggle").removeClass("show");
            return false;
        });
    }

    // Form Validation
    // initFormValidation() {
    //     // Example starter JavaScript for disabling form submissions if there are invalid fields
    //     // Fetch all the forms we want to apply custom Bootstrap validation styles to
    //     // Loop over them and prevent submission
    //     document.querySelectorAll(".needs-validation").forEach((form) => {
    //         form.addEventListener(
    //             "submit",
    //             (event) => {
    //                 if (!form.checkValidity()) {
    //                     event.preventDefault();
    //                     event.stopPropagation();
    //                 }

    //                 form.classList.add("was-validated");
    //             },
    //             false,
    //         );
    //     });
    // }

    // Counter for Numbers
    initCounter() {
        const counters = document.querySelectorAll("[data-target]");

        const observer = new IntersectionObserver(
            (entries, observer) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        const counter = entry.target;

                        // Parse the target value, removing any commas first
                        let target = counter
                            .getAttribute("data-target")
                            .replace(/,/g, "");

                        target = parseFloat(target); // Convert to float

                        let current = 0; // Initial counter value

                        let increment; // Increment step for smooth animation

                        if (Number.isInteger(target)) {
                            increment = Math.floor(target / 25); // Increment for integer values
                        } else {
                            increment = target / 25; // Increment for float values
                        }

                        const updateCounter = () => {
                            if (current < target) {
                                current += increment;
                                if (current > target) current = target; // Avoid overshooting
                                // Format as integer or decimal and add commas
                                counter.innerText = formatNumber(current);
                                requestAnimationFrame(updateCounter); // Continue animation frame by frame
                            } else {
                                counter.innerText = formatNumber(target); // Final display
                            }
                        };

                        updateCounter(); // Start the animation

                        // Function to format numbers with commas and decimal places if necessary
                        function formatNumber(num) {
                            if (num % 1 === 0) {
                                // Format as integer with commas
                                return num.toLocaleString();
                            } else {
                                // Format as float with two decimal places and commas
                                return num.toLocaleString(undefined, {
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2,
                                });
                            }
                        }

                        observer.unobserve(counter);
                    }
                });
            },
            {
                threshold: 1, // Adjust this threshold to control when to trigger (e.g., 0.5 means 50% of the element is visible)
            },
        );

        counters.forEach((counter) => observer.observe(counter));
    }

    // Toggle logic based on data attributes
    initToggle() {
        document.querySelectorAll("[data-toggler]").forEach((wrapper) => {
            const toggleOn = wrapper.querySelector("[data-toggler-on]");
            const toggleOff = wrapper.querySelector("[data-toggler-off]");
            let isOn = wrapper.getAttribute("data-toggler") === "on";

            const updateToggleState = () => {
                if (isOn) {
                    toggleOn?.classList.remove("d-none");
                    toggleOff?.classList.add("d-none");
                } else {
                    toggleOn?.classList.add("d-none");
                    toggleOff?.classList.remove("d-none");
                }
            };

            toggleOn?.addEventListener("click", () => {
                isOn = false;
                updateToggleState();
            });

            toggleOff?.addEventListener("click", () => {
                isOn = true;
                updateToggleState();
            });

            updateToggleState();
        });
    }

    // Password Show/Hide based on data attributes [data-password]
    initPassword() {
        document.querySelectorAll("[data-password]").forEach((element) => {
            const password = element.querySelector(".form-password");
            const eyeOn = element.querySelector(".password-eye-on");
            const eyeOff = element.querySelector(".password-eye-off");

            if (!password || !eyeOn || !eyeOff) return;

            // Initially show "eye-on" only
            eyeOff.classList.add("d-none");

            eyeOn.addEventListener("click", () => {
                password.type = "text";
                eyeOn.classList.add("d-none");
                eyeOff.classList.remove("d-none");
            });

            eyeOff.addEventListener("click", () => {
                password.type = "password";
                eyeOff.classList.add("d-none");
                eyeOn.classList.remove("d-none");
            });
        });
    }

    // Dismiss elements with [data-dismissible]
    initDismissible() {
        document.querySelectorAll("[data-dismissible]").forEach((trigger) => {
            trigger.addEventListener("click", () => {
                const selector = trigger.getAttribute("data-dismissible");
                const target = document.querySelector(selector);
                if (target) target.remove();
            });
        });
    }

    // Sidenav Link Activation
    initSidenav() {
        const sideNav = document.querySelector(".side-nav");
        if (!sideNav) return;

        // Prevent default toggle behavior
        sideNav
            .querySelectorAll("li [data-bs-toggle='collapse']")
            .forEach((toggle) => {
                toggle.addEventListener("click", (e) => e.preventDefault());
            });

        // Ensure only one collapse is open at a time
        const allCollapses = sideNav.querySelectorAll("li .collapse");
        allCollapses.forEach((collapse) => {
            collapse.addEventListener("show.bs.collapse", (event) => {
                const currentCollapse = event.target;

                const ancestors = [];
                let el = currentCollapse.parentElement;
                while (el && el !== sideNav) {
                    if (el.classList.contains("collapse")) {
                        ancestors.push(el);
                    }
                    el = el.parentElement;
                }

                allCollapses.forEach((other) => {
                    if (
                        other !== currentCollapse &&
                        !ancestors.includes(other)
                    ) {
                        new bootstrap.Collapse(other, { toggle: false }).hide();
                    }
                });
            });
        });

        // Get current page
        const currentUrl = window.location.href.split(/[?#]/)[0];
        const currentPage = window.location.pathname.split("/").pop();

        const allLinks = sideNav.querySelectorAll(".side-nav-link[href]");
        allLinks.forEach((link) => {
            const linkHref = link.getAttribute("href");
            if (!linkHref) return;

            const match = linkHref === currentPage || link.href === currentUrl;
            if (!match) return;

            // Clear previous active/show states
            sideNav
                .querySelectorAll("a.active, li.active, .collapse.show")
                .forEach((el) => {
                    el.classList.remove("active", "show");
                });

            // Mark link and <li> active
            link.classList.add("active");
            const li = link.closest("li.side-nav-item");
            if (li) li.classList.add("active");

            // Recursively walk up and show all parent collapses
            let parent = link.closest("li");
            while (parent && parent !== sideNav) {
                parent.classList.add("active");

                const parentCollapse = parent.closest(".collapse");
                if (parentCollapse) {
                    parentCollapse.classList.add("show");

                    const toggleLink = sideNav.querySelector(
                        `a[href="#${parentCollapse.id}"]`,
                    );
                    if (toggleLink) {
                        toggleLink.setAttribute("aria-expanded", "true");
                        const parentLi = toggleLink.closest("li.side-nav-item");
                        if (parentLi) parentLi.classList.add("active");
                    }

                    parent = parentCollapse.closest("li");
                } else {
                    parent = parent.parentElement;
                }
            }
        });

        // Auto-scroll to active item
        setTimeout(() => {
            const activeItem = sideNav.querySelector("li.active .active");
            const scrollContainer = document.querySelector(
                ".sidenav-menu .simplebar-content-wrapper",
            );

            if (activeItem && scrollContainer) {
                const offset = activeItem.offsetTop - 300;
                if (offset > 100) {
                    scrollToPosition(scrollContainer, offset, 600);
                }
            }
        }, 200);

        // Smooth scroll utility
        function scrollToPosition(element, to, duration) {
            const start = element.scrollTop;
            const change = to - start;
            const increment = 20;
            let currentTime = 0;

            function animateScroll() {
                currentTime += increment;
                element.scrollTop = easeInOutQuad(
                    currentTime,
                    start,
                    change,
                    duration,
                );
                if (currentTime < duration) {
                    setTimeout(animateScroll, increment);
                }
            }

            animateScroll();
        }

        function easeInOutQuad(t, b, c, d) {
            t /= d / 2;
            if (t < 1) return (c / 2) * t * t + b;
            t--;
            return (-c / 2) * (t * (t - 2) - 1) + b;
        }
    }

    // Title Text Animation
    initTitleTextAnimation() {
        const originalTitle = document.title;
        const fullTitle = originalTitle + " - LICA Blood Bank | ";
        let scrollIndex = 0;
        let animationId;

        function scrollTitle() {
            if (!document.hidden) {
                document.title =
                    fullTitle.slice(scrollIndex) +
                    fullTitle.slice(0, scrollIndex);
                scrollIndex = (scrollIndex + 1) % fullTitle.length;
                animationId = setTimeout(scrollTitle, 100);
            }
        }

        function handleVisibilityChange() {
            if (document.hidden) {
                clearTimeout(animationId);
                document.title = originalTitle; // Restore full title
            } else {
                scrollTitle(); // Restart animation
            }
        }

        document.addEventListener("visibilitychange", handleVisibilityChange);

        // Start animation if tab is visible
        if (!document.hidden) {
            scrollTitle();
        }
    }
}

// Layout Customizer
class LayoutCustomizer {
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
        } else {
            this.changeSidenavSize(size);
        }
    }

    initWindowSize() {
        window.addEventListener("resize", () => this._adjustLayout());
    }
}

// If you need only theme toggler not whole layout customizer, you can use this.
// Note: If you are using this, comment or remove LayoutCustomizer.

// const themeToggle = document.getElementById('light-dark-mode');
// if (themeToggle) {
//     const html = document.documentElement;
//
//     const storageKey = '__Simple_CONFIG__';
//     const savedConfig = sessionStorage.getItem(storageKey);
//     const config = savedConfig ? JSON.parse(savedConfig) : {
//         theme: html.getAttribute('data-bs-theme') || 'light'
//     };
//
//     themeToggle.addEventListener('click', () => {
//         const newTheme = config.theme === 'light' ? 'dark' : 'light';
//         config.theme = newTheme;
//         html.setAttribute('data-bs-theme', newTheme);
//         sessionStorage.setItem(storageKey, JSON.stringify(config));
//     });
// }

//
// ------------------------------ Optional scripts / plugin scripts ------------------------------
//

class Plugins {
    init() {
        // comment or remove plugins you don't need
        this.initTouchSpin();
    }

    // Touchspin: plus/minus increment controls
    initTouchSpin() {
        document.querySelectorAll("[data-touchspin]").forEach((spin) => {
            const minusBtn = spin.querySelector("[data-minus]");
            const plusBtn = spin.querySelector("[data-plus]");
            const input = spin.querySelector("input");

            if (input) {
                const min = Number(input.min);
                const max = Number(input.max ?? 0);
                const hasMin = Number.isFinite(min);
                const hasMax = Number.isFinite(max);

                const getValue = () => Number.parseInt(input.value) || 0;

                const isReadonly = () => input.hasAttribute("readonly");

                if (!isReadonly()) {
                    if (minusBtn)
                        minusBtn.addEventListener("click", () => {
                            let newVal = getValue() - 1;
                            if (!hasMin || newVal >= min) {
                                input.value = newVal.toString();
                            }
                        });

                    if (plusBtn)
                        plusBtn.addEventListener("click", () => {
                            let newVal = getValue() + 1;
                            if (!hasMax || newVal <= max) {
                                input.value = newVal.toString();
                            }
                        });
                }
            }
        });
    }
}

class I18nManager {
    constructor({
        defaultLang = "en",
        langPath = "/data/translations/",
        langImageSelector = "#selected-language-image",
        langCodeSelector = "#selected-language-code",
        translationKeySelector = "[data-lang]",
        translationKeyAttribute = "data-lang",
        languageSelector = "[data-translator-lang]",
    } = {}) {
        this.selectedLanguage =
            sessionStorage.getItem("__Simple_LANG__") || defaultLang;
        this.langPath = langPath;
        this.langImageSelector = langImageSelector;
        this.langCodeSelector = langCodeSelector;
        this.translationKeySelector = translationKeySelector;
        this.translationKeyAttribute = translationKeyAttribute;
        this.languageSelector = languageSelector;

        this.selectedLanguageImage = document.querySelector(
            this.langImageSelector,
        );
        this.selectedLanguageCode = document.querySelector(
            this.langCodeSelector,
        );
        this.languageButtons = document.querySelectorAll(this.languageSelector);
    }

    async init() {
        await this.applyTranslations();
        this.updateSelectedImage();
        this.updateSelectedCode();
        this.bindLanguageSwitchers();
    }

    async loadTranslations() {
        try {
            const response = await fetch(
                `${this.langPath}${this.selectedLanguage}.json`,
            );
            if (!response.ok)
                throw new Error(`Failed to load ${this.selectedLanguage}.json`);
            return await response.json();
        } catch (err) {
            console.error("Translation load error:", err);
            return {};
        }
    }

    async applyTranslations() {
        const translations = await this.loadTranslations();

        const getNestedValue = (obj, keyPath) => {
            return keyPath
                .split(".")
                .reduce((acc, key) => acc?.[key] ?? null, obj);
        };

        document.querySelectorAll(this.translationKeySelector).forEach((el) => {
            const key = el.getAttribute(this.translationKeyAttribute);
            const value = getNestedValue(translations, key);
            if (value) {
                el.innerHTML = value;
            } else {
                console.warn(`Missing translation for key: ${key}`);
            }
        });
    }

    setLanguage(lang) {
        this.selectedLanguage = lang;
        localStorage.setItem("__Simple_LANG__", lang);
        this.applyTranslations();
        this.updateSelectedImage();
        this.updateSelectedCode();
    }

    updateSelectedImage() {
        const activeImage = document.querySelector(
            `[data-translator-lang="${this.selectedLanguage}"] [data-translator-image]`,
        );
        if (activeImage && this.selectedLanguageImage) {
            this.selectedLanguageImage.src = activeImage.src;
        }
    }

    updateSelectedCode() {
        if (this.selectedLanguageCode) {
            this.selectedLanguageCode.textContent =
                this.selectedLanguage.toUpperCase();
        }
    }

    bindLanguageSwitchers() {
        this.languageButtons.forEach((button) => {
            button.addEventListener("click", () => {
                const lang = button.dataset.translatorLang;
                if (lang && lang !== this.selectedLanguage) {
                    this.setLanguage(lang);
                }
            });
        });
    }
}

document.addEventListener("DOMContentLoaded", function (e) {
    new App().init();
    new LayoutCustomizer().init();
    new Plugins().init();
    new I18nManager().init();
});

//
// ------------------------------ Required helpers ------------------------------
//

// Chart Color
export const ins = (v, a = 1) => {
    const val = getComputedStyle(document.documentElement)
        .getPropertyValue(`--ins-${v}`)
        .trim();
    return v.includes("-rgb") ? `rgba(${val}, ${a})` : val;
};

// Debounce function for performance
export function debounce(func, wait) {
    let timeout;
    return function () {
        clearTimeout(timeout);
        timeout = setTimeout(func, wait);
    };
}

// Updating charts on skin and theme change

// For ChartJs
export class CustomChartJs {
    static instances = [];

    constructor({ selector, options = () => ({}) }) {
        if (!selector) {
            console.warn("CustomChartJs: 'selector' is required.");
            return;
        }

        this.selector = selector;
        this.getOptions =
            typeof options === "function" ? options : () => options;
        this.element = null;
        this.chart = null;

        try {
            this.render();
            CustomChartJs.instances.push(this);
        } catch (err) {
            console.error("CustomChartJs: Initialization error", err);
        }
    }

    static getDefaultOptions() {
        const bodyFont = getComputedStyle(document.body).fontFamily.trim();

        return {
            responsive: true,
            maintainAspectRatio: false,
            layout: {
                padding: {
                    top: -10,
                },
            },
            scales: {
                x: {
                    ticks: {
                        font: { family: bodyFont },
                        color: ins("secondary-color"),
                        display: true,
                        drawTicks: true,
                    },
                    grid: {
                        display: false,
                        drawBorder: false,
                    },
                    border: {
                        display: false, // Hides bottom X axis line
                    },
                },
                y: {
                    ticks: {
                        font: { family: bodyFont },
                        color: ins("secondary-color"),
                    },
                    grid: {
                        display: true, // Keeps horizontal lines
                        drawBorder: false, // Hides Y axis border line
                        color: ins("chart-border-color"),
                        lineWidth: 1,
                    },
                    border: {
                        display: false, // Hides Y axis line (left)
                        dash: [5, 5],
                    },
                },
            },
            plugins: {
                legend: {
                    display: true,
                    position: "top",
                    labels: {
                        font: { family: bodyFont },
                        color: ins("secondary-color"),
                        usePointStyle: true, // Show circles instead of default box
                        pointStyle: "circle", // Circle shape
                        boxWidth: 8, // Circle size
                        boxHeight: 8, // (optional) same as width by default
                        padding: 15, // Space between legend items
                    },
                },
                tooltip: {
                    enabled: true,
                    titleFont: { family: bodyFont },
                    bodyFont: { family: bodyFont },
                },
            },
        };
    }

    render() {
        try {
            this.element =
                this.selector instanceof HTMLElement
                    ? this.selector
                    : document.querySelector(this.selector);

            if (!this.element) {
                console.warn(
                    `CustomChartJs: No element found for selector '${this.selector}'`,
                );
                return;
            }

            // Destroy existing chart instance if present
            if (this.chart) {
                this.chart.destroy();
            }

            const { type, data, options, plugins } = this.getOptions();

            // Merge dynamic default options with instance-specific options
            this.chart = new Chart(this.element, {
                type: type || "bar",
                data,
                options: {
                    ...structuredClone(CustomChartJs.getDefaultOptions()),
                    ...(options || {}),
                },
                plugins: plugins || [],
            });

            // Resize listener
            window.addEventListener(
                "resize",
                debounce(() => {
                    if (this.chart) {
                        this.chart.resize();
                    }
                }, 200),
            );
        } catch (err) {
            console.error(
                `CustomChartJs: Render error for '${this.selector}'`,
                err,
            );
        }
    }

    static rerenderAll() {
        for (const instance of CustomChartJs.instances) {
            instance.render();
        }
    }

    static reSizeAll() {
        for (const instance of CustomChartJs.instances) {
            if (instance.chart) {
                instance.chart.resize();
            }
        }
    }

    static destroyAll() {
        for (const instance of CustomChartJs.instances) {
            if (instance.chart) {
                instance.chart.destroy();
            }
        }
        CustomChartJs.instances = [];
    }
}

// Track instances
CustomChartJs.instances = [];

// Observe theme changes
const themeObserver = new MutationObserver(() => {
    CustomChartJs.rerenderAll();
});

themeObserver.observe(document.documentElement, {
    attributes: true,
    attributeFilter: ["data-skin", "data-bs-theme"],
});

// Observe menu size changes
const menuObserver = new MutationObserver(() => {
    requestAnimationFrame(() => {
        CustomChartJs.reSizeAll();
    });
});

menuObserver.observe(document.documentElement, {
    attributes: true,
    attributeFilter: ["data-sidenav-size"],
});

// ------------------------------ Advance Datatable for global config :begin ------------------------------
export class GlobalAdvanceDatatable {
    // Mulai constructor untuk global config datatable
    constructor(selector, options = {}) {
        // Ambil id berdasarkan selector untuk tableElement
        this.tableElement = document.querySelector(selector);

        // Return error jika selector datatable tidak ditemukan
        if (!this.tableElement) {
            console.error("DataTable element not found:", selector);
            return;
        }

        // Ambil status fitur show/hide column
        this.useHideColumn = options.useHideColumn === true;

        // Bikin default config untuk instance datatable
        let config = {
            processing: true,
            language: {
                paginate: {
                    first: '<i class="ti ti-chevrons-left"></i>',
                    previous: '<i class="ti ti-chevron-left"></i>',
                    next: '<i class="ti ti-chevron-right"></i>',
                    last: '<i class="ti ti-chevrons-right"></i>',
                },
                lengthMenu: "_MENU_ Entries per page",
                info: 'Showing <span class="fw-semibold">_START_</span> to <span class="fw-semibold">_END_</span> of <span class="fw-semibold">_TOTAL_</span> Entries',
            },
        };

        // Bikin config tambahan untuk instance jika useHideColumn true
        if (this.useHideColumn) {
            config.responsive = true;
            config.dom =
                "<'d-md-flex justify-content-between align-items-center mt-2 mb-3'<'columnToggleWrapper'>f>" +
                "rt" +
                "<'d-md-flex justify-content-between align-items-center mt-2'lp>";
        }

        // Bangun datatable instance dengan default config, serta gabungkan config option yang diterima
        this.instance = new DataTable(this.tableElement, {
            ...config,
            ...options,
        });

        // Simpan instance _datatable
        this.tableElement._datatable = this.instance;

        // Init toggle column setelah table ready
        if (this.useHideColumn) {
            this.initColumnToggle();
        }
    }

    // Ambil label column dari thead tabel HTML
    getColumnLabels() {
        const headers = this.tableElement.querySelectorAll("thead th");
        return Array.from(headers).map((th) => th.textContent.trim());
    }

    // Generate dropdown show/hide column
    initColumnToggle() {
        const columnLabels = this.getColumnLabels();

        const wrapper = document.querySelector(".columnToggleWrapper");
        if (!wrapper) return;

        const dropdown = document.createElement("div");
        dropdown.className = "dropdown";

        dropdown.innerHTML = `
            <button class="btn btn-sm btn-primary" type="button" data-bs-toggle="dropdown" data-bs-auto-close="outside">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye-off-icon lucide-eye-off"><path d="M10.733 5.076a10.744 10.744 0 0 1 11.205 6.575 1 1 0 0 1 0 .696 10.747 10.747 0 0 1-1.444 2.49"/><path d="M14.084 14.158a3 3 0 0 1-4.242-4.242"/><path d="M17.479 17.499a10.75 10.75 0 0 1-15.417-5.151 1 1 0 0 1 0-.696 10.75 10.75 0 0 1 4.446-5.143"/><path d="m2 2 20 20"/></svg>
            </button>
            <ul class="dropdown-menu" id="columnToggleMenu">
                ${columnLabels
                    .map(
                        (label, index) => `
                        <li class="dropdown-item">
                            <div class="form-check">
                                <input class="form-check-input toggle-vis" 
                                    type="checkbox" data-column="${index}" 
                                    id="colToggle${index}" checked>
                                <label class="form-check-label" for="colToggle${index}">
                                    ${label}
                                </label>
                            </div>
                        </li>
                    `,
                    )
                    .join("")}
            </ul>
        `;

        wrapper.appendChild(dropdown);

        // event toggle
        dropdown.addEventListener("change", (e) => {
            if (e.target.classList.contains("toggle-vis")) {
                const colIndex = parseInt(e.target.dataset.column, 10);
                const column = this.instance.column(colIndex);
                column.visible(e.target.checked);
            }
        });
    }
}
// ------------------------------ Advance Datatable for global config :end ------------------------------

// ------------------------------ Dynamic Datetime Formatter Config :begin ------------------------------
export class DateTimeFormatter {
    static format(date, format = "d M Y H:i") {
        if (!date) return "-";

        const d = new Date(date);

        const hours24 = d.getHours();
        const hours12 = hours24 % 12 || 12;
        const ampm = hours24 >= 12 ? "PM" : "AM";

        const map = {
            // Date
            d: String(d.getDate()).padStart(2, "0"),
            D: DateTimeFormatter.getWeekdayShort(d.getDay()),
            l: DateTimeFormatter.getWeekdayLong(d.getDay()),

            // Month
            m: String(d.getMonth() + 1).padStart(2, "0"),
            M: DateTimeFormatter.getMonthShort(d.getMonth()),
            F: DateTimeFormatter.getMonthLong(d.getMonth()),

            // Year
            Y: d.getFullYear(),
            y: String(d.getFullYear()).slice(-2),

            // 24h time
            H: String(hours24).padStart(2, "0"),
            G: String(hours24), // no leading zero

            // 12h time
            h: String(hours12).padStart(2, "0"),
            g: String(hours12), // no leading zero

            // Minutes & seconds
            i: String(d.getMinutes()).padStart(2, "0"),
            s: String(d.getSeconds()).padStart(2, "0"),

            // AM/PM
            A: ampm,
            a: ampm.toLowerCase(),
        };

        return format.replace(
            /d|D|l|m|M|F|Y|y|H|G|h|g|i|s|A|a/g,
            (match) => map[match],
        );
    }

    // ========================
    // PRESET HELPERS
    // ========================

    static human(date) {
        return this.format(date, "d M Y H:i");
    }

    static fullDate(date) {
        return this.format(date, "l, d F Y H:i");
    }

    static dateOnly(date) {
        return this.format(date, "d M Y");
    }

    static time24(date) {
        return this.format(date, "H:i");
    }

    static time12(date) {
        return this.format(date, "h:i A");
    }

    static datetime12(date) {
        return this.format(date, "d M Y h:i A");
    }

    static datetime24(date) {
        return this.format(date, "d M Y H:i");
    }

    // ========================
    // MONTHS
    // ========================

    static getMonthShort(index) {
        const months = [
            "Jan",
            "Feb",
            "Mar",
            "Apr",
            "May",
            "Jun",
            "Jul",
            "Aug",
            "Sep",
            "Oct",
            "Nov",
            "Dec",
        ];
        return months[index];
    }

    static getMonthLong(index) {
        const months = [
            "January",
            "February",
            "March",
            "April",
            "May",
            "June",
            "July",
            "August",
            "September",
            "October",
            "November",
            "December",
        ];
        return months[index];
    }

    // ========================
    // WEEKDAYS
    // ========================

    static getWeekdayShort(index) {
        const days = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
        return days[index];
    }

    static getWeekdayLong(index) {
        const days = [
            "Sunday",
            "Monday",
            "Tuesday",
            "Wednesday",
            "Thursday",
            "Friday",
            "Saturday",
        ];
        return days[index];
    }
}
// ------------------------------ Dynamic Datetime Formatter Config :end ------------------------------

// ------------------------------ Advance Flatpickr for global config :begin ------------------------------
export class GlobalAdvanceFlatpickr {
    // Mulai constructor untuk global config flatpickr
    constructor(selector, options = {}) {
        // Ambil selector HTML untuk element
        this.elements = document.querySelectorAll(selector);

        // Lempar error jika element tidak ditemukan
        if (!this.elements.length) {
            console.error("Flatpickr element not found:", selector);
            return;
        }

        // Ambil options dari client
        this.options = options;
        // Bikin init untuk instance
        this.init();
    }

    // Bangun init untuk flatpickr
    init() {
        // Terapkan config pada tiap element
        this.elements.forEach((item) => {
            // Ambil tipe flatpickr dari attribut data-provider
            const type = item.getAttribute("data-provider");
            const attrs = item.attributes;

            // Bikin default config
            let config = {
                disableMobile: true,
                defaultDate: new Date(),
            };

            // ---------- FLATPICKR ----------
            if (type === "flatpickr") {
                if (attrs["data-date-format"])
                    config.dateFormat = attrs["data-date-format"].value;

                if (attrs["data-enable-time"]) {
                    config.enableTime = true;
                    config.dateFormat = (config.dateFormat || "Y-m-d") + " H:i";
                }

                if (attrs["data-altformat"]) {
                    config.altInput = true;
                    config.altFormat = attrs["data-altformat"].value;
                }

                if (attrs["data-mindate"])
                    config.minDate = attrs["data-mindate"].value;

                if (attrs["data-maxdate"])
                    config.maxDate = attrs["data-maxdate"].value;

                if (attrs["data-default-date"])
                    config.defaultDate = attrs["data-default-date"].value;

                if (attrs["data-multiple-date"]) config.mode = "multiple";

                if (attrs["data-range-date"]) config.mode = "range";

                if (attrs["data-inline-date"]) {
                    config.inline = true;
                    config.defaultDate = attrs["data-default-date"]?.value;
                }

                if (attrs["data-disable-date"]) {
                    config.disable =
                        attrs["data-disable-date"].value.split(",");
                }

                if (attrs["data-week-number"]) {
                    config.weekNumbers = true;
                }

                // ---------- merge event dari luar ----------
                config = {
                    ...config,
                    ...this.options,
                };

                const instance = flatpickr(item, config);

                // simpan instance
                item._flatpickrInstance = instance;
            }

            // ---------- TIMEPICKR ----------
            else if (type === "timepickr") {
                let configTime = {
                    enableTime: true,
                    noCalendar: true,
                    dateFormat: "H:i",
                    defaultDate: new Date(),
                };

                if (attrs["data-time-hrs"]) configTime.time_24hr = true;
                if (attrs["data-min-time"])
                    configTime.minTime = attrs["data-min-time"].value;
                if (attrs["data-max-time"])
                    configTime.maxTime = attrs["data-max-time"].value;
                if (attrs["data-default-time"])
                    configTime.defaultDate = attrs["data-default-time"].value;
                if (attrs["data-time-inline"]) {
                    configTime.inline = true;
                    configTime.defaultDate = attrs["data-time-inline"].value;
                }

                configTime = {
                    ...configTime,
                    ...this.options,
                };

                const instance = flatpickr(item, configTime);

                item._flatpickrInstance = instance;
            }
        });
    }
}
// ------------------------------ Advance Flatpickr for global config :end ------------------------------

// ------------------------------ Global Config for submit data Form :begin ------------------------------
export class GlobalSubmitForm {
    // Mulai constructor untuk global config submit data Form
    constructor({
        formId,
        url,
        method = "POST",
        onSuccess = null,
        onError = null,
        beforeSubmit = null,
        resetOnSuccess = null,
        validator = null,
    }) {
        this.form = document.getElementById(formId);
        this.url = url;
        this.method = method;
        this.onSuccess = onSuccess;
        this.onError = onError;
        this.beforeSubmit = beforeSubmit;
        this.resetOnSuccess = resetOnSuccess;
        this.validator = validator;

        // Lempar error jika form tidak ditemukan
        if (!this.form) {
            console.error(`Form with ID ${formId} not found`);
            return;
        }

        this.init();
    }

    // Mulai init instance untuk global config submit data Form
    init() {
        this.form.addEventListener("submit", async (e) => {
            e.preventDefault();

            // Batalkan submit jika validasi gagal
            if (this.validator) {
                const status = await this.validator.validate();
                if (status !== "Valid") {
                    return;
                }
            }

            // Bikin variabel formdata dari form selector
            let formData = new FormData(this.form);

            // handle checkbox (biar konsisten 1 / 0)
            this.form
                .querySelectorAll('input[type="checkbox"]')
                .forEach((el) => {
                    formData.set(el.name, el.checked ? 1 : 0);
                });

            // hook sebelum submit
            if (this.beforeSubmit) {
                formData = this.beforeSubmit(formData) || formData;
            }

            // Panggil instance submit data
            this.submit(formData);
        });
    }

    // Mulai submit instance untuk global config submit data Form
    submit(formData) {
        if (this.method !== "POST") {
            formData.append("_method", this.method);
        }

        fetch(typeof this.url === "function" ? this.url() : this.url, {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": document
                    .querySelector('meta[name="csrf-token"]')
                    ?.getAttribute("content"),
                Accept: "application/json",
            },
            body: formData,
        })
            .then(async (res) => {
                const data = await res.json();

                if (!res.ok) {
                    throw data;
                }

                return data;
            })
            .then((data) => {
                if (this.onSuccess) this.onSuccess(data);

                if (this.resetOnSuccess) {
                    this.form.reset();

                    this.form.querySelectorAll("select").forEach((el) => {
                        if (el.tomselect) {
                            el.tomselect.clear();
                        }
                    });
                }
            })
            .catch((err) => {
                if (this.onError) this.onError(err);
                else console.error("GlobalSubmitForm Error:", err);
            });
    }
}
// ------------------------------ Global Config for submit data Form :end ------------------------------

// ------------------------------ Global Config for Form Validation :begin ------------------------------
export class GlobalFormValidation {
    // ------------------------------ Mulai static init ------------------------------
    static init(formSelector, rules) {
        // ------------------------------ Ambil form element dengan selector ------------------------------
        const form = document.querySelector(formSelector);

        // ------------------------------ Return error jika form tidak ditemukan ------------------------------
        if (!form) {
            console.error(`Form ${formSelector} not found`);
            return;
        }

        return {
            validate: () => {
                let isValid = true;

                // ------------------------------ Hapus semua class is-invalid terlebih dahulu ------------------------------
                form.querySelectorAll(".is-invalid").forEach((el) => {
                    el.classList.remove("is-invalid");
                });
                form.querySelectorAll(".ts-wrapper.is-invalid").forEach(
                    (el) => {
                        el.classList.remove("is-invalid");
                    },
                );

                // ------------------------------ Hapus semua elemen invalid-feedback terlebih dahulu ------------------------------
                form.querySelectorAll(".invalid-feedback").forEach((el) => {
                    el.remove();
                });

                // ------------------------------ Loop tiap field untuk menambahkan validasi ------------------------------
                Object.keys(rules).forEach((fieldName) => {
                    const input = form.querySelector(`[name="${fieldName}"]`);
                    if (!input) return;

                    // ------------------------------ Hapus validasi jika inputan berubah ------------------------------
                    input.addEventListener("input", () => {
                        input.classList.remove("is-invalid");
                        const feedback =
                            input.parentNode.querySelector(".invalid-feedback");
                        if (feedback) feedback.remove();
                    });

                    // ------------------------------ Hapus validasi pada inputan tom-select jika ada perubahan ------------------------------
                    if (input.classList.contains("tomselected")) {
                        input.addEventListener("change", () => {
                            const wrapper = input.closest(".ts-wrapper");
                            if (wrapper) wrapper.classList.remove("is-invalid");

                            input.classList.remove("is-invalid");

                            const feedback =
                                input.parentNode.querySelector(
                                    ".invalid-feedback",
                                );
                            if (feedback) feedback.remove();
                        });
                    }

                    // ------------------------------ Bikin variabel value inputa & peraturan validasi inputan ------------------------------
                    const value = input.value.trim();
                    const fieldRules = rules[fieldName].validators;

                    for (let rule in fieldRules) {
                        // ------------------------------ Bikin variabel pesan error ------------------------------
                        let message = fieldRules[rule].message;

                        // ------------------------------ Tampilkan error jika value input kosong ------------------------------
                        if (rule === "notEmpty" && value === "") {
                            this.showError(input, message);
                            isValid = false;
                            break;
                        }

                        // ------------------------------ Tampilkan error jika panjang value input tidak sesuai ------------------------------
                        if (rule === "stringLength") {
                            const min = fieldRules[rule].min || 0;
                            if (value.length < min) {
                                this.showError(input, message);
                                isValid = false;
                                break;
                            }
                        }

                        // ------------------------------ Tampilkan error jika value inputan bukan angka ------------------------------
                        if (rule === "isNumber") {
                            if (value === "" || isNaN(value)) {
                                this.showError(input, message);
                                isValid = false;
                                break;
                            }
                        }
                    }
                });

                // ------------------------------ Kembalikan valid atau tidak ------------------------------
                return isValid ? "Valid" : "Invalid";
            },
        };
    }

    // ------------------------------ Mulai static showError ------------------------------
    static showError(input, message) {
        let target = input;

        // ------------------------------ Tambahkan class is-invalid pada input tom-select ------------------------------
        if (input.classList.contains("tomselected")) {
            const wrapper = input.nextElementSibling;
            if (wrapper && wrapper.classList.contains("ts-wrapper")) {
                wrapper.classList.add("is-invalid");
                target = wrapper;
            }
        } else {
            // ------------------------------ Tambahkan class is-invalid pada input normal yang error ------------------------------
            input.classList.add("is-invalid");
        }

        // ------------------------------ Buat div dengan class invalid-feedback untuk menampilkan pesan ------------------------------
        const div = document.createElement("div");
        div.className = "invalid-feedback";
        div.innerText = message;

        // ------------------------------ Letakkan didalam div pembungkus inputan ------------------------------
        input.parentNode.appendChild(div);
    }
}
// ------------------------------ Global Config for Form Validation :end ------------------------------

// ------------------------------ Global Config for Delete Data Confirmation :begin ------------------------------
export class GlobalDeleteDataConfirmation {
    // ------------------------------ Buat constructor untuk terima option dari client js ------------------------------
    constructor(options = {}) {
        this.buttonSelector = options.ButtonSelector || ".btn-delete";
        this.dataAttributeID = options.DataAttributeID || "id";
        this.urlFetchData = options.UrlFetchData || null;
        this.modalConfirmID = options.ModalConfirmID || null;

        // ------------------------------ Panggil init ------------------------------
        this.init();
    }

    // ------------------------------ Mulai init ------------------------------
    init() {
        document.addEventListener("click", async (e) => {
            // ------------------------------ Cari tombol berdasarkan selector ------------------------------
            const btn = e.target.closest(this.buttonSelector);
            if (!btn) return;

            // ------------------------------ Cari data attribute dari tombol ------------------------------
            const deleteId = btn.dataset[this.dataAttributeID];
            if (!deleteId) {
                if (window.notyf) {
                    notyf.error({ message: "ID Data Not Found!" });
                }
                return;
            }

            // ------------------------------ Buat variabel untuk menampung data ------------------------------
            let data = null;

            try {
                // ------------------------------ Kondisi jika membutuhkan fetch data ------------------------------
                if (this.urlFetchData) {
                    const url =
                        typeof this.urlFetchData === "function"
                            ? this.urlFetchData(deleteId)
                            : this.urlFetchData.replace(":id", deleteId);

                    const res = await fetch(url);
                    const json = await res.json();
                    data = json.data;
                }

                // ------------------------------ Simpan instance biar bisa diakses dari luar ------------------------------
                this.currentData = data;
                this.currentId = deleteId;

                // ------------------------------ Trigger event biar client bisa handle sendiri ------------------------------
                document.dispatchEvent(
                    new CustomEvent("delete:open", {
                        detail: {
                            data,
                            id: deleteId,
                            button: btn,
                        },
                    }),
                );

                // ------------------------------ Tampilkan modal ------------------------------
                if (this.modalConfirmID) {
                    const modalEl = document.getElementById(
                        this.modalConfirmID,
                    );

                    if (modalEl) {
                        const modal = new bootstrap.Modal(modalEl);
                        modal.show();
                    }
                }
            } catch (err) {
                if (window.notyf) {
                    notyf.error({ message: "Failed to load data!" });
                }
                console.error(err);
            }
        });
    }
}
// ------------------------------ Global Config for Delete Data Confirmation :end ------------------------------

// ------------------------------ Global Config for Edit Data :begin ------------------------------
export class GlobalEditData {
    constructor(options = {}) {
        this.buttonSelector = options.ButtonSelector || ".btn-edit";
        this.dataAttributeID = options.DataAttributeID || "id";
        this.urlFetchData = options.UrlFetchData || null;
        this.modalEditID = options.ModalEditID || null;
        this.formSelector = options.FormSelector || null;

        this.init();
    }

    init() {
        document.addEventListener("click", async (e) => {
            const btn = e.target.closest(this.buttonSelector);
            if (!btn) return;

            const editId = btn.dataset[this.dataAttributeID];

            if (!editId) {
                if (window.notyf) {
                    notyf.error({ message: "ID Data Not Found!" });
                }
                return;
            }

            let data = null;

            try {
                // Fetch jika ada URL
                if (this.urlFetchData) {
                    const url =
                        typeof this.urlFetchData === "function"
                            ? this.urlFetchData(editId)
                            : this.urlFetchData.replace(":id", editId);

                    const res = await fetch(url);
                    const json = await res.json();
                    data = json.data;
                }

                // Simpan ke instance (optional)
                this.currentData = data;
                this.currentId = editId;

                // Trigger event biar client handle sendiri
                document.dispatchEvent(
                    new CustomEvent("edit:open", {
                        detail: {
                            data,
                            id: editId,
                            button: btn,
                        },
                    }),
                );

                // Set ID ke form (kalau ada)
                if (this.formSelector && data) {
                    const form = document.querySelector(this.formSelector);
                    if (form) {
                        form.dataset.id = data.public_id;
                    }
                }

                // Show modal
                if (this.modalEditID) {
                    const modalEl = document.getElementById(this.modalEditID);

                    if (modalEl) {
                        const modal = new bootstrap.Modal(modalEl);
                        modal.show();
                    }
                }
            } catch (err) {
                if (window.notyf) {
                    notyf.error({ message: "Failed to load data!" });
                }
                console.error(err);
            }
        });
    }
}
// ------------------------------ Global Config for Edit Data :end ------------------------------
