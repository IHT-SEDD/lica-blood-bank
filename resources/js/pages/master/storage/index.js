import { GlobalSubmitForm, GlobalFormValidation } from "../../../app";

// ---------- Handle form penambahan storage baru :begin ----------
function AddNewStorage() {
    // ---------- Validasi inputan form :begin ----------
    const AddNewStorageValidation = GlobalFormValidation.init(
        "#add_new_storage",
        {
            name: {
                validators: {
                    notEmpty: {
                        message: "Name is required",
                    },
                },
            },
            serial_number: {
                validators: {
                    notEmpty: {
                        message: "Serial Number is required",
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
        formId: "add_new_storage",
        url: "/master/storage",
        validator: AddNewStorageValidation,
        onSuccess: (data) => {
            notyf.success({
                message: "New storage added succesfully!",
            });
            console.log(data);
            window.dispatchEvent(new Event("master-storage-reload"));
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
        "#edit_data_storage",
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
        formId: "edit_data_storage",
        url: () => {
            const form = document.getElementById("edit_data_storage");
            return `/master/storage/${form.dataset.id}`;
        },
        method: "PATCH",
        validator: EditDataStorageValidation,
        onSuccess: (data) => {
            notyf.success({
                message: "Data storage updated succesfully!",
            });
            window.dispatchEvent(new Event("master-storage-reload"));
            const modalEl = document.getElementById(
                "edit_data_master_storage_modal",
            );
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
