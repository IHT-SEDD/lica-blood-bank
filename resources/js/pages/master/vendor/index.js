import TomSelect from "tom-select";
import { GlobalSubmitForm, GlobalFormValidation } from "../../../app";

const FormAddSelector = "add_new_vendor";
const FormAddURL = "/master/vendor";
const FormEditSelector = "edit_data_vendor";
const ReloadDatatableSelector = "master-vendor-reload";
const ModalEditSelector = "edit_data_master_vendor_modal";

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
