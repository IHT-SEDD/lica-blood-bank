import { AdvanceDatatable } from "../../../app";

let ListStockTable;

ListStockTable = () => {
    // Inisialisasi kolom pada tabel. data -> yang akan muncul di tabel, title -> untuk show/hide column
    const ListStockTableColumns = [
        {
            data: null,
            title: "No",
            render: (data, type, row, meta) => {
                return meta.row + 1;
            },
        },
        {
            data: "bag_number",
            title: "Bag Number",
            render: (data, type, row) => {
                return `<a class="link-danger text-decoration-underline link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover" href="#">${data}</a>`;
            },
        },
        { data: "blood_type", title: "Blood Type" },
        { data: "blood_rhesus", title: "Blood Rhesus" },
        { data: "supplier", title: "Supplier" },
        { data: "storage_name", title: "Storage/Rack" },
        { data: "expired_date", title: "Expired Date" },
        { data: "received_date", title: "Received Date" },
        {
            data: null,
            title: "Expiry Countdown",
            render: (data, type, row) => {
                const received = new Date(row.received_date);
                const expired = new Date(row.expired_date);

                let diffMs = expired - received;

                if (diffMs <= 0) return "expired";

                const diffMinutes = Math.floor(diffMs / (1000 * 60));
                const diffHours = Math.floor(diffMinutes / 60);
                const diffDays = Math.floor(diffHours / 24);

                const years = Math.floor(diffDays / 365);
                const months = Math.floor((diffDays % 365) / 30);
                const days = diffDays % 30;
                const hours = diffHours % 24;

                let result = [];

                if (years) result.push(`${years} tahun`);
                if (months) result.push(`${months} bulan`);
                if (days) result.push(`${days} hari`);
                if (hours) result.push(`${hours} jam`);

                return result.length ? result.join(" ") : "0 jam";
            },
        },
        {
            data: "status",
            title: "Status",
            render: (data) => {
                const isAvailable = data == "available";
                return `<span class="badge badge-label badge-soft-${isAvailable ? "success" : "danger"}">${data}</span>`;
            },
        },
    ];

    // Build tabel dengan menggunakan AdvanceDatatable
    new AdvanceDatatable("#list-stock-table", {
        ajax: "/data/datatable-list-stock.json",
        columns: ListStockTableColumns,
    });

    // Ambil title dari const table_column untuk isi dari show/hide column
    const columnLabels = ListStockTableColumns.map(
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
                    const column = table.column(colIndex);
                    column.visible(e.target.checked);
                }
            });
    }
};

document.addEventListener("DOMContentLoaded", function () {
    ListStockTable();
});
