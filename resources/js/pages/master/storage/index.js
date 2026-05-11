import { GlobalSubmitForm, GlobalFormValidation } from "../../../app";

// ---------- Global variable untuk memudahkan penyesuaian :begin ----------
const FormAddSelector = "add_new_storage"; // id selector untuk form add new
const FormAddURL = "/master/storage"; // url submit form add
const FormEditSelector = "edit_data_storage"; // id selector untuk form edit
const ReloadDatatableSelector = "master-storage-reload"; // reload datatable index
const ModalEditSelector = "edit_data_master_storage_modal"; // id selector untuk modal edit
// ---------- Global variable untuk memudahkan penyesuaian :end ----------

// ---------- Handle form penambahan storage baru :begin ----------
function AddNewStorage() {
    // ---------- Validasi inputan form :begin ----------
    const AddNewStorageValidation = GlobalFormValidation.init(
        "#" + FormAddSelector,
        {
            name: {
                validators: {
                    notEmpty: {
                        message: "Name is required",
                    },
                },
            },
            rack_capacity: {
                validators: {
                    notEmpty: {
                        message: "Rack capacity is required",
                    },
                    isNumber: {
                        message: "Rack capacity must be a number",
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
        validator: AddNewStorageValidation,
        onSuccess: (data) => {
            notyf.success({
                message: "New storage added succesfully!",
            });
            console.log(data);
            window.dispatchEvent(new Event(ReloadDatatableSelector));
        },
        onError: (err) => {
            notyf.error({
                message: "New storage failed to insert!",
            });

            console.error(err);
        },

        resetOnSuccess: true,
    });
    // ---------- Submit form ke url :end ----------
}
// ---------- Handle form penambahan storage baru :begin ----------

// ---------- Handle form pembaharuan data storage :begin ----------
function EditDataStorage() {
    // ---------- Validasi inputan form :begin ----------
    const EditDataStorageValidation = GlobalFormValidation.init(
        "#" + FormEditSelector,
        {
            name: {
                validators: {
                    notEmpty: {
                        message: "Name is required",
                    },
                },
            },
            rack_capacity: {
                validators: {
                    notEmpty: {
                        message: "Rack capacity is required",
                    },
                    isNumber: {
                        message: "Rack capacity must be a number",
                    },
                },
            },
        },
    );
    // ---------- Validasi inputan form :end ----------

    // ---------- Submit form ke url :begin ----------
    new GlobalSubmitForm({
        formId: FormEditSelector,
        url: () => {
            const form = document.getElementById(FormEditSelector);
            return FormAddURL + `/${form.dataset.id}`;
        },
        method: "PATCH",
        validator: EditDataStorageValidation,
        onSuccess: (data) => {
            notyf.success({
                message: "Data storage updated succesfully!",
            });
            window.dispatchEvent(new Event(ReloadDatatableSelector));
            const modalEl = document.getElementById(ModalEditSelector);
            const modal = bootstrap.Modal.getInstance(modalEl);
            modal.hide();
        },
        onError: (err) => {
            notyf.error({
                message: "Data storage failed to update!",
            });
        },

        resetOnSuccess: true,
    });
    // ---------- Submit form ke url :end ----------
}
// ---------- Handle form pembaharuan data storage :begin ----------

document.addEventListener("DOMContentLoaded", function () {
    AddNewStorage();
    EditDataStorage();
});
