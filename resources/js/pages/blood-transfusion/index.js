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
// ---------- Global variable untuk memudahkan penyesuaian :end ----------

// ---------- Filter tanggal dari flatpickr untuk data di tabel :begin ----------
function DateRangeFilter() {
    new GlobalAdvanceFlatpickr(DateFilterSelector, {
        maxDate: "today",
    });

    $(document)
        .off("change", DateFilterSelector)
        .on("change", DateFilterSelector, function () {
            console.log(123);

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

    // Toggle Check In button based on lab_number
    const btnCheckin = document.getElementById("btn-checkin-lab");
    if (btnCheckin) {
        if (data.lab_number && data.lab_number !== "-") {
            btnCheckin.classList.add("d-none");
        } else {
            btnCheckin.classList.remove("d-none");
            btnCheckin.dataset.id = data.public_id;
        }
    }

    // Update list bag request table
    window.currentTransfusionPublicId = data.public_id;
    window.currentBagDetailPublicId = null; // Reset bag filter when switching transfusion
    window.currentBagTransfusionResult = null;
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
            updatePatientDetailUI(data); // Panggil fungsi update
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
            window.currentBagTransfusionResult =
                data.transfusion_result || null;

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
    const btnCheckin = document.getElementById("btn-checkin-lab");
    if (!btnCheckin) return;

    const newBtn = btnCheckin.cloneNode(true);
    btnCheckin.parentNode.replaceChild(newBtn, btnCheckin);

    newBtn.addEventListener("click", async function () {
        const id = this.dataset.id;
        if (!id) return;
        console.log("clicked");

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
});

// ---------- Handle Done Button :begin ----------
function initDoneButton() {
    const btn = document.getElementById("btn-test-done");
    if (!btn) return;

    const newBtn = btn.cloneNode(true);
    btn.parentNode.replaceChild(newBtn, btn);

    // Initially disabled
    newBtn.disabled = true;

    newBtn.addEventListener("click", function () {
        completeTest();
    });
}
// ---------- Handle Done Button :end ----------
