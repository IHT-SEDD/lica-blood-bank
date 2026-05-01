// ---------- Import Libraries ----------
import { render } from "@fullcalendar/core/preact.js";
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
let masterBloodPackTableInstance; // instance datatable untuk global

// Filter datatable
const DateFilterSelector = ".master-blood-pack-table-date-filter"; // class selector filter tanggal

// Datatable
const DatatableSelector = "#master-blood-pack-table"; // id selector datatable
const MasterDataURL = "/master/blood-pack/data"; // url fetch data untuk datatable
const ReloadDatatableSelector = "master-blood-pack-reload"; // index reload datatable

// Delete Action
const ModalDeleteSelector = "delete_data_master_blood_pack_modal"; // id selector modal delete
const ActionDeleteSelector = ".btn-delete-blood-pack"; // class selector button delete
const AttributeDelete = "deleteId"; // attribute data id delete
const ConfirmDeleteSelector = "#confirm_delete"; // id selector confirm delete

// Restore Action
const ModalRestoreSelector = "restore_data_master_blood_pack_modal"; // id selector modal restore
const ActionRestoreSelector = ".btn-restore-blood-pack"; // class selector button restore
const AttributeRestore = "restoreId"; // attribute data id restore
const ConfirmRestoreSelector = "#confirm_restore"; // id selector confirm restore
// ---------- Global variable untuk memudahkan penyesuaian :end ----------

// ---------- Helper: Ambil semua filter :begin ----------
function getFilters() {
    const bloodGroup =
        document.querySelector("#filter-blood-group")?.value || "";
    const status = document.querySelector("#filter-status")?.value || "";
    const component = document.querySelector("#filter-component")?.value || "";
    const dateVal = document.querySelector(DateFilterSelector)?.value;

    let start_date = "";
    let end_date = "";

    if (dateVal) {
        const separator = dateVal.includes(" to ") ? " to " : " - ";
        const parts = dateVal.split(separator);

        start_date = parts[0] || "";
        end_date = parts[1] || "";
    }

    return { bloodGroup, status, component, start_date, end_date };
}
// ---------- Helper: Ambil semua filter :end ----------

// ---------- Helper: Reload tabel :begin ----------
function reloadTable() {
    if (masterBloodPackTableInstance?.instance) {
        masterBloodPackTableInstance.instance.ajax.reload();
    }
}
// ---------- Helper: Reload tabel :end ----------

// ---------- Datatable untuk master storage :begin ----------
function MasterBloodPackTable() {
    // ---------- Init kolom pada tabel ----------
    const MasterBloodPackTableColumns = [
        {
            data: null,
            title: "No",
            render: (data, type, row, meta) => {
                return meta.row + 1;
            },
        },
        { data: "bag_number", title: "Bag Number" },
        { data: "bag_number", title: "Bag Number LICA" },
        { data: "blood_group", title: "Group" },
        { data: "rhesus", title: "Rhesus" },
        { data: "blood_component", title: "Component" },
        { data: "blood_volume", title: "Volume" },
        {
            data: "blood_status",
            title: "Status",
            render: (data, type, row) => {
                const isDeleted = row.deleted_at !== null;
                if (isDeleted) {
                    return `<span class="badge badge-label badge-soft-danger">Trashed</span>`;
                }
                return `<span class="badge badge-label badge-soft-secondary">${data}</span>`;
            },
        },
        {
            data: "aftap_date",
            title: "Aftap",
            render: (data) => {
                return DateTimeFormatter.human(data);
            },
        },
        {
            data: "expiry_date",
            title: "Expired",
            render: (data) => {
                return DateTimeFormatter.human(data);
            },
        },
        {
            data: "is_hiv",
            title: "HIV",
            render: (data, type, row) => {
                return `<span class="badge badge-label badge-soft-${data == 1 ? "success" : "danger"}">
                    ${data == 1 ? "REACTIVE" : "NON REACTIVE"}
                </span>`;
            },
        },
        {
            data: "is_hcv",
            title: "HCV",
            render: (data, type, row) => {
                return `<span class="badge badge-label badge-soft-${data == 1 ? "success" : "danger"}">
                    ${data == 1 ? "REACTIVE" : "NON REACTIVE"}
                </span>`;
            },
        },
        {
            data: "is_hbsag",
            title: "HbsAG",
            render: (data, type, row) => {
                return `<span class="badge badge-label badge-soft-${data == 1 ? "success" : "danger"}">
                    ${data == 1 ? "REACTIVE" : "NON REACTIVE"}
                </span>`;
            },
        },
        {
            data: "is_syphilis",
            title: "Syphilis",
            render: (data, type, row) => {
                return `<span class="badge badge-label badge-soft-${data == 1 ? "success" : "danger"}">
                    ${data == 1 ? "REACTIVE" : "NON REACTIVE"}
                </span>`;
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
    masterBloodPackTableInstance = new GlobalAdvanceDatatable(
        DatatableSelector,
        {
            ajax: {
                url: MasterDataURL,
                data: function (d) {
                    const filters = getFilters();
                    d.start_date = filters.start_date;
                    d.end_date = filters.end_date;
                    d.bloodGroup = filters.bloodGroup;
                    d.status = filters.status;
                    d.component = filters.component;
                },
            },
            columns: MasterBloodPackTableColumns,
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

// ---------- Filter dari tom-select untuk data di tabel :begin ----------
function FilterGroup() {
    const filterGroup = new TomSelect("#filter-blood-group", {
        valueField: "text",
        labelField: "text",
        searchField: "text",
        preload: true,
        load: function (query, callback) {
            fetch(`/utility/select/blood-group?q=${encodeURIComponent(query)}`)
                .then((res) => res.json())
                .then((json) => callback(json.results))
                .catch(() => callback());
        },
    });

    filterGroup.on("change", reloadTable);
}

function FilterStatus() {
    const filterStatus = new TomSelect("#filter-status", {
        valueField: "text",
        labelField: "text",
        searchField: "text",
        preload: true,
        load: function (query, callback) {
            fetch(`/utility/select/blood-status?q=${encodeURIComponent(query)}`)
                .then((res) => res.json())
                .then((json) => callback(json.results))
                .catch(() => callback());
        },
    });

    filterStatus.on("change", reloadTable);
}

function FilterComponent() {
    const filterComponent = new TomSelect("#filter-component", {
        valueField: "text",
        labelField: "text",
        searchField: "text",
        preload: true,
        load: function (query, callback) {
            fetch(
                `/utility/select/blood-component?q=${encodeURIComponent(query)}`,
            )
                .then((res) => res.json())
                .then((json) => callback(json.results))
                .catch(() => callback());
        },
    });

    filterComponent.on("change", reloadTable);
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
function DeleteDataBloodPackActionModal() {
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
function RestoreDataBloodPackActionModal() {
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
    MasterBloodPackTable();

    // Select function
    FilterGroup();
    FilterStatus();
    FilterComponent();

    // Date range picker
    DateRangeFilter();

    // Action data
    DeleteDataBloodPackActionModal();
    RestoreDataBloodPackActionModal();

    // Reload table
    window.addEventListener(ReloadDatatableSelector, function () {
        reloadTable();
    });
});
