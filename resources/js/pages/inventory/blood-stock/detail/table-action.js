import {
    GlobalAdvanceFlatpickr,
    GlobalDeleteDataConfirmation,
    GlobalRestoreDataConfirmation,
    GlobalEditData,
    GlobalAdvanceTomselect,
    GlobalFormValidation,
    GlobalSubmitForm,
    GlobalDataConfirmation,
} from "../../../../app";
import { BloodStockStatus } from "../../../../utility/config/status-config";

// ---------- Global variable :begin ----------
const StockBloodDataURL = "/inventory/blood-stock/detail/data";
const StockBloodDataGetDataURL = "/inventory/blood-stock/detail/get-data";
const PrintBarcodeLicaBloodStockURL =
    "/inventory/blood-stock/detail/print-barcode-lica";
const DownloadBarcodeLicaBloodStockURL =
    "/inventory/blood-stock/detail/download-barcode-lica";

const ModalDeleteSelector = "delete_data_stock_blood_modal";
const ActionDeleteSelector = ".btn-delete-stock-blood";
const AttributeDelete = "deleteId";
const ConfirmDeleteSelector = "#confirm_delete";

const ModalPermanentDeleteSelector = "permanent_delete_data_blood_stock_modal";
const ActionPermanentDeleteSelector = ".btn-permanent-delete-stock-blood";
const AttributePermanentDelete = "permanentDeleteId";
const ConfirmPermanentDeleteSelector = "#confirm_permanent_delete";

const FormEditSelector = "edit_data_stock_blood";
const ModalEditSelector = "edit_data_stock_blood_modal";
const ActionEditSelector = ".btn-edit-stock-blood";
const AttributeEdit = "editId";

const ModalReturnSelector = "return_data_stock_blood_modal";
const ActionReturnSelector = ".btn-return-stock-blood";
const AttributeReturn = "returnId";
const ConfirmReturnSelector = "#confirm_return";
const CancelTransactionSelector = "#is_cancel_transaction";
const CancelReasonSelector = "#cancel_reason";
const CancelReasonWrapperSelector = "#cancel_transaction_reason_wrapper";
const CancelReasonErrorSelector = "#cancel_reason_error";

const ModalRestoreSelector = "restore_data_stock_blood_modal";
const ActionRestoreSelector = ".btn-restore-stock-blood";
const AttributeRestore = "restoreId";
const ConfirmRestoreSelector = "#confirm_restore";

const ActionPrintBarcodeLicaSelector = ".btn-print-barcode-lica-stock-blood";
const AttributePrintBarcodeLica = "printBarcodeLicaId";
const ActionDownloadBarcodeLicaSelector =
    ".btn-download-barcode-lica-stock-blood";
const AttributeDownloadBarcodeLica = "downloadBarcodeLicaId";
// ---------- Global variable :end ----------

export class TableActionHandler {
    constructor(reloadTable) {
        this.reloadTable = reloadTable;
    }

    #getCsrfToken() {
        return document
            .querySelector('meta[name="csrf-token"]')
            ?.getAttribute("content");
    }

    #getModalInstance(selectorId) {
        const modalEl = document.getElementById(selectorId);
        if (!modalEl) return null;
        return (
            bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl)
        );
    }

    DeleteDataStockBloodActionModal() {
        new GlobalDeleteDataConfirmation({
            ButtonSelector: ActionDeleteSelector,
            DataAttributeID: AttributeDelete,
            UrlFetchData: (id) => StockBloodDataGetDataURL + `/${id}`,
            ModalConfirmID: ModalDeleteSelector,
        });

        document.addEventListener("delete:open", (e) => {
            const { data } = e.detail;
            if (!data) return;

            document.querySelector("#deleted_data").textContent =
                `${data.bag_number} with ID ${data.public_id}`;
            document.querySelector(ConfirmDeleteSelector).dataset.id =
                data.public_id;
        });

        const confirmBtn = document.querySelector(ConfirmDeleteSelector);
        if (!confirmBtn) return;

        confirmBtn.addEventListener("click", async () => {
            const id = confirmBtn.dataset.id;
            if (!id) return;

            try {
                const response = await fetch(StockBloodDataURL + `/${id}`, {
                    method: "DELETE",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": this.#getCsrfToken(),
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
                this.#getModalInstance(ModalDeleteSelector)?.hide();
                confirmBtn.dataset.id = "";
                this.reloadTable();
            } catch (error) {
                console.error(error);
                notyf.error({ message: "Failed to delete data!" });
            }
        });
    }

    PermanentDeleteDataStockBloodActionModal() {
        new GlobalDeleteDataConfirmation({
            ButtonSelector: ActionPermanentDeleteSelector,
            DataAttributeID: AttributePermanentDelete,
            UrlFetchData: (id) => StockBloodDataGetDataURL + `/${id}`,
            ModalConfirmID: ModalPermanentDeleteSelector,
        });

        document.addEventListener("delete:open", (e) => {
            const { data } = e.detail;
            if (!data) return;

            document.querySelector("#permanent_deleted_data").textContent =
                `${data.bag_number} with ID ${data.public_id}`;
            document.querySelector(ConfirmPermanentDeleteSelector).dataset.id =
                data.public_id;
        });

        const confirmBtn = document.querySelector(
            ConfirmPermanentDeleteSelector,
        );
        if (!confirmBtn) return;

        confirmBtn.addEventListener("click", async () => {
            const id = confirmBtn.dataset.id;
            if (!id) return;

            try {
                const response = await fetch(
                    StockBloodDataURL + `/${id}/permanent`,
                    {
                        method: "DELETE",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": this.#getCsrfToken(),
                        },
                    },
                );

                const result = await response.json();

                if (!response.ok) {
                    notyf.error({
                        message:
                            result.message ||
                            "Failed to permanent delete data!",
                    });
                    return;
                }
                notyf.success({
                    message:
                        result.message ||
                        "Data permanent deleted successfully!",
                });
                this.#getModalInstance(ModalDeleteSelector)?.hide();
                confirmBtn.dataset.id = "";
                this.reloadTable();
            } catch (error) {
                console.error(error);
                notyf.error({ message: "Failed to permanent data!" });
            }
        });
    }

    EditDataStockBloodActionModal() {
        new GlobalEditData({
            ButtonSelector: ActionEditSelector,
            DataAttributeID: AttributeEdit,
            UrlFetchData: (id) => StockBloodDataGetDataURL + `/${id}`,
            ModalEditID: ModalEditSelector,
            FormSelector: FormEditSelector,
        });

        document.addEventListener("edit:open", function (e) {
            const { data } = e.detail;
            if (!data) return;

            // ---------- Volume ----------
            document.querySelector("#edit_data_blood_stock_volume").value =
                data.blood_volume ?? "";

            // ---------- Storage Rack (TomSelect) ----------
            const selectStorageRack = document.querySelector(
                "#edit_data_blood_stock_storage_rack",
            );
            if (selectStorageRack?.tomselect) {
                selectStorageRack.tomselect.clear();
                if (data.storage_rack_id) {
                    selectStorageRack.tomselect.setValue(
                        data.storage_racks.public_id,
                    );
                }
            }

            // ---------- Aftap Date (Flatpickr) ----------
            const aftapDate = document.querySelector(
                "#edit_data_blood_stock_aftap_date",
            );
            if (aftapDate?._flatpickr && data.aftap_date) {
                aftapDate._flatpickr.setDate(data.aftap_date, true);
            }
            // ---------- Process Date (Flatpickr) ----------
            const processDate = document.querySelector(
                "#edit_data_blood_stock_process_date",
            );
            if (processDate?._flatpickr && data.process_date) {
                processDate._flatpickr.setDate(data.process_date, true);
            }
            // ---------- Expiry Date (Flatpickr) ----------
            const expiryDate = document.querySelector(
                "#edit_data_blood_stock_expiry_date",
            );
            if (expiryDate?._flatpickr && data.expiry_date) {
                expiryDate._flatpickr.setDate(data.expiry_date, true);
            }

            // ---------- Blood Status (TomSelect) ----------
            const selectBloodStatus = document.querySelector(
                "#edit_data_blood_stock_status",
            );
            if (selectBloodStatus?.tomselect) {
                selectBloodStatus.tomselect.clear();
                if (data.blood_status) {
                    selectBloodStatus.tomselect.setValue(data.blood_status);
                }
            }

            // ---------- Checkbox: Is Expired ----------
            document.querySelector(
                "#edit_data_blood_stock_is_expired",
            ).checked = data.is_expired == 1;

            // ---------- Set data-id ke form untuk kebutuhan submit PATCH ----------
            document.querySelector("#" + FormEditSelector).dataset.id =
                data.public_id;
        });

        const EditDataStockBloodValidation = GlobalFormValidation.init(
            "#" + FormEditSelector,
            {
                volume: {
                    validators: {
                        notEmpty: {
                            message: "Volume is required",
                        },
                    },
                },
                status: {
                    validators: {
                        notEmpty: {
                            message: "Status is required",
                        },
                    },
                },
            },
        );

        // ---------- Submit form ke url  ----------
        new GlobalSubmitForm({
            formId: FormEditSelector,
            url: () => {
                const form = document.getElementById(FormEditSelector);
                return StockBloodDataURL + `/${form.dataset.id}`;
            },
            method: "PATCH",
            validator: EditDataStockBloodValidation,
            onSuccess: (data) => {
                notyf.success({
                    message: "Data blood stock updated succesfully!",
                });
                this.#getModalInstance(ModalEditSelector)?.hide();
                this.reloadTable();
            },
            onError: (err) => {
                notyf.error({
                    message: "Data blood stock failed to update!",
                });
            },

            resetOnSuccess: true,
        });
    }

    RestoreDataStockBloodActionModal() {
        new GlobalRestoreDataConfirmation({
            ButtonSelector: ActionRestoreSelector,
            DataAttributeID: AttributeRestore,
            UrlFetchData: (id) => StockBloodDataGetDataURL + `/${id}`,
            ModalConfirmID: ModalRestoreSelector,
        });

        document.addEventListener("restore:open", (e) => {
            const { data } = e.detail;
            if (!data) return;

            document.querySelector("#restored_data").textContent =
                `${data.bag_number} with ID ${data.public_id}`;
            document.querySelector(ConfirmRestoreSelector).dataset.id =
                data.public_id;
        });

        const confirmBtn = document.querySelector(ConfirmRestoreSelector);
        if (!confirmBtn) return;

        confirmBtn.addEventListener("click", async () => {
            const id = confirmBtn.dataset.id;
            if (!id) return;

            try {
                const response = await fetch(
                    StockBloodDataURL + `/${id}/restore`,
                    {
                        method: "PATCH",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": this.#getCsrfToken(),
                        },
                    },
                );

                const result = await response.json();

                if (!response.ok) {
                    notyf.error({
                        message: result.message || "Failed to restore data!",
                    });
                    return;
                }
                notyf.success({
                    message: result.message || "Data restored successfully!",
                });
                this.#getModalInstance(ModalRestoreSelector)?.hide();
                confirmBtn.dataset.id = "";
                this.reloadTable();
            } catch (error) {
                console.error(error);
                notyf.error({ message: "Failed to restore data!" });
            }
        });
    }

    PrintBarcodeLicaStockBloodAction() {
        document.addEventListener("click", async (e) => {
            const btn = e.target.closest(ActionPrintBarcodeLicaSelector);
            if (!btn) return;

            const id = btn.dataset[AttributePrintBarcodeLica];
            if (!id) return;

            showPageLoading();
            btn.disabled = true;

            try {
                const response = await fetch(
                    PrintBarcodeLicaBloodStockURL + `/${id}`,
                    {
                        method: "GET",
                        headers: {
                            "X-CSRF-TOKEN": this.#getCsrfToken(),
                            Accept: "application/json",
                        },
                    },
                );

                const result = await response.json();

                if (!response.ok) {
                    notyf.error({
                        message:
                            result.message || "Failed to prepare print data!",
                    });
                    hidePageLoading();
                    return;
                }

                const {
                    barcode_url,
                    bag_number_lica,
                    bag_number,
                    blood_group,
                    blood_rhesus,
                    blood_component,
                } = result.data;

                const printHtml = `<!DOCTYPE html>
                    <html>
                        <head>
                            <meta charset="UTF-8">
                            <title>Barcode - ${bag_number_lica}</title>
                            <style>
                                * { margin: 0; padding: 0; box-sizing: border-box; }

                                @page {
                                    size: 50mm 20mm;
                                    margin: 0;
                                }

                                body {
                                    width: 50mm;
                                    height: 20mm;
                                    display: flex;
                                    flex-direction: column;
                                    align-items: center;
                                    justify-content: center;
                                    font-family: monospace;
                                    font-size: 6pt;
                                    overflow: hidden;
                                }

                                .barcode-label {
                                    width: 100%;
                                    height: 100%;
                                    display: flex;
                                    flex-direction: column;
                                    align-items: center;
                                    justify-content: center;
                                    padding: 1mm;
                                    gap: 0.5mm;
                                }

                                .barcode-meta {
                                    font-size: 5pt;
                                    text-align: center;
                                    line-height: 1.3;
                                    letter-spacing: 0.3px;
                                }

                                .barcode-img {
                                    width: 46mm;
                                    height: 12mm;
                                    object-fit: contain;
                                }

                                .barcode-number {
                                    font-size: 5pt;
                                    letter-spacing: 1px;
                                    text-align: center;
                                }
                            </style>
                        </head>
                        <body>
                            <div class="barcode-label">
                                <div class="barcode-meta">${blood_group}${blood_rhesus} &bull; ${blood_component} &bull; ${bag_number}</div>
                                <img class="barcode-img" src="${barcode_url}" alt="Barcode ${bag_number_lica}" />
                                <div class="barcode-number">${bag_number_lica}</div>
                            </div>
                        </body>
                    </html>`;

                // ---------- Inject ke iframe hidden & trigger print ----------
                const iframe = document.createElement("iframe");
                iframe.style.cssText =
                    "position:fixed;width:0;height:0;border:none;left:-9999px;top:-9999px;";
                document.body.appendChild(iframe);

                iframe.addEventListener("load", () => {
                    try {
                        iframe.contentWindow.focus();
                        iframe.contentWindow.print();
                    } catch (printErr) {
                        console.error("Print error:", printErr);
                        notyf.error({
                            message: "Failed to open print dialog!",
                        });
                    } finally {
                        setTimeout(() => {
                            if (document.body.contains(iframe)) {
                                document.body.removeChild(iframe);
                            }
                        }, 2000);
                    }
                });

                iframe.contentDocument.open();
                iframe.contentDocument.write(printHtml);
                iframe.contentDocument.close();

                notyf.success({ message: "Print dialog opened successfully!" });
            } catch (error) {
                console.error(error);
                notyf.error({ message: "Failed to prepare print data!" });
                hidePageLoading();
            } finally {
                btn.disabled = false;
                hidePageLoading();
            }
        });
    }

    DownloadBarcodeLicaStockBloodAction() {
        document.addEventListener("click", async (e) => {
            const btn = e.target.closest(ActionDownloadBarcodeLicaSelector);
            if (!btn) return;

            const id = btn.dataset[AttributeDownloadBarcodeLica];
            if (!id) return;

            btn.disabled = true;
            showPageLoading();

            try {
                const response = await fetch(
                    DownloadBarcodeLicaBloodStockURL + `/${id}`,
                    {
                        method: "GET",
                        headers: {
                            "X-CSRF-TOKEN": this.#getCsrfToken(),
                        },
                    },
                );

                if (!response.ok) {
                    const result = await response.json();
                    notyf.error({
                        message:
                            result.message || "Failed to download barcode!",
                    });
                    hidePageLoading();
                    return;
                }

                // ---------- Parse filename dari Content-Disposition ----------
                const disposition = response.headers.get("Content-Disposition");
                let filename = "barcode-lica.png"; // fallback — PNG bukan PDF
                if (disposition) {
                    const match = disposition.match(
                        /filename[^;=\n]*=(['"]?)([^'";\n]*)\1/,
                    );
                    if (match?.[2]) {
                        filename = match[2].trim();
                    }
                }

                // ---------- Blob → trigger download ----------
                const blob = await response.blob();
                const objectUrl = URL.createObjectURL(blob);

                const anchor = document.createElement("a");
                anchor.href = objectUrl;
                anchor.download = filename;
                anchor.style.display = "none";
                document.body.appendChild(anchor);
                anchor.click();

                document.body.removeChild(anchor);
                URL.revokeObjectURL(objectUrl);

                notyf.success({ message: "Barcode downloaded successfully!" });
            } catch (error) {
                console.error(error);
                notyf.error({ message: "Failed to download barcode!" });
                hidePageLoading();
            } finally {
                btn.disabled = false;
                hidePageLoading();
            }
        });
    }

    ReturnStockBloodActionModal() {
        new GlobalDataConfirmation({
            ButtonSelector: ActionReturnSelector,
            DataAttributeID: AttributeReturn,
            UrlFetchData: (id) => StockBloodDataGetDataURL + `/${id}`,
            ModalConfirmID: ModalReturnSelector,
        });

        const selectEl = document.querySelector("#return_data_blood_stock");
        const cancelCheckbox = document.querySelector(
            CancelTransactionSelector,
        );
        const cancelReasonEl = document.querySelector(CancelReasonSelector);
        const cancelReasonWrapper = document.querySelector(
            CancelReasonWrapperSelector,
        );
        const cancelReasonError = document.querySelector(
            CancelReasonErrorSelector,
        );

        const updateConfirmButtonState = () => {
            const confirmBtn = document.querySelector(ConfirmReturnSelector);
            if (!confirmBtn) return;
            const selectedBag = selectEl?.tomselect?.getValue?.() || "";
            const isCancel = cancelCheckbox?.checked || false;
            const reason = cancelReasonEl?.value?.trim() || "";

            // Enabled jika: ada labu dipilih, ATAU cancel + ada alasan
            if (selectedBag || (isCancel && reason.length > 0)) {
                confirmBtn.disabled = false;
            } else {
                confirmBtn.disabled = true;
            }
        };
        const clearCancelReasonValidation = () => {
            if (cancelReasonEl) {
                cancelReasonEl.classList.remove("is-invalid");
            }
            if (cancelReasonError) {
                cancelReasonError.textContent = "";
            }
        };
        const setCancelReasonInvalid = (
            message = "Alasan pembatalan wajib diisi!",
        ) => {
            if (cancelReasonEl) {
                cancelReasonEl.classList.add("is-invalid");
            }
            if (cancelReasonError) {
                cancelReasonError.textContent = message;
            }
        };

        document.addEventListener("confirmation:open", (e) => {
            const { data } = e.detail;
            if (!data) return;

            const transfusionDetail = data.blood_transfusion_details?.[0];
            const patient = transfusionDetail?.blood_transfusion?.patient;
            const releasedBy = transfusionDetail?.blood_released_by_user;
            const genderMap = { M: "Laki-laki", F: "Perempuan" };

            // ---------- Tabel: patient_blood_before ----------
            const beforeTable = document.querySelector(
                '[data-table="patient_blood_before"]',
            );
            if (beforeTable) {
                beforeTable.querySelector("#bag_number").textContent =
                    data.bag_number ?? "-";
                beforeTable.querySelector("#blood_status").innerHTML =
                    BloodStockStatus(data.blood_status);
                beforeTable.querySelector("#bdrs_no").textContent =
                    transfusionDetail?.blood_transfusion?.lab_number ?? "-";
                beforeTable.querySelector("#patient_name").textContent =
                    patient?.name ?? "-";
                beforeTable.querySelector("#gender").textContent =
                    genderMap[patient?.gender] ?? patient?.gender ?? "-";
                beforeTable.querySelector("#released_at").textContent =
                    transfusionDetail?.blood_released_at ?? "-";
                beforeTable.querySelector("#released_by").textContent =
                    releasedBy?.name ?? "-";
            }

            // ---------- Tabel: patient_blood_new ----------
            const newTable = document.querySelector(
                '[data-table="patient_blood_new"]',
            );
            if (newTable) {
                newTable.querySelector("#blood_status").innerHTML = "-";
                newTable.querySelector("#bdrs_no").textContent =
                    transfusionDetail?.blood_transfusion?.lab_number ?? "-";
                newTable.querySelector("#patient_name").textContent =
                    patient?.name ?? "-";
                newTable.querySelector("#gender").textContent =
                    genderMap[patient?.gender] ?? patient?.gender ?? "-";
            }

            // ---------- Reset semua state modal ----------
            if (selectEl?.tomselect) {
                selectEl.tomselect.clear();
                selectEl.tomselect.enable();
            }
            if (cancelCheckbox) {
                cancelCheckbox.checked = false;
                cancelCheckbox.disabled = false;
            }
            if (cancelReasonEl) {
                cancelReasonEl.value = "";
            }
            if (cancelReasonWrapper) {
                cancelReasonWrapper.classList.add("d-none");
            }
            clearCancelReasonValidation();

            // ---------- Reset confirm button ----------
            const confirmBtn = document.querySelector(ConfirmReturnSelector);
            if (confirmBtn) {
                confirmBtn.disabled = true;
                confirmBtn.dataset.id = data.public_id;
                confirmBtn.dataset.bloodTransfusionId =
                    transfusionDetail?.blood_transfusion?.public_id ?? "";
                confirmBtn.dataset.selectedBloodStockId = "";
                confirmBtn.dataset.isCancelTransaction = "0";
                confirmBtn.dataset.cancelReason = "";
            }
        });

        // ---------- Listener: Checkbox is_cancel_transaction ----------
        if (cancelCheckbox && selectEl) {
            cancelCheckbox.addEventListener("change", () => {
                const confirmBtn = document.querySelector(
                    ConfirmReturnSelector,
                );
                const ts = selectEl.tomselect;
                const newTable = document.querySelector(
                    '[data-table="patient_blood_new"]',
                );
                const statusCell = newTable?.querySelector("#blood_status");

                if (cancelCheckbox.checked) {
                    // disable tomselect & clear, tampilkan textarea
                    if (ts) {
                        ts.clear();
                        ts.disable();
                    }
                    cancelReasonWrapper?.classList.remove("d-none");
                    if (statusCell) statusCell.innerHTML = "-";
                    if (confirmBtn) {
                        confirmBtn.dataset.selectedBloodStockId = "";
                        confirmBtn.dataset.isCancelTransaction = "1";
                    }
                } else {
                    // aktifkan kembali tomselect, sembunyikan textarea
                    if (ts) ts.enable();
                    cancelReasonWrapper?.classList.add("d-none");
                    if (cancelReasonEl) cancelReasonEl.value = "";
                    clearCancelReasonValidation();
                    if (confirmBtn) {
                        confirmBtn.dataset.isCancelTransaction = "0";
                        confirmBtn.dataset.cancelReason = "";
                    }
                }
                updateConfirmButtonState();
            });
        }

        // ---------- Listener: Textarea cancel_reason ----------
        if (cancelReasonEl) {
            cancelReasonEl.addEventListener("input", (e) => {
                const confirmBtn = document.querySelector(
                    ConfirmReturnSelector,
                );
                if (confirmBtn) {
                    confirmBtn.dataset.cancelReason = e.target.value.trim();
                }
                if (e.target.value.trim().length > 0) {
                    clearCancelReasonValidation();
                }
                updateConfirmButtonState();
            });
        }

        // ---------- Listener: TomSelect return_data_blood_stock ----------
        if (selectEl) {
            const attachTomSelectListener = () => {
                const ts = selectEl.tomselect;
                if (!ts) return;
                ts.on("change", async (value) => {
                    const confirmBtn = document.querySelector(
                        ConfirmReturnSelector,
                    );
                    const newTable = document.querySelector(
                        '[data-table="patient_blood_new"]',
                    );
                    const statusCell = newTable?.querySelector("#blood_status");
                    if (!statusCell) return;
                    if (!value) {
                        // Value dikosongkan — aktifkan kembali checkbox
                        if (cancelCheckbox) cancelCheckbox.disabled = false;
                        if (confirmBtn)
                            confirmBtn.dataset.selectedBloodStockId = "";
                        updateConfirmButtonState();
                        return;
                    }

                    try {
                        const response = await fetch(
                            `/utility/get/blood-stock/${value}`,
                            {
                                method: "GET",
                                headers: {
                                    "X-CSRF-TOKEN": this.#getCsrfToken(),
                                    Accept: "application/json",
                                },
                            },
                        );
                        const result = await response.json();
                        if (!response.ok) {
                            statusCell.innerHTML = "-";
                            if (confirmBtn) {
                                confirmBtn.disabled = true;
                                confirmBtn.dataset.selectedBloodStockId = "";
                            }
                            return;
                        }

                        statusCell.innerHTML = BloodStockStatus(
                            result.blood_status,
                        );

                        if (confirmBtn) {
                            confirmBtn.dataset.selectedBloodStockId =
                                result.public_id ?? value;
                            confirmBtn.dataset.isCancelTransaction = "0";
                        }
                        if (cancelCheckbox) {
                            cancelCheckbox.checked = false;
                            cancelCheckbox.disabled = true;
                        }
                        if (cancelReasonEl) cancelReasonEl.value = "";
                        if (cancelReasonWrapper)
                            cancelReasonWrapper.classList.add("d-none");

                        clearCancelReasonValidation();
                        updateConfirmButtonState();
                    } catch (error) {
                        console.error(error);
                        statusCell.innerHTML = "-";
                        if (confirmBtn) {
                            confirmBtn.disabled = true;
                            confirmBtn.dataset.selectedBloodStockId = "";
                        }
                    }
                });
            };
            if (selectEl.tomselect) {
                attachTomSelectListener();
            } else {
                const observer = new MutationObserver(() => {
                    if (selectEl.tomselect) {
                        observer.disconnect();
                        attachTomSelectListener();
                    }
                });
                observer.observe(selectEl, {
                    attributes: true,
                    attributeFilter: ["class"],
                });
            }
        }

        // ---------- Listener: Tombol Konfirmasi ----------
        const confirmBtn = document.querySelector(ConfirmReturnSelector);
        if (!confirmBtn) return;
        confirmBtn.addEventListener("click", async () => {
            const id = confirmBtn.dataset.id;
            const bloodTransfusionId = confirmBtn.dataset.bloodTransfusionId;
            const isCancelTransaction =
                confirmBtn.dataset.isCancelTransaction === "1";
            const cancelReason = confirmBtn.dataset.cancelReason ?? "";

            const selectedBloodStockId = isCancelTransaction
                ? ""
                : (confirmBtn.dataset.selectedBloodStockId ?? "");
            if (!id) return;

            if (
                isCancelTransaction &&
                (!cancelReason || cancelReason.trim() === "")
            ) {
                setCancelReasonInvalid(
                    "Alasan pembatalan transaksi wajib diisi!",
                );
                notyf.error({
                    message: "Alasan pembatalan transaksi wajib diisi!",
                });
                return;
            }
            try {
                const response = await fetch(
                    StockBloodDataURL + `/${id}/return`,
                    {
                        method: "PATCH",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": this.#getCsrfToken(),
                        },
                        body: JSON.stringify({
                            blood_transfusion_id: bloodTransfusionId,
                            new_blood_stock_id: selectedBloodStockId,
                            is_cancel_transaction: isCancelTransaction,
                            cancel_reason: cancelReason,
                        }),
                    },
                );

                const result = await response.json();
                if (!response.ok) {
                    notyf.error({
                        message: result.message || "Gagal mengembalikan darah!",
                    });
                    return;
                }
                notyf.success({
                    message:
                        result.message ||
                        "Darah berhasil dikembalikan ke stock!",
                });
                this.#getModalInstance(ModalReturnSelector)?.hide();
                confirmBtn.dataset.id = "";
                this.reloadTable();
            } catch (error) {
                console.error(error);
                notyf.error({ message: "Gagal mengembalikan darah!" });
                this.reloadTable();
            }
        });
    }

    init() {
        this.DeleteDataStockBloodActionModal();
        this.PermanentDeleteDataStockBloodActionModal();
        this.EditDataStockBloodActionModal();
        this.RestoreDataStockBloodActionModal();
        this.ReturnStockBloodActionModal();
        this.PrintBarcodeLicaStockBloodAction();
        this.DownloadBarcodeLicaStockBloodAction();
    }
}
