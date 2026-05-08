import { GlobalAdvanceDatatable } from "../../../app";

// ---------- Global Variable ----------
let listRequestTable = "#list-request-table";
let listRequestTableInstance;

// Initialize List Request Datatable
export function DatatableRequestBlood() {
    const tableEl = document.querySelector(listRequestTable);

    if ($.fn.DataTable.isDataTable(listRequestTable)) {
        return;
    }

    if (tableEl) {
        listRequestTableInstance = new GlobalAdvanceDatatable(
            listRequestTable,
            {
                serverSide: true,
                processing: true,
                searchDelay: 1000,
                dom: '<"mt-3"f>rtip',
                ajax: {
                    url: "/blood-transfusion/datatable-blood-request",
                    type: "GET",
                    data: function (d) {
                        const dateFilter = document.querySelector(
                            ".blood-transfusion-date-filter",
                        );
                        if (dateFilter) {
                            d.date_range = dateFilter.value;
                        }
                    },
                    dataSrc: "data",
                },
                columns: [
                    { data: "blood_request_at", name: "blood_request_at" },
                    { data: "patient.name", name: "patient.name" },
                    { data: "patient.medrec", name: "patient.medrec" },
                    { data: "lab_number", name: "lab_number" },
                    { data: "order_number", name: "order_number" },
                    { data: "room.name", name: "room.name" },
                    {
                        data: "is_cito",
                        name: "is_cito",
                        orderable: false,
                        searchable: false,
                        render: function (data) {
                            return data
                                ? `<i data-lucide="triangle-alert" class="fs-4 text-warning fill-warning" data-bs-title="CITO" data-bs-toggle="tooltip"></i>`
                                : `-`;
                        },
                    },
                    {
                        data: null,
                        name: "action",
                        orderable: false,
                        searchable: false,
                        render: function (data) {
                            return `
                            <div class="dropdown">
                               <button aria-expanded="false" class="btn btn-sm btn-soft-primary datatable-action-toggle" data-bs-toggle="dropdown" 
                                data-bs-auto-close="true" type="button">
                                    <i class="ti ti-dots align-middle"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="dropdown-item btn-edit-blood-transfusion" href="#" data-public-id="${data.public_id}" data-bs-toggle="modal" data-bs-target="#edit_data_blood_transfusion_modal">
                                            <i class="ti ti-pencil me-1"></i> Edit
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item text-danger btn-delete-blood-transfusion" href="#" data-public-id="${data.public_id}">
                                            <i class="ti ti-trash me-1"></i> Delete
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            `;
                        },
                    },
                ],
                order: [[0, "desc"]],
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
                drawCallback: function () {
                    // Initialize lucide icons after draw
                    if (typeof lucide !== "undefined") {
                        lucide.createIcons();
                    }
                    // Initialize tooltips
                    const tooltipTriggerList = document.querySelectorAll(
                        '[data-bs-toggle="tooltip"]',
                    );
                    const tooltipList = [...tooltipTriggerList].map(
                        (tooltipTriggerEl) =>
                            new bootstrap.Tooltip(tooltipTriggerEl),
                    );
                },
            },
        );
    }
}

document.addEventListener("DOMContentLoaded", function () {
    // Event listener for reloading datatable from other scripts
    window.addEventListener(listRequestTable, function () {
        if ($.fn.DataTable.isDataTable(listRequestTable)) {
            $(listRequestTable).DataTable().ajax.reload(null, false);
        }
    });
});
