// ---------- Import Libraries ----------
import {
    GlobalAdvanceFlatpickr,
    GlobalSubmitForm,
    GlobalFormValidation,
} from "../../app";
import { clearSelectedBloodPacks } from "./datatable-blood-pack";
import {
    initSelectPatient,
    initSelectBloodGroup,
    initSelectBloodRhesus,
    initSelectRelationType,
    initSelectInsurance,
    initSelectRoom,
    initSelectDoctor,
    initSelectBloodPack,
} from "./form-add/select";

// ---------- Global variable untuk memudahkan penyesuaian :begin ----------
// Form
const FormAddSelector = "add_new_blood_request";
const AddNewPatientBtn = "add-new-patient-data";
const FormAddURL = "/blood-transfusion/store";

// Patient
const PatientBirthdateDatePickerSelector = ".patient-birth-date";

// Blood Request
const BloodRequiredDatePickerSelector = ".patient-blood-required-date";
// ---------- Global variable untuk memudahkan penyesuaian :end ----------

// ---------- TomSelect instances ----------
let PatientSelectInstance;
let BloodGroupSelectInstance;
let BloodRhesusSelectInstance;
let RelationTypeSelectInstance;

// ---------- Flatpickr instance ----------
let birthdateFlatpickrInstance;

// ---------- Global State ----------
let isCreatingNewPatient = false;

// --------- Datepicker :begin ----------
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
function BloodRequiredDatePicker() {
    new GlobalAdvanceFlatpickr(BloodRequiredDatePickerSelector, {
        dateFormat: "Y-m-d H:i",
        static: true,
        enableTime: true,
    });
}
// --------- Datepicker :end ----------

// --------- Patient inputs helper ----------
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

    document.querySelectorAll('input[name="gender"]').forEach((radio) => {
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

    document.querySelectorAll('input[name="gender"]').forEach((radio) => {
        radio.checked = false;
    });

    if (BloodGroupSelectInstance) BloodGroupSelectInstance.clear();
    if (BloodRhesusSelectInstance) BloodRhesusSelectInstance.clear();
    if (RelationTypeSelectInstance) RelationTypeSelectInstance.clear();

    const birthdateField = document.querySelector(
        PatientBirthdateDatePickerSelector,
    );
    if (birthdateField) birthdateField.value = "";
}

// --------- Select patient handler ----------
function setupSelectPatient() {
    const result = initSelectPatient();
    if (!result) return;

    PatientSelectInstance = result.instance;

    PatientSelectInstance.on("change", function (value) {
        if (!value) return;

        fetch(`/utility/get/patient/${value}`)
            .then((res) => res.json())
            .then((data) => {
                const rawDate = data.birthdate || data.birth_date || "";
                const dateOnly = rawDate ? rawDate.split("T")[0] : "";

                document.getElementById("medrec").value = data.medrec || "";
                document.getElementById("name").value = data.name || "";
                document.getElementById("email").value = data.email || "";
                document.getElementById("phone_number").value =
                    data.phone || "";
                document.getElementById("birthdate").value = dateOnly;
                document.getElementById("relation_name").value =
                    data.relation_name || "";
                document.getElementById("address").value = data.address || "";

                document
                    .querySelectorAll('input[name="gender"]')
                    .forEach((radio) => {
                        radio.checked = radio.value === data.gender;
                    });

                if (data.blood_group && BloodGroupSelectInstance) {
                    BloodGroupSelectInstance.setValue(data.blood_group);
                }
                if (data.blood_rhesus && BloodRhesusSelectInstance) {
                    BloodRhesusSelectInstance.setValue(data.blood_rhesus);
                }
                if (data.relation_type && RelationTypeSelectInstance) {
                    RelationTypeSelectInstance.setValue(data.relation_type);
                }
                if (birthdateFlatpickrInstance && dateOnly) {
                    birthdateFlatpickrInstance.setDate(dateOnly, true, "Y-m-d");
                }

                setPatientDetailDisabled(true);
            })
            .catch(() => {});
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
        isCreatingNewPatient = true;

        if (PatientSelectInstance) {
            PatientSelectInstance.clear();
            PatientSelectInstance.disable();
        }
        if (selectPatient) selectPatient.disabled = true;
        if (medrecContainer) medrecContainer.classList.add("d-none");

        clearPatientDetails();
        setPatientDetailDisabled(false);

        btn.textContent = "Cancel";
        btn.classList.remove("btn-soft-primary");
        btn.classList.add("btn-danger");
    } else {
        isCreatingNewPatient = false;

        if (PatientSelectInstance) PatientSelectInstance.enable();
        if (selectPatient) selectPatient.disabled = false;
        if (medrecContainer) medrecContainer.classList.remove("d-none");

        setPatientDetailDisabled(false);

        btn.textContent = "Add New";
        btn.classList.remove("btn-danger");
        btn.classList.add("btn-soft-primary");
    }
}
// ---------- Add New Patient Toggle :end ----------

// ---------- Handle form Blood Request :begin ----------
function AddNewBloodRequest() {
    const AddNewBloodRequestValidation = GlobalFormValidation.init(
        "#" + FormAddSelector,
        {
            name: {
                validators: {
                    notEmpty: { message: "Name is required" },
                },
            },
            birthdate: {
                validators: {
                    notEmpty: { message: "Birthdate is required" },
                },
            },
            gender: {
                validators: {
                    notEmpty: { message: "Gender is required" },
                },
            },
            doctor_id: {
                validators: {
                    notEmpty: { message: "Doctor is required" },
                },
            },
            room_id: {
                validators: {
                    notEmpty: { message: "Room is required" },
                },
            },
            insurance_id: {
                validators: {
                    notEmpty: { message: "Insurance is required" },
                },
            },
            blood_required_at: {
                validators: {
                    notEmpty: { message: "Blood request at is required" },
                },
            },
        },
    );

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
                    const tabLink = document.querySelector(
                        `[data-wizard-nav] a[href="#${tabPane.id}"]`,
                    );
                    if (tabLink) new bootstrap.Tab(tabLink).show();
                }
            }
        },
        onSuccess: () => {
            notyf.success({ message: "New Blood Request added successfully!" });

            // Reset wizard ke step 1
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

            clearSelectedBloodPacks();

            const modalEl = document.getElementById("add_blood_request_modal");
            if (modalEl) {
                const modal = bootstrap.Modal.getInstance(modalEl);
                if (modal) modal.hide();
            }

            window.dispatchEvent(new Event("#list-request-table"));
        },
        onError: (err) => {
            notyf.error({ message: err.message });
            console.error(err);
        },
        resetOnSuccess: true,
    });
}
// ---------- Handle form Blood Request :begin ----------

document.addEventListener("DOMContentLoaded", function () {
    AddNewBloodRequest();

    document
        .getElementById(AddNewPatientBtn)
        .addEventListener("click", toggleAddNewPatient);

    setupSelectPatient();

    // ---------- Init saat modal dibuka :begin ----------
    const modalEl = document.getElementById("add_blood_request_modal");
    if (!modalEl) return;

    let isInitialized = false; // guard agar hanya init sekali

    modalEl.addEventListener("show.bs.modal", function () {
        if (isInitialized) return;
        isInitialized = true;

        // TomSelect — Patient
        setupSelectPatient();

        // TomSelect — ambil instance untuk dipakai di helpers
        const bloodGroupResult = initSelectBloodGroup();
        if (bloodGroupResult)
            BloodGroupSelectInstance = bloodGroupResult.instance;

        const bloodRhesusResult = initSelectBloodRhesus();
        if (bloodRhesusResult)
            BloodRhesusSelectInstance = bloodRhesusResult.instance;

        const relationTypeResult = initSelectRelationType();
        if (relationTypeResult)
            RelationTypeSelectInstance = relationTypeResult.instance;

        // TomSelect — sisanya tidak perlu instance disimpan
        initSelectInsurance();
        initSelectRoom();
        initSelectDoctor();
        initSelectBloodPack();

        // Datepicker
        PatientBirthdateDatepicker();
        BloodRequiredDatePicker();
    });
    // ---------- Init saat modal dibuka :end ----------
});
