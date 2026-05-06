import TomSelect from "tom-select";
import { GlobalSubmitForm, GlobalFormValidation } from "../../../app";

// ---------- Global variable untuk memudahkan penyesuaian :begin ----------
const FormAddSelector = "add_new_package"; // id selector untuk form add new
const FormAddURL = "/master/package"; // url submit form add
const FormEditSelector = "edit_data_package"; // id selector untuk form edit
const ReloadDatatableSelector = "master-package-reload"; // reload datatable index
const ModalEditSelector = "edit_data_master_package_modal"; // id selector untuk modal edit
// ---------- Global variable untuk memudahkan penyesuaian :end ----------

// ---------- Select blood component dari tom-select untuk form add new data :begin ----------
function SelectBloodComponent() {
    new TomSelect("#select-blood-component", {
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
            fetch(
                `/utility/select/blood-component?q=${encodeURIComponent(query)}`,
            )
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
// ---------- Select blood component dari tom-select untuk form add new data :begin ----------

// ---------- Handle form penambahan user baru :begin ----------
function AddNewPackage() {
    // ---------- Validasi inputan form :begin ----------
    const AddNewPackageValidation = GlobalFormValidation.init(
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
        validator: AddNewPackageValidation,
        onSuccess: (data) => {
            notyf.success({
                message: "New Package added succesfully!",
            });
            console.log(data);
            window.dispatchEvent(new Event(ReloadDatatableSelector));
        },
        onError: (err) => {
            notyf.error({
                message: "New Package failed to insert!",
            });

            console.error(err);
        },

        resetOnSuccess: true,
    });
    // ---------- Submit form ke url :end ----------
}
// ---------- Handle form penambahan user baru :begin ----------

// ---------- Handle form pembaharuan data user :begin ----------
function EditDataPackage() {
    // ---------- Validasi inputan form :begin ----------
    const EditDataPackageValidation = GlobalFormValidation.init(
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
        validator: EditDataPackageValidation,
        onSuccess: (data) => {
            notyf.success({
                message: "Data Package updated succesfully!",
            });
            window.dispatchEvent(new Event(ReloadDatatableSelector));
            const modalEl = document.getElementById(ModalEditSelector);
            const modal = bootstrap.Modal.getInstance(modalEl);
            modal.hide();
        },
        onError: (err) => {
            notyf.error({
                message: "Data Package failed to update!",
            });
        },

        resetOnSuccess: true,
    });
    // ---------- Submit form ke url :end ----------
}
// ---------- Handle form pembaharuan data user :begin ----------

document.addEventListener("DOMContentLoaded", function () {
    // SelectRole();
    SelectBloodComponent();
    AddNewPackage();
    EditDataPackage();
});
