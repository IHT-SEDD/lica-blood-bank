// ---------- Import Libraries ----------
import {
    GlobalAdvanceDatatable,
    GlobalAdvanceFlatpickr,
    GlobalDeleteDataConfirmation,
    GlobalRestoreDataConfirmation,
    GlobalEditData,
    GlobalAdvanceTomselect,
} from "../../../app";
import {
    GlobalRenderTimelineItem,
    DateTimeFormatter,
} from "../../../utility/ui";
import { BloodStockLogConfigTL } from "../../../utility/config/timeline-config";
import { TableActionHandler } from "./detail/table-action";

// ---------- Global variable untuk memudahkan penyesuaian :begin ----------
// Filter datatable
const DateFilterSelector = ".stock-blood-data-table-date-filter"; // class selector filter tanggal

// Datatable
const DatatableSelector = "#stock-blood-data-table"; // id selector datatable
const ReloadDatatableSelector = "stock-blood-data-reload"; // index reload datatable

// URL
const StockBloodDataURL = "/inventory/blood-stock/detail/data";
const StockBloodLogDataURL = "/inventory/blood-stock/detail/log";

// TIMELINE
const BloodStockLogContainerSelector = ".blood-stock-log-data-container";
const TimelineContainerSelector = ".timeline-blood-stock-log";
// ---------- Global variable untuk memudahkan penyesuaian :end ----------

// ---------- Global State ----------
let stockBloodDataTableInstance;
let bloodStockLogData = null;
const id = getIdFromUrl();

// ---------- Helper: ambil ID dari URL saat ini----------
function getIdFromUrl() {
    const segments = window.location.pathname.split("/");
    return segments[segments.length - 1];
}

// ---------- Helper: Ambil semua filter ----------
function getFilters() {
    const dateVal = document.querySelector(DateFilterSelector)?.value;
    const status = document.querySelector("#filter-blood-status")?.value || "";

    let start_date = "";
    let end_date = "";

    if (dateVal) {
        const separator = dateVal.includes(" to ") ? " to " : " - ";
        const parts = dateVal.split(separator);

        start_date = parts[0] || "";
        end_date = parts[1] || "";
    }

    return { status, start_date, end_date };
}

// ---------- Helper: Reload tabel ----------
function reloadTable() {
    if (stockBloodDataTableInstance?.instance) {
        stockBloodDataTableInstance.instance.ajax.reload();
    }
}

// ---------- Filter ----------
function FilterBloodStatus() {
    new GlobalAdvanceTomselect("#filter-blood-status", {
        valueField: "id",
        preload: true,
        load: function (query, callback) {
            fetch(
                `/utility/select/blood-stock-status?q=${encodeURIComponent(query)}`,
            )
                .then((res) => res.json())
                .then((json) => callback(json.results))
                .catch(() => callback());
        },
        onChange: function () {
            reloadTable();
        },
    });
}
function DateRangeFilter() {
    new GlobalAdvanceFlatpickr(DateFilterSelector, {
        onClose: reloadTable,
    });
}

// ---------- Datatable ----------
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
        { data: "bag_number", title: "Bag No." },
        { data: "bag_number_lica", title: "Bag No. LICA" },
        {
            data: null,
            title: "Blood Pack",
            render: (data, row) => {
                const bloodPack = data.blood_packs;
                return `${bloodPack.blood_group}${bloodPack.blood_rhesus} ${bloodPack.blood_component}`;
            },
        },
        { data: "blood_volume", title: "Volume" },
        {
            data: null,
            title: "Status",
            render: (data, type, row) => {
                const isDeleted = row.deleted_at !== null;
                if (isDeleted) {
                    return `<span class="badge badge-label fw-semibold badge-soft-danger">
                        <i class="ti ti-trash align-middle me-2 fs-4"></i>
                        Trashed
                    </span>`;
                }

                switch (row.blood_status) {
                    case "expired":
                        return `<span class="badge badge-label fw-semibold badge-soft-danger">
                            <i class="ti ti-calendar-x align-middle me-2 fs-4"></i>
                            Expired!
                        </span>`;
                        break;
                    case "in_use":
                        return `<span class="badge badge-label fw-semibold badge-soft-info">
                            <i class="ti ti-droplet-heart align-middle me-2 fs-4"></i>
                            In Use
                        </span>`;
                        break;
                    case "available":
                        return `<span class="badge badge-label fw-semibold badge-soft-success">
                            <i class="ti ti-circle-check align-middle me-2 fs-4"></i>
                            Available
                        </span>`;
                        break;
                    case "destroyed":
                        return `<span class="badge badge-label fw-semibold badge-soft-danger">
                            <i class="ti ti-heart-broken align-middle me-2 fs-4"></i>
                            Destroyed
                        </span>`;
                        break;

                    default:
                        return `<span class="badge badge-label fw-semibold badge-soft-primary">
                            <i class="ti ti-droplet align-middle me-2 fs-4"></i>
                            ${row.blood_status}
                        </span>`;
                        break;
                }
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
                         <button id="edit-data-${row.public_id}" class="dropdown-item fw-medium btn-edit-stock-blood ${isDeleted ? "disabled text-muted" : "text-info"}" data-edit-id="${row.public_id}" type="button">
                         <i class="ti ti-pencil align-middle me-2 fs-4"></i>
                         Edit
                         </button>
                     </li>
                     <li>
                         <button id="restore-data-${row.public_id}" class="dropdown-item fw-medium btn-restore-stock-blood ${isDeleted ? "enabled text-info" : "disabled"}" data-restore-id="${row.public_id}" type="button">
                         <i class="ti ti-recycle align-middle me-2 fs-4"></i>
                         Restore
                         </button>
                     </li>
                     <li class="${isDeleted ? "" : "d-none"}">
                         <button id="permanent-delete-data-${row.public_id}" class="dropdown-item fw-medium btn-permanent-delete-stock-blood ${isDeleted ? "enabled text-danger" : "text-muted"}" data-permanent-delete-id="${row.public_id}" type="button">
                         <i class="ti ti-trash align-middle me-2 fs-4"></i>
                         Permanent Delete
                         </button>
                     </li>
                     <li class="${isDeleted ? "d-none" : ""}">
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
                    d.status = filters.status;
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

// ---------- Fetch data blood stock log ----------
async function fetchDataBloodStockLog() {
    showPageLoading();

    try {
        const res = await fetch(`${StockBloodLogDataURL}/${id}`, {
            method: "GET",
            cache: "no-store",
            headers: {
                "Cache-Control": "no-cache",
                Pragma: "no-cache",
            },
        });
        if (!res.ok) throw new Error(`HTTP error! status: ${res.status}`);

        const data = await res.json();
        bloodStockLogData = data;
        return data;
    } catch (err) {
        notyf.error({ message: "Failed to fetch blood stock log data!" });
        console.error(err);
    } finally {
        hidePageLoading();
    }
}

// ---------- Generate Timeline dari array log ----------
function GenerateTimeline(logs = []) {
    const bloodStockTimeline = GlobalRenderTimelineItem.create({
        container: BloodStockLogContainerSelector,
        wrapper: TimelineContainerSelector,
        locale: "en-GB",
        statusConfig: BloodStockLogConfigTL,
    });

    bloodStockTimeline.render(logs);
}

// ---------- Select tom-select untuk data di modal edit ----------
function EditStorageRack() {
    new GlobalAdvanceTomselect("#edit_data_blood_stock_storage_rack", {
        valueField: "id",
        preload: true,
        noResultsText: "Storage rack not found",
        load: function (query, callback) {
            fetch(`/utility/select/storage-rack?q=${encodeURIComponent(query)}`)
                .then((res) => res.json())
                .then((json) => callback(json.results))
                .catch(() => callback());
        },
    });
}

document.addEventListener("DOMContentLoaded", async () => {
    const logData = await fetchDataBloodStockLog();

    // Datatable
    BloodStockDataTable();

    // Select function
    FilterBloodStatus();
    EditStorageRack();

    // Date range picker
    DateRangeFilter();

    new TableActionHandler(reloadTable).init();

    GenerateTimeline(logData);

    // Reload table
    window.addEventListener(ReloadDatatableSelector, function () {
        reloadTable();
    });
});
