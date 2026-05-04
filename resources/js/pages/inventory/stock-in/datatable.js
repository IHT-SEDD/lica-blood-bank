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
let stockInTableInstance; // instance datatable untuk global

// Filter datatable
const DateFilterSelector = ".stockin-table-date-filter"; // class selector filter tanggal

// Datatable
const DatatableSelector = "#stockin-table"; // id selector datatable
const DataURL = "/inventory/stock-in/data"; // url fetch data untuk datatable
const GetDataURL = "/inventory/stock-in/get-data"; // url fetch data
const ReloadDatatableSelector = "stock-in-reload"; // index reload datatable

// See More Action
const FormSeeSelector = "#see_data_stock_in"; // id selector form see
const ModalSeeSelector = "see_data_stock_in_modal"; // id selector modal see
const ActionSeeSelector = ".btn-see-stock-in"; // class selector button see
const AttributeSee = "seeId"; // attribute data id see

// Delete Action
const ModalDeleteSelector = "delete_data_stock_in_modal"; // id selector modal delete
const ActionDeleteSelector = ".btn-delete-stock-in"; // class selector button delete
const AttributeDelete = "deleteId"; // attribute data id delete
const ConfirmDeleteSelector = "#confirm_delete"; // id selector confirm delete

// Restore Action
const ModalRestoreSelector = "restore_data_stock_in_modal"; // id selector modal restore
const ActionRestoreSelector = ".btn-restore-stock-in"; // class selector button restore
const AttributeRestore = "restoreId"; // attribute data id restore
const ConfirmRestoreSelector = "#confirm_restore"; // id selector confirm restore
// ---------- Global variable untuk memudahkan penyesuaian :end ----------

// ---------- Helper: Ambil semua filter :begin ----------
function getFilters() {
    const status =
        document.querySelector("#filter-stockin-status")?.value || "";
    const vendor =
        document.querySelector("#filter-stockin-vendor")?.value || "";
    const dateVal = document.querySelector(DateFilterSelector)?.value;

    let start_date = "";
    let end_date = "";

    if (dateVal) {
        const separator = dateVal.includes(" to ") ? " to " : " - ";
        const parts = dateVal.split(separator);

        start_date = parts[0] || "";
        end_date = parts[1] || "";
    }

    return { status, vendor, start_date, end_date };
}
// ---------- Helper: Ambil semua filter :end ----------

// ---------- Helper: Reload tabel :begin ----------
function reloadTable() {
    if (stockInTableInstance?.instance) {
        stockInTableInstance.instance.ajax.reload();
    }
}
// ---------- Helper: Reload tabel :end ----------

// ---------- Datatable untuk master storage :begin ----------
function StockInTable() {
    // ---------- Init kolom pada tabel ----------
    const StockInTableColumns = [
        { data: "po_number", title: "PO Number" },
        {
            data: null,
            title: "Vendor",
            render: (data, type, row) => {
                return `<span class="badge badge-label badge-soft-primary">${row.order_bloods.vendors.name}</span>`;
            },
        },
        { data: "batch_number", title: "Batch Number" },
        { data: "total_blood_data", title: "Total Bloods" },
        {
            data: "status",
            title: "Status",
            render: (data, type, row) => {
                const isDeleted = row.deleted_at !== null;
                if (isDeleted) {
                    return `<span class="badge badge-label badge-soft-danger">Trashed</span>`;
                } else {
                    return `<span class="badge badge-label badge-soft-secondary">${data}</span>`;
                }
            },
        },
        {
            data: "created_at",
            title: "Created At",
            render: (data) => {
                return DateTimeFormatter.human(data);
            },
        },
        {
            data: "updated_at",
            title: "Updated At",
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

                return `<button aria-expanded="false" class="btn btn-sm btn-soft-primary datatable-action-toggle" data-bs-toggle="dropdown" 
                data-bs-auto-close="true" type="button">
                    <i class="ti ti-dots align-middle"></i>
                    </button>
                    <ul class="dropdown-menu">
                        <li>
                            <button id="see-data-${row.public_id}" class="dropdown-item fw-medium btn-see-stock-in ${isDeleted ? "disabled" : ""}" 
                            data-see-id="${row.public_id}" type="button">
                            See More</button>
                        </li>
                        <li>
                            <button id="restore-data-${row.public_id}" class="dropdown-item fw-medium btn-restore-stock-in ${isDeleted ? "enabled text-info" : "disabled"}" data-restore-id="${row.public_id}" type="button">
                            Restore</button>
                        </li>
                        <li>
                            <button id="delete-data-${row.public_id}" class="dropdown-item fw-medium btn-delete-stock-in ${isDeleted ? "disabled text-muted" : "text-danger"}" data-delete-id="${row.public_id}" type="button">
                            Delete</button>
                        </li>
                    </ul>
                `;
            },
        },
    ];

    // ---------- Panggil GlobalAdvanceDatatable untuk menampilkan tabel ----------
    stockInTableInstance = new GlobalAdvanceDatatable(DatatableSelector, {
        ajax: {
            url: DataURL,
            data: function (d) {
                const filters = getFilters();
                d.start_date = filters.start_date;
                d.end_date = filters.end_date;
                d.status = filters.status;
                d.vendor = filters.vendor;
            },
        },
        columns: StockInTableColumns,
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
    });
}
// ---------- Datatable untuk master storage :end ----------

// ---------- Filter dari tom-select untuk data di tabel :begin ----------
function FilterVendor() {
    const filterVendor = new TomSelect("#filter-stockin-vendor", {
        valueField: "id",
        labelField: "text",
        searchField: "text",
        preload: true,
        load: function (query, callback) {
            fetch(`/utility/select/vendor?q=${encodeURIComponent(query)}`)
                .then((res) => res.json())
                .then((json) => callback(json.results))
                .catch(() => callback());
        },
    });

    filterVendor.on("change", reloadTable);
}

function FilterStatus() {
    const filterOrderStatus = new TomSelect("#filter-stockin-status", {
        valueField: "id",
        labelField: "text",
        searchField: "text",
        preload: true,
        load: function (query, callback) {
            fetch(
                `/utility/select/incoming-stock-status?q=${encodeURIComponent(query)}`,
            )
                .then((res) => res.json())
                .then((json) => callback(json.results))
                .catch(() => callback());
        },
    });

    filterOrderStatus.on("change", reloadTable);
}
// ---------- Filter dari tom-select untuk data di tabel :end ----------

// ---------- Filter tanggal dari flatpickr untuk data di tabel :begin ----------
function DateRangeFilter() {
    new GlobalAdvanceFlatpickr(DateFilterSelector, {
        onClose: reloadTable,
    });
}
// ---------- Filter tanggal dari flatpickr untuk data di tabel :end ----------

// ---------- Handle modal delete data :begin ----------
function DeleteDataStockInActionModal() {
    // Panggil dan setup delete data
    new GlobalDeleteDataConfirmation({
        ButtonSelector: ActionDeleteSelector,
        DataAttributeID: AttributeDelete,
        UrlFetchData: (id) => GetDataURL + `/${id}`,
        ModalConfirmID: ModalDeleteSelector,
    });

    // Custom isi modal
    document.addEventListener("delete:open", function (e) {
        const { data } = e.detail;
        if (!data) return;

        // Isi text ke modal
        document.querySelector("#deleted_data").textContent =
            `Batch ${data.batch_number} of ${data.po_number} with ID ${data.public_id}`;

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
                const response = await fetch(DataURL + `/${id}`, {
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
function RestoreDataStockInActionModal() {
    // Panggil dan setup delete data
    new GlobalRestoreDataConfirmation({
        ButtonSelector: ActionRestoreSelector,
        DataAttributeID: AttributeRestore,
        UrlFetchData: (id) => GetDataURL + `/${id}`,
        ModalConfirmID: ModalRestoreSelector,
    });

    // Custom isi modal
    document.addEventListener("restore:open", function (e) {
        const { data } = e.detail;
        if (!data) return;

        // Isi text ke modal
        document.querySelector("#restored_data").textContent =
            `Batch ${data.batch_number} of ${data.po_number} with ID ${data.public_id}`;

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
                const response = await fetch(DataURL + `/${id}/restore`, {
                    method: "PATCH",
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

document.addEventListener("DOMContentLoaded", function () {
    // Datatable
    StockInTable();

    // Select function
    FilterVendor();
    FilterStatus();

    // Date range picker
    DateRangeFilter();

    // Action data
    DeleteDataStockInActionModal();
    RestoreDataStockInActionModal();
    // SeeDataHistoryDataAction();

    // Reload table
    window.addEventListener(ReloadDatatableSelector, function () {
        reloadTable();
    });
});
