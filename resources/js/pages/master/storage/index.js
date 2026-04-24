import { GlobalPostForm, GlobalFormValidation } from "../../../app";

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
    new GlobalPostForm({
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

document.addEventListener("DOMContentLoaded", function () {
    AddNewStorage();
});
