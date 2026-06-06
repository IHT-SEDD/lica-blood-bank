// ---------- Import Libraries ----------
import {
    GlobalAdvanceFlatpickr,
    GlobalAdvanceTomselect,
    GlobalDeleteDataConfirmation,
} from "../../app";
import {
    DatatableRequestBlood,
    listRequestTableInstance,
    DatatableListBagRequest,
    listBagRequestTableInstance,
    DatatableListTest,
    listTestTableInstance,
    completeTest,
    updateDoneButtonState,
} from "./analytic/datatables-helper";
import { GlobalRenderTimelineItem, TextFormatter } from "../../utility/ui";
import { BloodTransfusionLogConfigTL } from "../../utility/config/timeline-config";
import { initFormEdit } from "./form/edit";
import { QzManager } from "../../utility/config/qz";

// ---------- Global variable untuk memudahkan penyesuaian ----------
// TIMELINE
const BloodTransfusionLogContainerSelector =
    ".blood-transfusion-log-data-container";
const TimelineContainerSelector = ".timeline-blood-transfusion-log";

const DateFilterSelector = ".blood-transfusion-date-filter";
const PRINT_URL = "/blood-transfusion/detail/print";
const LogDataURL = "/blood-transfusion/detail/log";

const SelectorBtnCheckin = "btn-checkin-lab";
const SelectorBtnDone = "btn-test-done";
const SelectorBtnPrintNota = "btn-print-nota";
const SelectorBtnPrintResult = "btn-print-result";
const SelectorBtnComplete = "btn-complete-transaction";
const SelectorBtnPrintResultPerBlood = "btn-print-result-per-blood";
const SelectorBtnPrintBarcodePerBlood = "btn-print-barcode-per-blood";
const SelectorBtnDeletePerBlood = "btn-delete-per-blood";
const SelectorBtnPrintIncompLetter = "btn-print-incompletter";

const SelectorBtnRelease = "btn-release-blood-pack";
const SelectorBtnUnrelease = "btn-unrelease-blood-pack";
const SelectorBtnReleaseAll = "btn-release-all-blood-pack";
const SelectorBtnAccept = "btn-accept-blood-pack";
const SelectorBtnConfirm = "confirm_action";
const SelectorBtnConfimDelete = "confirm_delete";
const SelectorBtnHold = "btn-hold-blood-pack";
const SelectorBtnEditBloodPack = "btn-edit-blood-pack";

const getCheckinBtn = () => document.getElementById(SelectorBtnCheckin);
const getCompleteBtn = () => document.getElementById(SelectorBtnComplete);
const getFinishBtn = () => document.getElementById(SelectorBtnDone);
const getPrintNotaBtn = () => document.getElementById(SelectorBtnPrintNota);

// ---------- Named handler untuk delete:open (di luar fungsi agar removeEventListener bekerja) ----------
function handleDeleteOpen(e) {
    const { data } = e.detail;
    if (!data) return;

    const confirmBtn = document.getElementById("confirm_delete");
    if (confirmBtn) confirmBtn.dataset.detailId = data.public_id;

    const deletedDataEl = document.querySelector("#deleted_data");
    if (deletedDataEl) {
        deletedDataEl.textContent = `${data.component ?? "-"} with ID ${data.public_id}`;
    }
}

// ---------- Handle Button State ----------
window.HandlingButtonState = function (tableID, data, options = {}) {
    const { buttons = [], onReady = null, ...restOptions } = options;
    if (!data) return;

    buttons.forEach(({ selector, conditions, action = "show", className }) => {
        const el =
            document.getElementById(selector) ??
            document.querySelector(selector);
        if (!el) return;

        const conditionMet =
            typeof conditions === "function"
                ? conditions(data, { tableID, ...restOptions })
                : true;

        switch (action) {
            case "show":
                conditionMet
                    ? el.classList.remove("d-none")
                    : el.classList.add("d-none");
                break;
            case "hide":
                conditionMet
                    ? el.classList.add("d-none")
                    : el.classList.remove("d-none");
                break;
            case "enable":
                el.disabled = !conditionMet;
                break;
            case "disable":
                el.disabled = conditionMet;
                break;
            case "toggle":
                el.classList.toggle(className ?? "d-none", !conditionMet);
                break;
        }
    });

    if (typeof onReady === "function") {
        onReady(data, { tableID, ...restOptions });
    }
};

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
    const setElementText = (selector, text) => {
        const el = document.querySelector(
            `[data-patient-detail="${selector}"]`,
        );
        if (el) el.textContent = text || "-";
    };

    setElementText("name", data.patient?.name);
    setElementText("gender", data.patient?.gender);
    setElementText("email", data.patient?.email);
    setElementText("age", data.patient?.age);
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

    window.HandlingButtonState("#list-request-table", data, {
        buttons: [
            // btn-checkin-lab: tampil jika belum ada lab number
            {
                selector: SelectorBtnCheckin,
                action: "show",
                conditions: (d) => !hasLabNumber,
            },
            {
                selector: SelectorBtnComplete,
                action: "show",
                conditions: (d) => !isCompleted && hasLabNumber,
            },
            // btn-print-note: tampil & enable jika sudah ada lab number
            {
                selector: SelectorBtnPrintNota,
                action: "show",
                conditions: (d) => hasLabNumber,
            },
            {
                selector: SelectorBtnPrintNota,
                action: "enable",
                conditions: (d) => hasLabNumber,
            },
            // btn-edit-blood-pack: tampil & enable jika sudah ada lab number
            {
                selector: SelectorBtnEditBloodPack,
                action: "show",
                conditions: (d) => hasLabNumber,
            },
            {
                selector: SelectorBtnEditBloodPack,
                action: "enable",
                conditions: (d) => hasLabNumber,
            },
            {
                selector: SelectorBtnReleaseAll,
                action: "show",
                conditions: (d) => hasLabNumber,
            },
            {
                selector: SelectorBtnPrintResult,
                action: "show",
                conditions: (d) => hasLabNumber,
            },
        ],
        onReady: (d) => {
            // Set dataset.id pada checkin button jika belum ada lab number
            if (!hasLabNumber) {
                const BTN_CHECKIN = getCheckinBtn();
                if (BTN_CHECKIN) BTN_CHECKIN.dataset.id = d.public_id;
            }

            if (hasLabNumber) {
                const BTN_COMPLETE = getCompleteBtn();
                if (BTN_COMPLETE) BTN_COMPLETE.dataset.id = d.public_id;
                const BTN_PRINT_NOTA = getPrintNotaBtn();
                if (BTN_PRINT_NOTA) BTN_PRINT_NOTA.dataset.id = d.public_id;
            }

            // Update list bag request table
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
                        const allHaveCrossmatch =
                            json.data &&
                            json.data.length > 0 &&
                            json.data.every(
                                (bag) =>
                                    bag.crossmatch_result &&
                                    bag.crossmatch_result.toString().trim() !==
                                        "",
                            );
                        const bloodReleased =
                            json.data &&
                            json.data.length > 0 &&
                            json.data.every(
                                (bag) =>
                                    bag.blood_release_status &&
                                    bag.blood_release_status === 1,
                            );
                        const hasUnapprovedIncompatible =
                            json.data &&
                            json.data.some(
                                (bag) =>
                                    bag.crossmatch_result
                                        ?.toString()
                                        .toLowerCase() === "incompatible" &&
                                    Number(bag.is_approval_incompatible) !== 1,
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
                            btnReleaseAll.disabled =
                                !allHaveCrossmatch ||
                                bloodReleased ||
                                hasUnapprovedIncompatible;
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
                                    b.public_id ===
                                    window.currentBagDetailPublicId,
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

            if (
                listTestTableInstance &&
                $.fn.DataTable.isDataTable("#list-test-table")
            ) {
                $("#list-test-table").DataTable().ajax.reload(null, false);
            }
        },
    });
}
function initPatientDetail() {
    $(document)
        .off("click", "#list-request-table tbody tr")
        .on("click", "#list-request-table tbody tr", function (e) {
            if ($(e.target).closest(".dropdown").length > 0) return;
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
                $(e.target).closest(".dropdown, select, button, .ts-wrapper")
                    .length > 0
            )
                return;
            if (!listBagRequestTableInstance) return;

            const data = listBagRequestTableInstance.getRowData(this);
            if (!data || !data.public_id) return;

            // Block test list for rows with "Not Available Stock"
            if (!data.row_data.has_available_stock) {
                notyf.error({
                    message: "Gagal menampilkan data pemeriksaan",
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
            if (typeof window.updateWorkflowButtonsState === "function") {
                window.updateWorkflowButtonsState();
            }

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

// ---------- Handle Done Button ----------
function initDoneButton() {
    const BTN_FINISH = getFinishBtn();
    if (!BTN_FINISH) return;

    const newBtn = BTN_FINISH.cloneNode(true);
    BTN_FINISH.parentNode.replaceChild(newBtn, BTN_FINISH);

    // Initially disabled
    newBtn.disabled = true;

    newBtn.addEventListener("click", function () {
        completeTest();
    });
}

window.updateWorkflowButtonsState = function () {
    const btnHold = document.getElementById(SelectorBtnHold);
    const btnRelease = document.getElementById(SelectorBtnRelease);
    const btnUnrelease = document.getElementById(SelectorBtnUnrelease);
    const btnAccept = document.getElementById(SelectorBtnAccept);
    const btnPrintIncomp = document.getElementById(
        SelectorBtnPrintIncompLetter,
    );

    const showButtons = (...btns) =>
        btns.forEach((btn) => btn?.classList.remove("d-none"));
    const hideButtons = (...btns) =>
        btns.forEach((btn) => btn?.classList.add("d-none"));

    hideButtons(btnHold, btnRelease, btnUnrelease, btnAccept);

    const data = window.currentBagData;
    const {
        crossmatch_result,
        blood_stock_status,
        is_print_incompatible_letter,
        is_approval_incompatible,
    } = data.row_data;
    if (!data || !data.crossmatch_result) return;

    if (crossmatch_result === "Incompatible") {
        if (blood_stock_status === "in_use") {
            // Sudah print incompatible letter tetapi belum approve incompatible
            if (is_print_incompatible_letter && !is_approval_incompatible) {
                showButtons(btnAccept);
            }
            // Sudah approve incompatible
            if (is_approval_incompatible) {
                showButtons(btnRelease);
            }

            showButtons(btnHold, btnUnrelease);
        }

        if (blood_stock_status === "hold") {
            hideButtons(btnHold);
            showButtons(btnPrintIncomp, btnUnrelease);

            // Sudah print incompatible letter
            if (is_print_incompatible_letter) {
                showButtons(btnAccept, btnUnrelease);
            }

            // Sudah approve incompatible
            if (is_approval_incompatible) {
                showButtons(btnUnrelease, btnRelease);
                hideButtons(btnAccept);
            }
        }
    } else if (crossmatch_result === "Compatible") {
        if (blood_stock_status === "in_use") {
            showButtons(btnHold, btnRelease, btnUnrelease);
        }

        if (blood_stock_status === "hold") {
            hideButtons(btnHold);
            showButtons(btnRelease, btnUnrelease);
        }
    }
};

// ---------- Handle Bag Request Action Buttons ----------
function initBagRequestActionButtons() {
    const csrfToken = document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content");

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
    };
    const doAction = async ({
        url,
        method = "POST",
        successMessage = "Action successful!",
        errorMessage = "Action failed!",
        onSuccess = null,
    }) => {
        try {
            const response = await fetch(url, {
                method,
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken,
                },
            });

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
    const handlePrint = async (url) => {
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

            const blob = await res.blob();
            const blobUrl = URL.createObjectURL(blob);

            let iframe = document.getElementById("__print_preview_iframe__");

            if (iframe) iframe.remove();

            iframe = document.createElement("iframe");

            iframe.id = "__print_preview_iframe__";

            iframe.style.cssText =
                "position:fixed;top:0;left:0;width:100%;height:100%;border:none;opacity:0;pointer-events:none;z-index:-1;";

            iframe.src = blobUrl;

            iframe.onload = () => {
                try {
                    iframe.contentWindow.focus();
                    iframe.contentWindow.print();
                } catch (printErr) {
                    console.error(printErr);

                    notyf.error({
                        message: "Failed to open print dialog.",
                    });
                } finally {
                    hidePageLoading();

                    reloadBagRequestTable();

                    setTimeout(() => {
                        URL.revokeObjectURL(blobUrl);
                        iframe.remove();
                    }, 30000);
                }
            };

            document.body.appendChild(iframe);
        } catch (err) {
            console.error("[Print] Network error:", err);
            notyf.error({
                message: "Network error, failed to load print file.",
            });

            hidePageLoading();
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
    $(document)
        .off("click", "#" + SelectorBtnRelease)
        .on("click", "#" + SelectorBtnRelease, function (e) {
            e.preventDefault();
            if (!window.currentBagDetailPublicId) return;
            doAction({
                url: `/blood-transfusion/detail/${window.currentBagDetailPublicId}/release`,
            });
        });

    // Unrelease
    $(document)
        .off("click", "#" + SelectorBtnUnrelease)
        .on("click", "#" + SelectorBtnUnrelease, function (e) {
            e.preventDefault();
            if (!window.currentBagDetailPublicId) return;
            doAction({
                url: `/blood-transfusion/detail/${window.currentBagDetailPublicId}/unrelease`,
            });
        });

    // Print Incompatible Letter
    $(document)
        .off("click", "#" + SelectorBtnPrintIncompLetter)
        .on("click", "#" + SelectorBtnPrintIncompLetter, function (e) {
            e.preventDefault();
            handlePrint(
                `${PRINT_URL}/incompatible-letter/${window.currentTransfusionPublicId}`,
            );
        });

    // Release All Blood
    $(document)
        .off("click", "#" + SelectorBtnReleaseAll)
        .on("click", "#" + SelectorBtnReleaseAll, function (e) {
            e.preventDefault();
            doAction({
                url: `/blood-transfusion/detail/${window.currentTransfusionPublicId}/release-all`,
            });
        });

    // Print Result
    $(document)
        .off("click", "#" + SelectorBtnPrintResult)
        .on("click", "#" + SelectorBtnPrintResult, function (e) {
            e.preventDefault();
            handlePrint(
                `${PRINT_URL}/crossmatch-result/${window.currentTransfusionPublicId}`,
            );
        });
    $(document)
        .off("click", "." + SelectorBtnPrintResultPerBlood)
        .on("click", "." + SelectorBtnPrintResultPerBlood, function (e) {
            e.preventDefault();
            const detailId = $(this).data("public-id");
            if (!detailId) return;

            handlePrint(
                `${PRINT_URL}/crossmatch-result/${window.currentTransfusionPublicId}/${detailId}`,
            );
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

// ---------- Handle Print Barcode ----------
function PrintNota() {
    const BTN_PRINT_NOTA = getPrintNotaBtn();
    if (!BTN_PRINT_NOTA) return;

    const newBtn = BTN_PRINT_NOTA.cloneNode(true);
    BTN_PRINT_NOTA.parentNode.replaceChild(newBtn, BTN_PRINT_NOTA);

    newBtn.addEventListener("click", async function () {
        const id = this.dataset.id;
        if (!id) return;

        showPageLoading();

        try {
            const res = await fetch(
                `/blood-transfusion/detail/print/nota/${id}`,
            );

            if (!res.ok) {
                const err = await res.json().catch(() => ({}));
                notyf.error({
                    message:
                        err?.message ?? `HTTP error! status: ${res.status}`,
                });
                hidePageLoading();
                return;
            }

            const blob = await res.blob();
            const blobUrl = URL.createObjectURL(blob);

            let iframe = document.getElementById("__print_preview_iframe__");

            if (iframe) iframe.remove();

            iframe = document.createElement("iframe");

            iframe.id = "__print_preview_iframe__";

            iframe.style.cssText =
                "position:fixed;top:0;left:0;width:100%;height:100%;border:none;opacity:0;pointer-events:none;z-index:-1;";

            iframe.src = blobUrl;

            iframe.onload = () => {
                try {
                    iframe.contentWindow.focus();
                    iframe.contentWindow.print();
                } catch (printErr) {
                    console.error(printErr);

                    notyf.error({
                        message: "Failed to open print dialog.",
                    });
                } finally {
                    hidePageLoading();

                    reloadBagRequestTable();

                    setTimeout(() => {
                        URL.revokeObjectURL(blobUrl);
                        iframe.remove();
                    }, 30000);
                }
            };

            document.body.appendChild(iframe);
        } catch (err) {
            console.error("[Print] Network error:", err);
            notyf.error({
                message: "Network error, failed to load print file.",
            });

            hidePageLoading();
        }
    });
}

document.addEventListener("DOMContentLoaded", function () {
    // Date range picker
    DateRangeFilter();
    FilterStatus();

    // Datatables
    DatatableRequestBlood();
    DatatableListBagRequest();
    DatatableListTest();

    // Row interactions
    initPatientDetail();
    initBagRequestRowClick();

    // Action buttons
    CheckInLabNumber();
    PrintNota();
    CompleteTransaction();
    initDoneButton();
    initBagRequestActionButtons();

    // Form edit
    initFormEdit();
});
