// ---------- Import Libraries ----------
import {
    GlobalAdvanceDatatable,
    GlobalAdvanceFlatpickr,
    GlobalDeleteDataConfirmation,
    GlobalRestoreDataConfirmation,
    GlobalEditData,
    GlobalAdvanceTomselect,
} from "../../../app";
import { TextFormatter } from "../../../utility/ui";
import { DateTimeFormatter } from "../../../utility/ui";

// ---------- Global variable untuk memudahkan penyesuaian :begin ----------
let historyOrderTableInstance; // instance datatable untuk global

// Filter datatable
const DateFilterSelector = ".history-order-table-date-filter"; // class selector filter tanggal

// Datatable
const DatatableSelector = "#history-order-table"; // id selector datatable
const DataURL = "/inventory/history-order/data"; // url fetch data untuk datatable
const ReloadDatatableSelector = "history-order-reload"; // index reload datatable
const ExportExcelURL = "/inventory/history-order/export/excel";

// See More Action
const ActionSeeSelector = ".btn-see-history-order"; // class selector button see
const AttributeSee = "seeId"; // attribute data id see

// Delete Action
const ModalDeleteSelector = "delete_data_history_order_modal"; // id selector modal delete
const ActionDeleteSelector = ".btn-delete-history-order"; // class selector button delete
const AttributeDelete = "deleteId"; // attribute data id delete
const ConfirmDeleteSelector = "#confirm_delete"; // id selector confirm delete

// Restore Action
const ModalRestoreSelector = "restore_data_history_order_modal"; // id selector modal restore
const ActionRestoreSelector = ".btn-restore-history-order"; // class selector button restore
const AttributeRestore = "restoreId"; // attribute data id restore
const ConfirmRestoreSelector = "#confirm_restore"; // id selector confirm restore
// ---------- Global variable untuk memudahkan penyesuaian :end ----------

// ---------- Helper: Ambil semua filter :begin ----------
function getFilters() {
    const vendor = document.querySelector("#filter-order-vendor")?.value || "";
    const status = document.querySelector("#filter-order-status")?.value || "";
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
    if (historyOrderTableInstance?.instance) {
        historyOrderTableInstance.instance.ajax.reload();
    }
}
// ---------- Helper: Reload tabel :end ----------

// ---------- Datatable untuk master storage :begin ----------
function HistoryOrderTable() {
    // ---------- Init kolom pada tabel ----------
    const HistoryOrderTableColumns = [
        { data: "po_number", title: "PO Number" },
        {
            data: "vendor_id",
            title: "Vendor",
            render: (data, type, row) => {
                return `<span class="badge badge-label badge-soft-primary">${row.vendors.name}</span>`;
            },
        },
        { data: "total_quantity", title: "Total Qty" },
        {
            data: null,
            title: "Blood Group",
            render: (data, type, row) => {
                const orderBloodDetail = row.order_blood_details || [];
                if (!orderBloodDetail.length) return "-";

                const bloodGroups = orderBloodDetail
                    .map((item) => {
                        const group = item.blood_packs.blood_group || "";
                        const rhesus = item.blood_packs.blood_rhesus || "";
                        const component =
                            item.blood_packs.blood_component || "";
                        return group && rhesus
                            ? `${group}${rhesus} ${component}`
                            : group || "-";
                    })
                    .filter(Boolean)
                    .join(", ");

                return `<span class="fw-semibold text-muted">${bloodGroups}</span>`;
            },
        },
        {
            data: "status",
            title: "Status",
            render: (data, type, row) => {
                const isDeleted = row.deleted_at !== null;
                const isDone = data == "done";
                const isDraft = data == "draft";
                if (isDeleted) {
                    return `<span class="badge badge-label badge-soft-danger">Trashed</span>`;
                } else if (isDone) {
                    return `<span class="badge badge-label badge-soft-success">Done</span>`;
                } else if (isDraft) {
                    return `<span class="badge badge-label badge-soft-warning">Draft</span>`;
                } else {
                    return `<span class="badge badge-label badge-soft-secondary">${TextFormatter.format(data)}</span>`;
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
                            <button id="see-data-${row.public_id}" class="dropdown-item fw-medium btn-see-history-order ${isDeleted ? "disabled" : ""}" 
                            data-see-id="${row.public_id}" type="button">
                            <i class="ti ti-file-search align-middle me-2 fs-4"></i>
                            See More
                            </button>
                        </li>
                        <li>
                            <button id="restore-data-${row.public_id}" class="dropdown-item fw-medium btn-restore-history-order ${isDeleted ? "enabled text-info" : "disabled"}" data-restore-id="${row.public_id}" type="button">
                            <i class="ti ti-recycle align-middle me-2 fs-4"></i>
                            Restore
                            </button>
                        </li>
                        <li>
                            <button id="delete-data-${row.public_id}" class="dropdown-item fw-medium btn-delete-history-order ${isDeleted ? "disabled text-muted" : "text-danger"}" data-delete-id="${row.public_id}" type="button">
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
    historyOrderTableInstance = new GlobalAdvanceDatatable(DatatableSelector, {
        ajax: {
            url: DataURL,
            data: function (d) {
                const filters = getFilters();
                d.start_date = filters.start_date;
                d.end_date = filters.end_date;
                d.vendor = filters.vendor;
                d.status = filters.status;
            },
        },
        order: [5, "desc"],
        columns: HistoryOrderTableColumns,
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
    const filterVendor = new GlobalAdvanceTomselect("#filter-order-vendor", {
        valueField: "id",
        preload: true,
        load: function (query, callback) {
            fetch(`/utility/select/vendor?q=${encodeURIComponent(query)}`)
                .then((res) => res.json())
                .then((json) => callback(json.results))
                .catch(() => callback());
        },
        onChange: function () {
            reloadTable();
        },
    });
}

function FilterOrderStatus() {
    const filterOrderStatus = new GlobalAdvanceTomselect(
        "#filter-order-status",
        {
            valueField: "id",
            preload: true,
            load: function (query, callback) {
                fetch(
                    `/utility/select/order-status?q=${encodeURIComponent(query)}`,
                )
                    .then((res) => res.json())
                    .then((json) => callback(json.results))
                    .catch(() => callback());
            },
            onChange: function () {
                reloadTable();
            },
        },
    );
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
function DeleteDataHistoryDataActionModal() {
    // Panggil dan setup delete data
    new GlobalDeleteDataConfirmation({
        ButtonSelector: ActionDeleteSelector,
        DataAttributeID: AttributeDelete,
        UrlFetchData: (id) => DataURL + `/${id}`,
        ModalConfirmID: ModalDeleteSelector,
    });

    // Custom isi modal
    document.addEventListener("delete:open", function (e) {
        const { data } = e.detail;
        if (!data) return;

        // Isi text ke modal
        document.querySelector("#deleted_data").textContent =
            `${data.po_number} with ID ${data.public_id}`;

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
function RestoreDataHistoryDataActionModal() {
    // Panggil dan setup delete data
    new GlobalRestoreDataConfirmation({
        ButtonSelector: ActionRestoreSelector,
        DataAttributeID: AttributeRestore,
        UrlFetchData: (id) => DataURL + `/${id}`,
        ModalConfirmID: ModalRestoreSelector,
    });

    // Custom isi modal
    document.addEventListener("restore:open", function (e) {
        const { data } = e.detail;
        if (!data) return;

        // Isi text ke modal
        document.querySelector("#restored_data").textContent =
            `${data.po_number} with ID ${data.public_id}`;

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

// ---------- Handle see data :begin ----------
function SeeDataHistoryDataAction() {
    document.addEventListener("click", function (e) {
        const btn = e.target.closest(ActionSeeSelector);
        if (!btn) return;

        const id = btn.dataset[AttributeSee];
        if (!id) return;

        window.location.href = `/inventory/history-order/detail/${id}`;
    });
}
// ---------- Handle see data :end ----------

// ---------- Handle tombol export excel :begin ----------
function ExportToExcel() {
    const btn = document.getElementById("excel_order_btn");
    if (!btn) return;

    btn.addEventListener("click", async function () {
        const filters = getFilters();

        const params = new URLSearchParams();
        if (filters.start_date) params.append("start_date", filters.start_date);
        if (filters.end_date) params.append("end_date", filters.end_date);
        if (filters.vendor) params.append("vendor", filters.vendor);
        if (filters.status) params.append("status", filters.status);

        const url = ExportExcelURL + `?${params.toString()}`;

        try {
            const response = await fetch(url);

            // ---------- Cek content-type response ----------
            const contentType = response.headers.get("Content-Type") || "";
            const isJson = contentType.includes("application/json");

            // ---------- Jika response error atau balik JSON (bukan file) ----------
            if (!response.ok || isJson) {
                const result = await response.json();
                notyf.error({
                    message: result.message || "Failed to export data!",
                });
                return;
            }

            // ---------- Jika sukses, trigger download dari blob ----------
            const blob = await response.blob();
            const blobUrl = URL.createObjectURL(blob);

            const link = document.createElement("a");
            link.href = blobUrl;
            const today = new Date();
            const formattedDate =
                today.getFullYear() +
                String(today.getMonth() + 1).padStart(2, "0") +
                String(today.getDate()).padStart(2, "0");

            link.setAttribute(
                "download",
                `History Order - ${formattedDate}.xlsx`,
            );
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);

            URL.revokeObjectURL(blobUrl);

            notyf.success({
                message: "Data exported successfully!",
            });
        } catch (error) {
            console.error(error);
            notyf.error({
                message: "Failed to export data!",
            });
        }
    });
}

document.addEventListener("DOMContentLoaded", function () {
    // Datatable
    HistoryOrderTable();

    // Select function
    FilterVendor();
    FilterOrderStatus();

    // Date range picker
    DateRangeFilter();

    // Action data
    DeleteDataHistoryDataActionModal();
    RestoreDataHistoryDataActionModal();
    SeeDataHistoryDataAction();
    ExportToExcel();

    // Reload table
    window.addEventListener(ReloadDatatableSelector, function () {
        reloadTable();
    });
});
