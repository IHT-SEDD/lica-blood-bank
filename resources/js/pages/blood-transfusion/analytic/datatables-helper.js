import { GlobalAdvanceDatatable, GlobalAdvanceTomselect } from "../../../app";

// ---------- Global Variable ----------
const BASE_URL = "/blood-transfusion";
const DATATABLE_URL = `${BASE_URL}/datatable`;

const TABLE = {
    request: "#list-request-table",
    bagRequest: "#list-bag-request-table",
    test: "#list-test-table",
    bloodPack: "#edit-blood-pack-available-table",
};

export let listRequestTableInstance;
export let listBagRequestTableInstance;
export let listTestTableInstance;

// ------------------------------------------------------------------
// HELPERS
// ------------------------------------------------------------------
const csrfToken = () =>
    document.querySelector('meta[name="csrf-token"]').content;

const emptyCallback = (draw) => ({
    data: [],
    recordsTotal: 0,
    recordsFiltered: 0,
    draw,
});

const patchRequest = async (url, body, successMessage) => {
    try {
        const response = await fetch(url, {
            method: "PATCH",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrfToken(),
            },
            body: JSON.stringify(body),
        });
        const res = await response.json();

        if (!response.ok) throw new Error(res.message);
        notyf.success({
            message: res.message || successMessage,
        });
        return res;
    } catch (error) {
        console.error(error);
        notyf.error({
            message: error.message || "Something went wrong!",
        });
    }
};

const initTomSelect = (selector, options = {}) => {
    document.querySelectorAll(selector).forEach((el) => {
        if (!el.tomselect) {
            new GlobalAdvanceTomselect(el, {
                valueField: "id",
                sortField: {
                    field: "text",
                    direction: "asc",
                },
                ...options,
            });
        }
    });
};

const isTableInitialized = (selector) => $.fn.DataTable.isDataTable(selector);

// ------------------------------------------------------------------
// REQUEST DATATABLE
// ------------------------------------------------------------------
export function DatatableRequestBlood() {
    if (isTableInitialized(TABLE.request)) return;
    listRequestTableInstance = new GlobalAdvanceDatatable(TABLE.request, {
        serverSide: true,
        searchDelay: 1000,
        ajax: {
            url: `${DATATABLE_URL}/blood-request`,
            type: "GET",
            dataSrc: "data",
            data: (d) => {
                d.date_range = document.querySelector(
                    ".blood-transfusion-date-filter",
                )?.value;
            },
        },
        columns: [
            { data: "blood_request_at" },
            { data: "patient.name" },
            { data: "patient.medrec" },
            { data: "lab_number" },
            { data: "order_number" },
            { data: "room.name" },
            {
                data: "is_cito",
                orderable: false,
                searchable: false,
                render: (data) =>
                    data
                        ? `<i data-lucide="triangle-alert" class="fs-4 text-warning fill-warning"></i>`
                        : "-",
            },
            {
                data: null,
                orderable: false,
                searchable: false,
                render: (data) => `
                    <div class="dropdown">
                        <button class="btn btn-sm btn-soft-primary"
                            data-bs-toggle="dropdown">
                            <i class="ti ti-dots"></i>
                        </button>

                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item btn-edit-blood-transfusion"
                                    data-public-id="${data.public_id}"
                                    data-bs-toggle="modal"
                                    data-bs-target="#edit_data_blood_transfusion_modal">
                                    Edit
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item text-danger btn-delete-blood-transfusion"
                                    data-public-id="${data.public_id}">
                                    Delete
                                </a>
                            </li>
                        </ul>
                    </div>
                `,
            },
        ],
    });
}

// ------------------------------------------------------------------
// BAG REQUEST DATATABLE
// ------------------------------------------------------------------
export function DatatableListBagRequest() {
    if (isTableInitialized(TABLE.bagRequest)) return;
    listBagRequestTableInstance = new GlobalAdvanceDatatable(TABLE.bagRequest, {
        serverSide: true,
        removeSearch: true,
        removePageInfo: true,
        removePagination: true,
        ajax: (data, callback) => {
            if (!window.currentTransfusionPublicId) {
                return callback(emptyCallback(data.draw));
            }
            $.get(
                `${DATATABLE_URL}/${window.currentTransfusionPublicId}/bag-requests`,
                data,
            )
                .done(callback)
                .fail(() => callback(emptyCallback(data.draw)));
        },
        columns: [
            {
                data: null,
                orderable: false,
                searchable: false,
                render: (_, __, row) => {
                    if (!row.has_available_stock) {
                        return `<span class="text-danger fw-semibold">Not Available Stock</span>`;
                    }
                    const options = row.available_stocks
                        .map(
                            (stock) => `
                                    <option value="${stock.id}"
                                        ${row.selected_stock_id == stock.id ? "selected" : ""}>
                                        ${stock.text}
                                    </option>
                                `,
                        )
                        .join("");
                    return `
                            <select class="select-bag-number"
                                data-id="${row.public_id}">
                                <option value="">Choose Bag Number</option>
                                ${options}
                            </select>
                        `;
                },
            },
            { data: "blood_group" },
            { data: "blood_rhesus" },
            { data: "blood_component" },
        ],
        drawCallback: () => initTomSelect(".select-bag-number"),
    });
}

// ------------------------------------------------------------------
// TEST DATATABLE
// ------------------------------------------------------------------
export function DatatableListTest() {
    if (isTableInitialized(TABLE.test)) return;

    let resultOptions = [];
    listTestTableInstance = new GlobalAdvanceDatatable(TABLE.test, {
        serverSide: true,
        removeSearch: true,
        removePageInfo: true,
        removePagination: true,
        ajax: (data, callback) => {
            if (!window.currentTransfusionPublicId) {
                return callback(emptyCallback(data.draw));
            }
            $.get(
                `${BASE_URL}/${window.currentTransfusionPublicId}/tests`,
                data,
            )
                .done((res) => {
                    resultOptions = res.result_options || [];
                    callback(res);
                })
                .fail(() => callback(emptyCallback(data.draw)));
        },
        columns: [
            { data: "test_name" },
            {
                data: null,
                render: (_, __, row) => {
                    if (!row.detail_test_public_id) return "-";
                    const options = resultOptions
                        .map(
                            (opt) => `
                                <option value="${opt.id}"
                                    ${row.result_value === opt.id ? "selected" : ""}>
                                    ${opt.text}
                                </option>
                            `,
                        )
                        .join("");
                    return `
                        <select class="select-test-result"
                            data-id="${row.detail_test_public_id}">
                            <option value="">Choose Result</option>
                            ${options}
                        </select>
                    `;
                },
            },
            {
                data: "verified",
                className: "text-center",
                render: (data, _, row) => `
                    <input type="checkbox"
                        class="checkbox-update"
                        data-field="verified"
                        data-id="${row.detail_test_public_id}"
                        ${data ? "checked" : ""}>
                `,
            },
            {
                data: "validated",
                className: "text-center",
                render: (data, _, row) => `
                    <input type="checkbox"
                        class="checkbox-update"
                        data-field="validated"
                        data-id="${row.detail_test_public_id}"
                        ${data ? "checked" : ""}>
                `,
            },
        ],
        drawCallback: () => initTomSelect(".select-test-result"),
    });
}

// ------------------------------------------------------------------
// BLOOD PACK MODAL DATATABLE
// ------------------------------------------------------------------
export function DatatableBloodPackModal() {
    if (!document.querySelector(TABLE.bloodPack)) return;

    if (isTableInitialized(TABLE.bloodPack)) {
        $(TABLE.bloodPack).DataTable().destroy();
    }

    new GlobalAdvanceDatatable(TABLE.bloodPack, {
        serverSide: true,
        removePageInfo: true,
        removePagination: true,
        removeSearch: true,
        searchDelay: 800,
        scrollY: "250px",
        scrollCollapse: true,
        ajax: {
            url: `${DATATABLE_URL}/blood-pack`,
            type: "GET",
            dataSrc: "data",
            data: {
                blood_rhesus:
                    document.querySelector(
                        `[data-patient-detail="blood_rhesus"]`,
                    )?.innerText || null,

                blood_group:
                    document.querySelector(
                        `[data-patient-detail="blood_group"]`,
                    )?.innerText || null,
            },
        },
        columns: [
            {
                data: "blood_group",
                title: "Blood Group",
                className: "all",
            },
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
                render: (data) => `
                    <button
                        class="btn btn-sm btn-soft-primary select-edit-blood-pack"
                        type="button"
                        data-public-id="${data.public_id}"
                        data-group="${data.blood_group}"
                        data-rhesus="${data.blood_rhesus}"
                        data-component="${data.blood_component}"
                    >
                        <i class="ti ti-arrow-right"></i>
                    </button>
                `,
            },
        ],
        order: [[0, "asc"]],
    });
}

// ------------------------------------------------------------------
// EVENTS
// ------------------------------------------------------------------
document.addEventListener("change", async function (e) {
    // Update Bag Number
    if (e.target.matches(".select-bag-number")) {
        await patchRequest(
            `${BASE_URL}/detail/${e.target.dataset.id}/update-stock`,
            {
                blood_stock_id: e.target.value,
            },
            "Bag number updated!",
        );
    }
    // Update Test Result
    if (e.target.matches(".select-test-result")) {
        await patchRequest(
            `${BASE_URL}/test/${e.target.dataset.id}/update-result`,
            {
                result: e.target.value,
            },
            "Result updated!",
        );
    }
    // Update Verified / Validated
    if (e.target.matches(".checkbox-update")) {
        await patchRequest(
            `${BASE_URL}/test/${e.target.dataset.id}/update-verified-validated`,
            {
                field: e.target.dataset.field,
                value: e.target.checked,
            },
            "Status updated!",
        );
    }
});
