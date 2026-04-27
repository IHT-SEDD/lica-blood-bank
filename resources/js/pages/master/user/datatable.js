// ---------- Import Libraries ----------
import {
    GlobalAdvanceDatatable,
    GlobalAdvanceFlatpickr,
    DateTimeFormatter,
} from "../../../app";
import TomSelect from "tom-select";

// ---------- Init global variable ----------
let masterUserTableInstance;

// ---------- Helper: Ambil semua filter :begin ----------
function getFilters() {
    const role = document.querySelector("#filter-role")?.value || "";

    const dateVal = document.querySelector(
        ".master-user-table-date-filter",
    )?.value;

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
    if (masterUserTableInstance?.instance) {
        masterUserTableInstance.instance.ajax.reload();
    }
}
// ---------- Helper: Reload tabel :end ----------

// ---------- Datatable untuk master user :begin ----------
function MasterUserTable() {
    // ---------- Init kolom pada tabel ----------
    const MasterUserTableColumns = [
        {
            data: null,
            title: "No",
            render: (data, type, row, meta) => {
                return meta.row + 1;
            },
        },
        { data: "name", title: "Name" },
        { data: "username", title: "Username" },
        { data: "email", title: "Email" },
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
            data: "email_verified_at",
            title: "Email Verified At",
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
                            <button id="edit-data-${row.public_id}" class="dropdown-item btn-edit-user" data-edit-id="${row.public_id}" type="button">
                            Edit</button>
                        </li>
                        <li>
                            <button id="delete-data-${row.public_id}" class="dropdown-item btn-delete-user text-danger" data-delete-id="${row.public_id}" type="button">
                            Delete</button>
                        </li>
                    </ul>
                `;
            },
        },
    ];

    // ---------- Panggil GlobalAdvanceDatatable untuk menampilkan tabel ----------
    masterUserTableInstance = new GlobalAdvanceDatatable("#master-user-table", {
        ajax: {
            url: "/master/user/data",
            data: function (d) {
                const filters = getFilters();
                d.role = filters.role;
                d.start_date = filters.start_date;
                d.end_date = filters.end_date;
            },
        },
        columns: MasterUserTableColumns,
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
// ---------- Datatable untuk master user :end ----------

// ---------- Filter role dari tom-select untuk data di tabel :begin ----------
function FilterRole() {
    const filterRole = new TomSelect("#filter-role", {
        valueField: "text",
        labelField: "text",
        searchField: "text",
        preload: true,
        load: function (query, callback) {
            fetch(`/utility/select/role?q=${encodeURIComponent(query)}`)
                .then((res) => res.json())
                .then((json) => callback(json.results))
                .catch(() => callback());
        },
    });

    filterRole.on("change", reloadTable);
}
// ---------- Filter role dari tom-select untuk data di tabel :end ----------

// ---------- Select new role dari tom-select untuk data di modal edit :begin ----------
function EditRole() {
    const editRole = new TomSelect("#edit_data_user_role", {
        valueField: "text",
        labelField: "text",
        searchField: "text",
        preload: true,
        load: function (query, callback) {
            fetch(`/utility/select/role?q=${encodeURIComponent(query)}`)
                .then((res) => res.json())
                .then((json) => callback(json.results))
                .catch(() => callback());
        },
    });
}
// ---------- Select new role dari tom-select untuk data di modal edit :end ----------

// ---------- Filter tanggal dari flatpickr untuk data di tabel :begin ----------
function DateRangeFilter() {
    new GlobalAdvanceFlatpickr(".master-user-table-date-filter", {
        onClose: reloadTable,
    });
}
// ---------- Filter tanggal dari flatpickr untuk data di tabel :end ----------

// ---------- Handle modal edit data :begin ----------
function EditDataUserActionModal() {
    document.addEventListener("click", async function (e) {
        const btn = e.target.closest(".btn-edit-user");
        if (!btn) return;

        // Ambil id data dari attribut button
        const editId = btn.dataset.editId;
        if (!editId) {
            notyf.error({
                message: "ID Data Not Found!",
            });
            return;
        }

        try {
            // Jalankan get data ke url
            const res = await fetch(`/master/user/data/${id}`);
            // Terima respon data
            const json = await res.json();
            const data = json.data;

            // Isi value input form edit
            document.querySelector("#edit_data_user_name").value =
                data.name ?? "";
            document.querySelector("#edit_data_user_username").value =
                data.username ?? "";
            document.querySelector("#edit_data_user_email").value =
                data.email ?? "";
            document.querySelector("#edit_data_user_is_active").checked =
                data.is_active == 1;

            // Kondisi untuk tom select
            const select = document.querySelector("#edit_data_user_role");

            if (select) {
                // Ambil data roles dari fetch
                const role = data.roles;
                // Hapus option
                select.tomselect.clear();
                // Select value role sesuai dengan data
                select.tomselect.setValue(role.map((role) => role.name));
            }

            // Berikan attribute form edit dengan id data
            document.querySelector("#edit_data_user").dataset.id =
                data.public_id;

            // ===== Show Modal =====
            const modal = new bootstrap.Modal(
                document.getElementById("edit_data_master_user_modal"),
            );
            modal.show();
        } catch (err) {
            notyf.error({
                message: "Failed to load data!",
            });
        }
    });
}
// ---------- Handle modal edit data :end ----------

document.addEventListener("DOMContentLoaded", function () {
    // Datatable
    MasterUserTable();

    // Select function
    FilterRole();
    EditRole();

    // Date range picker
    DateRangeFilter();

    // Action data
    EditDataUserActionModal();

    window.addEventListener("master-user-reload", function () {
        reloadTable();
    });
});
