// ---------- Import Libraries ----------
import { GlobalAdvanceYajraDatatable } from "../../../../utility/datatable/datatables";

// ---------- Global variable untuk memudahkan penyesuaian :begin ----------
let configBloodComponentTableInstance; // instance datatable untuk global

// Datatable
const DatatableSelector = "#list-config-blood-component-table"; // id selector datatable
const DataURL = "/playground/setting/config/blood-component/data"; // url fetch data untuk datatable
const ReloadDatatableSelector = "list-config-blood-component-reload"; // event reload datatable
// ---------- Global variable untuk memudahkan penyesuaian :end ----------

// ---------- Helper: Reload tabel :begin ----------
function reloadTable() {
    if (configBloodComponentTableInstance?.instance) {
        configBloodComponentTableInstance.instance.ajax.reload();
    }
}
// ---------- Helper: Reload tabel :end ----------

// ---------- Datatable untuk konfig blood component :begin ----------
function ConfigBloodComponentTable() {
    // ---------- Init kolom pada tabel ----------
    const ConfigBloodComponentTableColumns = [
        {
            data: null,
            title: "No",
            orderable: false,
            searchable: false,
            render: (data, type, row, meta) => meta.row + 1,
        },
        {
            data: "blood_component",
            title: "Component",
        },
        {
            data: "blood_component_label",
            title: "Label",
        },
        {
            data: "keywords",
            title: "Keyword",
            orderable: false,
            render: (data) => {
                if (!Array.isArray(data) || data.length === 0) return "-";
                return data
                    .map(
                        (keyword) =>
                            `<span class="badge bg-soft-secondary text-secondary me-1">${keyword}</span>`,
                    )
                    .join("");
            },
        },
        {
            data: "general_codes",
            title: "General Code",
            orderable: false,
            render: (data) => {
                if (!Array.isArray(data) || data.length === 0) return "-";
                return data
                    .map(
                        (code) =>
                            `<span class="badge bg-soft-info text-info me-1">${code}</span>`,
                    )
                    .join("");
            },
        },
        {
            data: null,
            title: "Action",
            orderable: false,
            searchable: false,
            render: (data, type, row, meta) => {
                return `<button aria-expanded="false" class="btn btn-sm btn-soft-primary datatable-action-toggle" data-bs-toggle="dropdown" 
                data-bs-auto-close="true" type="button">
                    <i class="ti ti-dots align-middle"></i>
                    </button>
                    <ul class="dropdown-menu">
                        <li>
                            <button id="edit-data-${row.blood_component}" class="dropdown-item fw-medium text-primary btn-edit-config-blood-component" data-edit-id="${row.blood_component}" type="button">
                            <i class="ti ti-pencil align-middle me-1 fs-4"></i>
                            Edit
                            </button>
                        </li>
                    </ul>
                `;
            },
        },
    ];

    // ---------- Panggil GlobalAdvanceDatatable untuk menampilkan tabel ----------
    configBloodComponentTableInstance = new GlobalAdvanceYajraDatatable(
        DatatableSelector,
        {
            ajax: {
                url: DataURL,
            },
            columns: ConfigBloodComponentTableColumns,
            useHideColumn: true,
            columnDefs: [{ targets: 0, responsivePriority: 1 }],
            pageLengthOptions: [10, 25, 50, 100],
            pageLength: 25,
            bodyFontSize: "12px",
            bodyFontStyle: "medium",
        },
    );
}
// ---------- Datatable untuk konfig blood component :end ----------

document.addEventListener("DOMContentLoaded", function () {
    // Datatable
    ConfigBloodComponentTable();

    // Reload table
    window.addEventListener(ReloadDatatableSelector, function () {
        reloadTable();
    });
});
