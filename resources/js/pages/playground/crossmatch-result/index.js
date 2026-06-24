import { GlobalAdvanceTomselect } from "../../../app";
import {
    DatatableTests,
    DestroyDatatableTests,
    EditCrossmatchAction,
} from "./datatable";

// ---------- Global variable untuk memudahkan penyesuaian ----------
const SelectorChooseOrderNumber = "#choose_order_number";
const SelectorChooseBDRSNumber = "#choose_lab_number";

const URLSelectPO = "/utility/select-manual/order-number";
const URLSelectBdrsNumber = "/utility/select-manual/bdrs-number";
const URLDataTransfusion =
    "/playground/fixing/crossmatch-result/data-transfusion";

// ---------- State gobal ----------
let selectOrderNumber = null;
let selectBdrsNumber = null;
let selectedNoOrder = null;
let selectedNoBDRS = null;

// ---------- HELPERS ----------
function hasValue(value) {
    return value !== null && value !== undefined && value !== "";
}
function updateSelectState() {
    if (hasValue(selectedNoOrder)) {
        selectBdrsNumber?.disable();
    } else {
        selectBdrsNumber?.enable();
    }

    if (hasValue(selectedNoBDRS)) {
        selectOrderNumber?.disable();
    } else {
        selectOrderNumber?.enable();
    }
}
function clearPatientDetail() {
    document.querySelectorAll("[data-patient-detail]").forEach((el) => {
        el.textContent = "-";
    });
}

// ---------- Tom Select ----------
function SelectOrderNumber() {
    const wrapper = new GlobalAdvanceTomselect(SelectorChooseOrderNumber, {
        valueField: "text",
        preload: true,
        blurOnItemAdd: false,
        closeAfterSelect: false,
        noResultsText: "Nomor order tidak ditemukan",
        load(query, callback) {
            fetch(`${URLSelectPO}?q=${encodeURIComponent(query)}`)
                .then((res) => res.json())
                .then((json) => callback(json.results))
                .catch(() => callback());
        },
        onChange(value) {
            selectedNoOrder = value || null;
            if (hasValue(selectedNoOrder)) {
                selectBdrsNumber?.clear(true);
                selectedNoBDRS = null;
                fetchAndPopulate("order_number", selectedNoOrder);
            } else {
                clearPatientDetail();
                DestroyDatatableTests();
            }
            updateSelectState();
        },
    });
    selectOrderNumber = wrapper.getInstances()[0];
}
function SelectBDRSNumber() {
    const wrapper = new GlobalAdvanceTomselect(SelectorChooseBDRSNumber, {
        valueField: "text",
        preload: true,
        noResultsText: "No. BDRS tidak ditemukan",
        load(query, callback) {
            fetch(`${URLSelectBdrsNumber}?q=${encodeURIComponent(query)}`)
                .then((res) => res.json())
                .then((json) => callback(json.results))
                .catch(() => callback());
        },
        onChange(value) {
            selectedNoBDRS = value || null;
            if (hasValue(selectedNoBDRS)) {
                selectOrderNumber?.clear(true);
                selectedNoOrder = null;
                fetchAndPopulate("bdrs_number", selectedNoBDRS);
            } else {
                clearPatientDetail();
                DestroyDatatableTests();
            }
            updateSelectState();
        },
    });
    selectBdrsNumber = wrapper.getInstances()[0];
}
function EditMayorResult() {
    new GlobalAdvanceTomselect("#edit_data_crossmatch_mayor_result", {
        valueField: "id",
        preload: true,
        noResultsText: "Hasil tidak ditemukan",
        load: function (query, callback) {
            fetch(`/utility/select/result-test?q=${encodeURIComponent(query)}`)
                .then((res) => res.json())
                .then((json) => callback(json.results))
                .catch(() => callback());
        },
    });
}
function EditMinorResult() {
    new GlobalAdvanceTomselect("#edit_data_crossmatch_minor_result", {
        valueField: "id",
        preload: true,
        noResultsText: "Hasil tidak ditemukan",
        load: function (query, callback) {
            fetch(`/utility/select/result-test?q=${encodeURIComponent(query)}`)
                .then((res) => res.json())
                .then((json) => callback(json.results))
                .catch(() => callback());
        },
    });
}
function EditAutoControlResult() {
    new GlobalAdvanceTomselect("#edit_data_crossmatch_auto_control_result", {
        valueField: "id",
        preload: true,
        noResultsText: "Hasil tidak ditemukan",
        load: function (query, callback) {
            fetch(`/utility/select/result-test?q=${encodeURIComponent(query)}`)
                .then((res) => res.json())
                .then((json) => callback(json.results))
                .catch(() => callback());
        },
    });
}

// ---------- Populate Patient Detail ----------
function populatePatientDetail(data) {
    const fieldMap = {
        name: data.patient_name ?? "-",
        gender: data.gender ?? "-",
        email: data.email ?? "-",
        age: data.data_patient.patient_age ?? "-",
        blood_group: data.blood_group ?? "-",
        blood_rhesus: data.rhesus ?? "",
        address: data.address ?? "-",
        insurance: data.insurance ?? "-",
        room: data.room ?? "-",
        type_patient: data.type ?? "-",
        doctor: data.doctor ?? "-",
        diagnosis: data.diagnosis ?? "-",
        created_at: data.created_at ?? "-",
        checkedin_by: data.checkin_by ?? "-",
        lab_number: data.bdrs_number ?? "-",
        order_number: data.order_number ?? "-",
    };

    Object.entries(fieldMap).forEach(([key, value]) => {
        document
            .querySelectorAll(`[data-patient-detail="${key}"]`)
            .forEach((el) => {
                el.textContent = value;
            });
    });
}
async function fetchAndPopulate(paramKey, value) {
    try {
        const url = `${URLDataTransfusion}?${paramKey}=${encodeURIComponent(value)}`;
        const response = await fetch(url, {
            headers: { "X-Requested-With": "XMLHttpRequest" },
        });
        const json = await response.json();

        if (!json.success || !json.data) {
            clearPatientDetail();
            DestroyDatatableTests();
            console.warn(
                "[fetchAndPopulate] Data tidak ditemukan:",
                json.message,
            );
            return;
        }
        populatePatientDetail(json.data);
        DatatableTests(json.data.public_id);
    } catch (err) {
        console.error("[fetchAndPopulate] Gagal fetch data transfusion:", err);
        clearPatientDetail();
        DestroyDatatableTests();
    }
}

document.addEventListener("DOMContentLoaded", function () {
    SelectOrderNumber();
    SelectBDRSNumber();
    EditMayorResult();
    EditMinorResult();
    EditAutoControlResult();
    EditCrossmatchAction();
});
