export class Plugins {
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
