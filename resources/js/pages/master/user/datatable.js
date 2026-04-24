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
                return meta.row + 1;
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

// ---------- Filter tanggal dari flatpickr untuk data di tabel :begin ----------
function DateRangeFilter() {
    new GlobalAdvanceFlatpickr(".master-user-table-date-filter", {
        onClose: reloadTable,
    });
}
// ---------- Filter tanggal dari flatpickr untuk data di tabel :end ----------

document.addEventListener("DOMContentLoaded", function () {
    MasterUserTable();
    FilterRole();
    DateRangeFilter();
    window.addEventListener("master-user-reload", function () {
        reloadTable();
    });
});
