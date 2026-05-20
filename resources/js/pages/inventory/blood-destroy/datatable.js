// ---------- Import Libraries ----------
import { GlobalAdvanceDatatable, GlobalAdvanceFlatpickr } from "../../../app";
import { DateTimeFormatter } from "../../../utility/ui";
import { TableActionHandler } from "./table-action";

// ---------- Global variable untuk memudahkan penyesuaian ----------
// Filter datatable
const DateFilterSelector = ".blood-destroy-table-date-filter"; // class selector filter tanggal

// Datatable
const DatatableSelector = "#blood-destroy-table"; // id selector datatable
const DataURL = "/inventory/destroy-blood/data"; // url fetch data untuk datatable
const ReloadDatatableSelector = "blood-destroy-reload"; // index reload datatable

// ---------- Global State ----------
let bloodDestroyTableInstance;

// ---------- Helper: Ambil semua filter ----------
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

// ---------- Helper: Reload tabel ----------
function reloadTable() {
    if (bloodDestroyTableInstance?.instance) {
        bloodDestroyTableInstance.instance.ajax.reload();
    }
}

// ---------- Filter tanggal ----------
function DateRangeFilter() {
    new GlobalAdvanceFlatpickr(DateFilterSelector, {
        onClose: reloadTable,
    });
}

// ---------- Datatable untuk master storage :begin ----------
function BloodDestroyTable() {
    // ---------- Init kolom pada tabel ----------
    const BloodDestroyTableColumns = [
        {
            data: null,
            title: "No",
            render: (data, type, row, meta) => {
                return meta.row + 1;
            },
        },
        { data: "blood_stocks.bag_number", title: "Bag Number" },
        {
            data: null,
            title: "Blood Pack",
            render: (data, type, row) => {
                const bloodStocks = row.blood_stocks;
                const bloodPacks = bloodStocks.blood_packs;
                const bloodGroup = bloodPacks.blood_group || "";
                const bloodRhesus = bloodPacks.blood_rhesus || "";
                const bloodComponent = bloodPacks.blood_component || "";
                const bloodPack =
                    bloodGroup && bloodRhesus
                        ? `${bloodGroup}${bloodRhesus} ${bloodComponent}`
                        : bloodGroup || "-";

                return `<span class="fw-semibold fs-5">${bloodPack}</span>`;
            },
        },
        {
            data: "blood_stocks.expiry_date",
            title: "Expiry Date",
            render: (data) => {
                return DateTimeFormatter.dateOnly(data);
            },
        },
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

                return `<span class="badge badge-label fw-semibold badge-soft-danger">
                    <i class="ti ti-heart-x align-middle me-2 fs-4"></i>
                    ${row.status}
                </span>`;
            },
        },
        {
            data: "created_at",
            title: "Destroyed At",
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
                        <button id="undestroy-data-${row.public_id}" class="dropdown-item fw-medium btn-undestroy-destroy-blood ${isDeleted ? "disabled" : "enabled text-info"}" data-undestroy-id="${row.public_id}" type="button">
                        <i class="ti ti-heart-plus align-middle me-2 fs-4"></i>
                        Undestroy
                        </button>
                    </li>
                    <li class="${isDeleted ? "" : "d-none"}">
                        <button id="restore-data-${row.public_id}" class="dropdown-item fw-medium btn-restore-destroy-blood ${isDeleted ? "enabled text-info" : "disabled"}" data-restore-id="${row.public_id}" type="button">
                        <i class="ti ti-recycle align-middle me-2 fs-4"></i>
                        Restore
                        </button>
                    </li>
                    <li class="${isDeleted ? "" : "d-none"}">
                        <button id="permanent-delete-data-${row.public_id}" class="dropdown-item fw-medium btn-permanent-delete-destroy-blood ${isDeleted ? "enabled text-danger" : "text-muted"}" data-permanent-delete-id="${row.public_id}" type="button">
                        <i class="ti ti-trash align-middle me-2 fs-4"></i>
                        Permanent Delete
                        </button>
                    </li>
                    <li class="${isDeleted ? "d-none" : ""}">
                        <button id="delete-data-${row.public_id}" class="dropdown-item fw-medium btn-delete-destroy-blood ${isDeleted ? "disabled text-muted" : "text-danger"}" data-delete-id="${row.public_id}" type="button">
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
    bloodDestroyTableInstance = new GlobalAdvanceDatatable(DatatableSelector, {
        ajax: {
            url: DataURL,
            data: function (d) {
                const filters = getFilters();
                d.start_date = filters.start_date;
                d.end_date = filters.end_date;
            },
        },
        columns: BloodDestroyTableColumns,
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

document.addEventListener("DOMContentLoaded", function () {
    // Datatable
    BloodDestroyTable();

    // Date range picker
    DateRangeFilter();

    new TableActionHandler(reloadTable).init();

    // Reload table
    window.addEventListener(ReloadDatatableSelector, function () {
        reloadTable();
    });
});
