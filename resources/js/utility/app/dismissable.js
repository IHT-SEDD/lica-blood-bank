export function initDismissible() {
    document.querySelectorAll("[data-dismissible]").forEach((trigger) => {
        trigger.addEventListener("click", () => {
            document
                .querySelector(trigger.getAttribute("data-dismissible"))
                ?.remove();
        });
    });
}
