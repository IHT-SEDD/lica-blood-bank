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
export let availableBloodComponentsInstance;

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
        rowSelect: true,
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
                                <a class="dropdown-item btn-edit-blood-transfusion ${!data.lab_number || data.lab_number === "-" ? "disabled" : ""}"
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
        rowSelect: true,
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
                    const isDisabled =
                        !window.currentTransfusionLabNumber ||
                        window.currentTransfusionLabNumber === "-"
                            ? "disabled"
                            : "";
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

                    let optionsHtml =
                        '<option value="" selected disabled>Choose Bag Number</option>' +
                        options;
                    return `
                            <select class="select-bag-number" placeholder="Choose Bag Number"
                                data-id="${row.public_id}" ${isDisabled}>
                                ${optionsHtml}
                            </select>
                        `;
                },
            },
            {
                data: "blood_group",
                render: function (_, __, row) {
                    return `
                        <span class="text-danger fw-semibold">${row.blood_group}</span>
                        <span class="text-danger fw-semibold">${row.blood_rhesus}</span>
                        <span class="text-danger fw-semibold">${row.blood_component}</span>
                    `;
                },
            },
            {
                data: "crossmatch_result",
                render: function (_, __, row) {
                    return renderTransfusionResult(row.crossmatch_result);
                },
            }
        ],
        drawCallback: () => initTomSelect(".select-bag-number"),
    });
}

function renderTransfusionResult(result) {
    switch (result) {
        case "Compatible":
            return `<span class="badge badge-pill bg-success">Compatible</span>`;
        case "Incompatible":
            return `<span class="badge badge-pill bg-danger">Incompatible</span>`;
        default:
            return `<span class="badge badge-pill bg-secondary">Not Result Yet</span>`;
    }
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
            if (
                !window.currentTransfusionPublicId ||
                !window.currentBagDetailPublicId
            ) {
                return callback(emptyCallback(data.draw));
            }

            data.detail_id = window.currentBagDetailPublicId;

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
            { data: "bag_number" },
            { data: "test_name" },
            {
                data: "result_value",
                render: (_, __, row) => {
                    if (!row.detail_test_public_id) return "-";

                    const isDisabled =
                        !window.currentTransfusionLabNumber ||
                        window.currentTransfusionLabNumber === "-"
                            ? "disabled"
                            : "";
                    // 1. BUAT PLACEHOLDER MANUAL: Jika result_value null/kosong, berikan atribut 'selected'
                    const isPlaceholderSelected =
                        row.result_value === null || row.result_value === ""
                            ? "selected"
                            : "";
                    let optionsHtml = `<option value="" disabled ${isPlaceholderSelected}>Choose Result</option>`;

                    const options = resultOptions
                        .map((opt) => {
                            const isSelected =
                                String(opt.id) === String(row.result_value)
                                    ? "selected"
                                    : "";
                            return `
                <option value="${opt.id}" ${isSelected}>
                    ${opt.text}
                </option>
            `;
                        })
                        .join("");

                    // 2. MASUKKAN optionsHtml DI ATAS options
                    return `
            <select class="select-test-result" data-id="${row.detail_test_public_id}" placeholder="Choose Result" ${isDisabled}>
                ${optionsHtml}
                ${options}
            </select>
        `;
                },
            },
        ],
        drawCallback: () => {
            initTomSelect(".select-test-result");
            // Defer so the DOM is fully rendered before checking button state
            setTimeout(() => updateDoneButtonState(), 0);
        },
    });
}

// ------------------------------------------------------------------
// BLOOD COMPONENTS MODAL DATATABLE
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
                data: "text",
                title: "Component",
                className: "all text-start",
                width: "100%",
                render: (data, type, row) => {
                    return `${row.text} (${row.id})`;
                },
            },
            {
                data: null,
                title: "Action",
                className: "all text-end",
                orderable: false,
                searchable: false,
                render: (data) => `
                    <button
                        class="btn btn-sm btn-soft-success select-edit-blood-component"
                        type="button"
                        data-public-id="${data.public_id}"
                        data-id="${data.id}"
                        data-text="${data.text}">
                        <i class="ti ti-plus"></i>
                    </button>
                `,
            },
        ],
        order: [[0, "asc"]],
    });
}

// ------------------------------------------------------------------
// BLOOD COMPONENTS DATATABLE
// ------------------------------------------------------------------
export function initAvailableBloodComponentsTable() {
    const tableSelector = "#available-blood-components-table";

    if ($.fn.DataTable.isDataTable(tableSelector)) {
        return;
    }

    availableBloodComponentsInstance = new GlobalAdvanceDatatable(
        tableSelector,
        {
            serverSide: true,
            removePagination: true,
            removePageInfo: true,
            removeSearch: true,
            useHideColumn: false,
            scrollY: "350px",
            scrollCollapse: true,
            ajax: {
                url: `${DATATABLE_URL}/blood-pack`,
                type: "GET",
                data: function (d) {
                    d.blood_group = $("#blood_group_filter").val();
                    d.blood_rhesus = $("#blood_rhesus_filter").val();
                },
                dataSrc: "data",
            },
            columns: [
                {
                    data: "text",
                    title: "Component",
                    className: "all text-start",
                    width: "100%",
                    render: (data, type, row) => {
                        return `${row.text} (${row.id})`;
                    },
                },
                {
                    data: null,
                    title: "Action",
                    width: "100%",
                    orderable: false,
                    searchable: false,
                    className: "all text-start",
                    render: (data) => {
                        return `<button class="btn btn-sm btn-soft-success select-blood-component" type="button"
                        data-id="${data.id}"
                        data-text="${data.text}">
                        <i class="ti ti-plus"></i>
                    </button>`;
                    },
                },
            ],
            order: [[0, "asc"]],
        },
    );
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

        // Cari checkbox yang berada di baris yang sama berdasarkan data-id
        const targetCheckbox = $(
            `.checkbox-update[data-id="${e.target.dataset.id}"]`,
        );

        if (e.target.value === "" || e.target.value === null) {
            targetCheckbox.prop("checked", false);
            targetCheckbox.prop("disabled", true);
            targetCheckbox.css("cursor", "not-allowed");
        } else {
            targetCheckbox.prop("checked", false);
            targetCheckbox.prop("disabled", false);
            targetCheckbox.css("cursor", "pointer");
        }

        // Update Done button state after result change
        updateDoneButtonState();
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

        if (e.target.dataset.field === "verified") {
            const targetCheckbox = $(
                `.checkbox-update[data-id="${e.target.dataset.id}"][data-field="validated"]`,
            );

            targetCheckbox.prop("disabled", !e.target.checked);
            targetCheckbox.css("cursor", "pointer");
        }

        // Update Done button state after checkbox change
        updateDoneButtonState();
    }
});

// ------------------------------------------------------------------
// DONE BUTTON (Complete Test)
// ------------------------------------------------------------------
export function updateDoneButtonState() {
    const btn = document.getElementById("btn-test-done");
    if (!btn) return;

    // If no bag selected or already completed, disable
    if (!window.currentBagDetailPublicId) {
        btn.disabled = true;
        return;
    }

    // If this bag already has a transfusion result, keep Done disabled
    if (window.currentBagTransfusionResult) {
        btn.disabled = true;
        return;
    }

    // Check all visible test rows in the table
    const table = document.querySelector(TABLE.test);
    if (!table) {
        btn.disabled = true;
        return;
    }

    const rows = table.querySelectorAll("tbody tr");
    if (rows.length === 0) {
        btn.disabled = true;
        return;
    }

    let allComplete = true;

    rows.forEach((row) => {
        // Check result select — must have a non-empty value
        const resultSelect = row.querySelector(".select-test-result");
        if (!resultSelect || !resultSelect.value) {
            allComplete = false;
            return;
        }

        // // Check verified checkbox
        // const verifiedCb = row.querySelector(
        //     '.checkbox-update[data-field="verified"]',
        // );
        // if (!verifiedCb || !verifiedCb.checked) {
        //     allComplete = false;
        //     return;
        // }

        // // Check validated checkbox
        // const validatedCb = row.querySelector(
        //     '.checkbox-update[data-field="validated"]',
        // );
        // if (!validatedCb || !validatedCb.checked) {
        //     allComplete = false;
        //     return;
        // }
    });

    btn.disabled = !allComplete;
}

export async function completeTest() {
    const detailPublicId = window.currentBagDetailPublicId;
    if (!detailPublicId) {
        notyf.error({ message: "Please select a bag first." });
        return;
    }

    const btn = document.getElementById("btn-test-done");
    if (!btn) return;

    // Prevent multiple clicks
    const originalText = btn.innerHTML;
    btn.innerHTML =
        '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...';
    btn.disabled = true;

    try {
        const response = await fetch(
            `${BASE_URL}/test/${detailPublicId}/complete`,
            {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken(),
                },
            },
        );

        const res = await response.json();

        if (!response.ok) {
            throw new Error(res.message);
        }

        notyf.success({ message: res.message });

        // Mark bag as completed so Done button stays disabled
        window.currentBagTransfusionResult = res.transfusion_result;

        // Disable Done button after success
        btn.disabled = true;
        btn.innerHTML = originalText;

        // Reload test table
        if (listTestTableInstance && $.fn.DataTable.isDataTable(TABLE.test)) {
            $(TABLE.test).DataTable().ajax.reload(null, false);
        }

        // Reload bag request table to reflect updated transfusion_result badge
        if (
            listBagRequestTableInstance &&
            $.fn.DataTable.isDataTable(TABLE.bagRequest)
        ) {
            $(TABLE.bagRequest).DataTable().ajax.reload(function(json) {
                if (window.currentBagDetailPublicId && json.data) {
                    const updatedBag = json.data.find(b => b.public_id === window.currentBagDetailPublicId);
                    if (updatedBag) {
                        window.currentBagData = updatedBag;
                        if (typeof window.updateWorkflowButtonsState === "function") {
                            window.updateWorkflowButtonsState();
                        }
                    }
                }
            }, false);
        }
    } catch (error) {
        console.error(error);
        notyf.error({ message: error.message || "Failed to complete test." });
        btn.innerHTML = originalText;
        btn.disabled = false;
    }
}
