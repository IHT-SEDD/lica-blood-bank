import { GlobalAdvanceDatatable, GlobalAdvanceTomselect } from "../../../app";
import { TextFormatter } from "../../../utility/ui";
import { DateTimeFormatter } from "../../../utility/ui";

// ---------- GLOBAL VARIABLES ----------
const BASE_URL = "/blood-transfusion";
const DATATABLE_URL = `${BASE_URL}/datatable`;
const TABLE = {
    request: "#list-request-table",
    bagRequest: "#list-bag-request-table",
    test: "#list-test-table",
    bloodPack: "#edit-blood-pack-available-table",
};
const SelectorBtnPrintResult = "btn-print-result";
const SelectorBtnComplete = "btn-complete-transaction";
const SelectorBtnReleaseAll = "btn-release-all-blood-pack";

// ---------- INSTANCES ----------
export let listRequestTableInstance;
export let listBagRequestTableInstance;
export let listTestTableInstance;
export let availableBloodComponentsInstance;

// ---------- HELPERS ----------
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

// ---------- BLOOD REQUEST TABLE ----------
export function DatatableRequestBlood() {
    if (isTableInitialized(TABLE.request)) return;
    const REQUESTCOLUMNS = [
        {
            data: "blood_request_at",
            render: (data) => {
                const bloodRequestAt = DateTimeFormatter.dateOnly(data);
                return `<span class="fs-6 fw-semibold">${bloodRequestAt}</span>`;
            },
        },
        {
            data: "patient.name",
            render: (data) => {
                return `<span class="fs-6 fw-semibold">${data}</span>`;
            },
        },
        {
            data: "patient.medrec",
            render: (data) => {
                return `<span class="fs-6 fw-semibold">${data}</span>`;
            },
        },
        {
            data: "lab_number",
            render: (data) => {
                return `<span class="fs-6 fw-semibold">${data}</span>`;
            },
        },
        {
            data: "order_number",
            render: (data) => {
                return `<span class="fs-6 fw-semibold">${data}</span>`;
            },
        },
        {
            data: "room.name",
            render: (data) => {
                return `<span class="fs-6 fw-semibold">${data}</span>`;
            },
        },
        {
            data: null,
            orderable: false,
            searchable: false,
            render: (row, data) => {
                const status = TextFormatter.format(row.status);
                switch (status) {
                    case "Blood Transfusion Checked In":
                        return `<span style="font-size: 20px;" class="text-success" data-bs-title="Checked In" data-bs-toggle="tooltip" data-bs-trigger="hover">
                            <i class="ti ti-user-check"></i>
                        </span>`;
                        break;
                    case "Blood Transfusion Finished":
                        return `<span style="font-size: 20px;" class="text-success" data-bs-title="Finished" data-bs-toggle="tooltip" data-bs-trigger="hover">
                            <i class="ti ti-shield-check"></i>
                        </span>`;
                        break;
                    case "Blood Transfusion Registered":
                        return `<span style="font-size: 20px;" class="text-info" data-bs-title="Registered" data-bs-toggle="tooltip" data-bs-trigger="hover">
                                <i class="ti ti-circle-dashed-check"></i>
                            </span>`;
                        break;
                    case "Blood Transfusion Deleted":
                        return `<span style="font-size: 20px;" class="text-danger" data-bs-title="Deleted" data-bs-toggle="tooltip" data-bs-trigger="hover">
                                <i class="ti ti-trash"></i>
                            </span>`;
                        break;
                    default:
                        return `<span class="fs-6 fw-semibold uppercase">-</span>`;
                        break;
                }
            },
        },
        {
            data: null,
            orderable: false,
            searchable: false,
            render: (data) => {
                const hasLabNumber =
                    data.lab_number !== null || data.lab_number !== "-";
                const isDeleted =
                    data.deleted_at !== null || data.deleted_at !== "-";

                return `<button aria-expanded="false" class="btn btn-sm btn-soft-primary datatable-action-toggle" data-bs-toggle="dropdown" data-bs-auto-close="true" type="button">
                    <i class="ti ti-dots align-middle"></i>
                    </button>
                    <ul class="dropdown-menu">
                        <li>
                            <button data-public-id="${data.public_id}" class="dropdown-item fw-medium text-primary btn-edit-blood-transfusion ${hasLabNumber ? "" : "disabled"}" data-bs-toggle="modal" data-bs-target="#edit_data_blood_transfusion_modal" type="button">
                            <i class="ti ti-pencil align-middle me-1 fs-4"></i>
                                Edit
                            </button>
                        </li>
                        <li>
                            <button data-public-id="${data.public_id}" class="dropdown-item fw-medium btn-archive-transfusion ${isDeleted ? "" : "disabled text-muted"}" type="button">
                            <i class="ti ti-archive align-middle me-1 fs-4"></i>
                                Archive
                            </button>
                        </li>
                        <li>
                            <button data-public-id="${data.public_id}" class="dropdown-item fw-medium btn-delete-blood-transfusion ${isDeleted ? "text-danger" : "disabled text-muted"}" type="button">
                            <i class="ti ti-trash align-middle me-1 fs-4"></i>
                                Delete
                            </button>
                        </li>
                    </ul>`;
            },
        },
    ];

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
        columns: REQUESTCOLUMNS,
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
        drawCallback: function () {
            const tooltipTriggerList = document.querySelectorAll(
                '[data-bs-toggle="tooltip"]',
            );

            [...tooltipTriggerList].forEach((tooltipTriggerEl) => {
                new bootstrap.Tooltip(tooltipTriggerEl);
            });
        },
    });
}

// ---------- BAG REQUEST TABLE ----------
export function DatatableListBagRequest() {
    if (isTableInitialized(TABLE.bagRequest)) return;
    const BAGREQUESTCOLUMNS = [
        {
            data: null,
            orderable: false,
            searchable: false,
            render: (_, __, row) => {
                const bloodRhesusEmpty = row.blood_rhesus === "-";
                const bloodGroupEmpty = row.blood_group === "-";
                const stockNotAvailable = row.has_available_stock === null;

                let message = null;

                if (bloodRhesusEmpty && bloodGroupEmpty) {
                    message = "Please Set Blood Group & Rhesus First!";
                } else if (bloodRhesusEmpty) {
                    message = "Please Set Blood Rhesus First!";
                } else if (bloodGroupEmpty) {
                    message = "Please Set Blood Group First!";
                } else if (stockNotAvailable) {
                    message = "Blood Stock Not Available!";
                }

                if (message) {
                    return `<span class="text-danger fw-semibold">${message}</span>`;
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
                            <select class="select-bag-number fs-6 fw-semibold" placeholder="Choose Bag Number"
                                data-id="${row.public_id}" ${isDisabled}>
                                ${optionsHtml}
                            </select>
                        `;
            },
        },
        {
            data: "blood_stock_status",
            render: function (_, data, row) {
                const status = TextFormatter.format(row.blood_stock_status);
                switch (status) {
                    case "In Use":
                        return `<span class="fs-6 fw-semibold uppercase d-flex align-items-center justify-content-start gap-1">
                                <i class="ti ti-heartbeat fs-4"></i> In Use
                            </span>`;
                        break;
                    case "Used":
                        return `<span class="fs-6 fw-semibold uppercase d-flex align-items-center justify-content-start gap-1">
                                <i class="ti ti-heart-x fs-4"></i> Not Release
                            </span>`;
                        break;
                    case "Taken Out":
                        return `<span class="fs-6 fw-semibold uppercase d-flex align-items-center justify-content-start gap-1">
                                <i class="ti ti-heart-up fs-4"></i> Released
                            </span>`;
                        break;
                    case "Hold":
                        return `<span class="fs-6 fw-semibold uppercase d-flex align-items-center justify-content-start gap-1">
                                <i class="ti ti-heart-pause fs-4"></i> Hold
                            </span>`;
                        break;
                    default:
                        return `<span class="fs-6 fw-semibold uppercase d-flex align-items-center justify-content-start gap-1">${status}</span>`;
                        break;
                }
            },
        },
        {
            data: "blood_group",
            render: function (_, __, row) {
                return `
                        <span class="text-danger fs-6 fw-semibold">${row.blood_group}</span>
                        <span class="text-danger fs-6 fw-semibold">${row.blood_rhesus}</span>
                        <span class="text-danger fs-6 fw-semibold">${row.blood_component}</span>
                    `;
            },
        },
        {
            data: null,
            title: "Expiry Date",
            orderable: false,
            searchable: false,
            render: (_, __, row) => {
                if (!row.selected_stock_id || !row.available_stocks?.length) {
                    return '<span class="text-muted">-</span>';
                }

                const selectedStock = row.available_stocks.find(
                    (stock) => stock.id === row.selected_stock_id,
                );

                if (!selectedStock?.expiry) {
                    return '<span class="text-muted">-</span>';
                }

                const expiry = new Date(selectedStock.expiry);
                const today = new Date();
                today.setHours(0, 0, 0, 0);

                const diffDays = Math.ceil(
                    (expiry - today) / (1000 * 60 * 60 * 24),
                );

                const formatted = expiry.toLocaleDateString("id-ID", {
                    day: "2-digit",
                    month: "short",
                    year: "numeric",
                });

                // Warna berdasarkan jarak expiry
                const badgeClass =
                    diffDays <= 0
                        ? "text-danger"
                        : diffDays <= 7
                          ? "text-warning"
                          : diffDays <= 30
                            ? "text-info"
                            : "text-success";

                return `<span class="${badgeClass} fw-semibold fs-6">${formatted}</span>`;
            },
        },
        {
            data: "crossmatch_result",
            render: function (_, __, row) {
                return renderCrossmatchResult(row.crossmatch_result);
            },
        },
        {
            data: null,
            orderable: false,
            searchable: false,
            render: (data) => `
                    <div class="dropdown">
                        <a class="dropdown-toggle drop-arrow-none text-muted card-drop" data-bs-toggle="dropdown" href="#">
                            <i class="ti ti-dots-vertical fs-lg"></i>
                        </a>

                        <div class="dropdown-menu dropdown-menu-end">
                            <a class="dropdown-item btn-print-result-per-blood fw-medium ${!data.crossmatch_result || data.crossmatch_result === "" ? "disabled" : ""}" id="btn-print-result-per-blood" href="#" data-public-id="${data.public_id}">
                                <i class="ti ti-printer fs-4 me-1"></i> Result
                            </a>
                        </div>
                    </div>
                `,
        },
    ];

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
        columns: BAGREQUESTCOLUMNS,
        drawCallback: () => initTomSelect(".select-bag-number"),
    });
}

// ---------- RENDER RESULT CROSSMATCH ----------
function renderCrossmatchResult(result) {
    switch (result) {
        case "Compatible":
            return `<span class="badge badge-label text-bg-success">Compatible</span>`;
        case "Incompatible":
            return `<span class="badge badge-label text-bg-danger">Incompatible</span>`;
        default:
            return `<span class="badge badge-label text-bg-secondary">No Result Yet</span>`;
    }
}

// ---------- TEST TABLE ----------
export function DatatableListTest() {
    if (isTableInitialized(TABLE.test)) return;

    let resultOptions = [];

    const TESTCOLUMNS = [
        {
            data: "bag_number",
            render: function (_, data, row) {
                return `<span class="fw-semibold uppercase" style="font-size: 11.5px;">${row.bag_number}</span>`;
            },
        },
        {
            data: "test_name",
            render: function (_, data, row) {
                return `<span class="fw-medium uppercase" style="font-size: 11.9px;">${row.test_name}</span>`;
            },
        },
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
            <select class="select-test-result fs-6 fw-semibold" data-id="${row.detail_test_public_id}" placeholder="Choose Result" ${isDisabled}>
                ${optionsHtml}
                ${options}
            </select>
        `;
            },
        },
    ];

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
        columns: TESTCOLUMNS,
        drawCallback: () => {
            initTomSelect(".select-test-result");
            // Defer so the DOM is fully rendered before checking button state
            setTimeout(() => updateDoneButtonState(), 0);
        },
    });
}

// ---------- BLOOD PACK TABLE IN MODAL ----------
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

// ---------- BLOOD COMPONENT ----------
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

// ---------- COMPLETE TEST BUTTON ----------
export function updateDoneButtonState() {
    const btn = document.getElementById("btn-test-done");
    if (!btn) return;

    // If no bag selected or already completed, disable
    if (!window.currentBagDetailPublicId) {
        btn.disabled = true;
        return;
    }

    // If this bag already has a transfusion result, keep Done disabled
    if (window.currentBagCrossmatchResult) {
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
        window.currentBagCrossmatchResult = res.crossmatch_result;

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
            $(TABLE.bagRequest)
                .DataTable()
                .ajax.reload(function (json) {
                    const allHaveCrossmatch =
                        json.data &&
                        json.data.length > 0 &&
                        json.data.every(
                            (bag) =>
                                bag.crossmatch_result &&
                                bag.crossmatch_result.toString().trim() !== "",
                        );

                    const btnComplete =
                        document.getElementById(SelectorBtnComplete);
                    if (btnComplete) {
                        btnComplete.disabled = !allHaveCrossmatch;
                    }

                    const btnReleaseAll = document.getElementById(
                        SelectorBtnReleaseAll,
                    );
                    if (btnReleaseAll) {
                        btnReleaseAll.disabled = !allHaveCrossmatch;
                    }

                    const btnPrintResult = document.getElementById(
                        SelectorBtnPrintResult,
                    );
                    if (btnPrintResult) {
                        btnPrintResult.disabled = !allHaveCrossmatch;
                    }

                    if (window.currentBagDetailPublicId && json.data) {
                        const updatedBag = json.data.find(
                            (b) =>
                                b.public_id === window.currentBagDetailPublicId,
                        );
                        if (updatedBag) {
                            window.currentBagData = updatedBag;
                            if (
                                typeof window.updateWorkflowButtonsState ===
                                "function"
                            ) {
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

// ---------- EVENTS ----------
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
