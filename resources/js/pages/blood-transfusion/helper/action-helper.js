import { GlobalDeleteDataConfirmation } from "../../../app";
import { DateTimeFormatter } from "../../../utility/ui";

let _deleteTransactionInitialized = false;

// ---------- Validasi visual nomor darah ----------
export function validateBloodNumber(inputVal, expectedBagNumber) {
    const bloodNumberInput = document.getElementById("blood_number");
    const bloodNumberStatus = document.getElementById("blood_number_status");
    const bloodNumberError = document.getElementById("blood_number_error");

    if (!inputVal) {
        bloodNumberInput?.classList.remove("is-valid", "is-invalid");
        if (bloodNumberStatus) bloodNumberStatus.innerHTML = "";
        if (bloodNumberError) {
            bloodNumberError.textContent = "";
            bloodNumberError.style.display = "none";
        }
        return false;
    }

    // Jika expectedBagNumber tidak tersedia dari client, lolos — backend tetap memvalidasi
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

// ---------- Enable/disable tombol confirm ----------
export function updateConfirmButtonState(confirmBtnId = "confirm_release") {
    const receivedByInput = document.getElementById("blood_received_by");
    const bloodNumberInput = document.getElementById("blood_number");
    const confirmBtn = document.getElementById(confirmBtnId);
    if (!confirmBtn) return;
    const printBarcodeBtn = document.getElementById(
        "print_barcode_release_btn",
    );
    if (!printBarcodeBtn) return;

    const modalEl = document.getElementById("blood_release_modal");
    const barcodePrinted = modalEl?._barcodePrinted ?? true;

    const receivedByOk = receivedByInput?.value.trim().length > 0;
    const bloodNumberOk = bloodNumberInput?.classList.contains("is-valid");
    confirmBtn.disabled = !(receivedByOk && bloodNumberOk);
    printBarcodeBtn.disabled = !(receivedByOk && bloodNumberOk);
}

// ---------- Reset field modal ----------
function resetModal(confirmBtnId) {
    const fields = [
        { id: "blood_received_by", type: "input" },
        { id: "blood_number", type: "input" },
    ];
    fields.forEach(({ id }) => {
        const el = document.getElementById(id);
        if (!el) return;
        el.value = "";
        el.classList.remove("is-invalid", "is-valid");
    });

    const bloodNumberStatus = document.getElementById("blood_number_status");
    if (bloodNumberStatus) bloodNumberStatus.innerHTML = "";

    const bloodNumberError = document.getElementById("blood_number_error");
    if (bloodNumberError) {
        bloodNumberError.textContent = "";
        bloodNumberError.style.display = "none";
    }

    const confirmBtn = document.getElementById(confirmBtnId);
    if (confirmBtn) confirmBtn.disabled = true;
    const printBarcodeBtn = document.getElementById(
        "print_barcode_release_btn",
    );
    if (printBarcodeBtn) printBarcodeBtn.disabled = true;
}
// ---------- Reset field modal release-all ----------
function resetModalAll() {
    ["blood_received_by_all", "blood_numbers_all"].forEach((id) => {
        const el = document.getElementById(id);
        if (!el) return;
        el.value = "";
        el.classList.remove("is-invalid", "is-valid");
    });

    const errorEl = document.getElementById("blood_numbers_all_error");
    if (errorEl) {
        errorEl.textContent = "";
        errorEl.style.display = "none";
    }

    const confirmBtn = document.getElementById("confirm_release_all");
    if (confirmBtn) confirmBtn.disabled = true;
}

// ---------- Listener real-time release single ----------
function attachModalListeners(expectedBagNumber, confirmBtnId) {
    const oldBloodNumberInput = document.getElementById("blood_number");
    const oldReceivedByInput = document.getElementById("blood_received_by");

    if (oldBloodNumberInput) {
        const newInput = oldBloodNumberInput.cloneNode(true);
        oldBloodNumberInput.parentNode.replaceChild(
            newInput,
            oldBloodNumberInput,
        );
        newInput.addEventListener("input", function () {
            validateBloodNumber(this.value.trim(), expectedBagNumber);
            updateConfirmButtonState(confirmBtnId);
        });
    }

    if (oldReceivedByInput) {
        const newInput = oldReceivedByInput.cloneNode(true);
        oldReceivedByInput.parentNode.replaceChild(
            newInput,
            oldReceivedByInput,
        );
        newInput.addEventListener("input", function () {
            const hasValue = this.value.trim().length > 0;
            this.classList.toggle("is-valid", hasValue);
            this.classList.toggle("is-invalid", !hasValue);
            updateConfirmButtonState(confirmBtnId);
        });
    }
}
// ---------- Listener real-time release-all ----------
function attachModalListenersAll(
    expectedUnreleased = [],
    expectedReleased = [],
) {
    const normalizeSet = (arr) => new Set(arr.map((s) => s.toString()));

    const unreleased = normalizeSet(expectedUnreleased);
    const released = normalizeSet(expectedReleased);

    const checkAndUpdateBtn = () => {
        const receivedByOk =
            document.getElementById("blood_received_by_all")?.value.trim()
                .length > 0;
        const bloodNumbersOk = document
            .getElementById("blood_numbers_all")
            ?.classList.contains("is-valid");
        const confirmBtn = document.getElementById("confirm_release_all");
        if (confirmBtn) confirmBtn.disabled = !(receivedByOk && bloodNumbersOk);
    };

    // Listener: penerima darah
    const oldReceivedByInput = document.getElementById("blood_received_by_all");
    if (oldReceivedByInput) {
        const newInput = oldReceivedByInput.cloneNode(true);
        oldReceivedByInput.parentNode.replaceChild(
            newInput,
            oldReceivedByInput,
        );
        newInput.addEventListener("input", function () {
            const hasValue = this.value.trim().length > 0;
            this.classList.toggle("is-valid", hasValue);
            this.classList.toggle("is-invalid", !hasValue);
            checkAndUpdateBtn();
        });
    }

    // Listener: nomor darah (textarea)
    const oldBloodNumbersInput = document.getElementById("blood_numbers_all");
    if (oldBloodNumbersInput) {
        const newInput = oldBloodNumbersInput.cloneNode(true);
        oldBloodNumbersInput.parentNode.replaceChild(
            newInput,
            oldBloodNumbersInput,
        );

        newInput.addEventListener("input", function () {
            const errorEl = document.getElementById("blood_numbers_all_error");

            // Jika tidak ada data expected dari client, lolos — backend memvalidasi
            if (!unreleased.size && !released.size) {
                this.classList.remove("is-invalid");
                this.classList.add("is-valid");
                if (errorEl) errorEl.style.display = "none";
                checkAndUpdateBtn();
                return;
            }

            // Parse baris textarea
            const inputNumbers = this.value
                .split("\n")
                .map((s) => s.trim())
                .filter(Boolean);

            const errors = [];

            // 1. Nomor yang di-input tapi sudah dikeluarkan (tidak boleh)
            const alreadyReleased = inputNumbers.filter((num) =>
                released.has(num),
            );
            if (alreadyReleased.length) {
                errors.push(
                    `Nomor labu: ${alreadyReleased.join(", ")} sudah dikeluarkan. Mohon perbaiki.`,
                );
            }

            // 2. Nomor yang wajib tapi belum di-scan
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

            checkAndUpdateBtn();
        });
    }
}

// ---------- Guard validasi sebelum submit ----------
function validateReleaseForm() {
    const receivedByInput = document.getElementById("blood_received_by");
    const bloodNumberInput = document.getElementById("blood_number");

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
// ---------- Guard validasi release-all sebelum submit ----------
function validateReleaseAllForm() {
    const receivedByInput = document.getElementById("blood_received_by_all");
    const bloodNumbersInput = document.getElementById("blood_numbers_all");

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

// ---------- Factory: init modal release (single atau all) ----------
function initReleaseModal({
    doAction,
    btnOpenSelector,
    confirmBtnId,
    modalId,
    getUrl,
    qzManager = null,
}) {
    // Buka modal
    $(document)
        .off("click", "#" + btnOpenSelector)
        .on("click", "#" + btnOpenSelector, function (e) {
            e.preventDefault();
            if (!window.currentTransfusionPublicId) return;
            resetModal(confirmBtnId);

            // ---------- Reset flag barcode ----------
            const modalEl = document.getElementById(modalId);
            if (modalEl) modalEl._barcodePrinted = false;
            // ---------- Disable confirm sampai barcode dicetak ----------
            const confirmBtn = document.getElementById(confirmBtnId);
            if (confirmBtn) confirmBtn.disabled = true;

            const expectedBagNumber =
                window.currentBagData?.row_data?.bag_number ?? null;
            attachModalListeners(expectedBagNumber, confirmBtnId);
            if (modalEl) bootstrap.Modal.getOrCreateInstance(modalEl).show();
        });
    // Print barcode
    $(document)
        .off("click", "#print_barcode_release_btn")
        .on("click", "#print_barcode_release_btn", async function () {
            if (!qzManager) {
                console.warn("QzManager tidak tersedia.");
                return;
            }
            const bagNumber =
                window.currentBagData?.row_data?.bag_number ?? "-";
            const receivedBy =
                document.getElementById("blood_received_by")?.value.trim() ||
                "-";
            const releasedBy = window.currentUserName ?? "-";
            const now = DateTimeFormatter.datetime24(new Date());
            await qzManager.sendZpl(
                {
                    bag_number: bagNumber,
                    received_by: receivedBy,
                    released_by: releasedBy,
                    released_at: now,
                },
                "barcode-release",
                "BarcodeBDRS2",
            );
            // ---------- Set flag & update confirm button ----------
            const modalEl = document.getElementById(modalId);
            if (modalEl) modalEl._barcodePrinted = true;
            updateConfirmButtonState(confirmBtnId);
        });
    // Konfirmasi
    $(document)
        .off("click", "#" + confirmBtnId)
        .on("click", "#" + confirmBtnId, async function (e) {
            e.preventDefault();
            // ---------- Guard: barcode wajib dicetak dulu ----------
            const modalEl = document.getElementById(modalId);
            if (!modalEl?._barcodePrinted) {
                notyf.error({
                    message: "Harap cetak barcode terlebih dahulu!",
                });
                return;
            }

            if (!validateReleaseForm()) return;

            const bloodReceivedBy = document
                .getElementById("blood_received_by")
                ?.value.trim();
            const bloodNumber = document
                .getElementById("blood_number")
                ?.value.trim();

            const originalText = this.innerHTML;
            this.innerHTML =
                '<span class="spinner-border spinner-border-sm"></span> Processing...';
            this.disabled = true;

            await doAction({
                url: getUrl(),
                body: {
                    blood_received_by: bloodReceivedBy,
                    blood_number: bloodNumber,
                },
                successMessage: "Darah berhasil dikeluarkan!",
                errorMessage: "Gagal mengeluarkan darah!",
                onSuccess: () => {
                    const modalEl = document.getElementById(modalId);
                    if (modalEl)
                        bootstrap.Modal.getOrCreateInstance(modalEl).hide();
                },
            });

            this.innerHTML = originalText;
            this.disabled = false;
        });
}
function initReleaseAllModal({
    doAction,
    btnOpenSelector,
    confirmBtnId,
    modalId,
    getUrl,
}) {
    $(document)
        .off("click", "#" + btnOpenSelector)
        .on("click", "#" + btnOpenSelector, function (e) {
            e.preventDefault();
            if (!window.currentTransfusionPublicId) return;
            resetModalAll();

            const expectedUnreleased = [];
            const expectedReleased = [];
            if ($.fn.DataTable.isDataTable("#list-bag-request-table")) {
                $("#list-bag-request-table")
                    .DataTable()
                    .rows()
                    .data()
                    .each((row) => {
                        const bagNumber = row?.row_data?.bag_number;
                        const isReleased = row?.row_data?.blood_release_status;
                        if (!bagNumber) return;

                        if (isReleased) {
                            expectedReleased.push(bagNumber);
                        } else {
                            expectedUnreleased.push(bagNumber);
                        }
                    });
            }

            attachModalListenersAll(expectedUnreleased, expectedReleased);

            const modalEl = document.getElementById(modalId);
            if (modalEl) bootstrap.Modal.getOrCreateInstance(modalEl).show();
        });

    // Konfirmasi
    $(document)
        .off("click", "#" + confirmBtnId)
        .on("click", "#" + confirmBtnId, async function (e) {
            e.preventDefault();
            const formData = validateReleaseAllForm();
            if (!formData) return;

            const bloodReceivedBy = document
                .getElementById("blood_received_by_all")
                ?.value.trim();
            const bloodNumber = document
                .getElementById("blood_number_all")
                ?.value.trim();

            const originalText = this.innerHTML;
            this.innerHTML =
                '<span class="spinner-border spinner-border-sm"></span> Processing...';
            this.disabled = true;

            await doAction({
                url: getUrl(),
                body: formData,
                successMessage: "Semua darah berhasil dikeluarkan!",
                errorMessage: "Gagal mengeluarkan semua darah!",
                onSuccess: () => {
                    const modalEl = document.getElementById(modalId);
                    if (modalEl)
                        bootstrap.Modal.getOrCreateInstance(modalEl).hide();
                },
            });

            this.innerHTML = originalText;
            this.disabled = false;
        });
}

// ---------- Export: release satu labu ----------
export function initReleaseBloodPack({
    doAction,
    SelectorBtnRelease,
    qzManager,
}) {
    initReleaseModal({
        doAction,
        btnOpenSelector: SelectorBtnRelease,
        confirmBtnId: "confirm_release",
        modalId: "blood_release_modal",
        getUrl: () =>
            `/blood-transfusion/detail/${window.currentBagDetailPublicId}/release`,
        qzManager,
    });
}

// ---------- Export: release semua labu ----------
export function initReleaseAllBloodPack({ doAction, SelectorBtnReleaseAll }) {
    initReleaseAllModal({
        doAction,
        btnOpenSelector: SelectorBtnReleaseAll,
        confirmBtnId: "confirm_release_all",
        modalId: "blood_release_all_modal",
        getUrl: () =>
            `/blood-transfusion/detail/${window.currentTransfusionPublicId}/release-all`,
    });
}

// ---------- Export: Delete Transaction ----------
export function initDeleteTransaction({
    reloadTable,
    ActionDeleteSelector,
    AttributeDelete,
    ModalDeleteSelector,
    ConfirmDeleteSelector,
    StockBloodDataURL,
}) {
    if (_deleteTransactionInitialized) return;
    _deleteTransactionInitialized = true;

    const getCsrfToken = () =>
        document
            .querySelector('meta[name="csrf-token"]')
            ?.getAttribute("content");

    new GlobalDeleteDataConfirmation({
        ButtonSelector: ActionDeleteSelector,
        DataAttributeID: AttributeDelete,
        UrlFetchData: (id) => `/blood-transfusion/${id}/`,
        ModalConfirmID: ModalDeleteSelector,
    });

    const handleDeleteOpen = (e) => {
        const { data } = e.detail;
        if (!data) return;
        const deletedDataEl = document.querySelector("#deleted_data");
        if (deletedDataEl) {
            deletedDataEl.textContent = `${data.blood_transfusion.lab_number ?? "-"} dengan nama ${data.patient_name}`;
        }
        const confirmBtn = document.querySelector(ConfirmDeleteSelector);
        if (confirmBtn) confirmBtn.dataset.id = data.blood_transfusion.public_id;
    };

    // ---------- Remove dulu sebelum pasang baru — cegah duplikasi ----------
    document.removeEventListener("delete:open", handleDeleteOpen);
    document.addEventListener("delete:open", handleDeleteOpen);

    const confirmBtn = document.querySelector(ConfirmDeleteSelector);
    if (!confirmBtn) return;

    // ---------- Clone button untuk bersihkan listener lama ----------
    const newConfirmBtn = confirmBtn.cloneNode(true);
    confirmBtn.parentNode.replaceChild(newConfirmBtn, confirmBtn);

    newConfirmBtn.addEventListener("click", async () => {
        const id = newConfirmBtn.dataset.id;
        if (!id) return;

        try {
            const response = await fetch(`/blood-transfusion/${id}/`, {
                method: "DELETE",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": getCsrfToken(),
                },
            });

            const result = await response.json();

            if (!response.ok) {
                notyf.error({
                    message: result.message || "Failed to delete data!",
                });
                return;
            }
            notyf.success({
                message: result.message || "Data deleted successfully!",
            });

            const modalEl = document.getElementById(ModalDeleteSelector);
            if (modalEl) bootstrap.Modal.getInstance(modalEl)?.hide();

            newConfirmBtn.dataset.id = "";
            reloadTable();
        } catch (error) {
            console.error(error);
            notyf.error({ message: "Failed to delete data!" });
        }
    });
}
