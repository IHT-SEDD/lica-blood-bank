import { createIcons, icons } from "lucide";

export function initComponents() {
    createIcons({ icons });

    document.querySelectorAll('[data-bs-toggle="popover"]').forEach((el) => {
        new bootstrap.Popover(el);
    });
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach((el) => {
        new bootstrap.Tooltip(el);
    });
    document.querySelectorAll(".offcanvas").forEach((el) => {
        new bootstrap.Offcanvas(el);
    });
    document.querySelectorAll(".toast").forEach((el) => {
        new bootstrap.Toast(el);
    });
}
