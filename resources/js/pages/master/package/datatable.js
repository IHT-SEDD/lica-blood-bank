// ---------- Import Libraries ----------
import {
    GlobalAdvanceDatatable,
    GlobalAdvanceFlatpickr,
    GlobalDeleteDataConfirmation,
    GlobalRestoreDataConfirmation,
    GlobalEditData,
} from "../../../app";
import TomSelect from "tom-select";
import { DateTimeFormatter } from "../../../utility/ui";

// ---------- Global variable untuk memudahkan penyesuaian :begin ----------
let masterPackageTableInstance; // instance datatable untuk global

// Filter datatable
const DateFilterSelector = ".master-package-table-date-filter"; // class selector filter tanggal

// Datatable
const DatatableSelector = "#master-package-table"; // id selector datatable
const MasterDataURL = "/master/package/data"; // url fetch data untuk datatable
const ReloadDatatableSelector = "master-package-reload"; // index reload datatable

// Edit Action
const FormEditSelector = "#edit_data_package"; // id selector form edit
const ModalEditSelector = "edit_data_master_package_modal"; // id selector modal edit
const ActionEditSelector = ".btn-edit-package"; // class selector button edit
const AttributeEdit = "editId"; // attribute data id edit

// Delete Action
const ModalDeleteSelector = "delete_data_master_package_modal"; // id selector modal delete
const ActionDeleteSelector = ".btn-delete-package"; // class selector button delete
const AttributeDelete = "deleteId"; // attribute data id delete
const ConfirmDeleteSelector = "#confirm_delete"; // id selector confirm delete

// Restore Action
const ModalRestoreSelector = "restore_data_master_package_modal"; // id selector modal restore
const ActionRestoreSelector = ".btn-restore-package"; // class selector button restore
const AttributeRestore = "restoreId"; // attribute data id restore
const ConfirmRestoreSelector = "#confirm_restore"; // id selector confirm restore
// ---------- Global variable untuk memudahkan penyesuaian :end ----------

// ---------- Helper: Ambil semua filter :begin ----------
function getFilters() {
    const dateVal = document.querySelector(DateFilterSelector)?.value;
    const role = document.querySelector("#filter-role")?.value || "";

    let start_date = "";
    let end_date = "";

    if (dateVal) {
        const separator = dateVal.includes(" to ") ? " to " : " - ";
        const parts = dateVal.split(separator);

        start_date = parts[0] || "";
        end_date = parts[1] || "";
    }

    return { role, start_date, end_date };
}
// ---------- Helper: Ambil semua filter :end ----------

// ---------- Helper: Reload tabel :begin ----------
function reloadTable() {
    if (masterPackageTableInstance?.instance) {
        masterPackageTableInstance.instance.ajax.reload();
    }
}
// ---------- Helper: Reload tabel :end ----------

// ---------- Datatable untuk master package :begin ----------
function MasterPackageTable() {
    // ---------- Init kolom pada tabel ----------
    const MasterPackageTableColumns = [
        {
            data: null,
            title: "No",
            render: (data, type, row, meta) => {
                return meta.row + 1;
            },
        },
        { data: "name", title: "Name" },
        { data: "blood_component", title: "Blood Component" },
        {
            data: "package_tests",
            title: "Test List",
            render: (data) => {
                if (Array.isArray(data) && data.length > 0) {
                    return data
                        .map(
                            (test) => test.test?.name || `Test ${test.test_id}`,
                        )
                        .join(", ");
                }
                return "-";
            },
        },
        { data: "general_code", title: "General Code" },
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
                            <button id="edit-data-${row.public_id}" class="dropdown-item btn-edit-package" data-edit-id="${row.public_id}" type="button">
                            Edit</button>
                        </li>
                        <li>
                            <button id="restore-data-${row.public_id}" class="dropdown-item fw-medium btn-restore-package ${isDeleted ? "enabled text-info" : "disabled"}" data-restore-id="${row.public_id}" type="button">
                            Restore</button>
                        </li>
                        <li>
                            <button id="delete-data-${row.public_id}" class="dropdown-item btn-delete-package text-danger" data-delete-id="${row.public_id}" type="button">
                            Delete</button>
                        </li>
                    </ul>
                `;
            },
        },
    ];

    // ---------- Panggil GlobalAdvanceDatatable untuk menampilkan tabel ----------
    masterPackageTableInstance = new GlobalAdvanceDatatable(
        "#master-package-table",
        {
            ajax: {
                url: MasterDataURL,
                data: function (d) {
                    const filters = getFilters();
                    d.role = filters.role;
                    d.start_date = filters.start_date;
                    d.end_date = filters.end_date;
                },
            },
            columns: MasterPackageTableColumns,
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
// ---------- Datatable untuk master package :end ----------

// ---------- Filter tanggal dari flatpickr untuk data di tabel :begin ----------
function DateRangeFilter() {
    new GlobalAdvanceFlatpickr(DateFilterSelector, {
        onClose: reloadTable,
    });
}
// ---------- Filter tanggal dari flatpickr untuk data di tabel :end ----------
// ---------- Function ini tomselect blood component :begin ----------
function EditBloodComponent() {
    const editBloodComponent = new TomSelect(
        "#edit_data_package_select-blood-component",
        {
            valueField: "id",
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
        },
    );
}
function EditTests() {
    const editTests = new TomSelect("#edit_data_package_select-tests", {
        maxItems: null,
        valueField: "id",
        labelField: "text",
        searchField: "text",
        preload: true,
        load: function (query, callback) {
            fetch(`/utility/select/test?q=${encodeURIComponent(query)}`)
                .then((res) => res.json())
                .then((json) => callback(json.results))
                .catch(() => callback());
        },
    });
}
// ---------- Function ini tomselect blood component :end ----------
// ---------- Handle modal edit data :begin ----------
function EditDataPackageActionModal() {
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
        console.log(data.package_tests);
        document.querySelector("#edit_data_package_name").value =
            data.name ?? "";
        // Kondisi untuk tom select

        const selectBloodComponent = document.querySelector(
            "#edit_data_package_select-blood-component",
        );
        const selectTests = document.querySelector(
            "#edit_data_package_select-tests",
        );

        if (selectTests) {
            selectTests.tomselect.clear();
            selectTests.tomselect.clearOptions();
            // load option dulu
            data.package_tests.forEach((test) => {
                console.log(test.test);
                selectTests.tomselect.addOption({
                    id: test.test.public_id,
                    text: test.test?.name || `Test ${test.test.public_id}`,
                });
            });
            selectTests.tomselect.setValue(
                data.package_tests.map((test) => test.test.public_id),
            );
        }
        if (selectBloodComponent) {
            selectBloodComponent.tomselect.clear();

            selectBloodComponent.tomselect.setValue(data.blood_component);
        }

        document.querySelector("#edit_data_package_general_code").value =
            data.general_code ?? "";
    });
}
// ---------- Handle modal edit data :end ----------

// ---------- Handle modal delete data :begin ----------
function DeleteDataPackageActionModal() {
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
function RestoreDataPackageActionModal() {
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
    MasterPackageTable();

    // Select function
    // FilterRole();
    // EditRole();
    EditBloodComponent();
    EditTests();

    // Date range picker
    DateRangeFilter();

    // Action data
    EditDataPackageActionModal();
    DeleteDataPackageActionModal();
    RestoreDataPackageActionModal();

    // Reload table
    window.addEventListener(ReloadDatatableSelector, function () {
        reloadTable();
    });
});
