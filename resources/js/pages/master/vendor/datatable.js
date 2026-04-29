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
let masterVendorTableInstance; // instance datatable untuk global

// Filter datatable
const DateFilterSelector = ".master-vendor-table-date-filter"; // class selector filter tanggal

// Datatable
const DatatableSelector = "#master-vendor-table"; // id selector datatable
const MasterDataURL = "/master/vendor/data"; // url fetch data untuk datatable
const ReloadDatatableSelector = "master-vendor-reload"; // index reload datatable

// Edit Action
const FormEditSelector = "#edit_data_vendor"; // id selector form edit
const ModalEditSelector = "edit_data_master_vendor_modal"; // id selector modal edit
const ActionEditSelector = ".btn-edit-vendor"; // class selector button edit
const AttributeEdit = "editId"; // attribute data id edit

// Delete Action
const ModalDeleteSelector = "delete_data_master_vendor_modal"; // id selector modal delete
const ActionDeleteSelector = ".btn-delete-vendor"; // class selector button delete
const AttributeDelete = "deleteId"; // attribute data id delete
const ConfirmDeleteSelector = "#confirm_delete"; // id selector confirm delete

// Restore Action
const ModalRestoreSelector = "restore_data_master_vendor_modal"; // id selector modal restore
const ActionRestoreSelector = ".btn-restore-vendor"; // class selector button restore
const AttributeRestore = "restoreId"; // attribute data id restore
const ConfirmRestoreSelector = "#confirm_restore"; // id selector confirm restore
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
    if (masterVendorTableInstance?.instance) {
        masterVendorTableInstance.instance.ajax.reload();
    }
}
// ---------- Helper: Reload tabel :end ----------

// ---------- Datatable untuk master storage :begin ----------
function MasterVendorTable() {
    // ---------- Init kolom pada tabel ----------
    const MasterVendorTableColumns = [
        {
            data: null,
            title: "No",
            render: (data, type, row, meta) => {
                return meta.row + 1;
            },
        },
        { data: "name", title: "Name" },
        { data: "address", title: "Address" },
        { data: "phone_number", title: "Phone Number" },
        { data: "telephone_number", title: "Telephone Number" },
        { data: "pic_name", title: "PIC Name" },
        {
            data: "is_active",
            title: "Status",
            render: (data, type, row) => {
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
                            <button id="edit-data-${row.public_id}" class="dropdown-item btn-edit-vendor" data-edit-id="${row.public_id}" type="button">
                            Edit</button>
                        </li>
                        <li>
                            <button id="restore-data-${row.public_id}" class="dropdown-item fw-medium btn-restore-vendor ${isDeleted ? "enabled text-info" : "disabled"}" data-restore-id="${row.public_id}" type="button">
                            Restore</button>
                        </li>
                        <li>
                            <button id="delete-data-${row.public_id}" class="dropdown-item btn-delete-vendor text-danger" data-delete-id="${row.public_id}" type="button">
                            Delete</button>
                        </li>
                    </ul>
                `;
            },
        },
    ];

    // ---------- Panggil GlobalAdvanceDatatable untuk menampilkan tabel ----------
    masterVendorTableInstance = new GlobalAdvanceDatatable(DatatableSelector, {
        ajax: {
            url: MasterDataURL,
            data: function (d) {
                const filters = getFilters();
                d.start_date = filters.start_date;
                d.end_date = filters.end_date;
            },
        },
        columns: MasterVendorTableColumns,
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
function EditDataVendorActionModal() {
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

        document.querySelector("#edit_data_vendor_name").value =
            data.name ?? "";
        document.querySelector("#edit_data_vendor_address").value =
            data.address ?? "";
        document.querySelector("#edit_data_vendor_phone_number").value =
            data.phone_number ?? "";
        document.querySelector("#edit_data_vendor_telephone_number").value =
            data.telephone_number ?? "";
        document.querySelector("#edit_data_vendor_pic_name").value =
            data.pic_name ?? "";
        document.querySelector("#edit_data_vendor_is_active").checked =
            data.is_active == 1;

        document.querySelector(FormEditSelector).dataset.id = data.public_id;
    });
}
// ---------- Handle modal edit data :end ----------

// ---------- Handle modal delete data :begin ----------
function DeleteDataVendorActionModal() {
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

    const confirmBtn = document.querySelector(ConfirmDeleteSelector);

    if (confirmBtn) {
        confirmBtn.addEventListener("click", async function () {
            const id = this.dataset.id;

            if (!id) return;

            try {
                const response = await fetch(MasterDataURL + `/${id}`, {
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
function RestoreDataVendorActionModal() {
    // Panggil dan setup delete data
    new GlobalRestoreDataConfirmation({
        ButtonSelector: ActionRestoreSelector,
        DataAttributeID: AttributeRestore,
        UrlFetchData: (id) => MasterDataURL + `/${id}`,
        ModalConfirmID: ModalRestoreSelector,
    });

    // Custom isi modal
    document.addEventListener("restore:open", function (e) {
        const { data } = e.detail;
        if (!data) return;

        // Isi text ke modal
        document.querySelector("#restored_data").textContent =
            `${data.name} with ID ${data.public_id}`;

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
                const response = await fetch(MasterDataURL + `/${id}/restore`, {
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
    MasterVendorTable();

    // Select function

    // Date range picker
    DateRangeFilter();

    // Action data
    EditDataVendorActionModal();
    DeleteDataVendorActionModal();
    RestoreDataVendorActionModal();

    // Reload table
    window.addEventListener(ReloadDatatableSelector, function () {
        reloadTable();
    });
});
