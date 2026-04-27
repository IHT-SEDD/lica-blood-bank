// ---------- Import Libraries ----------
import {
    GlobalAdvanceDatatable,
    GlobalAdvanceFlatpickr,
    GlobalDeleteDataConfirmation,
    GlobalEditData,
    DateTimeFormatter,
} from "../../../app";

// ---------- Init global variable ----------
let masterStorageTableInstance;

const DateFilterSelector = ".master-storage-table-date-filter";
const DatatableSelector = "#master-storage-table";
const MasterDataURL = "/master/storage/data";
const FormEditSelector = "#edit_data_storage";
const ModalEditSelector = "edit_data_master_storage_modal";
const ModalDeleteSelector = "delete_data_master_storage_modal";
const ActionEditSelector = ".btn-edit-storage";
const ActionDeleteSelector = ".btn-delete-storage";
const AttributeDelete = "deleteId";
const AttributeEdit = "editId";
const ConfirmDeleteSelector = "#confirm_delete";
const ReloadDatatableSelector = "master-storage-reload";

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
    if (masterStorageTableInstance?.instance) {
        masterStorageTableInstance.instance.ajax.reload();
    }
}
// ---------- Helper: Reload tabel :end ----------

// ---------- Datatable untuk master storage :begin ----------
function MasterStorageTable() {
    // ---------- Init kolom pada tabel ----------
    const MasterStorageTableColumns = [
        {
            data: null,
            title: "No",
            render: (data, type, row, meta) => {
                return meta.row + 1;
            },
        },
        { data: "name", title: "Name" },
        { data: "model", title: "Model" },
        { data: "serial_number", title: "Serial Number" },
        { data: "manufacturer", title: "Manufacturer" },
        { data: "rack_capacity", title: "Rack Capacity" },
        {
            data: "is_active",
            title: "Status",
            render: (data) => {
                return `<span class="badge badge-label badge-soft-${data == 1 ? "success" : "danger"}">${data == 1 ? "Active" : "Inactive"}</span>`;
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
                return `<button aria-expanded="false" class="btn btn-sm btn-soft-primary datatable-action-toggle" data-bs-toggle="dropdown" 
                data-bs-auto-close="true" type="button">
                    <i class="ti ti-dots align-middle"></i>
                    </button>
                    <ul class="dropdown-menu">
                        <li>
                            <button id="edit-data-${row.public_id}" class="dropdown-item btn-edit-storage" data-edit-id="${row.public_id}" type="button">
                            Edit</button>
                        </li>
                        <li>
                            <button id="delete-data-${row.public_id}" class="dropdown-item btn-delete-storage text-danger" data-delete-id="${row.public_id}" type="button">
                            Delete</button>
                        </li>
                    </ul>
                `;
            },
        },
    ];

    // ---------- Panggil GlobalAdvanceDatatable untuk menampilkan tabel ----------
    masterStorageTableInstance = new GlobalAdvanceDatatable(DatatableSelector, {
        ajax: {
            url: MasterDataURL,
            data: function (d) {
                const filters = getFilters();
                d.start_date = filters.start_date;
                d.end_date = filters.end_date;
            },
        },
        columns: MasterStorageTableColumns,
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

// ---------- Handle modal edit data :begin ----------
function EditDataStorageActionModal() {
    new GlobalEditData({
        ButtonSelector: ActionEditSelector,
        DataAttributeID: AttributeEdit,
        UrlFetchData: (id) => `${MasterDataURL}/${id}`,
        ModalEditID: ModalEditSelector,
        FormSelector: FormEditSelector,
    });

    document.addEventListener("edit:open", function (e) {
        const { data } = e.detail;

        if (!data) return;

        document.querySelector("#edit_data_storage_name").value =
            data.name ?? "";
        document.querySelector("#edit_data_storage_model").value =
            data.model ?? "";
        document.querySelector("#edit_data_storage_serial_number").value =
            data.serial_number ?? "";
        document.querySelector("#edit_data_storage_manufacturer").value =
            data.manufacturer ?? "";
        document.querySelector("#edit_data_storage_rack_capacity").value =
            data.rack_capacity ?? "";
        document.querySelector("#edit_data_storage_is_active").checked =
            data.is_active == 1;

        // tetap seperti sebelumnya
        document.querySelector(FormEditSelector).dataset.id = data.public_id;
    });
}
// ---------- Handle modal edit data :end ----------

// ---------- Handle modal delete data :begin ----------
function DeleteDataStorageActionModal() {
    // Panggil dan setup delete data
    new GlobalDeleteDataConfirmation({
        ButtonSelector: ActionDeleteSelector,
        DataAttributeID: AttributeDelete,
        UrlFetchData: (id) => MasterDataURL + `/${id}`,
        ModalConfirmID: ModalDeleteSelector,
    });

    // Custom isi modal
    document.addEventListener("delete:open", function (e) {
        const { data } = e.detail;
        if (!data) return;

        // Isi text ke modal
        document.querySelector("#deleted_data").textContent =
            `${data.name} with ID ${data.public_id}`;

        // Berikan attribute button delete dengan id data
        document.querySelector(ConfirmDeleteSelector).dataset.id =
            data.public_id;
    });
}
// ---------- Handle modal delete data :end ----------

document.addEventListener("DOMContentLoaded", function () {
    // Datatable
    MasterStorageTable();

    // Select function

    // Date range picker
    DateRangeFilter();

    // Action data
    EditDataStorageActionModal();
    DeleteDataStorageActionModal();

    // Reload table
    window.addEventListener(ReloadDatatableSelector, function () {
        reloadTable();
    });
});
