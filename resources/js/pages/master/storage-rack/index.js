import TomSelect from "tom-select";
import { GlobalSubmitForm, GlobalFormValidation } from "../../../app";

const FormAddSelector = "add_new_storage_rack";
const FormAddURL = "/master/storage-rack";
const FormEditSelector = "edit_data_storage_rack";
const ReloadDatatableSelector = "master-storage-rack-reload";
const ModalEditSelector = "edit_data_master_storage_rack_modal";

// ---------- Select storage dari tom-select untuk form add new data :begin ----------
function SelectStorage() {
    new TomSelect("#select-storage", {
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
            fetch(`/utility/select/storage?q=${encodeURIComponent(query)}`)
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
// ---------- Select storage dari tom-select untuk form add new data :begin ----------

// ---------- Handle form penambahan data baru :begin ----------
function AddNewStorageRack() {
    // ---------- Validasi inputan form :begin ----------
    const AddNewStorageRackValidation = GlobalFormValidation.init(
        "#" + FormAddSelector,
        {
            name: {
                validators: {
                    notEmpty: {
                        message: "Name is required",
                    },
                },
            },
            storage: {
                validators: {
                    notEmpty: {
                        message: "Storage is required",
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
        validator: AddNewStorageRackValidation,
        onSuccess: (data) => {
            notyf.success({
                message: "New storage rack added succesfully!",
            });
            console.log(data);
            window.dispatchEvent(new Event(ReloadDatatableSelector));
        },
        onError: (err) => {
            notyf.error({
                message: "New storage rack failed to insert!",
            });

            console.error(err);
        },

        resetOnSuccess: true,
    });
    // ---------- Submit form ke url :end ----------
}
// ---------- Handle form penambahan data baru :begin ----------

// ---------- Handle form pembaharuan data :begin ----------
function EditDataStorageRack() {
    // ---------- Validasi inputan form :begin ----------
    const EditDataStorageRackValidation = GlobalFormValidation.init(
        "#" + FormEditSelector,
        {
            name: {
                validators: {
                    notEmpty: {
                        message: "Name is required",
                    },
                },
            },
            storage: {
                validators: {
                    notEmpty: {
                        message: "Storage is required",
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
        validator: EditDataStorageRackValidation,
        onSuccess: (data) => {
            notyf.success({
                message: "Data storage rack updated succesfully!",
            });
            window.dispatchEvent(new Event(ReloadDatatableSelector));
            const modalEl = document.getElementById(ModalEditSelector);
            const modal = bootstrap.Modal.getInstance(modalEl);
            modal.hide();
        },
        onError: (err) => {
            notyf.error({
                message: "Data storage rack failed to update!",
            });
        },

        resetOnSuccess: true,
    });
    // ---------- Submit form ke url :end ----------
}
// ---------- Handle form pembaharuan data :begin ----------

document.addEventListener("DOMContentLoaded", function () {
    SelectStorage();
    AddNewStorageRack();
    EditDataStorageRack();
});
