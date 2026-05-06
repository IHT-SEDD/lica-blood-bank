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
