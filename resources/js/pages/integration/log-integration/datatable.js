import { GlobalAdvanceDatatable, GlobalAdvanceFlatpickr } from "../../../app";
import { DateTimeFormatter } from "../../../utility/ui";

let receiveDataTableInstance;
let sendResultTableInstance;

// Helper: Date filter getter
function getReceiveDataFilters() {
    return document.querySelector(".receive-data-date-filter")?.value || "";
}

function getSendResultFilters() {
    return document.querySelector(".send-result-date-filter")?.value || "";
}

// Helpers: Reload tables
function reloadReceiveTable() {
    if (receiveDataTableInstance?.instance) {
        receiveDataTableInstance.instance.ajax.reload();
    }
}

function reloadSendTable() {
    if (sendResultTableInstance?.instance) {
        sendResultTableInstance.instance.ajax.reload();
    }
}

// Render status badge helper
function renderStatusBadge(status) {
    const cleanStatus = String(status).toLowerCase();
    if (cleanStatus === "success") {
        return `<span class="badge badge-label badge-soft-success text-capitalize">Success</span>`;
    } else if (cleanStatus === "failed") {
        return `<span class="badge badge-label badge-soft-danger text-capitalize">Failed</span>`;
    }
    return `<span class="badge badge-label badge-soft-secondary text-capitalize">${status}</span>`;
}

// Init Datatables
function initReceiveDataTable() {
    const columns = [
        {
            data: "created_at",
            title: "Date",
            render: (data) => DateTimeFormatter.human(data) || "-",
        },
        {
            data: "order_number",
            title: "Order Number",
            render: (data) => `<span class="fw-semibold">${data || "-"}</span>`,
        },
        {
            data: "message",
            title: "Message",
            render: (data) => data || "-",
        },
        {
            data: "status",
            title: "Status",
            render: (data) => renderStatusBadge(data),
        },
        {
            data: "endpoint",
            title: "EndPoint",
            render: (data) =>
                `<code class="fs-xxs text-muted">${data || "-"}</code>`,
        },
        {
            data: null,
            title: "Payload",
            orderable: false,
            searchable: false,
            render: () => {
                return `<button class="btn btn-sm btn-soft-info btn-view-payload" type="button">
                            <i class="ti ti-eye align-middle me-1"></i> View
                        </button>`;
            },
        },
    ];

    receiveDataTableInstance = new GlobalAdvanceDatatable(
        "#receive-data-table",
        {
            ajax: {
                url: "/integration/log-integration/data",
                data: function (d) {
                    d.type = "new_request";
                    d.date_range = getReceiveDataFilters();
                },
            },
            columns: columns,
            useHideColumn: true,
            order: [[0, "desc"]],
        },
    );
}

function initSendResultDataTable() {
    const columns = [
        {
            data: "created_at",
            title: "Date",
            render: (data) => DateTimeFormatter.human(data) || "-",
        },
        {
            data: "order_number",
            title: "Order Number",
            render: (data) => `<span class="fw-semibold">${data || "-"}</span>`,
        },
        {
            data: "message",
            title: "Message",
            render: (data) => data || "-",
        },
        {
            data: "status",
            title: "Status",
            render: (data) => renderStatusBadge(data),
        },
        {
            data: "endpoint",
            title: "EndPoint",
            render: (data) =>
                `<code class="fs-xxs text-muted">${data || "-"}</code>`,
        },
        {
            data: null,
            title: "Payload",
            orderable: false,
            searchable: false,
            render: () => {
                return `<button class="btn btn-sm btn-soft-info btn-view-payload" type="button">
                            <i class="ti ti-eye align-middle me-1"></i> View
                        </button>`;
            },
        },
    ];

    sendResultTableInstance = new GlobalAdvanceDatatable("#send-result-table", {
        ajax: {
            url: "/integration/log-integration/data",
            data: function (d) {
                d.type = "send_result";
                d.date_range = getSendResultFilters();
            },
        },
        columns: columns,
        useHideColumn: true,
        order: [[0, "desc"]],
    });
}

// Payload View Handler
function initPayloadViewHandler() {
    const modalElement = document.getElementById("modal-view-payload");
    if (!modalElement) return;

    const modal = new bootstrap.Modal(modalElement);
    const codeDisplay = document.getElementById("payload-display");

    // Listen to clicks on the tables for view buttons
    document.addEventListener("click", function (e) {
        const btn = e.target.closest(".btn-view-payload");
        if (!btn) return;

        // Determine which table the button is in
        const isReceiveTable = btn.closest("#receive-data-table") !== null;
        const datatable = isReceiveTable
            ? $("#receive-data-table").DataTable()
            : $("#send-result-table").DataTable();

        const tr = btn.closest("tr");
        const row = datatable.row(tr);
        const data = row.data();

        if (data && data.row_data) {
            const payload = data.row_data.payload;
            console.log(typeof payload);
            codeDisplay.textContent = JSON.stringify(payload, null, 2);
            console.log(codeDisplay.textContent);
            console.log(codeDisplay.innerHTML);
            modal.show();
        }
    });
}

document.addEventListener("DOMContentLoaded", function () {
    // Init Tables
    initReceiveDataTable();
    initSendResultDataTable();

    // Init Date Filters
    new GlobalAdvanceFlatpickr(".receive-data-date-filter", {
        onClose: reloadReceiveTable,
    });

    new GlobalAdvanceFlatpickr(".send-result-date-filter", {
        onClose: reloadSendTable,
    });

    // Init Payload Modals
    initPayloadViewHandler();
});
