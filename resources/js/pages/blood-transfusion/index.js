// ---------- Import Libraries ----------
import { GlobalAdvanceFlatpickr, GlobalAdvanceTomselect } from "../../app";
import {
    DatatableRequestBlood,
    listRequestTableInstance,
    DatatableListBagRequest,
    listBagRequestTableInstance,
    DatatableListTest,
    listTestTableInstance,
    DatatableHistoryTestTable,
    listHistoryTestTableInstance,
    completeTest,
} from "./datatable/datatables-helper";
import { GlobalRenderTimelineItem } from "../../utility/ui";
import { BloodTransfusionLogConfigTL } from "../../utility/config/timeline-config";
import { initFormEdit } from "./form/edit";
import { QzManager } from "../../utility/config/qz";
import {
    initReleaseBloodPack,
    initReleaseAllBloodPack,
    initDeleteTransaction,
    initArchiveTransaction,
    initNotReleaseBloodPack,
    initReactionTransfusion,
} from "./helper/action-helper";
import {
    initPrintIncompatibleLetter,
    initPrintNota,
    initPrintCrossmatchResult,
    initPrintCrossmatchResultPerBlood,
} from "./helper/print-helper";
import {
    applyButtonState,
    getPatientDetailButtonConfig,
    evaluateBagListState,
    applyBagListButtonState,
    updateWorkflowButtonsState,
    getCheckinBtn,
    getCompleteBtn,
    getCrossmatchSelesaiBtn,
    getPrintNotaBtn,
    SelectorBtnHold,
    SelectorBtnRelease,
    SelectorBtnUnrelease,
    SelectorBtnReleaseAll,
    SelectorBtnAccept,
    SelectorBtnConfirm,
    SelectorBtnConfimDelete,
    SelectorBtnPrintBarcodePerBlood,
    SelectorBtnDeletePerBlood,
    getSendResultBtn,
    SelectorBtnReactionTransfusion,
} from "./helper/button-state";

// ---------- Global variable untuk memudahkan penyesuaian ----------
const BloodTransfusionLogContainerSelector =
    ".blood-transfusion-log-data-container";
const TimelineContainerSelector = ".timeline-blood-transfusion-log";

const DateFilterSelector = ".blood-transfusion-date-filter";
const MonthFilterHistoryTestSelector = ".history-test-month-filter";
const PRINT_URL = "/blood-transfusion/detail/print";

const ModalDeleteTransactionSelector = "delete_data_blood_transfusion_modal";
const ActionDeleteTransactionSelector = ".btn-delete-blood-transfusion";
const AttributeDeleteTransaction = "deleteId";
const ConfirmDeleteTransactionSelector = "#confirm_delete";

const ModalArchiveTransactionSelector =
    "confirmation_data_archive_blood_transfusion_modal";
const ActionArchiveTransactionSelector = ".btn-archive-blood-transfusion";
const AttributeArchiveTransaction = "archiveId";
const ConfirmArchiveTransactionSelector = "#confirm_action";

const csrfToken = document
    .querySelector('meta[name="csrf-token"]')
    .getAttribute("content");

// ---------- Filter tanggal dari flatpickr untuk data di tabel ----------
function DateRangeFilter() {
    new GlobalAdvanceFlatpickr(DateFilterSelector, {
        maxDate: "today",
        defaultDate: "today",
    });

    $(document)
        .off("change", DateFilterSelector)
        .on("change", DateFilterSelector, function () {
            if (
                listRequestTableInstance &&
                $.fn.DataTable.isDataTable("#list-request-table")
            ) {
                listRequestTableInstance.instance.ajax.reload(null, false);
            }
        });
}
// ---------- Filter bulan dan tahun dari flatpickr untuk data di tabel history test ----------
function MonthHistoryTestFilter() {
    new GlobalAdvanceFlatpickr(MonthFilterHistoryTestSelector);

    $(document)
        .off("change", MonthFilterHistoryTestSelector)
        .on("change", MonthFilterHistoryTestSelector, function () {
            if (
                listHistoryTestTableInstance &&
                $.fn.DataTable.isDataTable("#list-history-test-table")
            ) {
                listHistoryTestTableInstance.instance.ajax.reload(null, false);
            }
        });
}
function FilterStatus() {
    const filterStatus = new GlobalAdvanceTomselect(
        "#filter-status-transaction",
        {
            valueField: "id",
            preload: true,
            load: function (query, callback) {
                fetch(
                    `/utility/select/blood-transfusion-status?q=${encodeURIComponent(query)}`,
                )
                    .then((res) => res.json())
                    .then((json) => callback(json.results))
                    .catch(() => callback());
            },
            onChange: function () {
                if (
                    listRequestTableInstance &&
                    $.fn.DataTable.isDataTable("#list-request-table")
                ) {
                    listRequestTableInstance.instance.ajax.reload(null, false);
                }
            },
        },
    );
}

// ---------- Menampilkan detail pasien dari row yang diklik ----------
export function updatePatientDetailUI(data) {
    if (!data) return;
    window.currentTransfusionData = data;

    const setElementText = (selector, text) => {
        const el = document.querySelector(
            `[data-patient-detail="${selector}"]`,
        );
        if (el) el.textContent = text || "-";
    };

    setElementText("name", data.patient?.name);
    setElementText("gender", data.patient?.gender);
    setElementText("email", data.patient?.email);
    setElementText("age", data.patient?.patient_age);
    setElementText("insurance", data.insurance?.name);
    setElementText("room", data.room?.name);
    setElementText("doctor", data.doctor?.name);
    setElementText("type_patient", data.room?.type);
    setElementText("diagnosis", data.diagnosis);
    setElementText("blood_group", data.patient?.blood_group);
    setElementText("blood_rhesus", data.patient?.blood_rhesus);

    const hasLabNumber =
        data.lab_number?.toString().trim() &&
        data.lab_number?.toString().trim() !== "-";
    const isCompleted =
        data.status && data.status === "blood_transfusion_finished";
    const isCanceled =
        data.status && data.status === "blood_transfusion_canceled";

    applyButtonState("#list-request-table", data, {
        buttons: getPatientDetailButtonConfig(
            hasLabNumber,
            isCompleted,
            isCanceled,
        ),
        onReady: (d) => {
            // Set dataset.id pada checkin button jika belum ada lab number
            if (!hasLabNumber) {
                const BTN_CHECKIN = getCheckinBtn();
                if (BTN_CHECKIN) BTN_CHECKIN.dataset.id = d.public_id;
            }
            if (hasLabNumber) {
                console.log("Has lab number? True");
                const BTN_COMPLETE = getCompleteBtn();
                if (BTN_COMPLETE) BTN_COMPLETE.dataset.id = d.public_id;
                const BTN_SEND_RESULT = getSendResultBtn();
                if (BTN_SEND_RESULT) BTN_SEND_RESULT.dataset.id = d.public_id;
                const BTN_PRINT_NOTA = getPrintNotaBtn();
                if (BTN_PRINT_NOTA) BTN_PRINT_NOTA.dataset.id = d.public_id;
            }

            // Update list bag request table
            window.currentTransfusionPatientId = d.patient_id;
            window.currentTransfusionPublicId = d.public_id;
            window.currentTransfusionLabNumber = d.lab_number;
            window.currentBagDetailPublicId = null;
            window.currentBagCrossmatchResult = null;

            if (
                listBagRequestTableInstance &&
                $.fn.DataTable.isDataTable("#list-bag-request-table")
            ) {
                $("#list-bag-request-table")
                    .DataTable()
                    .ajax.reload(function (json) {
                        const state = evaluateBagListState(json.data ?? []);
                        console.log(state);
                        applyBagListButtonState(state);

                        if (window.currentBagDetailPublicId && json.data) {
                            const updatedBag = json.data.find(
                                (b) =>
                                    b.public_id ===
                                    window.currentBagDetailPublicId,
                            );

                            if (updatedBag) {
                                window.currentBagData = updatedBag;
                                updateWorkflowButtonsState(updatedBag);
                            }
                        }
                    }, false);
            }
            if (
                listTestTableInstance &&
                $.fn.DataTable.isDataTable("#list-test-table")
            ) {
                $("#list-test-table").DataTable().ajax.reload(null, false);
            }
            if (
                listHistoryTestTableInstance &&
                $.fn.DataTable.isDataTable("#list-history-test-table")
            ) {
                $("#list-history-test-table")
                    .DataTable()
                    .ajax.reload(null, false);
            }
        },
    });
}
function initPatientDetail() {
    $(document)
        .off("click", "#list-request-table tbody tr")
        .on("click", "#list-request-table tbody tr", function (e) {
            if (
                $(e.target).closest(".dropdown, select, button, .ts-wrapper")
                    .length > 0
            )
                return;
            if (!listRequestTableInstance) return;
            const data = listRequestTableInstance.getRowData(this);
            const lab_number = data.lab_number;
            updatePatientDetailUI(data);
            fetchDataBloodStockLog();
        });
}

// ---------- Menampilkan test list dari row bag request yang diklik ----------
function initBagRequestRowClick() {
    $(document)
        .off("click", "#list-bag-request-table tbody tr")
        .on("click", "#list-bag-request-table tbody tr", function (e) {
            // Ignore clicks on interactive elements (dropdowns, selects, buttons)
            if (
                $(e.target).closest(".dropdown, select, .ts-wrapper").length > 0
            )
                return;
            if (!listBagRequestTableInstance) return;

            const data = listBagRequestTableInstance.getRowData(this);
            if (!data || !data.public_id) return;

            // Block test list for rows with "Not Available Stock"
            if (!data.row_data.has_available_stock) {
                notyf.error({
                    message:
                        "Gagal menampilkan data pemeriksaan. Tidak ada labu darah yang tersedia",
                });
                return;
            }

            // Highlight selected row
            $("#list-bag-request-table tbody tr").removeClass("table-active");
            $(this).addClass("table-active");

            // Set the detail filter and reload test table
            window.currentBagDetailPublicId = data.public_id;
            window.currentBagCrossmatchResult = data.crossmatch_result || null;
            window.currentBagData = data;

            // Update workflow buttons
            updateWorkflowButtonsState(window.currentBagData);

            if (
                listTestTableInstance &&
                $.fn.DataTable.isDataTable("#list-test-table")
            ) {
                $("#list-test-table").DataTable().ajax.reload(null, false);
            }
        });
}

// ---------- Handle Check In Lab Number ----------
function CheckInLabNumber() {
    const BTN_CHECKIN = getCheckinBtn();
    if (!BTN_CHECKIN) return;

    const newBtn = BTN_CHECKIN.cloneNode(true);
    BTN_CHECKIN.parentNode.replaceChild(newBtn, BTN_CHECKIN);

    newBtn.addEventListener("click", async function () {
        const id = this.dataset.id;
        if (!id) return;

        // Prevent multiple clicks
        const originalText = this.innerHTML;
        this.innerHTML =
            '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...';
        this.disabled = true;

        try {
            const response = await fetch(`/blood-transfusion/${id}/checkin`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content"),
                },
            });

            const result = await response.json();

            if (!response.ok) {
                notyf.error({
                    message: result.message || "Gagal checkin pasien!",
                });
            } else {
                notyf.success({
                    message:
                        result.message || "Pasien berhasil dicheckin/diterima!",
                });

                // Hide button after successful check-in
                this.classList.add("d-none");

                // Reload datatable to reflect new lab number
                if (
                    listRequestTableInstance &&
                    $.fn.DataTable.isDataTable("#list-request-table")
                ) {
                    listRequestTableInstance.instance.ajax.reload(null, false);
                }
                fetchDataBloodStockLog();
            }
        } catch (error) {
            console.error(error);
            notyf.error({
                message: error.message || "Gagal checkin pasien!",
            });
        } finally {
            this.innerHTML = originalText;
            this.disabled = false;
        }
    });
}

// ---------- Handle Complete ----------
function CompleteTransaction() {
    const BTN_COMPLETE = getCompleteBtn();
    if (!BTN_COMPLETE) return;

    const completeNewBtn = BTN_COMPLETE.cloneNode(true);
    BTN_COMPLETE.parentNode.replaceChild(completeNewBtn, BTN_COMPLETE);

    completeNewBtn.addEventListener("click", async function () {
        const id = this.dataset.id;
        if (!id) return;

        // Prevent multiple clicks
        const originalText = this.innerHTML;
        this.innerHTML =
            '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...';
        this.disabled = true;

        try {
            const response = await fetch(`/blood-transfusion/${id}/complete`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content"),
                },
            });

            const result = await response.json();

            if (!response.ok) {
                notyf.error({
                    message: result.message || "Failed to complete request!",
                });
            } else {
                notyf.success({
                    message:
                        result.message ||
                        "Blood Request Completed Successfully!",
                });
                this.classList.add("d-none");
                if (
                    listRequestTableInstance &&
                    $.fn.DataTable.isDataTable("#list-request-table")
                ) {
                    listRequestTableInstance.instance.ajax.reload(null, false);
                }
            }
        } catch (error) {
            console.error(error);
            notyf.error({
                message: error.message || "Failed to complete request!",
            });
        } finally {
            this.innerHTML = originalText;
            this.disabled = false;
        }
    });
}

// ---------- Handle Send Result ----------
function SendResultToSIMRS() {
    const BTN_SEND_RESULT = getSendResultBtn();
    if (!BTN_SEND_RESULT) return;

    const sendResultNewBtn = BTN_SEND_RESULT.cloneNode(true);
    BTN_SEND_RESULT.parentNode.replaceChild(sendResultNewBtn, BTN_SEND_RESULT);

    sendResultNewBtn.addEventListener("click", async function () {
        const id = this.dataset.id;
        if (!id) return;

        // Prevent multiple clicks
        const originalText = this.innerHTML;
        this.innerHTML =
            '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...';
        this.disabled = true;

        showPageLoading();
        try {
            const response = await fetch(
                `/blood-transfusion/${id}/send-result`,
                {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document
                            .querySelector('meta[name="csrf-token"]')
                            .getAttribute("content"),
                    },
                },
            );
            const result = await response.json();

            if (!response.ok) {
                notyf.error({
                    message: result.message || "Failed to send result!",
                });
                hidePageLoading();
            } else {
                notyf.success({
                    message:
                        result.message || "Send result to SIMRS successfully!",
                });
                this.classList.add("d-none");
                if (listRequestTableInstance) {
                    listRequestTableInstance.reload();
                }
                hidePageLoading();
            }
        } catch (error) {
            console.error(error);
            notyf.error({
                message: error.message || "Failed to send result!",
            });
            hidePageLoading();
        } finally {
            this.innerHTML = originalText;
            this.disabled = false;
            hidePageLoading();
        }
    });
}

// ---------- Handle Done Button ----------
function initDoneButton() {
    const BTN_FINISH = getCrossmatchSelesaiBtn();
    if (!BTN_FINISH) return;

    const newBtn = BTN_FINISH.cloneNode(true);
    BTN_FINISH.parentNode.replaceChild(newBtn, BTN_FINISH);

    // Sembunyikan tombol secara default
    newBtn.classList.add("d-none");

    newBtn.addEventListener("click", function () {
        completeTest();
    });
}

// ---------- Handle Bag Request Action Buttons ----------
function initBagRequestActionButtons() {
    // ---------- Helpers ----------
    const reloadBagRequestTable = () => {
        if (
            listBagRequestTableInstance &&
            $.fn.DataTable.isDataTable("#list-bag-request-table")
        ) {
            $("#list-bag-request-table")
                .DataTable()
                .ajax.reload(function (json) {
                    if (window.currentBagDetailPublicId && json.data) {
                        const updatedBag = json.data.find(
                            (b) =>
                                b.public_id === window.currentBagDetailPublicId,
                        );

                        if (updatedBag) {
                            window.currentBagData = updatedBag;
                            updateWorkflowButtonsState(updatedBag);
                        }
                    }
                }, false);
        }
    };
    const doAction = async ({
        url,
        method = "POST",
        body = null,
        successMessage = "Action successful!",
        errorMessage = "Action failed!",
        onSuccess = null,
    }) => {
        try {
            const fetchOptions = {
                method,
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken,
                },
            };

            if (body) fetchOptions.body = JSON.stringify(body);

            const response = await fetch(url, fetchOptions);
            const result = await response.json();

            if (!response.ok) {
                notyf.error({
                    message: result.message || errorMessage,
                });
                return;
            }

            notyf.success({
                message: result.message || successMessage,
            });

            reloadBagRequestTable();
            fetchDataBloodStockLog();
            if (typeof onSuccess === "function") {
                onSuccess(result);
            }
        } catch (error) {
            console.error(error);
            notyf.error({
                message: errorMessage,
            });
        }
    };
    const handlePrintBarcode = async (url) => {
        showPageLoading();

        try {
            const res = await fetch(url, {
                method: "GET",
            });

            if (!res.ok) {
                const err = await res.json().catch(() => ({}));
                notyf.error({
                    message:
                        err?.message ?? `HTTP error! status: ${res.status}`,
                });

                hidePageLoading();
                return;
            }

            const data = await res.json();
            console.log(data);
            await QzManager.sendZpl(data.data ?? [], "barcode-blood");

            hidePageLoading();
        } catch (err) {
            console.error("[Print] Network error:", err);
            notyf.error({
                message: "Network error, failed to load print file.",
            });

            hidePageLoading();
        }
    };

    // Hold Blood
    $(document)
        .off("click", "#" + SelectorBtnHold)
        .on("click", "#" + SelectorBtnHold, function (e) {
            e.preventDefault();
            if (!window.currentBagDetailPublicId) return;
            doAction({
                url: `/blood-transfusion/detail/${window.currentBagDetailPublicId}/hold`,
            });
        });

    // Release Blood
    initReleaseBloodPack({
        doAction,
        SelectorBtnRelease,
        qzManager: QzManager,
    });

    // Release All Blood
    initReleaseAllBloodPack({ doAction, SelectorBtnReleaseAll });

    // Unrelease
    initNotReleaseBloodPack({ doAction, SelectorBtnUnrelease });

    // Reaction Transfusion
    initReactionTransfusion({ doAction, SelectorBtnReactionTransfusion });

    // Print Incompatible Letter
    initPrintIncompatibleLetter({
        PRINT_URL,
        onDone: () => {
            reloadBagRequestTable();
        },
        printType: "incompatible_letter",
    });

    // Print Result
    initPrintCrossmatchResult({
        PRINT_URL,
        onDone: () => {
            reloadBagRequestTable();
        },
        printType: "crossmatch_result",
    });

    // Print Result Per Blood
    initPrintCrossmatchResultPerBlood({
        PRINT_URL,
        onDone: () => {
            reloadBagRequestTable();
        },
        printType: "crossmatch_result_per_blood",
    });

    // Print Barcode
    $(document)
        .off("click", "." + SelectorBtnPrintBarcodePerBlood)
        .on("click", "." + SelectorBtnPrintBarcodePerBlood, function (e) {
            e.preventDefault();
            const detailId = $(this).data("public-id");
            if (!detailId) return;

            handlePrintBarcode(
                `${PRINT_URL}/barcode/${window.currentTransfusionPublicId}/${detailId}`,
            );
        });

    // Delete Blood
    $(document)
        .off("click", "#" + SelectorBtnDeletePerBlood)
        .on("click", "#" + SelectorBtnDeletePerBlood, function (e) {
            e.preventDefault();
            const publicId = $(this).data("public-id");
            if (!publicId) return;

            const confirmBtn = document.getElementById(SelectorBtnConfimDelete);
            if (confirmBtn) confirmBtn.dataset.detailId = publicId;

            const modalEl = document.getElementById("delete_data_blood_modal");
            if (modalEl) bootstrap.Modal.getOrCreateInstance(modalEl).show();
        });
    $(document)
        .off("click", "#" + SelectorBtnConfimDelete)
        .on("click", "#" + SelectorBtnConfimDelete, async function (e) {
            e.preventDefault();
            const detailId = this.dataset.detailId;
            if (!detailId) return;

            const originalText = this.innerHTML;
            this.innerHTML =
                '<span class="spinner-border spinner-border-sm"></span> Processing...';
            this.disabled = true;

            await doAction({
                url: `/blood-transfusion/detail/${detailId}/delete`,
                successMessage: "Data darah berhasil dihapus!",
                errorMessage: "Gagal menghapus data darah!",
                onSuccess: () => {
                    const modalEl = document.getElementById(
                        "delete_data_blood_modal",
                    );
                    if (modalEl)
                        bootstrap.Modal.getOrCreateInstance(modalEl).hide();
                    this.dataset.detailId = "";
                },
            });

            this.innerHTML = originalText;
            this.disabled = false;
        });

    // Approve incompatible
    $(document)
        .off("click", "#" + SelectorBtnAccept)
        .on("click", "#" + SelectorBtnAccept, function (e) {
            e.preventDefault();
            const detailId = window.currentBagDetailPublicId;
            if (!detailId) return;

            const confirmBtn = document.getElementById(SelectorBtnConfirm);
            if (confirmBtn) confirmBtn.dataset.detailId = detailId;

            const modalEl = document.getElementById(
                "confirmation_data_approve_incompatible_modal",
            );
            if (modalEl) bootstrap.Modal.getOrCreateInstance(modalEl).show();
        });
    $(document)
        .off("click", "#" + SelectorBtnConfirm)
        .on("click", "#" + SelectorBtnConfirm, async function (e) {
            e.preventDefault();
            const detailId = this.dataset.detailId;
            if (!detailId) return;

            const originalText = this.innerHTML;
            this.innerHTML =
                '<span class="spinner-border spinner-border-sm"></span> Processing...';
            this.disabled = true;

            await doAction({
                url: `/blood-transfusion/detail/${detailId}/accept-incompatible`,
                successMessage: "Incompatible blood accepted successfully!",
                errorMessage: "Failed to accept incompatible blood!",
                onSuccess: () => {
                    const modalEl = document.getElementById(
                        "confirmation_data_approve_incompatible_modal",
                    );
                    if (modalEl)
                        bootstrap.Modal.getOrCreateInstance(modalEl).hide();
                },
            });

            this.innerHTML = originalText;
            this.disabled = false;
        });
}

// ---------- Generate Timeline dari array log ----------
async function fetchDataBloodStockLog() {
    const id = window.currentTransfusionPublicId;
    if (!id) return;

    try {
        const res = await fetch(`/blood-transfusion/${id}/log`, {
            method: "GET",
            cache: "no-store",
            headers: {
                "Cache-Control": "no-cache",
                Pragma: "no-cache",
            },
        });
        if (!res.ok) throw new Error(`HTTP error! status: ${res.status}`);

        const data = await res.json();
        GenerateTimeline(data);
    } catch (err) {
        notyf.error({ message: "Failed to fetch blood transfusion log data!" });
        console.error(err);
        GenerateTimeline([]);
    }
}
function GenerateTimeline(logs = []) {
    const bloodTransfusionTimeline = GlobalRenderTimelineItem.create({
        container: BloodTransfusionLogContainerSelector,
        wrapper: TimelineContainerSelector,
        locale: "en-GB",
        statusConfig: BloodTransfusionLogConfigTL,
        iconLibrary: "tabler",
    });

    bloodTransfusionTimeline.render(logs);
}

document.addEventListener("DOMContentLoaded", function () {
    // Date range picker
    DateRangeFilter();
    MonthHistoryTestFilter();
    FilterStatus();

    // Datatables
    DatatableRequestBlood();
    DatatableListBagRequest();
    DatatableHistoryTestTable();
    DatatableListTest();

    // Row interactions
    initPatientDetail();
    initBagRequestRowClick();

    // Action buttons
    CheckInLabNumber();
    initPrintNota({
        PRINT_URL,
        onDone: () => {
            if (
                listBagRequestTableInstance &&
                $.fn.DataTable.isDataTable("#list-bag-request-table")
            ) {
                $("#list-bag-request-table")
                    .DataTable()
                    .ajax.reload(null, false);
            }
        },
        printType: "nota",
    });
    CompleteTransaction();
    SendResultToSIMRS();
    initDoneButton();
    initBagRequestActionButtons();
    initDeleteTransaction({
        reloadTable: () => {
            if (listRequestTableInstance) {
                listRequestTableInstance.reload();
            }
        },
        ActionDeleteSelector: ActionDeleteTransactionSelector,
        AttributeDelete: AttributeDeleteTransaction,
        ModalDeleteSelector: ModalDeleteTransactionSelector,
        ConfirmDeleteSelector: ConfirmDeleteTransactionSelector,
    });
    initArchiveTransaction({
        reloadTable: () => {
            if (listRequestTableInstance) {
                listRequestTableInstance.reload();
            }
        },
        ActionArchiveSelector: ActionArchiveTransactionSelector,
        AttributeArchive: AttributeArchiveTransaction,
        ModalArchiveSelector: ModalArchiveTransactionSelector,
        ConfirmArchiveSelector: ConfirmArchiveTransactionSelector,
    });

    // Form edit
    initFormEdit();
});
