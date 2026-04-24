import { AdvanceDatatable, DateTimeFormatter } from "../../../app";
import TomSelect from "tom-select";

let MasterStorageRackTable, FilterStorage, SelectStorage;

MasterStorageRackTable = () => {
    // Inisialisasi kolom pada tabel. data -> yang akan muncul di tabel, title -> untuk show/hide column
    const MasterStorageRackTableColumns = [
        {
            data: null,
            defaultContent: "",
            orderable: false,
        },
        {
            data: null,
            title: "No",
            render: (data, type, row, meta) => {
                return meta.row + 1;
            },
        },
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
                return meta.row + 1;
            },
        },
    ];

    // Build tabel dengan menggunakan AdvanceDatatable
    const masterStorageRackTable = new AdvanceDatatable(
        "#master-storage-rack-table",
        {
            ajax: "/master/storage-rack/data",
            columns: MasterStorageRackTableColumns,
        },
    );

    // Ambil title dari const table_column untuk isi dari show/hide column
    const columnLabels = MasterStorageRackTableColumns.map(
        (col) => col.title || col.data,
    );

    // Ambil wrapper show/hide column dari dom datatable
    const columnToggleWrapper = document.querySelector(".columnToggleWrapper");

    // Buat dropdown untuk show/hide column
    if (columnToggleWrapper) {
        const dropdown = document.createElement("div");
        dropdown.className = "dropdown";

        dropdown.innerHTML = `
        <button class="btn btn-sm btn-primary" type="button" data-bs-toggle="dropdown" data-bs-auto-close="outside">
            Show/Hide Columns
        </button>
        <ul class="dropdown-menu" id="columnToggleMenu">
            ${columnLabels
                .map(
                    (label, index) => `
                <li class="dropdown-item">
                    <div class="form-check">
                        <input class="form-check-input form-check-input-light fs-14 mt-0 toggle-vis" 
                               type="checkbox" data-column="${index}" id="colToggle${index}" checked>
                        <label class="form-check-label fw-medium" for="colToggle${index}">
                            ${label}
                        </label>
                    </div>
                </li>
            `,
                )
                .join("")}
        </ul>
        `;

        columnToggleWrapper.appendChild(dropdown);

        document
            .getElementById("columnToggleMenu")
            .addEventListener("change", function (e) {
                if (e.target.classList.contains("toggle-vis")) {
                    const colIndex = parseInt(e.target.dataset.column, 10);
                    const column = masterStorageRackTable.column(colIndex);
                    if (!column) return;
                    column.visible(e.target.checked);
                }
            });
    }
};

FilterStorage = () => {
    new TomSelect("#filter-storage", {
        valueField: "id",
        labelField: "text",
        searchField: "text",
        sortField: {
            field: "id",
            direction: "asc",
        },
        create: false,
        preload: true,
        load: function (query, callback) {
            fetch(`/utility/select/storage?q=${encodeURIComponent(query)}`)
                .then((response) => response.json())
                .then((json) => {
                    callback(json.results);
                })
                .catch(() => {
                    callback();
                });
        },
    });
};

SelectStorage = () => {
    new TomSelect("#select-storage", {
        valueField: "id",
        labelField: "text",
        searchField: "text",
        sortField: {
            field: "id",
            direction: "asc",
        },
        create: false,
        preload: true,
        load: function (query, callback) {
            fetch(`/utility/select/storage?q=${encodeURIComponent(query)}`)
                .then((response) => response.json())
                .then((json) => {
                    callback(json.results);
                })
                .catch(() => {
                    callback();
                });
        },
    });
};

document.addEventListener("DOMContentLoaded", function () {
    MasterUserTable();
    FilterStorage();
    SelectStorage();
});
