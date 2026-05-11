// ---------- Import Libraries ----------
import { GlobalAdvanceFlatpickr } from "../../app";
import {
    DatatableRequestBlood,
    listRequestTableInstance,
    DatatableListBagRequest,
    listBagRequestTableInstance,
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
}
// ---------- Filter tanggal dari flatpickr untuk data di tabel :end ----------

// ---------- Menampilkan detail pasien dari row yang diklik :begin ----------
function ShowPatientDetail() {
    $(document).on("click", "#list-request-table tbody tr", function (e) {
        // Abaikan jika yang diklik adalah action dropdown
        if ($(e.target).closest(".dropdown").length > 0) return;

        if (!listRequestTableInstance) return;

        const data = listRequestTableInstance.getRowData(this);
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
        if (
            listBagRequestTableInstance &&
            $.fn.DataTable.isDataTable("#list-bag-request-table")
        ) {
            $("#list-bag-request-table").DataTable().ajax.reload(null, false);
        }
    });
}
// ---------- Menampilkan detail pasien dari row yang diklik :end ----------

// ---------- Handle Check In Lab Number :begin ----------
function CheckInLabNumber() {
    const btnCheckin = document.getElementById("btn-checkin-lab");
    if (!btnCheckin) return;

    btnCheckin.addEventListener("click", async function () {
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
                window.dispatchEvent(new Event("#list-request-table"));
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
    ShowPatientDetail();
    CheckInLabNumber();
});
