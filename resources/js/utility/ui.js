// Import notyf
import { Notyf } from "notyf";

// Show loading overlay
window.showPageLoading = function () {
    const overlay = document.getElementById("fullscreen_loading_overlay");
    if (overlay) overlay.classList.remove("d-none");
};

// Hide loading overlay
window.hidePageLoading = function () {
    const overlay = document.getElementById("fullscreen_loading_overlay");
    if (overlay) overlay.classList.add("d-none");
};

// Global config untuk notyf
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

// Class global format text
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
