// ---------- Import Libraries ----------
import { log } from "handlebars/runtime";
import {
    GlobalAdvanceFlatpickr,
    GlobalSubmitForm,
    GlobalFormValidation,
} from "../../app";
import TomSelect from "tom-select";
import { clearSelectedBloodPacks } from "./datatable-blood-pack";

// ---------- Global variable untuk memudahkan penyesuaian :begin ----------
// Form
const FormAddSelector = "add_new_blood_request";
const AddNewPatientBtn = "add-new-patient-data";
const FormAddURL = "/blood-transfusion/store"; // url submit form add

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

// Select Blood Group and Rhesus
let PatientSelectInstance;
let BloodGroupSelectInstance;
let BloodRhesusSelectInstance;
let RelationTypeSelectInstance;
let isCreatingNewPatient = false;

// set variable global
let birthdateFlatpickrInstance;

// ---------- Tom Select Begin ----------

function SelectBloodRhesus() {
    BloodRhesusSelectInstance = initializeTomSelect(
        SelectBloodRhesusSelector,
        "/utility/select/blood-rhesus",
    );
}
function SelectBloodGroup() {
    BloodGroupSelectInstance = initializeTomSelect(
        SelectBloodGroupSelector,
        "/utility/select/blood-group",
    );
}
function SelectRelationType() {
    RelationTypeSelectInstance = initializeTomSelect(
        SelectRelationTypeSelector,
        "/utility/select/relation-type",
    );
}

// Transaction
function SelectInsurance() {
    initializeTomSelect(SelectInsuranceSelector, "/utility/select/insurance");
}
function SelectRoom() {
    initializeTomSelect(SelectRoomSelector, "/utility/select/room");
}
function SelectDoctor() {
    initializeTomSelect(SelectDoctorSelector, "/utility/select/doctor");
}

// Blood Request
function SelectBloodPack() {
    initializeTomSelect(SelectBloodPackSelector, "/utility/select/blood-pack");
}
// ---------- Tom Select :end ----------

// ---------- Tom Select :begin ----------
// Generic function for initializing TomSelect
function initializeTomSelect(selector, endpoint) {
    // Check if element exists before initializing
    if (!document.querySelector(selector)) {
        return;
    }

    return new TomSelect(selector, {
        valueField: "id",
        labelField: "text",
        searchField: "text",
        preload: true,
        load: function (query, callback) {
            fetch(`${endpoint}?q=${encodeURIComponent(query)}`)
                .then((res) => res.json())
                .then((json) => callback(json.results))
                .catch(() => callback());
        },
    });
}

// --------- Datepicker :begin ----------
// Patient
function PatientBirthdateDatepicker() {
    birthdateFlatpickrInstance = new GlobalAdvanceFlatpickr(
        PatientBirthdateDatePickerSelector,
        {
            maxDate: "today",
            dateFormat: "Y-m-d",
            static: true,
        },
    );
}

// Blood Request
function BloodRequiredDatePicker() {
    new GlobalAdvanceFlatpickr(BloodRequiredDatePickerSelector, {
        dateFormat: "Y-m-d H:i",
        static: true,
        enableTime: true,
    });
}
// --------- Datepicker :end ----------

// Patient
function setPatientDetailDisabled(isDisabled) {
    const patientFieldIds = [
        "medrec",
        "name",
        "email",
        "phone_number",
        "birth_date",
        "relation_name",
        "address",
        "select-blood-group",
        "select-blood-rhesus",
        "select-relation-type",
    ];

    patientFieldIds.forEach((id) => {
        const field = document.getElementById(id);
        if (field) field.disabled = isDisabled;
    });

    const genderRadios = document.querySelectorAll('input[name="gender"]');
    genderRadios.forEach((radio) => {
        radio.disabled = isDisabled;
    });

    if (BloodGroupSelectInstance) {
        isDisabled
            ? BloodGroupSelectInstance.disable()
            : BloodGroupSelectInstance.enable();
    }
    if (BloodRhesusSelectInstance) {
        isDisabled
            ? BloodRhesusSelectInstance.disable()
            : BloodRhesusSelectInstance.enable();
    }
    if (RelationTypeSelectInstance) {
        isDisabled
            ? RelationTypeSelectInstance.disable()
            : RelationTypeSelectInstance.enable();
    }
}

function clearPatientDetails() {
    const patientFieldIds = [
        "medrec",
        "name",
        "email",
        "phone_number",
        "birth_date",
        "relation_name",
        "address",
    ];

    patientFieldIds.forEach((id) => {
        const field = document.getElementById(id);
        if (field) field.value = "";
    });

    const genderRadios = document.querySelectorAll('input[name="gender"]');
    genderRadios.forEach((radio) => {
        radio.checked = false;
    });

    if (BloodGroupSelectInstance) {
        BloodGroupSelectInstance.clear();
    }
    if (BloodRhesusSelectInstance) {
        BloodRhesusSelectInstance.clear();
    }
    if (RelationTypeSelectInstance) {
        RelationTypeSelectInstance.clear();
    }

    // Clear birthdate field
    const birthdateField = document.querySelector(
        PatientBirthdateDatePickerSelector,
    );
    if (birthdateField) {
        birthdateField.value = "";
    }
}

function SelectPatient() {
    // Check if element exists before initializing
    if (!document.querySelector(SelectPatientSelector)) {
        return;
    }

    PatientSelectInstance = new TomSelect(SelectPatientSelector, {
        valueField: "id",
        labelField: "text",
        searchField: "text",
        plugins: ["clear_button"],
        preload: true,
        load: function (query, callback) {
            fetch(`/utility/select/patient?q=${encodeURIComponent(query)}`)
                .then((res) => res.json())
                .then((json) => callback(json.results))
                .catch(() => callback());
        },
    });

    // Add event listener for when patient is selected
    PatientSelectInstance.on("change", function (value) {
        if (value) {
            // Fetch patient data
            fetch(`/utility/get/patient/${value}`)
                .then((res) => res.json())
                .then((data) => {
                    // Fill patient details
                    const rawDate = data.birthdate || data.birth_date || "";
                    const dateOnly = rawDate ? rawDate.split("T")[0] : "";
                    document.getElementById("medrec").value = data.medrec || "";
                    document.getElementById("name").value = data.name || "";
                    document.getElementById("email").value = data.email || "";
                    document.getElementById("phone_number").value =
                        data.phone || "";
                    document.getElementById("birthdate").value = dateOnly || "";
                    document.getElementById("relation_name").value =
                        data.relation_name || "";
                    document.getElementById("address").value =
                        data.address || "";

                    // Set gender
                    const genderRadios = document.querySelectorAll(
                        'input[name="gender"]',
                    );
                    genderRadios.forEach((radio) => {
                        radio.checked = radio.value === data.gender;
                    });

                    // Set selects if needed
                    if (data.blood_group) {
                        BloodGroupSelectInstance.setValue(data.blood_group);
                    }
                    if (data.blood_rhesus) {
                        BloodRhesusSelectInstance.setValue(data.blood_rhesus);
                    }
                    if (data.relation_type && RelationTypeSelectInstance) {
                        RelationTypeSelectInstance.setValue(data.relation_type);
                    }

                    if (birthdateFlatpickrInstance && dateOnly) {
                        birthdateFlatpickrInstance.setDate(
                            dateOnly,
                            true,
                            "Y-m-d",
                        );
                    }

                    setPatientDetailDisabled(true);
                })
                .catch(() => {
                    // Handle error
                });
        }
    });
}

// ---------- Add New Patient Toggle :begin ----------
function toggleAddNewPatient() {
    const btn = document.getElementById(AddNewPatientBtn);
    const selectPatient = document.getElementById("select-patient");
    const medrecContainer = document
        .getElementById("medrec")
        ?.closest(".col-xxl-6");

    if (!isCreatingNewPatient) {
        // Switch to add new mode: disable choose patient and clear existing patient values
        isCreatingNewPatient = true;
        if (PatientSelectInstance) {
            PatientSelectInstance.clear();
            PatientSelectInstance.disable();
        }
        if (selectPatient) {
            selectPatient.disabled = true;
        }
        if (medrecContainer) {
            medrecContainer.classList.add("d-none");
        }
        clearPatientDetails();
        setPatientDetailDisabled(false);
        btn.textContent = "Cancel";
        btn.classList.remove("btn-soft-primary");
        btn.classList.add("btn-danger");
    } else {
        // Switch back to select mode: enable choose patient again
        isCreatingNewPatient = false;
        if (PatientSelectInstance) {
            PatientSelectInstance.enable();
        }
        if (selectPatient) {
            selectPatient.disabled = false;
        }
        if (medrecContainer) {
            medrecContainer.classList.remove("d-none");
        }
        setPatientDetailDisabled(false);
        btn.textContent = "Add New";
        btn.classList.remove("btn-danger");
        btn.classList.add("btn-soft-primary");
    }
}
// ---------- Add New Patient Toggle :end ----------

// ---------- Handle form Blood Request :begin ----------
function AddNewBloodRequest() {
    // ---------- Validasi inputan form :begin ----------
    const AddNewBloodRequestValidation = GlobalFormValidation.init(
        "#" + FormAddSelector,
        {
            name: {
                validators: {
                    notEmpty: {
                        message: "Name is required",
                    },
                },
            },
            birthdate: {
                validators: {
                    notEmpty: {
                        message: "Birthdate is required",
                    },
                },
            },
            gender: {
                validators: {
                    notEmpty: {
                        message: "Gender is required",
                    },
                },
            },
            doctor_id: {
                validators: {
                    notEmpty: {
                        message: "Doctor is required",
                    },
                },
            },
            room_id: {
                validators: {
                    notEmpty: {
                        message: "Room is required",
                    },
                },
            },
            insurance_id: {
                validators: {
                    notEmpty: {
                        message: "Insurance is required",
                    },
                },
            },
            blood_required_at: {
                validators: {
                    notEmpty: {
                        message: "Blood request at is required",
                    },
                },
            },
        },
    );
    // ---------- Validasi inputan form :end ----------

    // ---------- Submit form ke url :begin ----------
    new GlobalSubmitForm({
        formId: FormAddSelector,
        url: FormAddURL,
        validator: AddNewBloodRequestValidation,
        onValidationError: () => {
            const firstError = document.querySelector(
                "#" + FormAddSelector + " .is-invalid",
            );
            if (firstError) {
                const tabPane = firstError.closest(".tab-pane");
                if (tabPane) {
                    const tabId = tabPane.id;
                    const tabLink = document.querySelector(
                        `[data-wizard-nav] a[href="#${tabId}"]`,
                    );
                    if (tabLink) {
                        new bootstrap.Tab(tabLink).show();
                    }
                }
            }
        },
        onSuccess: (data) => {
            notyf.success({
                message: "New Blood Request added succesfully!",
            });

            // Reset wizard to step 1
            const wizardTabs = document.querySelectorAll(
                "#add_new_blood_request [data-wizard-nav] .nav-link",
            );
            if (wizardTabs.length > 0) {
                new bootstrap.Tab(wizardTabs[0]).show();
                wizardTabs.forEach((tab, index) => {
                    if (index > 0) tab.classList.add("disabled");
                    tab.classList.remove("wizard-item-done");
                });
            }

            // Clear selected blood packs
            clearSelectedBloodPacks();

            // Hide Modal
            const modalEl = document.getElementById("add_blood_request_modal");
            if (modalEl) {
                const modal = bootstrap.Modal.getInstance(modalEl);
                if (modal) {
                    modal.hide();
                }
            }

            window.dispatchEvent(new Event("#list-request-table"));
        },
        onError: (err) => {
            notyf.error({
                message: err.message,
            });

            console.error(err);
        },

        resetOnSuccess: true,
    });
    // ---------- Submit form ke url :end ----------
}
// ---------- Handle form Blood Request :begin ----------

document.addEventListener("DOMContentLoaded", function () {
    // Tom Select
    SelectPatient();
    SelectBloodRhesus();
    SelectBloodGroup();
    SelectRelationType();
    SelectInsurance();
    SelectRoom();
    SelectDoctor();
    SelectBloodPack();
    AddNewBloodRequest();

    // Datepicker
    PatientBirthdateDatepicker();
    BloodRequiredDatePicker();

    // Add New Patient Toggle
    document
        .getElementById(AddNewPatientBtn)
        .addEventListener("click", toggleAddNewPatient);
});
