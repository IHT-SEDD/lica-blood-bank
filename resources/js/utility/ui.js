import { Notyf } from "notyf";

// ---------- Show/Hide Loading Overlay ----------
window.showPageLoading = function () {
    const overlay = document.getElementById("fullscreen_loading_overlay");
    if (overlay) overlay.classList.remove("d-none");
};

window.hidePageLoading = function () {
    const overlay = document.getElementById("fullscreen_loading_overlay");
    if (overlay) overlay.classList.add("d-none");
};

// ---------- Notyf Global Config ----------
window.notyf = new Notyf({
    duration: 4000,
    ripple: true,
    dismissible: true,
    position: {
        x: "right",
        y: "top",
    },
    types: [
        {
            type: "error",
            background: "red",
            className: "notyf-allow-html",
        },
        {
            type: "success",
            background: "green",
            className: "notyf-allow-html",
        },
    ],
});

// ---------- Global Text Formatter Class ----------
export class TextFormatter {
    static format(text, format = "underscoreReplace") {
        if (!text) return "-";

        switch (format) {
            case "underscoreReplace":
                return this.underscoreReplace(text);

            case "capitalize":
                return this.capitalize(text);

            case "uppercase":
                return text.toUpperCase();

            case "lowercase":
                return text.toLowerCase();

            case "titleCase":
                return this.titleCase(text);

            case "slugToTitle":
                return this.slugToTitle(text);

            case "camelToTitle":
                return this.camelToTitle(text);

            default:
                return text;
        }
    }

    static underscoreReplace(text) {
        return text
            .replaceAll("_", " ")
            .replace(/\b\w/g, (c) => c.toUpperCase());
    }

    static capitalize(text) {
        return text.charAt(0).toUpperCase() + text.slice(1);
    }

    static titleCase(text) {
        return text.toLowerCase().replace(/\b\w/g, (c) => c.toUpperCase());
    }

    static slugToTitle(text) {
        return text
            .replaceAll("-", " ")
            .replace(/\b\w/g, (c) => c.toUpperCase());
    }

    static camelToTitle(text) {
        return text
            .replace(/([A-Z])/g, " $1")
            .replace(/^./, (str) => str.toUpperCase());
    }
}

// ---------- Global Set Hidden Element Function ----------
export function setHidden(id, hidden) {
    const el = document.getElementById(id);
    if (!el) return;
    hidden ? el.classList.add("d-none") : el.classList.remove("d-none");
}

// ---------- Helper: Timestamp Formatter ----------
export function TimestampFormatter(isoString, locale) {
    if (!isoString) return "-";

    const date = new Date(isoString);
    const options = {
        day: "2-digit",
        month: "short",
        year: "numeric",
        hour: "2-digit",
        minute: "2-digit",
        hour12: true,
    };

    return date.toLocaleString(locale, options);
}

// ---------- Global render timeline item ----------
export class GlobalRenderTimelineItem {
    constructor(options = {}) {
        this.containerSelector = options.container ?? ".timeline-container";
        this.wrapperSelector = options.wrapper ?? ".timeline-wrapper";
        this.locale = options.locale ?? "en-GB";
        this.statusConfig = options.statusConfig;
        this.formatTimestamp = options.timestampFormatter ?? TimestampFormatter;
        this.renderItem =
            options.renderItem ?? this._defaultRenderItem.bind(this);

        // Simpan referensi tooltip Bootstrap agar bisa di-dispose saat destroy
        this._tooltipInstances = [];

        this._container = document.querySelector(this.containerSelector);
        this._wrapper =
            this._container?.querySelector(this.wrapperSelector) ?? null;
    }

    // ---------- Public: render dari array log ----------
    render(logs = []) {
        if (!this._wrapper) {
            console.warn(
                `[GlobalRenderTimelineItem] wrapper tidak ditemukan: "${this.wrapperSelector}"`,
            );
            return this;
        }

        this._disposeTooltips();

        if (!logs.length) {
            this.renderEmpty();
            return this;
        }

        this._wrapper.innerHTML = logs.map(this.renderItem).join("");
        this._initLucide();
        this._initTooltips();

        return this; // chainable
    }

    // ---------- Public: render state kosong ----------
    renderEmpty() {
        if (!this._wrapper) return this;

        this._wrapper.innerHTML = `
            <div class="text-center text-muted py-4">
                <i data-lucide="inbox" class="mb-2" style="width:32px;height:32px;"></i>
                <p class="mb-0">No activity log found.</p>
            </div>
        `;
        this._initLucide();

        return this;
    }

    // ---------- Public: update log (alias render, lebih semantik) ----------
    update(logs = []) {
        return this.render(logs);
    }

    // ---------- Public: bersihkan tooltip & referensi DOM ----------
    destroy() {
        this._disposeTooltips();
        this._wrapper = null;
        this._container = null;
    }

    // ---------- Static factory: shortcut tanpa new ----------
    static create(options = {}) {
        return new GlobalRenderTimelineItem(options);
    }

    // ---------- Private: render satu item (default, bisa di-override) ----------
    _defaultRenderItem(log) {
        const config =
            this.statusConfig[log.status] ?? this.statusConfig.fallback;

        const timestamp = this.formatTimestamp(
            log.timestamp ?? log.created_at,
            this.locale,
        );
        const description = log.description ?? "-";
        const createdByUser = log.created_by_user_name ?? "-";

        return `
            <div class="timeline-item d-flex align-items-stretch">
                <div class="timeline-time pe-3 fw-semibold fs-6 text-muted">
                    ${timestamp}
                </div>
                <div class="timeline-dot">
                    <i class="ti ti-${config.icon} fs-4 ${config.colorClass} align-middle"
                       data-bs-title="${config.tooltipTitle}"
                       data-bs-toggle="tooltip"
                       data-bs-trigger="hover"></i>
                </div>
                <div class="timeline-content ps-3 pb-2">
                    <h5 class="mb-1 fw-semibold">${config.title}</h5>
                    <p class="mb-1 fw-medium fs-6 text-muted">${description}</p>
                    <div class="d-flex align-items-center justify-content-start gap-1">
                        <span class="text-primary fs-5 fw-medium">${createdByUser}</span>
                    </div>
                </div>
            </div>
        `;
    }

    // ---------- Private: re-init lucide icons ----------
    _initLucide() {
        if (typeof lucide !== "undefined") {
            lucide.createIcons();
        }
    }

    // ---------- Private: inisialisasi Bootstrap tooltip ----------
    _initTooltips() {
        if (typeof bootstrap === "undefined" || !this._wrapper) return;

        this._wrapper
            .querySelectorAll('[data-bs-toggle="tooltip"]')
            .forEach((el) => {
                const instance = new bootstrap.Tooltip(el);
                this._tooltipInstances.push(instance);
            });
    }

    // ---------- Private: buang semua tooltip agar tidak memory leak ----------
    _disposeTooltips() {
        this._tooltipInstances.forEach((t) => t.dispose?.());
        this._tooltipInstances = [];
    }
}
