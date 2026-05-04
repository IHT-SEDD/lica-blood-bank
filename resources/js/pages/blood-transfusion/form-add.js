// ---------- Import Libraries ----------
import { GlobalAdvanceFlatpickr } from "../../app";
import TomSelect from "tom-select";

// ---------- Global variable untuk memudahkan penyesuaian :begin ----------
// Form
const AddNewBloodRequestForm = "add_new_blood_request";
const AddNewPatientBtn = "add-new-patient-data";

// Patient
const SelectPatientSelector = "#select-patient";
const SelectBloodGroupSelector = "#select-blood-group";
const SelectBloodRhesusSelector = "#select-blood-rhesus";
const SelectRelationTypeSelector = "#select-relation-type";
const PatientBirthdateDatePickerSelector = ".patient-birth-date";

// Transaction
const SelectInsuranceSelector = "#select-insurance";
const SelectRoomSelector = "#select-room";
const SelectDoctorSelector = "#select-doctor";

// Blood Request
const SelectBloodPackSelector = "#select-blood-pack";
const BloodRequiredDatePickerSelector = ".patient-blood-required-date";
const AddNewBloodPackRequestBtn = "add-new-blood-pack-request";
// ---------- Global variable untuk memudahkan penyesuaian :end ----------

// ---------- Tom Select :begin ----------
// Patient
function SelectPatient() {
    const selectPatient = new TomSelect(SelectPatientSelector, {
        valueField: "id",
        labelField: "text",
        searchField: "text",
        preload: true,
        load: function (query, callback) {
            fetch(`/utility/select/patient?q=${encodeURIComponent(query)}`)
                .then((res) => res.json())
                .then((json) => callback(json.results))
                .catch(() => callback());
        },
    });
}
function SelectBloodRhesus() {
    const selectBloodRhesus = new TomSelect(SelectBloodRhesusSelector, {
        valueField: "id",
        labelField: "text",
        searchField: "text",
        preload: true,
        load: function (query, callback) {
            fetch(`/utility/select/blood-rhesus?q=${encodeURIComponent(query)}`)
                .then((res) => res.json())
                .then((json) => callback(json.results))
                .catch(() => callback());
        },
    });
}
function SelectBloodGroup() {
    const selectBloodGroup = new TomSelect(SelectBloodGroupSelector, {
        valueField: "id",
        labelField: "text",
        searchField: "text",
        preload: true,
        load: function (query, callback) {
            fetch(`/utility/select/blood-group?q=${encodeURIComponent(query)}`)
                .then((res) => res.json())
                .then((json) => callback(json.results))
                .catch(() => callback());
        },
    });
}
function SelectRelationType() {
    const selectRelationType = new TomSelect(SelectRelationTypeSelector, {
        valueField: "id",
        labelField: "text",
        searchField: "text",
        preload: true,
        load: function (query, callback) {
            fetch(
                `/utility/select/relation-type?q=${encodeURIComponent(query)}`,
            )
                .then((res) => res.json())
                .then((json) => callback(json.results))
                .catch(() => callback());
        },
    });
}

// Transaction
function SelectInsurance() {
    const selectInsurance = new TomSelect(SelectInsuranceSelector, {
        valueField: "id",
        labelField: "text",
        searchField: "text",
        preload: true,
        load: function (query, callback) {
            fetch(`/utility/select/insurance?q=${encodeURIComponent(query)}`)
                .then((res) => res.json())
                .then((json) => callback(json.results))
                .catch(() => callback());
        },
    });
}
function SelectRoom() {
    const selectRoom = new TomSelect(SelectRoomSelector, {
        valueField: "id",
        labelField: "text",
        searchField: "text",
        preload: true,
        load: function (query, callback) {
            fetch(`/utility/select/room?q=${encodeURIComponent(query)}`)
                .then((res) => res.json())
                .then((json) => callback(json.results))
                .catch(() => callback());
        },
    });
}
function SelectDoctor() {
    const selectDoctor = new TomSelect(SelectDoctorSelector, {
        valueField: "id",
        labelField: "text",
        searchField: "text",
        preload: true,
        load: function (query, callback) {
            fetch(`/utility/select/doctor?q=${encodeURIComponent(query)}`)
                .then((res) => res.json())
                .then((json) => callback(json.results))
                .catch(() => callback());
        },
    });
}

// Blood Request
function SelectBloodPack() {
    const selectBloodPack = new TomSelect(SelectBloodPackSelector, {
        valueField: "id",
        labelField: "text",
        searchField: "text",
        preload: true,
        load: function (query, callback) {
            fetch(`/utility/select/blood-pack?q=${encodeURIComponent(query)}`)
                .then((res) => res.json())
                .then((json) => callback(json.results))
                .catch(() => callback());
        },
    });
}
// ---------- Tom Select :begin ----------

// --------- Datepicker :begin ----------
// Patient
function PatientBirthdateDatepicker() {
    new GlobalAdvanceFlatpickr(PatientBirthdateDatePickerSelector, {
        maxDate: "today",
    });
}

// Blood Request
function BloodRequiredDatePicker() {
    new GlobalAdvanceFlatpickr(
        BloodRequiredDatePickerSelector,
    );
}
// --------- Datepicker :end ----------

document.addEventListener("DOMContentLoaded", function () {
    // Tom Select
    SelectPatient();
    SelectBloodRhesus();
    SelectBloodGroup();
    SelectRelationType();
    SelectInsurance();
    // SelectRoom();
    SelectDoctor();
    SelectBloodPack();

    // Datepicker
    PatientBirthdateDatepicker();
    BloodRequiredDatePicker();
});
