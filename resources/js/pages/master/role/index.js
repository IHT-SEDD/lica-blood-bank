import { GlobalSubmitForm, GlobalFormValidation } from "../../../app";

// ---------- Global variable untuk memudahkan penyesuaian :begin ----------
const FormAddSelector = "add_new_role"; // id selector untuk form add new
const FormAddURL = "/master/role"; // url submit form add
const FormEditSelector = "edit_data_role"; // id selector untuk form edit
const ReloadDatatableSelector = "master-role-reload"; // reload datatable index
const ModalEditSelector = "edit_data_master_role_modal"; // id selector untuk modal edit
// ---------- Global variable untuk memudahkan penyesuaian :end ----------

// ---------- Handle form penambahan role baru :begin ----------
function AddNewRole() {
    // ---------- Validasi inputan form :begin ----------
    const AddNewRoleValidation = GlobalFormValidation.init(
        "#" + FormAddSelector,
        {
            name: {
                validators: {
                    notEmpty: {
                        message: "Name is required",
                    },
                },
            },
            guard_name: {
                validators: {
                    notEmpty: {
                        message: "Guard is required",
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
        validator: AddNewRoleValidation,
        onSuccess: (data) => {
            notyf.success({
                message: "New role added succesfully!",
            });
            console.log(data);
            window.dispatchEvent(new Event(ReloadDatatableSelector));
        },
        onError: (err) => {
            notyf.error({
                message: "New role failed to insert!",
            });

            console.error(err);
        },

        resetOnSuccess: true,
    });
    // ---------- Submit form ke url :end ----------
}
// ---------- Handle form penambahan role baru :begin ----------

// ---------- Handle form pembaharuan data role :begin ----------
function EditDataRole() {
    // ---------- Validasi inputan form :begin ----------
    const EditDataRoleValidation = GlobalFormValidation.init(
        "#" + FormEditSelector,
        {
            name: {
                validators: {
                    notEmpty: {
                        message: "Name is required",
                    },
                },
            },
            guard_name: {
                validators: {
                    notEmpty: {
                        message: "Guard is required",
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
        validator: EditDataRoleValidation,
        onSuccess: (data) => {
            notyf.success({
                message: "Data role updated succesfully!",
            });
            window.dispatchEvent(new Event(ReloadDatatableSelector));
            const modalEl = document.getElementById(ModalEditSelector);
            const modal = bootstrap.Modal.getInstance(modalEl);
            modal.hide();
        },
        onError: (err) => {
            notyf.error({
                message: "Data role failed to update!",
            });
        },

        resetOnSuccess: true,
    });
    // ---------- Submit form ke url :end ----------
}
// ---------- Handle form pembaharuan data storage :begin ----------

document.addEventListener("DOMContentLoaded", function () {
    AddNewRole();
    EditDataRole();
});
