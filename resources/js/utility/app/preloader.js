export function initPreloader() {
    window.addEventListener("load", () => {
        const status = document.getElementById("status");
        const preloader = document.getElementById("preloader");
        if (status) status.style.display = "none";
        if (preloader)
            setTimeout(() => (preloader.style.display = "none"), 350);
    });
}
