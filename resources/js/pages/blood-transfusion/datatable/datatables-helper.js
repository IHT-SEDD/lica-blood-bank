import { GlobalAdvanceDatatable, GlobalAdvanceTomselect } from "../../../app";
import {
    BooleanStatus,
    CitoStatus,
    TransactionOrderStatus,
    TransfusionBloodStatus,
} from "../../../utility/config/status-config";
import { GlobalAdvanceYajraDatatable } from "../../../utility/datatable/datatables";
import { TextFormatter } from "../../../utility/ui";
import { DateTimeFormatter } from "../../../utility/ui";
import {
    evaluateBagListState,
    applyBagListButtonState,
    updateWorkflowButtonsState,
    updateDoneButtonState,
} from "../helper/button-state";

// ---------- GLOBAL VARIABLES ----------
const BASE_URL = "/blood-transfusion";
const DATATABLE_URL = `${BASE_URL}/datatable`;
const TABLE = {
    request: "#list-request-table",
    bagRequest: "#list-bag-request-table",
    test: "#list-test-table",
    bloodPack: "#edit-blood-pack-available-table",
    historyTest: "#list-history-test-table",
};

// ---------- INSTANCES ----------
export let listRequestTableInstance;
export let listBagRequestTableInstance;
export let listTestTableInstance;
export let availableBloodComponentsInstance;
export let listHistoryTestTableInstance;

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

// ---------- RENDER BAG NUMBER COLUMN ----------
function renderBagNumber(row) {
    const rowData = row.row_data;
    const bloodRhesusEmpty = rowData.blood_rhesus === "-";
    const bloodGroupEmpty = rowData.blood_group === "-";
    const stockNotAvailable = rowData.has_available_stock === null;

    // ---------- Validasi data pasien ----------
    let message = null;
    if (bloodRhesusEmpty && bloodGroupEmpty) {
        message = "Pasien belum mempunyai golongan darah & rhesus!";
    } else if (bloodRhesusEmpty) {
        message = "Pasien belum mempunyai rhesus!";
    } else if (bloodGroupEmpty) {
        message = "Pasien belum mempunyai golongan darah!";
    } else if (stockNotAvailable) {
        message = "Tidak ada labu darah yang tersedia untuk pasien ini!";
    }
    if (message) {
        return `<span class="text-danger fw-semibold">${message}</span>`;
    }

    // ---------- Jika sudah ada bag number → tampilkan teks langsung ----------
    if (rowData.selected_bag_number) {
        return `<span class="fs-6 fw-semibold text-dark">${rowData.selected_bag_number}</span>`;
    }

    // ---------- Kondisi disabled ----------
    const noLabNumber =
        !window.currentTransfusionLabNumber ||
        window.currentTransfusionLabNumber === "-";
    const isCrossmatchResult =
        row.crossmatch_result !== "" && row.crossmatch_result;
    const isReleased = rowData.blood_release_status === true;
    const isDisabled =
        noLabNumber || isCrossmatchResult || isReleased ? "disabled" : "";

    return `<div class="bag_number_input_wrapper">
        <div class="input-group">
            <input
                autocomplete="off"
                class="form-control form-control-sm"
                id="bag_number"
                name="bag_number"
                type="text"
                data-id="${row.public_id}"
                data-available-stocks='${JSON.stringify(rowData.available_stocks ?? [])}'
                ${isDisabled}
                placeholder="Scan labu"
            />
            <button
                class="btn btn-sm btn-soft-dark"
                type="submit"
                id="update_bag_blood_btn"
                ${isDisabled}>
                <i class="ti ti-plus fs-6"></i>
            </button>
        </div>
        <div class="invalid-feedback fw-semibold" style="display:none;"></div>
    </div>`;
}
// ---------- VALIDASI BAG NUMBER ----------
async function validateBagNumber(inputEl) {
    const bagNumber = inputEl.value.trim();
    const wrapper = inputEl.closest(".bag_number_input_wrapper");
    const feedback = wrapper?.querySelector(".invalid-feedback");
    const availableStocks = JSON.parse(inputEl.dataset.availableStocks || "[]");

    // ---------- Reset state ----------
    inputEl.classList.remove("is-invalid");
    if (feedback) feedback.style.display = "none";
    if (!bagNumber) return;

    // ---------- Cek apakah bag number ada di available stocks ----------
    const matched = availableStocks.find(
        (stock) =>
            String(stock.id) === bagNumber || stock.text?.includes(bagNumber),
    );
    if (!matched) {
        inputEl.classList.add("is-invalid");
        if (feedback) {
            feedback.textContent = "Labu darah tidak tersedia";
            feedback.style.display = "block";
        }
        return;
    }

    // ---------- Jalankan patch jika ditemukan ----------
    const res = await patchRequest(
        `${BASE_URL}/detail/${inputEl.dataset.id}/update-stock`,
        { blood_stock_id: matched.id },
        "Bag number sukses diperbaharui!",
    );

    // ---------- Jika sukses, ganti input group menjadi teks biasa ----------
    if (res) {
        const selectedBagNumber = res?.selected_bag_number ?? bagNumber;
        const inputGroup = wrapper?.querySelector(".input-group");
        if (inputGroup) {
            inputGroup.outerHTML = `<span class="fs-6 fw-semibold text-dark">${selectedBagNumber}</span>`;
        }
        if (feedback) feedback.style.display = "none";
    }

    // ---------- Reload tabel setelah update ----------
    if (
        listBagRequestTableInstance &&
        $.fn.DataTable.isDataTable(TABLE.bagRequest)
    ) {
        $(TABLE.bagRequest).DataTable().ajax.reload(null, false);
    }
}
// ---------- OPEN MODAL UPDATE BLOOD ----------
async function openUpdateBloodModal(btn) {
    const inputEl = btn.closest(".input-group")?.querySelector("#bag_number");
    if (!inputEl) return;

    const bagNumber = inputEl.value.trim();
    const availableStocks = JSON.parse(inputEl.dataset.availableStocks || "[]");
    const feedback = inputEl
        .closest(".bag_number_input_wrapper")
        ?.querySelector(".invalid-feedback");

    // ---------- Reset state ----------
    inputEl.classList.remove("is-invalid");
    if (feedback) feedback.style.display = "none";
    if (!bagNumber) return;

    // ---------- Cek apakah bag number ada di available stocks ----------
    const matched = availableStocks.find(
        (stock) =>
            String(stock.id) === bagNumber || stock.text?.includes(bagNumber),
    );
    if (!matched) {
        inputEl.classList.add("is-invalid");
        if (feedback) {
            feedback.textContent = "Labu darah tidak tersedia";
            feedback.style.display = "block";
        }
        return;
    }

    // ---------- Cek rekomendasi: stok lain yang expiry lebih dekat ----------
    // const matchedExpiry = matched.expiry ? new Date(matched.expiry) : null;
    // const recommendation = matchedExpiry
    //     ? (availableStocks
    //           .filter((stock) => {
    //               if (
    //                   String(stock.id) === bagNumber ||
    //                   stock.text?.includes(bagNumber)
    //               )
    //                   return false;
    //               if (!stock.expiry) return false;
    //               return new Date(stock.expiry) < matchedExpiry;
    //           })
    //           .sort((a, b) => new Date(a.expiry) - new Date(b.expiry))
    //           .at(0) ?? null)
    //     : null;
    const recommendation = null;

    // ---------- Simpan state ke modal ----------
    const modal = document.getElementById("update_blood_modal");
    modal._inputEl = inputEl;
    modal._matched = matched;
    modal._recommendation = recommendation;

    // ---------- Render konten modal ----------
    renderUpdateBloodModal(matched, recommendation);
    bootstrap.Modal.getOrCreateInstance(modal).show();
}
// ---------- RENDER KONTEN MODAL UPDATE BLOOD ----------
function renderUpdateBloodModal(matched, recommendation) {
    const elBloodSummary = document.getElementById("blood_summary");
    if (elBloodSummary) elBloodSummary.textContent = matched.text ?? matched.id;

    if (recommendation) {
        const elBloodSuggestion = document.getElementById("blood_suggestion");
        const elSuggestionExpiry = document.getElementById(
            "blood_sugestion_expiry_date",
        );
        const formattedExpiry = new Date(
            recommendation.expiry,
        ).toLocaleDateString("id-ID", {
            day: "2-digit",
            month: "long",
            year: "numeric",
        });

        if (elBloodSuggestion)
            elBloodSuggestion.textContent =
                recommendation.text ?? recommendation.id;
        if (elSuggestionExpiry)
            elSuggestionExpiry.textContent = formattedExpiry;
        showSuggestionView();
    } else {
        showSummaryView();
    }
}
// ---------- TAMPILKAN SUGGESTION VIEW ----------
function showSuggestionView() {
    document.getElementById("blood_suggestion_wrapper").style.display = "block";
    document.getElementById("blood_summary_wrapper").style.display = "none";
}
// ---------- TAMPILKAN SUMMARY VIEW ----------
function showSummaryView() {
    document.getElementById("blood_suggestion_wrapper").style.display = "none";
    document.getElementById("blood_summary_wrapper").style.display = "block";
}
// ---------- KONFIRMASI GUNAKAN LABU YANG DIINPUT ----------
async function handleConfirmUseBlood() {
    const modal = document.getElementById("update_blood_modal");
    const inputEl = modal._inputEl;
    const matched = modal._matched;
    if (!inputEl || !matched) return;

    bootstrap.Modal.getInstance(modal)?.hide();
    await validateBagNumber(inputEl, matched);
}
// ---------- KONFIRMASI GUNAKAN LABU REKOMENDASI ----------
async function handleConfirmUseRecommendation() {
    const modal = document.getElementById("update_blood_modal");
    const inputEl = modal._inputEl;
    const recommendation = modal._recommendation;
    if (!inputEl || !recommendation) return;

    inputEl.value = recommendation.id;
    bootstrap.Modal.getInstance(modal)?.hide();
    await validateBagNumber(inputEl, recommendation);
}

// ---------- BLOOD REQUEST TABLE ----------
export function DatatableRequestBlood() {
    if (isTableInitialized(TABLE.request)) return;
    const REQUESTCOLUMNS = [
        {
            data: "blood_request_at",
            title: "Tgl. Permintaan",
            render: (data) => {
                const bloodRequestAt = DateTimeFormatter.shortDateTime(data);
                return `<span class="fs-6 fw-semibold">${bloodRequestAt}</span>`;
            },
        },
        {
            data: "patient.name",
            title: "Nama",
            render: (data) => {
                return `<span class="fs-6 fw-semibold">${data}</span>`;
            },
        },
        {
            data: "patient.medrec",
            title: "Medrec",
            render: (data) => {
                return `<span class="fs-6 fw-semibold">${data ?? ""}</span>`;
            },
        },
        {
            data: "lab_number",
            title: "No. BDRS",
            render: (data) => {
                return `<span class="fs-6 fw-semibold">${data ?? ""}</span>`;
            },
        },
        {
            data: "order_number",
            title: "No. Order",
            render: (data) => {
                return `<span class="fs-6 fw-semibold">${data ?? ""}</span>`;
            },
        },
        {
            data: "room.name",
            title: "Ruangan",
            render: (data) => {
                return `<span class="fs-6 fw-semibold">${data}</span>`;
            },
        },
        {
            data: null,
            defaultContent: "",
            orderable: false,
            searchable: false,
            render: (row, data) => {
                const status = TextFormatter.format(row.status);
                return TransactionOrderStatus(status);
            },
        },
        {
            data: null,
            defaultContent: "",
            orderable: false,
            searchable: false,
            render: (row, data) => {
                const cito = row.is_cito;
                return CitoStatus(cito);
            },
        },
        {
            data: null,
            orderable: false,
            searchable: false,
            title: "Aksi",
            render: (data, type, row) => {
                const hasLabNumber = row.lab_number !== null;
                const isDeleted = row.deleted_at !== null;
                const isComplete = row.finish_at !== null;
                const canDelete = !isDeleted && !isComplete;
                return `<button aria-expanded="false" class="btn btn-sm btn-soft-primary datatable-action-toggle" data-bs-toggle="dropdown" data-bs-auto-close="true" type="button">
                    <i class="ti ti-dots align-middle"></i>
                    </button>
                    <ul class="dropdown-menu">
                        <li>
                            <button data-public-id="${data.public_id}" class="dropdown-item fw-medium text-primary btn-edit-blood-transfusion ${hasLabNumber ? "" : "disabled text-muted"}" type="button">
                            <i class="ti ti-pencil align-middle me-1 fs-4"></i>
                                Edit
                            </button>
                        </li>
                        <li>
                            <button id="archive-data-${data.public_id}" data-archive-id="${data.public_id}" class="dropdown-item fw-medium btn-archive-blood-transfusion ${isDeleted ? "disabled text-muted" : ""}" type="button">
                            <i class="ti ti-archive align-middle me-1 fs-4"></i>
                                Arsipkan
                            </button>
                        </li>
                        <li>
                            <button id="delete-data-${data.public_id}" data-delete-id="${data.public_id}" class="dropdown-item fw-medium btn-delete-blood-transfusion
                            ${canDelete ? "text-danger" : "disabled text-muted"}" type="button">
                            <i class="ti ti-trash align-middle me-1 fs-4"></i>
                                Hapus
                            </button>
                        </li>
                    </ul>`;
            },
        },
    ];

    listRequestTableInstance = new GlobalAdvanceYajraDatatable(TABLE.request, {
        searchDelay: 1000,
        rowSelect: true,
        ajax: {
            url: `${DATATABLE_URL}/blood-request`,
            dataSrc: "data",
            data: (d) => {
                d.date_range = document.querySelector(
                    ".blood-transfusion-date-filter",
                )?.value;
                d.status = document.querySelector(
                    "#filter-status-transaction",
                )?.value;
            },
        },
        columns: REQUESTCOLUMNS,
        useHideColumn: true,
        createdRow: function (row, data) {
            if (data.is_cito === "1" || Number(data.is_cito) === 1) {
                row.classList.add("row-cito");
            }
        },
        columnDefs: [
            { targets: -1, responsivePriority: 1 },
            { targets: 0, responsivePriority: 2 },
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
            title: "No. Labu",
            orderable: false,
            searchable: false,
            render: (_, __, row) => renderBagNumber(row),
        },
        {
            data: null,
            title: "Status",
            render: function (_, data, row) {
                const rowData = row.row_data;
                return TransfusionBloodStatus(row.bag_status);
            },
        },
        {
            data: null,
            title: "Detail",
            render: function (_, __, row) {
                const rowData = row.row_data;
                return `
                        <span class="text-danger fs-6 fw-semibold">${rowData.blood_pack_label}</span>
                    `;
            },
        },
        {
            data: null,
            orderable: false,
            title: "Tgl. Expire",
            searchable: false,
            render: (_, __, row) => {
                const rowData = row.row_data;
                if (
                    !rowData.selected_stock_id ||
                    !rowData.available_stocks?.length
                ) {
                    return '<span class="text-muted">-</span>';
                }

                const selectedStock = rowData.available_stocks.find(
                    (stock) => stock.id === rowData.selected_stock_id,
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
            title: "Hasil",
            render: function (_, __, row) {
                return renderCrossmatchResult(row.crossmatch_result);
            },
        },
        {
            data: null,
            title: "Aksi",
            orderable: false,
            searchable: false,
            render: (data) => {
                return `<button aria-expanded="false" class="btn btn-sm btn-soft-primary datatable-action-toggle" data-bs-toggle="dropdown" data-bs-auto-close="true" type="button">
                        <i class="ti ti-dots align-middle"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <button data-public-id="${data.public_id}" class="dropdown-item btn-print-result-per-blood fw-medium ${!data.crossmatch_result || data.crossmatch_result === "" ? "disabled text-muted" : ""}" type="button">
                                <i class="ti ti-printer fs-4 me-1"></i> Hasil
                            </button>
                        </li>
                        <li>
                            <button data-public-id="${data.public_id}" class="dropdown-item btn-print-barcode-per-blood fw-medium ${!data.crossmatch_result || data.crossmatch_result === "" ? "disabled text-muted" : ""}" type="button">
                                <i class="ti ti-printer fs-4 me-1"></i> Barcode
                            </button>
                        </li>
                        <li>
                            <button id="btn-delete-per-blood" data-public-id="${data.public_id}" class="dropdown-item fw-medium btn-delete-per-blood ${data.blood_release_status === 1 ? "disabled text-muted" : "text-danger"}" type="button">
                            <i class="ti ti-trash align-middle me-1 fs-4"></i>
                                Hapus
                            </button>
                        </li>
                    </ul>`;
            },
        },
    ];

    listBagRequestTableInstance = new GlobalAdvanceYajraDatatable(
        TABLE.bagRequest,
        {
            removeSearch: true,
            removePageInfo: true,
            removePageLength: true,
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
            columnDefs: [
                {
                    targets: 0,
                    width: "200px",
                },
            ],
            // drawCallback: () => initTomSelect(".select-bag-number"),
        },
    );
}

// ---------- RENDER RESULT CROSSMATCH ----------
function renderCrossmatchResult(result) {
    switch (result) {
        case "Compatible":
            return `<span class="badge badge-label text-bg-success">Compatible</span>`;
        case "Incompatible":
            return `<span class="badge badge-label text-bg-danger">Incompatible</span>`;
        default:
            return `<span class="badge badge-label text-bg-info">Belum Dilakukan</span>`;
    }
}

// ---------- TEST TABLE ----------
export function DatatableListTest() {
    if (isTableInitialized(TABLE.test)) return;

    let resultOptions = [];

    const TESTCOLUMNS = [
        {
            data: "bag_number",
            title: "No. Labu",
            render: function (_, data, row) {
                return `<span class="fw-semibold uppercase" style="font-size: 11.5px;">${row.bag_number}</span>`;
            },
        },
        {
            data: "test_name",
            title: "Test",
            render: function (_, data, row) {
                return `<span class="fw-medium uppercase" style="font-size: 11.9px;">${row.test_name}</span>`;
            },
        },
        {
            data: "result_value",
            title: "Hasil",
            render: (_, __, row) => {
                if (!row.detail_test_public_id) return "-";

                const stockStatus =
                    window.currentBagData?.bag_status ?? null;
                const isStockFinalized = ["taken_out", "used"].includes(
                    stockStatus,
                );
                const isDisabled =
                    !window.currentTransfusionLabNumber ||
                    window.currentTransfusionLabNumber === "-" ||
                    row.bag_released === 1 ||
                    isStockFinalized
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
            <select class="select-test-result fs-6 fw-semibold" data-id="${row.detail_test_public_id}" data-test-name="${row.test_name}" data-component="${row.component}" placeholder="Choose Result" ${isDisabled}>
                ${optionsHtml}
                ${options}
            </select>
        `;
            },
        },
    ];

    listTestTableInstance = new GlobalAdvanceYajraDatatable(TABLE.test, {
        removeSearch: true,
        removePageInfo: true,
        removePagination: true,
        removePageLength: true,
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

    new GlobalAdvanceYajraDatatable(TABLE.bloodPack, {
        removePageInfo: true,
        removePagination: true,
        removePageLength: true,
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

// ---------- HISTORY TEST TABLE ----------
export function DatatableHistoryTestTable() {
    if (isTableInitialized(TABLE.historyTest)) return;
    const HISTORYTESTCOLUMNS = [
        {
            data: "blood_request_at",
            title: "Tgl. Permintaan",
            render: (data) => {
                const bloodRequestAt = DateTimeFormatter.shortDateTime(data);
                return `<span class="fs-6 fw-semibold">${bloodRequestAt}</span>`;
            },
        },
        {
            data: "lab_number",
            title: "No. BDRS",
            render: (data) => {
                return `<span class="fs-6 fw-semibold">${data}</span>`;
            },
        },
        {
            data: "order_number",
            title: "No. Order",
            render: (data) => {
                return `<span class="fs-6 fw-semibold">${data ?? "-"}</span>`;
            },
        },
        {
            data: "bag_number",
            title: "No. Labu",
            render: (data) => {
                return `<span class="fs-6 fw-semibold">${data ?? "-"}</span>`;
            },
        },
        {
            data: "crossmatch_result",
            title: "Hasil",
            render: (data) => {
                return renderCrossmatchResult(data);
            },
        },
        {
            data: "blood_release_status",
            title: "Status",
            render: (data) => {
                const isReleased = data === 1;
                if (isReleased) {
                    return `<span class="badge badge-label fw-semibold badge-soft-primary">
                        <i class="ti ti-heart-up align-middle me-2 fs-4"></i>
                        Dikeluarkan
                    </span>`;
                }
                return `<span class="badge badge-label fw-semibold badge-soft-info">
                    <i class="ti ti-heart-x align-middle me-2 fs-4"></i>
                    Tidak Dikeluarkan
                </span>`;
            },
        },
        {
            data: "blood_received_by",
            title: "Penerima Labu",
            render: (data) => {
                return `<span class="fs-6 fw-semibold">${data ?? "-"}</span>`;
            },
        },
    ];

    listHistoryTestTableInstance = new GlobalAdvanceYajraDatatable(
        TABLE.historyTest,
        {
            removeSearch: true,
            removePageInfo: true,
            removePagination: true,
            removePageLength: true,
            ajax: (data, callback) => {
                if (!window.currentTransfusionPatientId) {
                    return callback(emptyCallback(data.draw));
                }
                $.get(
                    `${DATATABLE_URL}/${window.currentTransfusionPatientId}/history-test`,
                    data,
                )
                    .done(callback)
                    .fail(() => callback(emptyCallback(data.draw)));
            },
            columns: HISTORYTESTCOLUMNS,
            // columnDefs: [
            //     {
            //         targets: -1,
            //         responsivePriority: 1,
            //     },
            //     {
            //         targets: 0,
            //         responsivePriority: 2,
            //     },
            // ],
        },
    );
}

// ---------- COMPLETE TEST BUTTON ----------
export async function completeTest() {
    const detailPublicId = window.currentBagDetailPublicId;
    if (!detailPublicId) {
        c;
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
        btn.innerHTML = originalText;
        btn.disabled = false;
        btn.classList.add("d-none");

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
                    if (window.currentBagDetailPublicId && json.data) {
                        const updatedBag = json.data.find(
                            (b) =>
                                b.public_id === window.currentBagDetailPublicId,
                        );
                        if (updatedBag) {
                            window.currentBagData = updatedBag;
                            window.currentBagCrossmatchResult =
                                updatedBag.crossmatch_result ||
                                window.currentBagCrossmatchResult;
                            updateWorkflowButtonsState(updatedBag);
                        }
                    }

                    const state = evaluateBagListState(json.data ?? []);
                    applyBagListButtonState(state);

                    updateDoneButtonState();
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
document.addEventListener("click", async function (e) {
    if (e.target.closest("#update_bag_blood_btn"))
        await openUpdateBloodModal(e.target.closest("#update_bag_blood_btn"));
    if (e.target.closest("#confirm_use_blood")) await handleConfirmUseBlood();
    if (e.target.closest("#confirm_use_blood_recomendation"))
        await handleConfirmUseRecommendation();
    if (e.target.closest("#cancel_use_blood_recomendation")) showSummaryView();
});
document.addEventListener("change", async function (e) {
    // Update Test Result
    if (e.target.matches(".select-test-result")) {
        await patchRequest(
            `${BASE_URL}/test/${e.target.dataset.id}/update-result`,
            { result: e.target.value },
            "Result updated!",
        );

        // Cari checkbox yang berada di baris yang sama berdasarkan data-id
        // const targetCheckbox = $(
        //     `.checkbox-update[data-id="${e.target.dataset.id}"]`,
        // );

        // if (e.target.value === "" || e.target.value === null) {
        //     targetCheckbox.prop("checked", false);
        //     targetCheckbox.prop("disabled", true);
        //     targetCheckbox.css("cursor", "not-allowed");
        // } else {
        //     targetCheckbox.prop("checked", false);
        //     targetCheckbox.prop("disabled", false);
        //     targetCheckbox.css("cursor", "pointer");
        // }

        window.currentBagCrossmatchResult = null;
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
