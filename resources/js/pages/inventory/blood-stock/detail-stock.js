// ---------- Import Libraries ----------
import {
    GlobalAdvanceDatatable,
    GlobalAdvanceFlatpickr,
    GlobalDeleteDataConfirmation,
    GlobalRestoreDataConfirmation,
    GlobalEditData,
    DateTimeFormatter,
} from "../../../app";
import TomSelect from "tom-select";

// ---------- Global variable untuk memudahkan penyesuaian :begin ----------
let stockBloodDataTableInstance; // instance datatable untuk global

// Filter datatable
const DateFilterSelector = ".stock-blood-data-table-date-filter"; // class selector filter tanggal

// Datatable
const DatatableSelector = "#stock-blood-data-table"; // id selector datatable
const ReloadDatatableSelector = "stock-blood-data-reload"; // index reload datatable

// URL
const StockBloodDataURL = "/inventory/blood-stock/detail/data"; // url fetch data untuk datatable
const StockBloodDataGetDataURL = "/inventory/blood-stock/detail/get-data";
const PrintBarcodeLicaBloodStockURL =
    "/inventory/blood-stock/detail/print-barcode-lica";
const DownloadBarcodeLicaBloodStockURL =
    "/inventory/blood-stock/detail/download-barcode-lica";

// Print Barcode Action
const ActionPrintBarcodeLicaSelector = ".btn-print-barcode-lica-stock-blood";
const AttributePrintBarcodeLica = "printBarcodeLicaId";

// Download Barcode Action
const ActionDownloadBarcodeLicaSelector =
    ".btn-download-barcode-lica-stock-blood";
const AttributeDownloadBarcodeLica = "downloadBarcodeLicaId";

// Delete Action
const ModalDeleteSelector = "delete_data_stock_blood_modal"; // id selector modal delete
const ActionDeleteSelector = ".btn-delete-stock-blood"; // class selector button delete
const AttributeDelete = "deleteId"; // attribute data id delete
const ConfirmDeleteSelector = "#confirm_delete"; // id selector confirm delete

// Restore Action
const ModalRestoreSelector = "restore_data_stock_blood_modal"; // id selector modal restore
const ActionRestoreSelector = ".btn-restore-stock-blood"; // class selector button restore
const AttributeRestore = "restoreId"; // attribute data id restore
const ConfirmRestoreSelector = "#confirm_restore"; // id selector confirm restore
// ---------- Global variable untuk memudahkan penyesuaian :end ----------

const id = getIdFromUrl();

// ---------- Helper: ambil ID dari URL saat ini----------
function getIdFromUrl() {
    const segments = window.location.pathname.split("/");
    return segments[segments.length - 1];
}

// ---------- Helper: Ambil semua filter :begin ----------
function getFilters() {
    const dateVal = document.querySelector(DateFilterSelector)?.value;

    let start_date = "";
    let end_date = "";

    if (dateVal) {
        const separator = dateVal.includes(" to ") ? " to " : " - ";
        const parts = dateVal.split(separator);

        start_date = parts[0] || "";
        end_date = parts[1] || "";
    }

    return { start_date, end_date };
}
// ---------- Helper: Ambil semua filter :end ----------

// ---------- Helper: Reload tabel :begin ----------
function reloadTable() {
    if (stockBloodDataTableInstance?.instance) {
        stockBloodDataTableInstance.instance.ajax.reload();
    }
}
// ---------- Helper: Reload tabel :end ----------

// ---------- Filter tanggal dari flatpickr untuk data di tabel :begin ----------
function DateRangeFilter() {
    new GlobalAdvanceFlatpickr(DateFilterSelector, {
        onClose: reloadTable,
    });
}
// ---------- Filter tanggal dari flatpickr untuk data di tabel :end ----------

// ---------- Datatable untuk master storage :begin ----------
function BloodStockDataTable() {
    // ---------- Init kolom pada tabel ----------
    const BloodStockDataTableColumns = [
        {
            data: null,
            title: "No",
            render: (data, type, row, meta) => {
                return meta.row + 1;
            },
        },
        { data: "bag_number", title: "Bag Number" },
        { data: "bag_number_lica", title: "Bag Number LICA" },
        {
            data: null,
            title: "Blood Pack",
            render: (data, row) => {
                const bloodPack = data.blood_packs;
                return `${bloodPack.blood_group}${bloodPack.blood_rhesus} ${bloodPack.blood_component}`;
            },
        },
        { data: "blood_volume", title: "Volume" },
        { data: "blood_status", title: "Status" },
        {
            data: "aftap_date",
            title: "Aftap",
            render: (data) => {
                return DateTimeFormatter.dateOnly(data);
            },
        },
        {
            data: "process_date",
            title: "Process",
            render: (data) => {
                return DateTimeFormatter.dateOnly(data);
            },
        },
        {
            data: "expiry_date",
            title: "Expired",
            render: (data) => {
                return DateTimeFormatter.dateOnly(data);
            },
        },
        {
            data: "used_at",
            title: "Used At",
            render: (data) => {
                return DateTimeFormatter.human(data);
            },
        },
        {
            data: "created_at",
            title: "Ready At",
            render: (data) => {
                return DateTimeFormatter.human(data);
            },
        },
        {
            data: "deleted_at",
            title: "Deleted At",
            render: (data) => {
                return DateTimeFormatter.human(data);
            },
        },
        {
            data: null,
            title: "Action",
            render: (data, type, row, meta) => {
                const isDeleted = row.deleted_at !== null;
                return `<button aria-expanded="false" class="btn btn-sm btn-soft-primary datatable-action-toggle" data-bs-toggle="dropdown" data-bs-auto-close="true" type="button">
                  <i class="ti ti-dots align-middle"></i>
                 </button>
                 <ul class="dropdown-menu">
                     <li>
                         <button id="print-barcode-lica-data-${row.public_id}" class="dropdown-item fw-medium btn-print-barcode-lica-stock-blood ${isDeleted ? "disabled text-muted" : "text-primary"}" data-print-barcode-lica-id="${row.public_id}" type="button">
                         <i class="ti ti-barcode align-middle me-2 fs-4"></i>
                         Print Barcode LICA
                         </button>
                     </li>
                     <li>
                         <button id="download-barcode-lica-data-${row.public_id}" class="dropdown-item fw-medium btn-download-barcode-lica-stock-blood ${isDeleted ? "disabled text-muted" : "text-primary"}" data-download-barcode-lica-id="${row.public_id}" type="button">
                         <i class="ti ti-file-barcode align-middle me-2 fs-4"></i>
                         Download Barcode LICA
                         </button>
                     </li>
                     <li>
                         <button id="restore-data-${row.public_id}" class="dropdown-item fw-medium btn-restore-stock-blood ${isDeleted ? "enabled text-info" : "disabled"}" data-restore-id="${row.public_id}" type="button">
                         <i class="ti ti-recycle align-middle me-2 fs-4"></i>
                         Restore
                         </button>
                     </li>
                     <li>
                         <button id="delete-data-${row.public_id}" class="dropdown-item fw-medium btn-delete-stock-blood ${isDeleted ? "disabled text-muted" : "text-danger"}" data-delete-id="${row.public_id}" type="button">
                         <i class="ti ti-trash align-middle me-2 fs-4"></i>
                         Delete
                         </button>
                     </li>
                 </ul>
                `;
            },
        },
    ];

    // ---------- Panggil GlobalAdvanceDatatable untuk menampilkan tabel ----------
    stockBloodDataTableInstance = new GlobalAdvanceDatatable(
        DatatableSelector,
        {
            ajax: {
                url: StockBloodDataURL + `/${id}`,
                data: function (d) {
                    const filters = getFilters();
                    d.start_date = filters.start_date;
                    d.end_date = filters.end_date;
                },
            },
            columns: BloodStockDataTableColumns,
            useHideColumn: true,
            columnDefs: [
                {
                    targets: -1,
                    responsivePriority: 1,
                },
                {
                    targets: 0,
                    responsivePriority: 2,
                },
            ],
        },
    );
}
// ---------- Datatable untuk master storage :end ----------

// ---------- Handle modal delete data :begin ----------
function DeleteDataStockBloodActionModal() {
    // Panggil dan setup delete data
    new GlobalDeleteDataConfirmation({
        ButtonSelector: ActionDeleteSelector,
        DataAttributeID: AttributeDelete,
        UrlFetchData: (id) => StockBloodDataGetDataURL + `/${id}`,
        ModalConfirmID: ModalDeleteSelector,
    });

    // Custom isi modal
    document.addEventListener("delete:open", function (e) {
        const { data } = e.detail;
        if (!data) return;

        // Isi text ke modal
        document.querySelector("#deleted_data").textContent =
            `${data.bag_number} with ID ${data.public_id}`;

        // Berikan attribute button delete dengan id data
        document.querySelector(ConfirmDeleteSelector).dataset.id =
            data.public_id;
    });

    const confirmBtn = document.querySelector(ConfirmDeleteSelector);

    if (confirmBtn) {
        confirmBtn.addEventListener("click", async function () {
            const id = this.dataset.id;

            if (!id) return;

            try {
                const response = await fetch(StockBloodDataURL + `/${id}`, {
                    method: "DELETE",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document
                            .querySelector('meta[name="csrf-token"]')
                            .getAttribute("content"),
                    },
                });

                const result = await response.json();

                if (!response.ok) {
                    notyf.error({
                        message: result.message || "Failed to delete data!",
                    });
                }

                notyf.success({
                    message: result.message || "Data deleted successfully!",
                });

                const modalEl = document.getElementById(ModalDeleteSelector);
                const modal =
                    bootstrap.Modal.getInstance(modalEl) ||
                    new bootstrap.Modal(modalEl);

                modal.hide();

                this.dataset.id = "";

                reloadTable();

                console.log(result.message);
            } catch (error) {
                console.error(error);
                notyf.error({
                    message: error || "Failed to delete data!",
                });
            }
        });
    }
}
// ---------- Handle modal delete data :end ----------

// ---------- Handle modal restore data :begin ----------
function RestoreDataStockBloodActionModal() {
    // Panggil dan setup delete data
    new GlobalRestoreDataConfirmation({
        ButtonSelector: ActionRestoreSelector,
        DataAttributeID: AttributeRestore,
        UrlFetchData: (id) => StockBloodDataGetDataURL + `/${id}`,
        ModalConfirmID: ModalRestoreSelector,
    });

    // Custom isi modal
    document.addEventListener("restore:open", function (e) {
        const { data } = e.detail;
        if (!data) return;

        // Isi text ke modal
        document.querySelector("#restored_data").textContent =
            `${data.bag_number} with ID ${data.public_id}`;

        // Berikan attribute button restore dengan id data
        document.querySelector(ConfirmRestoreSelector).dataset.id =
            data.public_id;
    });

    const confirmBtn = document.querySelector(ConfirmRestoreSelector);

    if (confirmBtn) {
        confirmBtn.addEventListener("click", async function () {
            const id = this.dataset.id;

            if (!id) return;

            try {
                const response = await fetch(
                    StockBloodDataURL + `/${id}/restore`,
                    {
                        method: "PATCH",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": document
                                .querySelector('meta[name="csrf-token"]')
                                .getAttribute("content"),
                        },
                    },
                );

                const result = await response.json();

                if (!response.ok) {
                    notyf.error({
                        message: result.message || "Failed to restore data!",
                    });
                }

                notyf.success({
                    message: result.message || "Data restored successfully!",
                });

                const modalEl = document.getElementById(ModalRestoreSelector);
                const modal =
                    bootstrap.Modal.getInstance(modalEl) ||
                    new bootstrap.Modal(modalEl);

                modal.hide();

                this.dataset.id = "";

                reloadTable();

                console.log(result.message);
            } catch (error) {
                console.error(error);
                notyf.error({
                    message: error || "Failed to restore data!",
                });
            }
        });
    }
}
// ---------- Handle modal restore data :end ----------

// ---------- Handle print barcode lica :begin ----------
function PrintBarcodeLicaStockBloodAction() {
    const printBarcodeLicaBtn = document.querySelector(
        ActionPrintBarcodeLicaSelector,
    );

    if (printBarcodeLicaBtn) {
        printBarcodeLicaBtn.addEventListener("click", async function () {
            const id = this.dataset.id;
            if (!id) return;

            try {
                const response = await fetch(
                    PrintBarcodeLicaBloodStockURL + `/${id}`,
                    {
                        method: "GET",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": document
                                .querySelector('meta[name="csrf-token"]')
                                .getAttribute("content"),
                        },
                    },
                );

                const result = await response.json();

                if (!response.ok) {
                    notyf.error({
                        message:
                            result.message || "Failed to prepare print data!",
                    });
                }

                notyf.success({
                    message:
                        result.message ||
                        "Data prepared for print successfully!",
                });
            } catch (error) {
                console.error(error);
                notyf.error({
                    message: error || "Failed to prepare print data!",
                });
            }
        });
    }
}
// ---------- Handle print barcode lica :end ----------

// ---------- Handle download barcode lica :begin ----------
function DownloadBarcodeLicaStockBloodAction() {
    const downloadBarcodeLicaBtn = document.querySelector(
        ActionDownloadBarcodeLicaSelector,
    );

    if (downloadBarcodeLicaBtn) {
        downloadBarcodeLicaBtn.addEventListener("click", async function () {
            const id = this.dataset.id;
            if (!id) return;

            try {
                const response = await fetch(
                    DownloadBarcodeLicaBloodStockURL + `/${id}`,
                    {
                        method: "GET",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": document
                                .querySelector('meta[name="csrf-token"]')
                                .getAttribute("content"),
                        },
                    },
                );

                const result = await response.json();

                if (!response.ok) {
                    notyf.error({
                        message:
                            result.message || "Failed to prepare print data!",
                    });
                }

                notyf.success({
                    message:
                        result.message ||
                        "Data prepared for print successfully!",
                });
            } catch (error) {
                console.error(error);
                notyf.error({
                    message: error || "Failed to prepare print data!",
                });
            }
        });
    }
}
// ---------- Handle download barcode lica :end ----------

document.addEventListener("DOMContentLoaded", function () {
    // Datatable
    BloodStockDataTable();

    // Select function

    // Date range picker
    DateRangeFilter();

    // Action data
    DeleteDataStockBloodActionModal();
    RestoreDataStockBloodActionModal();
    PrintBarcodeLicaStockBloodAction();
    DownloadBarcodeLicaStockBloodAction();

    // Reload table
    window.addEventListener(ReloadDatatableSelector, function () {
        reloadTable();
    });
});
