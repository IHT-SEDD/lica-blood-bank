// ---------- Import Libraries ----------
import {
    GlobalAdvanceDatatable,
    GlobalAdvanceFlatpickr,
    GlobalDeleteDataConfirmation,
    GlobalRestoreDataConfirmation,
    GlobalEditData,
    DateTimeFormatter,
} from "../../../app";

// ---------- Global variable untuk memudahkan penyesuaian :begin ----------
let bloodStockTableInstance; // instance datatable untuk global

// Filter datatable
const DateFilterSelector = ".blood-stock-table-date-filter"; // class selector filter tanggal

// Datatable
const DatatableSelector = "#blood-stock-table"; // id selector datatable
const DataURL = "/inventory/blood-stock/data"; // url fetch data untuk datatable
const ReloadDatatableSelector = "blood-stock-reload"; // index reload datatable

// See Detail Action
const ActionDetailSelector = ".btn-see-detail-blood-stock";
const AttributeSeeDetail = "seeDetailId";
// ---------- Global variable untuk memudahkan penyesuaian :end ----------

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
    if (bloodStockTableInstance?.instance) {
        bloodStockTableInstance.instance.ajax.reload();
    }
}
// ---------- Helper: Reload tabel :end ----------

// ---------- Datatable untuk master storage :begin ----------
function BloodStockTable() {
    // ---------- Init kolom pada tabel ----------
    const BloodStockTableColumns = [
        {
            data: null,
            title: "No",
            render: (data, type, row, meta) => {
                return meta.row + 1;
            },
        },
        { data: "blood_group", title: "Group" },
        { data: "blood_rhesus", title: "Rhesus" },
        { data: "blood_component", title: "Component" },
        { data: "total_blood_data", title: "Quantity" },
        {
            data: null,
            title: "Status",
            render: (data, type, row) => {
                const total = Number(row.total_blood_data);
                const danger = Number(row.danger_quantity);
                const warning = Number(row.warning_quantity);

                const isDanger = total <= danger;
                const isWarning = total <= warning && total > danger;

                if (isDanger) {
                    return `<span class="badge badge-label badge-soft-danger">Stock in Danger</span>`;
                }

                if (isWarning) {
                    return `<span class="badge badge-label badge-soft-warning">Stock Qty Warning</span>`;
                }

                return `<span class="badge badge-label badge-soft-success">Stock Safe</span>`;
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
            data: null,
            title: "Action",
            render: (data, type, row, meta) => {
                return `<button aria-expanded="false" class="btn btn-sm btn-soft-primary datatable-action-toggle" data-bs-toggle="dropdown" 
                data-bs-auto-close="true" type="button">
                    <i class="ti ti-dots align-middle"></i>
                    </button>
                    <ul class="dropdown-menu">
                        <li>
                            <button id="see-detail-${row.public_id}" class="dropdown-item fw-medium btn-see-detail-blood-stock text-primary" data-see-detail-id="${row.public_id}" type="button">
                            Detail
                            </button>
                        </li>
                    </ul>
                `;
            },
        },
    ];

    // ---------- Panggil GlobalAdvanceDatatable untuk menampilkan tabel ----------
    bloodStockTableInstance = new GlobalAdvanceDatatable(DatatableSelector, {
        ajax: {
            url: DataURL,
            data: function (d) {
                const filters = getFilters();
                d.start_date = filters.start_date;
                d.end_date = filters.end_date;
            },
        },
        columns: BloodStockTableColumns,
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

// ---------- Filter tanggal dari flatpickr untuk data di tabel :begin ----------
function DateRangeFilter() {
    new GlobalAdvanceFlatpickr(DateFilterSelector, {
        onClose: reloadTable,
    });
}
// ---------- Filter tanggal dari flatpickr untuk data di tabel :end ----------

// ---------- Handle see detail :begin ----------
function SeeDetailBloodStockAction() {
    document.addEventListener("click", function (e) {
        const btn = e.target.closest(ActionDetailSelector);
        if (!btn) return;

        const id = btn.dataset[AttributeSeeDetail];
        if (!id) return;

        window.location.href = `/inventory/blood-stock/detail/${id}`;
    });
}
// ---------- Handle see detail :end ----------

document.addEventListener("DOMContentLoaded", function () {
    // Datatable
    BloodStockTable();

    // Select function

    // Date range picker
    DateRangeFilter();

    // Action data
    SeeDetailBloodStockAction();

    // Reload table
    window.addEventListener(ReloadDatatableSelector, function () {
        reloadTable();
    });
});
