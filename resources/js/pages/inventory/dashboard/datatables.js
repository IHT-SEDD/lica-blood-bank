import { GlobalAdvanceDatatable, DateTimeFormatter } from "../../../app";

// ---------- Global variable untuk memudahkan penyesuaian :begin ----------
let listStockTableInstance;

// Table List Stock
const ListStockTableSelector = "#list-stock-table";
const ListStockTableDataURL = "/inventory/dashboard/data/blood-stock";
const ReloadListStockTableSelector = "list-stock-reload";
// ---------- Global variable untuk memudahkan penyesuaian :end ----------

// ---------- Helper: Reload tabel :begin ----------
function reloadTable() {
    if (listStockTableInstance?.instance) {
        listStockTableInstance.instance.ajax.reload();
    }
}
// ---------- Helper: Reload tabel :end ----------

// ---------- Datatable untuk master :begin ----------
function ListStockTable() {
    // ---------- Init kolom pada tabel ----------
    const ListStockTableColumns = [
        {
            data: null,
            title: "No",
            render: (data, type, row, meta) => {
                return meta.row + 1;
            },
        },
        { data: "bag_number", title: "Bag Number" },
        { data: "blood_packs.blood_component", title: "Blood Pack" },   
        {
            data: "is_active",
            title: "Status",
            render: (data, type, row) => {
                console.log(row)
                const isDeleted = row.deleted_at !== null;
                if (isDeleted) {
                    return `<span class="badge badge-label badge-soft-danger">Trashed</span>`;
                }

                return `<span class="badge badge-label badge-soft-${data == 1 ? "success" : "danger"}">
                    ${data == 1 ? "Active" : "Inactive"}
                </span>`;
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
                            <button id="edit-data-${row.public_id}" class="dropdown-item btn-edit-blood-pack" data-edit-id="${row.public_id}" type="button">
                            Edit</button>
                        </li>
                        <li>
                            <button id="restore-data-${row.public_id}" class="dropdown-item fw-medium btn-restore-blood-pack ${isDeleted ? "enabled text-info" : "disabled"}" data-restore-id="${row.public_id}" type="button">
                            Restore</button>
                        </li>
                        <li>
                            <button id="delete-data-${row.public_id}" class="dropdown-item fw-medium btn-delete-blood-pack ${isDeleted ? "disabled text-muted" : "text-danger"}" data-delete-id="${row.public_id}" type="button">
                            Delete</button>
                        </li>
                    </ul>
                `;
            },
        },
    ];

    // ---------- Panggil GlobalAdvanceDatatable untuk menampilkan tabel ----------
    listStockTableInstance = new GlobalAdvanceDatatable(
        ListStockTableSelector,
        {
            ajax: {
                url: ListStockTableDataURL,
                data: function (d) {
                    console.log(d);
                    // d.blood_group = blood_group;
                    // d.blood_rhesus = blood_rhesus;
                },
            },
            columns: ListStockTableColumns,
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
// ---------- Datatable untuk master :end ----------
document.addEventListener("DOMContentLoaded", function () {
    ListStockTable();
})
    // Event listener untuk tombol reload pada tabel list stock