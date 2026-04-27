import TomSelect from "tom-select";
import { GlobalSubmitForm, GlobalFormValidation } from "../../../app";

// ---------- Select role dari tom-select untuk form add new data :begin ----------
function SelectRole() {
    new TomSelect("#select-role", {
        valueField: "id",
        labelField: "text",
        searchField: "text",
        sortField: {
            field: "id",
            direction: "asc",
        },
        create: false,
        preload: true,
        load: function (query, callback) {
            fetch(`/utility/select/role?q=${encodeURIComponent(query)}`)
                .then((response) => response.json())
                .then((json) => {
                    callback(json.results);
                })
                .catch(() => {
                    callback();
                });
        },
    });
}
// ---------- Select role dari tom-select untuk form add new data :begin ----------

// ---------- Handle form penambahan user baru :begin ----------
function AddNewUser() {
    // ---------- Validasi inputan form :begin ----------
    const AddNewUserValidation = GlobalFormValidation.init("#add_new_user", {
        name: {
            validators: {
                notEmpty: {
                    message: "Name is required",
                },
            },
        },
        username: {
            validators: {
                notEmpty: {
                    message: "Username is required",
                },
            },
        },
        password: {
            validators: {
                notEmpty: {
                    message: "Password is required",
                },
                stringLength: {
                    min: 5,
                    message: "Password minimum is 5 characters",
                },
            },
        },
        role: {
            validators: {
                notEmpty: {
                    message: "Role is required",
                },
            },
        },
    });
    // ---------- Validasi inputan form :end ----------

    // ---------- Submit form ke url :begin ----------
    new GlobalSubmitForm({
        formId: "add_new_user",
        url: "/master/user",
        validator: AddNewUserValidation,
        onSuccess: (data) => {
            notyf.success({
                message: "New user added succesfully!",
            });
            console.log(data);
            window.dispatchEvent(new Event("master-user-reload"));
        },
        onError: (err) => {
            notyf.error({
                message: "New user failed to insert!",
            });

            console.error(err);
        },

        resetOnSuccess: true,
    });
    // ---------- Submit form ke url :end ----------
}
// ---------- Handle form penambahan user baru :begin ----------

// ---------- Handle form pembaharuan data user :begin ----------
function EditDataUser() {
    // ---------- Validasi inputan form :begin ----------
    const EditDataUserValidation = GlobalFormValidation.init(
        "#edit_data_user",
        {
            name: {
                validators: {
                    notEmpty: {
                        message: "Name is required",
                    },
                },
            },
            username: {
                validators: {
                    notEmpty: {
                        message: "Username is required",
                    },
                },
            },
            role: {
                validators: {
                    notEmpty: {
                        message: "Role is required",
                    },
                },
            },
        },
    );
    // ---------- Validasi inputan form :end ----------

    // ---------- Submit form ke url :begin ----------
    new GlobalSubmitForm({
        formId: "edit_data_user",
        url: () => {
            const form = document.getElementById("edit_data_user");
            return `/master/user/${form.dataset.id}`;
        },
        method: "PATCH",
        validator: EditDataUserValidation,
        onSuccess: (data) => {
            notyf.success({
                message: "Data user updated succesfully!",
            });
            window.dispatchEvent(new Event("master-user-reload"));
            const modalEl = document.getElementById(
                "edit_data_master_user_modal",
            );
            const modal = bootstrap.Modal.getInstance(modalEl);
            modal.hide();
        },
        onError: (err) => {
            notyf.error({
                message: "Data user failed to update!",
            });
        },

        resetOnSuccess: true,
    });
    // ---------- Submit form ke url :end ----------
}
// ---------- Handle form pembaharuan data user :begin ----------

document.addEventListener("DOMContentLoaded", function () {
    SelectRole();
    AddNewUser();
    EditDataUser();
});
