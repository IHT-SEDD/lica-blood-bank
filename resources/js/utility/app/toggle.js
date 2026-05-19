export function initToggle() {
    document.querySelectorAll("[data-toggler]").forEach((wrapper) => {
        const toggleOn = wrapper.querySelector("[data-toggler-on]");
        const toggleOff = wrapper.querySelector("[data-toggler-off]");
        let isOn = wrapper.getAttribute("data-toggler") === "on";

        const update = () => {
            toggleOn?.classList.toggle("d-none", !isOn);
            toggleOff?.classList.toggle("d-none", isOn);
        };

        toggleOn?.addEventListener("click", () => {
            isOn = false;
            update();
        });
        toggleOff?.addEventListener("click", () => {
            isOn = true;
            update();
        });
        update();
    });
}
