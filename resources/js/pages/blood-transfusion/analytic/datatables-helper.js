import { GlobalAdvanceDatatable, GlobalAdvanceTomselect } from "../../../app";
import TomSelect from "tom-select";

// ---------- Global Variable ----------
let listRequestTable = "#list-request-table";
export let listRequestTableInstance;
export let listBagRequestTableInstance;

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
                searchDelay: 1000,
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
            },
        );
    }
}

// Initialize List Bag Request Datatable
export function DatatableListBagRequest() {
    const tableId = "#list-bag-request-table";
    const tableEl = document.querySelector(tableId);

    if (!tableEl) return;

    if ($.fn.DataTable.isDataTable(tableId)) {
        return;
    }

    listBagRequestTableInstance = new GlobalAdvanceDatatable(tableId, {
        serverSide: true,
        removeSearch: true,
        removePageInfo: true,
        removePagination: true,
        ajax: function (data, callback, settings) {
            if (!window.currentTransfusionPublicId) {
                callback({
                    data: [],
                    recordsTotal: 0,
                    recordsFiltered: 0,
                    draw: data.draw,
                });
                return;
            }

            $.ajax({
                url: `/blood-transfusion/${window.currentTransfusionPublicId}/bag-requests`,
                type: "GET",
                data: data,
                success: function (res) {
                    callback(res);
                },
                error: function () {
                    callback({
                        data: [],
                        recordsTotal: 0,
                        recordsFiltered: 0,
                        draw: data.draw,
                    });
                },
            });
        },
        columns: [
            {
                data: null,
                name: "bag_number",
                orderable: false,
                searchable: false,
                render: function (data, type, row) {
                    if (row.has_available_stock) {
                        let optionsHtml =
                            '<option value="">Choose Bag Number</option>';
                        row.available_stocks.forEach((stock) => {
                            const selected =
                                row.selected_stock_id == stock.id
                                    ? "selected"
                                    : "";
                            optionsHtml += `<option value="${stock.id}" ${selected}>${stock.text}</option>`;
                        });
                        return `
                            <select class="form-control form-control-sm tomselect-sm select-bag-number" data-id="${row.public_id}" placeholder="Choose Bag Number">
                                ${optionsHtml}
                            </select>
                        `;
                    } else {
                        return `<span class="text-danger fw-semibold">Not Available Stock</span>`;
                    }
                },
            },
            {
                data: "blood_group",
                name: "blood_group",
                orderable: false,
                searchable: false,
            },
            {
                data: "blood_rhesus",
                name: "blood_rhesus",
                orderable: false,
                searchable: false,
            },
            {
                data: "blood_component",
                name: "blood_component",
                orderable: false,
                searchable: false,
            },
        ],
        drawCallback: function () {
            const selects = document.querySelectorAll(".select-bag-number");
            selects.forEach((select) => {
                if (!select.tomselect) {
                    new GlobalAdvanceTomselect(select, {
                        valueField: "id",
                        sortField: {
                            field: "text",
                            direction: "asc",
                        },
                    });

                    select.addEventListener("change", async function () {
                        const detailId = this.dataset.id;
                        const stockId = this.value;

                        if (!stockId) return;

                        try {
                            const response = await fetch(
                                `/blood-transfusion/detail/${detailId}/update-stock`,
                                {
                                    method: "PATCH",
                                    headers: {
                                        "Content-Type": "application/json",
                                        "X-CSRF-TOKEN": document
                                            .querySelector(
                                                'meta[name="csrf-token"]',
                                            )
                                            .getAttribute("content"),
                                    },
                                    body: JSON.stringify({
                                        blood_stock_id: stockId,
                                    }),
                                },
                            );

                            const result = await response.json();

                            if (!response.ok) {
                                notyf.error({
                                    message:
                                        result.message ||
                                        "Failed to update bag number!",
                                });
                            } else {
                                notyf.success({
                                    message:
                                        result.message ||
                                        "Bag number successfully updated!",
                                });
                            }
                        } catch (error) {
                            console.error(error);
                            notyf.error({
                                message:
                                    "An error occurred while updating bag number.",
                            });
                        }
                    });
                }
            });
        },
    });
}

// Initialize Blood Pack Datatable for Edit Modal
export function DatatableBloodPackModal() {
    const tableId = "#edit-blood-pack-available-table";
    const tableEl = document.querySelector(tableId);

    if (!tableEl) return;

    if ($.fn.DataTable.isDataTable(tableId)) {
        $(tableId).DataTable().destroy();
    }

    new GlobalAdvanceDatatable(tableId, {
        serverSide: true,
        removePageInfo: true,
        removePagination: true,
        removeSearch: true,
        searchDelay: 800,
        scrollY: "250px",
        scrollCollapse: true,
        ajax: {
            url: "/blood-transfusion/datatable-blood-pack",
            type: "GET",
            dataSrc: "data",
        },
        columns: [
            { data: "blood_group", title: "Blood Group", className: "all" },
            {
                data: "blood_rhesus",
                title: "Rhesus",
                className: "all text-center",
            },
            {
                data: "blood_component",
                title: "Component",
                className: "all text-center",
            },
            {
                data: null,
                title: "Action",
                className: "all text-end",
                orderable: false,
                searchable: false,
                render: (data) => {
                    return `<button class="btn btn-sm btn-soft-primary select-edit-blood-pack" type="button"
                        data-public-id="${data.public_id}"
                        data-group="${data.blood_group}"
                        data-rhesus="${data.blood_rhesus}"
                        data-component="${data.blood_component}">
                        <i class="ti ti-arrow-right"></i>
                    </button>`;
                },
            },
        ],
        order: [[0, "asc"]],
    });
}

document.addEventListener("DOMContentLoaded", function () {
    // Event listener for reloading datatable from other scripts
    window.addEventListener(listRequestTable, function () {
        if ($.fn.DataTable.isDataTable(listRequestTable)) {
            $(listRequestTable).DataTable().ajax.reload(null, false);
        }
    });
});
