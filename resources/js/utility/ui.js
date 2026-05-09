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
