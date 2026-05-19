import {
    GlobalAdvanceFlatpickr,
    GlobalDeleteDataConfirmation,
    GlobalRestoreDataConfirmation,
    GlobalEditData,
    GlobalAdvanceTomselect,
    GlobalFormValidation,
    GlobalSubmitForm,
} from "../../../../app";

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
                storage_rack_id: {
                    validators: {
                        notEmpty: {
                            message: "Storage rack is required",
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

    init() {
        this.DeleteDataStockBloodActionModal();
        this.PermanentDeleteDataStockBloodActionModal();
        this.EditDataStockBloodActionModal();
        this.RestoreDataStockBloodActionModal();
        this.PrintBarcodeLicaStockBloodAction();
        this.DownloadBarcodeLicaStockBloodAction();
    }
}
