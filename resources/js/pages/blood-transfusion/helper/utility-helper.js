export const SelectorModalRelease = "blood_release_modal";
export const SelectorModalReleaseAll = "blood_release_all_modal";
export const SelectorModalUnRelease = "blood_unrelease_modal";

export const SelectorConfirmBtnRelease = "confirm_release";
export const SelectorConfirmBtnReleaseAll = "confirm_release_all";
export const SelectorConfirmBtnUnRelease = "confirm_unrelease";

// ---------- PRIVATE HELPERS ----------
function scopedById(id, scope = document) {
    return scope.querySelector(`#${id}`);
}
function replaceAndListen(el, handler) {
    if (!el) return null;
    const newEl = el.cloneNode(true);
    el.parentNode.replaceChild(newEl, el);
    newEl.addEventListener("input", handler);
    return newEl;
}

export function validateBloodNumber(
    inputVal,
    expectedBagNumber,
    scope = document,
) {
    const bloodNumberInput = scopedById("blood_number", scope);
    const bloodNumberStatus = scopedById("blood_number_status", scope);
    const bloodNumberError = scopedById("blood_number_error", scope);

    if (!inputVal) {
        bloodNumberInput?.classList.remove("is-valid", "is-invalid");
        if (bloodNumberStatus) bloodNumberStatus.innerHTML = "";
        if (bloodNumberError) {
            bloodNumberError.textContent = "";
            bloodNumberError.style.display = "none";
        }
        return false;
    }

    if (!expectedBagNumber) {
        bloodNumberInput?.classList.remove("is-invalid");
        bloodNumberInput?.classList.add("is-valid");
        if (bloodNumberStatus) bloodNumberStatus.innerHTML = "";
        if (bloodNumberError) {
            bloodNumberError.textContent = "";
            bloodNumberError.style.display = "none";
        }
        return true;
    }

    const isMatch =
        inputVal.toLowerCase() === expectedBagNumber.toString().toLowerCase();

    bloodNumberInput?.classList.toggle("is-valid", isMatch);
    bloodNumberInput?.classList.toggle("is-invalid", !isMatch);

    if (bloodNumberStatus) {
        bloodNumberStatus.innerHTML = isMatch
            ? `<i class="ti ti-circle-check text-success fs-5"></i>`
            : `<i class="ti ti-circle-x text-danger fs-5"></i>`;
    }
    if (bloodNumberError) {
        bloodNumberError.textContent = isMatch
            ? ""
            : "Nomor darah tidak sesuai dengan labu darah pasien ini!";
        bloodNumberError.style.display = isMatch ? "none" : "block";
    }

    return isMatch;
}

// ---------- CONFIRM BUTTON STATE ----------
export function updateConfirmButtonState(modalId) {
    const modalEl = document.getElementById(modalId);
    if (!modalEl) return;

    switch (modalId) {
        case SelectorModalRelease: {
            const receivedByInput = scopedById("blood_received_by", modalEl);
            const bloodNumberInput = scopedById("blood_number", modalEl);
            const confirmBtn = document.getElementById(
                SelectorConfirmBtnRelease,
            );
            const printBarcodeBtn = document.getElementById(
                "print_barcode_release_btn",
            );

            const barcodePrinted = modalEl._barcodePrinted ?? false;
            const receivedByOk =
                (receivedByInput?.value.trim().length ?? 0) > 0;
            const bloodNumberOk =
                bloodNumberInput?.classList.contains("is-valid") ?? false;

            if (printBarcodeBtn)
                printBarcodeBtn.disabled = !(receivedByOk && bloodNumberOk);
            if (confirmBtn)
                confirmBtn.disabled = !(
                    receivedByOk &&
                    bloodNumberOk &&
                    barcodePrinted
                );
            break;
        }

        case SelectorModalUnRelease: {
            const bloodNumberInput = scopedById("blood_number", modalEl);
            const confirmBtn = document.getElementById(
                SelectorConfirmBtnUnRelease,
            );

            const bloodNumberOk =
                bloodNumberInput?.classList.contains("is-valid") ?? false;
            if (confirmBtn) confirmBtn.disabled = !bloodNumberOk;
            break;
        }

        case SelectorModalReleaseAll: {
            const receivedByInput = scopedById(
                "blood_received_by_all",
                modalEl,
            );
            const bloodNumbersInput = scopedById("blood_numbers_all", modalEl);
            const confirmBtn = document.getElementById(
                SelectorConfirmBtnReleaseAll,
            );

            const receivedByOk =
                (receivedByInput?.value.trim().length ?? 0) > 0;
            const bloodNumberOk =
                bloodNumbersInput?.classList.contains("is-valid") ?? false;
            if (confirmBtn)
                confirmBtn.disabled = !(receivedByOk && bloodNumberOk);
            break;
        }
    }
}

// ---------- RESET MODAL ----------
export function resetModalRelease() {
    const modalEl = document.getElementById(SelectorModalRelease);
    if (!modalEl) return;

    ["blood_received_by", "blood_number"].forEach((id) => {
        const el = scopedById(id, modalEl);
        if (!el) return;
        el.value = "";
        el.classList.remove("is-valid", "is-invalid");
    });

    const bloodNumberStatus = scopedById("blood_number_status", modalEl);
    if (bloodNumberStatus) bloodNumberStatus.innerHTML = "";
    const bloodNumberError = scopedById("blood_number_error", modalEl);
    if (bloodNumberError) {
        bloodNumberError.textContent = "";
        bloodNumberError.style.display = "none";
    }

    modalEl._barcodePrinted = false;

    const confirmBtn = document.getElementById(SelectorConfirmBtnRelease);
    const printBarcodeBtn = document.getElementById(
        "print_barcode_release_btn",
    );
    if (confirmBtn) confirmBtn.disabled = true;
    if (printBarcodeBtn) printBarcodeBtn.disabled = true;
}
export function resetModalReleaseAll() {
    const modalEl = document.getElementById(SelectorModalReleaseAll);
    if (!modalEl) return;

    ["blood_received_by_all", "blood_numbers_all"].forEach((id) => {
        const el = scopedById(id, modalEl);
        if (!el) return;
        el.value = "";
        el.classList.remove("is-valid", "is-invalid");
    });

    const errorEl = scopedById("blood_numbers_all_error", modalEl);
    if (errorEl) {
        errorEl.textContent = "";
        errorEl.style.display = "none";
    }

    const confirmBtn = document.getElementById(SelectorConfirmBtnReleaseAll);
    if (confirmBtn) confirmBtn.disabled = true;
}
export function resetModalUnRelease() {
    const modalEl = document.getElementById(SelectorModalUnRelease);
    if (!modalEl) return;

    const bloodNumberInput = scopedById("blood_number", modalEl);
    if (bloodNumberInput) {
        bloodNumberInput.value = "";
        bloodNumberInput.classList.remove("is-valid", "is-invalid");
    }

    const bloodNumberStatus = scopedById("blood_number_status", modalEl);
    if (bloodNumberStatus) bloodNumberStatus.innerHTML = "";
    const bloodNumberError = scopedById("blood_number_error", modalEl);
    if (bloodNumberError) {
        bloodNumberError.textContent = "";
        bloodNumberError.style.display = "none";
    }

    const confirmBtn = document.getElementById(SelectorConfirmBtnUnRelease);
    if (confirmBtn) confirmBtn.disabled = true;
}

// ---------- REALTIME MODAL LISTENER ----------
export function attachModalListenerRelease(expectedBagNumber) {
    const modalEl = document.getElementById(SelectorModalRelease);
    if (!modalEl) return;

    replaceAndListen(scopedById("blood_number", modalEl), function () {
        validateBloodNumber(this.value.trim(), expectedBagNumber, modalEl);
        updateConfirmButtonState(SelectorModalRelease);
    });

    replaceAndListen(scopedById("blood_received_by", modalEl), function () {
        const hasValue = this.value.trim().length > 0;
        this.classList.toggle("is-valid", hasValue);
        this.classList.toggle("is-invalid", !hasValue);
        updateConfirmButtonState(SelectorModalRelease);
    });
}
export function attachModalListenerReleaseAll(
    expectedUnreleased = [],
    expectedReleased = [],
) {
    const modalEl = document.getElementById(SelectorModalReleaseAll);
    if (!modalEl) return;

    const normalizeSet = (arr) => new Set(arr.map((s) => s.toString()));
    const unreleased = normalizeSet(expectedUnreleased);
    const released = normalizeSet(expectedReleased);

    replaceAndListen(scopedById("blood_received_by_all", modalEl), function () {
        const hasValue = this.value.trim().length > 0;
        this.classList.toggle("is-valid", hasValue);
        this.classList.toggle("is-invalid", !hasValue);
        updateConfirmButtonState(SelectorModalReleaseAll);
    });

    replaceAndListen(scopedById("blood_numbers_all", modalEl), function () {
        const errorEl = scopedById("blood_numbers_all_error", modalEl);

        // Jika tidak ada data expected dari client → lolos, backend yang validasi
        if (!unreleased.size && !released.size) {
            this.classList.remove("is-invalid");
            this.classList.add("is-valid");
            if (errorEl) errorEl.style.display = "none";
            updateConfirmButtonState(SelectorModalReleaseAll);
            return;
        }

        const inputNumbers = this.value
            .split("\n")
            .map((s) => s.trim())
            .filter(Boolean);

        const errors = [];

        const alreadyReleased = inputNumbers.filter((num) => released.has(num));
        if (alreadyReleased.length) {
            errors.push(
                `Nomor labu: ${alreadyReleased.join(", ")} sudah dikeluarkan. Mohon perbaiki.`,
            );
        }

        const missing = [...unreleased].filter(
            (num) => !inputNumbers.includes(num),
        );
        if (missing.length) {
            errors.push(`Nomor labu belum di-scan: ${missing.join(", ")}`);
        }

        const isValid = errors.length === 0;
        this.classList.toggle("is-valid", isValid);
        this.classList.toggle("is-invalid", !isValid);

        if (errorEl) {
            errorEl.innerHTML = isValid ? "" : errors.join("<br>");
            errorEl.style.display = isValid ? "none" : "block";
        }

        updateConfirmButtonState(SelectorModalReleaseAll);
    });
}
export function attachModalListenerUnRelease(expectedBagNumber) {
    const modalEl = document.getElementById(SelectorModalUnRelease);
    if (!modalEl) return;

    replaceAndListen(scopedById("blood_number", modalEl), function () {
        validateBloodNumber(this.value.trim(), expectedBagNumber, modalEl);
        updateConfirmButtonState(SelectorModalUnRelease);
    });
}

// ---------- VALIDASI FORM ----------
export function validateReleaseForm() {
    const modalEl = document.getElementById(SelectorModalRelease);
    const scope = modalEl ?? document;
    const receivedByInput = scopedById("blood_received_by", scope);
    const bloodNumberInput = scopedById("blood_number", scope);

    if (!receivedByInput?.value.trim()) {
        notyf.error({
            message: "Harap masukan penerima darah terlebih dahulu!",
        });
        receivedByInput?.classList.add("is-invalid");
        return false;
    }
    if (!bloodNumberInput?.value.trim()) {
        notyf.error({ message: "Harap masukan nomor darah terlebih dahulu!" });
        bloodNumberInput?.classList.add("is-invalid");
        return false;
    }
    if (bloodNumberInput?.classList.contains("is-invalid")) {
        notyf.error({ message: "Nomor darah tidak sesuai, periksa kembali!" });
        return false;
    }
    return true;
}
export function validateReleaseAllForm() {
    const modalEl = document.getElementById(SelectorModalReleaseAll);
    const scope = modalEl ?? document;
    const receivedByInput = scopedById("blood_received_by_all", scope);
    const bloodNumbersInput = scopedById("blood_numbers_all", scope);

    if (!receivedByInput?.value.trim()) {
        notyf.error({
            message: "Harap masukan penerima darah terlebih dahulu!",
        });
        receivedByInput?.classList.add("is-invalid");
        return null;
    }
    if (!bloodNumbersInput?.value.trim()) {
        notyf.error({ message: "Harap masukan nomor darah terlebih dahulu!" });
        bloodNumbersInput?.classList.add("is-invalid");
        return null;
    }
    if (bloodNumbersInput?.classList.contains("is-invalid")) {
        notyf.error({
            message: "Ada nomor darah yang tidak sesuai, periksa kembali!",
        });
        return null;
    }

    return {
        blood_received_by: receivedByInput.value.trim(),
        blood_numbers: bloodNumbersInput.value
            .split("\n")
            .map((s) => s.trim())
            .filter(Boolean),
    };
}
export function validateNotReleaseForm() {
    const modalEl = document.getElementById(SelectorModalUnRelease);
    const scope = modalEl ?? document;
    const bloodNumberInput = scopedById("blood_number", scope);

    if (!bloodNumberInput?.value.trim()) {
        notyf.error({ message: "Harap masukan nomor darah terlebih dahulu!" });
        bloodNumberInput?.classList.add("is-invalid");
        return false;
    }
    if (bloodNumberInput?.classList.contains("is-invalid")) {
        notyf.error({ message: "Nomor darah tidak sesuai, periksa kembali!" });
        return false;
    }
    return true;
}
