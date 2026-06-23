import {
    GlobalDataConfirmation,
    GlobalDeleteDataConfirmation,
} from "../../../app";
import { DateTimeFormatter } from "../../../utility/ui";
import {
    // Constants
    SelectorModalRelease,
    SelectorModalReleaseAll,
    SelectorModalUnRelease,
    SelectorConfirmBtnRelease,
    SelectorConfirmBtnReleaseAll,
    SelectorConfirmBtnUnRelease,
    // Reset
    resetModalRelease,
    resetModalReleaseAll,
    resetModalUnRelease,
    // Attach listeners
    attachModalListenerRelease,
    attachModalListenerReleaseAll,
    attachModalListenerUnRelease,
    // Validate form (submit guard)
    validateReleaseForm,
    validateReleaseAllForm,
    validateNotReleaseForm,
    // Confirm button state
    updateConfirmButtonState,
} from "./utility-helper";

let _deleteTransactionInitialized = false;
let _archiveTransactionInitialized = false;

// ---------- PRIVATE FUNCTION: INIT MODAL ----------
function initReleaseModal({
    doAction,
    btnOpenSelector,
    getUrl,
    qzManager = null,
}) {
    // Buka modal
    $(document)
        .off("click", "#" + btnOpenSelector)
        .on("click", "#" + btnOpenSelector, function (e) {
            e.preventDefault();
            if (!window.currentTransfusionPublicId) return;

            resetModalRelease();

            const expectedBagNumber =
                window.currentBagData?.row_data?.bag_number ?? null;
            attachModalListenerRelease(expectedBagNumber);

            const modalEl = document.getElementById(SelectorModalRelease);
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
            const modalEl = document.getElementById(SelectorModalRelease);
            const receivedBy =
                modalEl?.querySelector("#blood_received_by")?.value.trim() ||
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

            if (modalEl) modalEl._barcodePrinted = true;
            updateConfirmButtonState(SelectorModalRelease);
        });
    // Konfirmasi submit
    $(document)
        .off("click", "#" + SelectorConfirmBtnRelease)
        .on("click", "#" + SelectorConfirmBtnRelease, async function (e) {
            e.preventDefault();

            const modalEl = document.getElementById(SelectorModalRelease);
            if (!modalEl?._barcodePrinted) {
                notyf.error({
                    message: "Harap cetak barcode terlebih dahulu!",
                });
                return;
            }
            if (!validateReleaseForm()) return;

            const body = {
                blood_received_by: modalEl
                    .querySelector("#blood_received_by")
                    ?.value.trim(),
                blood_number: modalEl
                    .querySelector("#blood_number")
                    ?.value.trim(),
            };

            const originalText = this.innerHTML;
            this.innerHTML =
                '<span class="spinner-border spinner-border-sm"></span> Processing...';
            this.disabled = true;

            await doAction({
                url: getUrl(),
                body,
                successMessage: "Darah berhasil dikeluarkan!",
                errorMessage: "Gagal mengeluarkan darah!",
                onSuccess: () =>
                    bootstrap.Modal.getOrCreateInstance(modalEl).hide(),
            });

            this.innerHTML = originalText;
            this.disabled = false;
        });
}
function initReleaseAllModal({ doAction, btnOpenSelector, getUrl }) {
    $(document)
        .off("click", "#" + btnOpenSelector)
        .on("click", "#" + btnOpenSelector, function (e) {
            e.preventDefault();
            if (!window.currentTransfusionPublicId) return;

            resetModalReleaseAll();

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
                        isReleased
                            ? expectedReleased.push(bagNumber)
                            : expectedUnreleased.push(bagNumber);
                    });
            }

            attachModalListenerReleaseAll(expectedUnreleased, expectedReleased);

            const modalEl = document.getElementById(SelectorModalReleaseAll);
            if (modalEl) bootstrap.Modal.getOrCreateInstance(modalEl).show();
        });

    // Konfirmasi submit
    $(document)
        .off("click", "#" + SelectorConfirmBtnReleaseAll)
        .on("click", "#" + SelectorConfirmBtnReleaseAll, async function (e) {
            e.preventDefault();

            const formData = validateReleaseAllForm();
            if (!formData) return;

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
                    const modalEl = document.getElementById(
                        SelectorModalReleaseAll,
                    );
                    if (modalEl)
                        bootstrap.Modal.getOrCreateInstance(modalEl).hide();
                },
            });

            this.innerHTML = originalText;
            this.disabled = false;
        });
}
function initNotReleaseModal({ doAction, btnOpenSelector, getUrl }) {
    // Buka modal
    $(document)
        .off("click", "#" + btnOpenSelector)
        .on("click", "#" + btnOpenSelector, function (e) {
            e.preventDefault();
            if (!window.currentTransfusionPublicId) return;

            resetModalUnRelease();

            const expectedBagNumber =
                window.currentBagData?.row_data?.bag_number ?? null;
            attachModalListenerUnRelease(expectedBagNumber);

            const modalEl = document.getElementById(SelectorModalUnRelease);
            if (modalEl) bootstrap.Modal.getOrCreateInstance(modalEl).show();
        });

    // Konfirmasi submit
    $(document)
        .off("click", "#" + SelectorConfirmBtnUnRelease)
        .on("click", "#" + SelectorConfirmBtnUnRelease, async function (e) {
            e.preventDefault();

            if (!validateNotReleaseForm()) return;

            const modalEl = document.getElementById(SelectorModalUnRelease);
            const bloodNumberInput = modalEl?.querySelector("#blood_number");

            const originalText = this.innerHTML;
            this.innerHTML =
                '<span class="spinner-border spinner-border-sm"></span> Processing...';
            this.disabled = true;

            await doAction({
                url: getUrl(),
                body: { blood_number: bloodNumberInput?.value.trim() },
                successMessage: "Darah berhasil tidak dikeluarkan!",
                errorMessage: "Gagal tidak mengeluarkan darah!",
                onSuccess: () => {
                    if (modalEl)
                        bootstrap.Modal.getOrCreateInstance(modalEl).hide();
                },
            });

            this.innerHTML = originalText;
            this.disabled = false;
        });
}

// ---------- EXPORTS ----------
export function initReleaseBloodPack({
    doAction,
    SelectorBtnRelease,
    qzManager,
}) {
    initReleaseModal({
        doAction,
        btnOpenSelector: SelectorBtnRelease,
        getUrl: () =>
            `/blood-transfusion/detail/${window.currentBagDetailPublicId}/release`,
        qzManager,
    });
}
export function initReleaseAllBloodPack({ doAction, SelectorBtnReleaseAll }) {
    initReleaseAllModal({
        doAction,
        btnOpenSelector: SelectorBtnReleaseAll,
        getUrl: () =>
            `/blood-transfusion/detail/${window.currentTransfusionPublicId}/release-all`,
    });
}
export function initNotReleaseBloodPack({ doAction, SelectorBtnUnrelease }) {
    initNotReleaseModal({
        doAction,
        btnOpenSelector: SelectorBtnUnrelease,
        getUrl: () =>
            `/blood-transfusion/detail/${window.currentBagDetailPublicId}/unrelease`,
    });
}
export function initDeleteTransaction({
    reloadTable,
    ActionDeleteSelector,
    AttributeDelete,
    ModalDeleteSelector,
    ConfirmDeleteSelector,
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
        if (confirmBtn)
            confirmBtn.dataset.id = data.blood_transfusion.public_id;
    };

    document.removeEventListener("delete:open", handleDeleteOpen);
    document.addEventListener("delete:open", handleDeleteOpen);

    const confirmBtn = document.querySelector(ConfirmDeleteSelector);
    if (!confirmBtn) return;

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
export function initArchiveTransaction({
    reloadTable,
    ActionArchiveSelector,
    AttributeArchive,
    ModalArchiveSelector,
    ConfirmArchiveSelector,
}) {
    if (_archiveTransactionInitialized) return;
    _archiveTransactionInitialized = true;

    const getCsrfToken = () =>
        document
            .querySelector('meta[name="csrf-token"]')
            ?.getAttribute("content");

    new GlobalDataConfirmation({
        ButtonSelector: ActionArchiveSelector,
        DataAttributeID: AttributeArchive,
        UrlFetchData: (id) => `/blood-transfusion/${id}/`,
        ModalConfirmID: ModalArchiveSelector,
    });

    const handleArchiveOpen = (e) => {
        const { data } = e.detail;
        if (!data) return;
        const archivedDataEl = document.querySelector("#confirm_data");
        if (archivedDataEl) {
            archivedDataEl.textContent = `${data.blood_transfusion.lab_number ?? "-"} dengan nama ${data.patient_name}`;
        }
        const modalEl = document.getElementById(ModalArchiveSelector);
        if (modalEl)
            modalEl.dataset.targetId = data.blood_transfusion.public_id;
    };

    document.removeEventListener("confirmation:open", handleArchiveOpen);
    document.addEventListener("confirmation:open", handleArchiveOpen);
    document.removeEventListener("click", handleConfirmArchiveClick);
    document.addEventListener("click", handleConfirmArchiveClick);

    async function handleConfirmArchiveClick(e) {
        const btn = e.target.closest(ConfirmArchiveSelector);
        if (!btn) return;

        const modalEl = document.getElementById(ModalArchiveSelector);
        const id = modalEl?.dataset.targetId;
        if (!id) return;

        showPageLoading();
        try {
            const response = await fetch(`/blood-transfusion/${id}/archive`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": getCsrfToken(),
                },
            });
            const result = await response.json();

            if (!response.ok) {
                notyf.error({
                    message: result.message || "Failed to archive data!",
                });
                hidePageLoading();
                return;
            }

            notyf.success({
                message: result.message || "Data archived successfully!",
            });
            if (modalEl) bootstrap.Modal.getInstance(modalEl)?.hide();
            if (modalEl) delete modalEl.dataset.targetId;
            reloadTable();
            hidePageLoading();
        } catch (error) {
            console.error(error);
            notyf.error({ message: "Failed to archive data!" });
            hidePageLoading();
        }
    }
}
