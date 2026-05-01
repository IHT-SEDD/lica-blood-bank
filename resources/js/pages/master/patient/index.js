import { GlobalSubmitForm, GlobalFormValidation, GlobalAdvanceFlatpickr } from "../../../app";

// ---------- Global variable untuk memudahkan penyesuaian :begin ----------
const FormAddSelector = "add_new_patient"; // id selector untuk form add new
const FormAddURL = "/master/patient"; // url submit form add
const FormEditSelector = "edit_data_patient"; // id selector untuk form edit
const ReloadDatatableSelector = "master-patient-reload"; // reload datatable index
const ModalEditSelector = "edit_data_master_patient_modal"; // id selector untuk modal edit
// ---------- Global variable untuk memudahkan penyesuaian :end ----------

// ---------- Handle form penambahan patient baru :begin ----------
function AddNewPatient() {
    // ---------- Validasi inputan form :begin ----------
    const AddNewPatientValidation = GlobalFormValidation.init(
        "#" + FormAddSelector,
        {
            name: {
                validators: {
                    notEmpty: {
                        message: "Name is required",
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
            birthdate: {
                validators: {
                    notEmpty: {
                        message: "Birthdate is required",
                    },
                },
            },
              phone : {
                validators: {
                    isNumber : {
                        message : "Phone Number must be a number"
                    }
                }
            }
        },
    );
    // ---------- Validasi inputan form :end ----------

    // ---------- Submit form ke url :begin ----------
    new GlobalSubmitForm({
        formId: FormAddSelector,
        url: FormAddURL,
        validator: AddNewPatientValidation,
        onSuccess: (data) => {
            notyf.success({
                message: "New patient added succesfully!",
            });
            window.dispatchEvent(new Event(ReloadDatatableSelector));
        },
        onError: (err) => {
            notyf.error({
                message: "New patient failed to insert!",
            });
            console.error(err);
        },
        resetOnSuccess: true,
    });
    // ---------- Submit form ke url :end ----------
}
// ---------- Handle form penambahan patient baru :begin ----------

// ---------- Handle form pembaharuan data patient :begin ----------
function EditDataPatient() {
    const EditDataPatientValidation = GlobalFormValidation.init(
        "#" + FormEditSelector,
        {
            name: {
                validators: {
                    notEmpty: {
                        message: "Name is required",
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
            birthdate: {
                validators: {
                    notEmpty: {
                        message: "Birthdate is required",
                    },
                },
            },
            phone : {
                validators: {
                    isNumber : {
                        message : "Phone Number must be a number"
                    }
                }
            }
        },
    );

    new GlobalSubmitForm({
        formId: FormEditSelector,
        url: () => {
            const form = document.getElementById(FormEditSelector);
            return FormAddURL + `/${form.dataset.id}`;
        },
        method: "PATCH",
        validator: EditDataPatientValidation,
        onSuccess: () => {
            notyf.success({
                message: "Patient updated succesfully!",
            });
            window.dispatchEvent(new Event(ReloadDatatableSelector));
            const modalEl = document.getElementById(ModalEditSelector);
            const modal = bootstrap.Modal.getInstance(modalEl);
            modal.hide();
        },
        onError: (err) => {
            notyf.error({
                message: "Patient failed to update!",
            });
            console.error(err);
        },
        resetOnSuccess: true,
    });
}
// ---------- Handle form pembaharuan data patient :begin ----------

document.addEventListener("DOMContentLoaded", function () {
    // ---------- Inisialisasi GlobalAdvanceFlatpickr untuk birthdate :begin ----------
    new GlobalAdvanceFlatpickr('.birthdate', {
          dateFormat: "Y-m-d",
            maxDate: "today",
    });
    // ---------- Inisialisasi GlobalAdvanceFlatpickr untuk birthdate :end ----------

    AddNewPatient();
    EditDataPatient();
});
