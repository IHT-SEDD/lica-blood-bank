import { GlobalSubmitForm, GlobalFormValidation } from "../../../app";

// ---------- Global variable untuk memudahkan penyesuaian :begin ----------
const FormAddSelector = "add_new_vendor"; // id selector untuk form add new
const FormAddURL = "/master/vendor"; // url submit form add
const FormEditSelector = "edit_data_vendor"; // id selector untuk form edit
const ReloadDatatableSelector = "master-vendor-reload"; // reload datatable index
const ModalEditSelector = "edit_data_master_vendor_modal"; // id selector untuk modal edit
// ---------- Global variable untuk memudahkan penyesuaian :end ----------

// ---------- Handle form penambahan data baru :begin ----------
function AddNewVendor() {
    // ---------- Validasi inputan form :begin ----------
    const AddNewVendorValidation = GlobalFormValidation.init(
        "#" + FormAddSelector,
        {
            name: {
                validators: {
                    notEmpty: {
                        message: "Name is required",
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
        validator: AddNewVendorValidation,
        onSuccess: (data) => {
            notyf.success({
                message: "New vendor added succesfully!",
            });
            console.log(data);
            window.dispatchEvent(new Event(ReloadDatatableSelector));
        },
        onError: (err) => {
            notyf.error({
                message: "New vendor failed to insert!",
            });

            console.error(err);
        },

        resetOnSuccess: true,
    });
    // ---------- Submit form ke url :end ----------
}
// ---------- Handle form penambahan data baru :begin ----------

// ---------- Handle form pembaharuan data :begin ----------
function EditDataVendor() {
    // ---------- Validasi inputan form :begin ----------
    const EditDataVendorValidation = GlobalFormValidation.init(
        "#" + FormEditSelector,
        {
            name: {
                validators: {
                    notEmpty: {
                        message: "Name is required",
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
        validator: EditDataVendorValidation,
        onSuccess: (data) => {
            notyf.success({
                message: "Data vendor updated succesfully!",
            });
            window.dispatchEvent(new Event(ReloadDatatableSelector));
            const modalEl = document.getElementById(ModalEditSelector);
            const modal = bootstrap.Modal.getInstance(modalEl);
            modal.hide();
        },
        onError: (err) => {
            notyf.error({
                message: "Data vendor failed to update!",
            });
        },

        resetOnSuccess: true,
    });
    // ---------- Submit form ke url :end ----------
}
// ---------- Handle form pembaharuan data :begin ----------

document.addEventListener("DOMContentLoaded", function () {
    AddNewVendor();
    EditDataVendor();
});
