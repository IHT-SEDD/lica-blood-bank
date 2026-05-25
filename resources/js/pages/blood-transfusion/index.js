// ---------- Import Libraries ----------
import { GlobalAdvanceFlatpickr } from "../../app";
import { TextFormatter } from "../../utility/ui";
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
import TomSelect from "tom-select";

// ---------- Global variable untuk memudahkan penyesuaian :begin ----------
const DateFilterSelector = ".blood-transfusion-date-filter";
const PRINT_URL = "/blood-transfusion/detail/print";

const getCheckinBtn = () => document.getElementById("btn-checkin-lab");
const getFinishBtn = () => document.getElementById("btn-test-done");
const getPrintBarcodeBtn = () => document.getElementById("btn-print-barcode");

const SelectorBtnRelease = "btn-release-blood-pack";
const SelectorBtnUnrelease = "btn-unrelease-blood-pack";
const SelectorBtnAccept = "btn-accept-blood-pack";
const SelectorBtnHold = "btn-hold-blood-pack";

const BTNPRINT_INCOMPLETTER = document.getElementById("btn-print-incompletter");
const BTNPRINT_RESULT = document.getElementById("btn-print-result");
const BTN_EDITBLOOD = document.getElementById("btn-edit-blood-pack");
const BTN_RELEASEALL = document.getElementById("btn-release-all-blood-pack");
// ---------- Global variable untuk memudahkan penyesuaian :end ----------

// ---------- Filter tanggal dari flatpickr untuk data di tabel :begin ----------
function DateRangeFilter() {
    new GlobalAdvanceFlatpickr(DateFilterSelector, {
        maxDate: "today",
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
// ---------- Filter tanggal dari flatpickr untuk data di tabel :end ----------

// ---------- Menampilkan detail pasien dari row yang diklik :begin ----------
export function updatePatientDetailUI(data) {
    if (!data) return;

    // Update DOM dengan data dari baris yang dipilih
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

    const BTN_CHECKIN = getCheckinBtn();
    const BTNPRINT_BARCODE = getPrintBarcodeBtn();
    const hasLabNumber = data.lab_number?.toString().trim();
    if (hasLabNumber && hasLabNumber !== "-") {
        if (BTN_CHECKIN) {
            BTN_CHECKIN.classList.add("d-none");
        }
        if (BTNPRINT_BARCODE) {
            BTNPRINT_BARCODE.disabled = false;
            BTNPRINT_BARCODE.classList.remove("d-none");
        }
        if (BTN_EDITBLOOD) {
            BTN_EDITBLOOD.disabled = false;
            BTN_EDITBLOOD.classList.remove("d-none");
        }
        if (BTN_RELEASEALL) {
            BTN_RELEASEALL.disabled = true;
            BTN_RELEASEALL.classList.remove("d-none");
        }
    } else {
        if (BTN_CHECKIN) {
            BTN_CHECKIN.classList.remove("d-none");
            BTN_CHECKIN.dataset.id = data.public_id;
        }
        if (BTNPRINT_BARCODE) {
            BTNPRINT_BARCODE.disabled = true;
            BTNPRINT_BARCODE.classList.add("d-none");
        }
        if (BTN_EDITBLOOD) {
            BTN_EDITBLOOD.disabled = true;
            BTN_EDITBLOOD.classList.add("d-none");
        }
        if (BTN_RELEASEALL) {
            BTN_RELEASEALL.disabled = true;
            BTN_RELEASEALL.classList.add("d-none");
        }
    }

    // Update list bag request table
    window.currentTransfusionPublicId = data.public_id;
    window.currentTransfusionLabNumber = data.lab_number; // Save lab number state
    window.currentBagDetailPublicId = null; // Reset bag filter when switching transfusion
    window.currentBagCrossmatchResult = null;
    if (
        listBagRequestTableInstance &&
        $.fn.DataTable.isDataTable("#list-bag-request-table")
    ) {
        $("#list-bag-request-table").DataTable().ajax.reload(null, false);
    }

    // Reset test table (requires bag row click to load)
    if (
        listTestTableInstance &&
        $.fn.DataTable.isDataTable("#list-test-table")
    ) {
        $("#list-test-table").DataTable().ajax.reload(null, false);
    }
}
// ---------- Menampilkan detail pasien dari row yang diklik :end ----------

function initPatientDetail() {
    $(document)
        .off("click", "#list-request-table tbody tr")
        .on("click", "#list-request-table tbody tr", function (e) {
            if ($(e.target).closest(".dropdown").length > 0) return;
            if (!listRequestTableInstance) return;
            const data = listRequestTableInstance.getRowData(this);
            const lab_number = data.lab_number;
            updatePatientDetailUI(data);
        });
}

// ---------- Menampilkan test list dari row bag request yang diklik :begin ----------
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
            if (!data.has_available_stock) {
                notyf.error({
                    message:
                        "Cannot show test list: stock is not available for this bag.",
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
// ---------- Menampilkan test list dari row bag request yang diklik :end ----------

// ---------- Handle Check In Lab Number :begin ----------
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
                    message: result.message || "Failed to check in!",
                });
            } else {
                notyf.success({
                    message: result.message || "Successfully checked in!",
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
                message: error.message || "Failed to check in!",
            });
        } finally {
            this.innerHTML = originalText;
            this.disabled = false;
        }
    });
}
// ---------- Handle Check In Lab Number :end ----------

// ---------- Handle Done Button :begin ----------
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
// ---------- Handle Done Button :end ----------

window.updateWorkflowButtonsState = function () {
    const btnHold = document.getElementById(SelectorBtnHold);
    const btnRelease = document.getElementById(SelectorBtnRelease);
    const btnUnrelease = document.getElementById(SelectorBtnUnrelease);
    const btnAccept = document.getElementById(SelectorBtnAccept);

    const showButtons = (...btns) =>
        btns.forEach((btn) => btn?.classList.remove("d-none"));
    const hideButtons = (...btns) =>
        btns.forEach((btn) => btn?.classList.add("d-none"));

    // Hide all initially
    hideButtons(btnHold, btnRelease, btnUnrelease, btnAccept);

    const data = window.currentBagData;
    const { crossmatch_result, blood_stock_status, is_approval_incompatible } =
        data;
    if (!data || !data.crossmatch_result) return;

    if (crossmatch_result === "Incompatible") {
        if (blood_stock_status !== "hold") {
            showButtons(btnHold);
        } else if (blood_stock_status === "hold") {
            if (!is_approval_incompatible) {
                showButtons(btnAccept, btnUnrelease);
            } else {
                showButtons(btnRelease, btnUnrelease);
            }
        }
    } else if (crossmatch_result === "Compatible") {
        if (
            blood_stock_status !== "taken_out" &&
            blood_stock_status !== "used"
        ) {
            showButtons(btnRelease, btnUnrelease);
        }
    }
};

function initBagRequestActionButtons() {
    // Helper for fetch actions
    const doAction = async (url, method = "POST") => {
        try {
            const response = await fetch(url, {
                method: method,
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content"),
                },
            });
            const result = await response.json();
            if (!response.ok) {
                notyf.error({ message: result.message || "Action failed!" });
            } else {
                notyf.success({
                    message: result.message || "Action successful!",
                });
                if (
                    listBagRequestTableInstance &&
                    $.fn.DataTable.isDataTable("#list-bag-request-table")
                ) {
                    $("#list-bag-request-table")
                        .DataTable()
                        .ajax.reload(function (json) {
                            // Find the updated bag data and refresh buttons
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
            }
        } catch (error) {
            console.error(error);
            notyf.error({ message: "An error occurred." });
        }
    };

    $(document)
        .off("click", "#btn-hold-blood-pack")
        .on("click", "#btn-hold-blood-pack", function (e) {
            e.preventDefault();
            if (window.currentBagDetailPublicId)
                doAction(
                    `/blood-transfusion/detail/${window.currentBagDetailPublicId}/hold`,
                );
        });

    $(document)
        .off("click", "#btn-release-blood-pack")
        .on("click", "#btn-release-blood-pack", function (e) {
            e.preventDefault();
            if (window.currentBagDetailPublicId)
                doAction(
                    `/blood-transfusion/detail/${window.currentBagDetailPublicId}/release`,
                );
        });

    $(document)
        .off("click", "#btn-unrelease-blood-pack")
        .on("click", "#btn-unrelease-blood-pack", function (e) {
            e.preventDefault();
            if (window.currentBagDetailPublicId)
                doAction(
                    `/blood-transfusion/detail/${window.currentBagDetailPublicId}/unrelease`,
                );
        });

    $(document)
        .off("click", "#btn-print-crossmatch-incompatible")
        .on("click", "#btn-print-crossmatch-incompatible", async function (e) {
            e.preventDefault();
            showPageLoading();

            try {
                const res = await fetch(
                    `${PRINT_URL}/incompatible-letter/${window.currentTransfusionPublicId}`,
                    { method: "GET" },
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

                let iframe = document.getElementById(
                    "__print_preview_iframe__",
                );
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
                        setTimeout(() => {
                            URL.revokeObjectURL(blobUrl);
                            iframe.remove();
                        }, 30_000);
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

    $(document)
        .off("click", "#btn-accept-blood-pack")
        .on("click", "#btn-accept-blood-pack", function (e) {
            e.preventDefault();
            const confirmBtn = document.getElementById(
                "confirm_accept_incompatible",
            );
            if (confirmBtn) {
                confirmBtn.dataset.detailId = window.currentBagDetailPublicId;
            }
            const modalEl = document.getElementById(
                "accept_incompatible_blood_modal",
            );
            if (modalEl) {
                const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
                modal.show();
            }
        });

    $(document)
        .off("click", "#confirm_accept_incompatible")
        .on("click", "#confirm_accept_incompatible", async function (e) {
            e.preventDefault();
            const detailId = this.dataset.detailId;
            if (!detailId) return;

            const originalText = this.innerHTML;
            this.innerHTML =
                '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...';
            this.disabled = true;

            try {
                const response = await fetch(
                    `/blood-transfusion/detail/${detailId}/accept-incompatible`,
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
                        message:
                            result.message ||
                            "Failed to accept incompatible blood!",
                    });
                } else {
                    notyf.success({
                        message:
                            result.message ||
                            "Incompatible blood accepted successfully!",
                    });

                    const modalEl = document.getElementById(
                        "accept_incompatible_blood_modal",
                    );
                    if (modalEl) {
                        const modal =
                            bootstrap.Modal.getOrCreateInstance(modalEl);
                        if (modal) modal.hide();
                    }

                    if (
                        listBagRequestTableInstance &&
                        $.fn.DataTable.isDataTable("#list-bag-request-table")
                    ) {
                        $("#list-bag-request-table")
                            .DataTable()
                            .ajax.reload(function (json) {
                                if (
                                    window.currentBagDetailPublicId &&
                                    json.data
                                ) {
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
                }
            } catch (error) {
                console.error(error);
                notyf.error({ message: "An error occurred." });
            } finally {
                this.innerHTML = originalText;
                this.disabled = false;
            }
        });
}
// ---------- Handle Bag Request Action Buttons :end ----------

document.addEventListener("DOMContentLoaded", function () {
    // Date range picker
    DateRangeFilter();
    DatatableRequestBlood();
    DatatableListBagRequest();
    DatatableListTest();
    initPatientDetail();
    initBagRequestRowClick();
    CheckInLabNumber();
    initDoneButton();
    initBagRequestActionButtons();
});
