import { GlobalSubmitForm, GlobalFormValidation } from "../../../app";
import TomSelect from "tom-select";

// ---------- Global variable untuk memudahkan penyesuaian :begin ----------
const FormAddSelector = "add_new_blood_pack"; // id selector untuk form add new
const FormAddURL = "/master/blood-pack"; // url submit form add
const FormEditSelector = "edit_data_blood_pack"; // id selector untuk form edit
const ReloadDatatableSelector = "master-blood-pack-reload"; // reload datatable index
const ModalEditSelector = "edit_data_master_blood_pack_modal"; // id selector untuk modal edit
// ---------- Global variable untuk memudahkan penyesuaian :end ----------

// ---------- Select dari tom-select untuk form add new data :begin ----------
function SelectBloodGroup() {
    new TomSelect("#select-blood-group", {
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
            fetch(`/utility/select/blood-group?q=${encodeURIComponent(query)}`)
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
function SelectBloodRhesus() {
    new TomSelect("#select-blood-rhesus", {
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
            fetch(`/utility/select/blood-rhesus?q=${encodeURIComponent(query)}`)
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
// ---------- Select dari tom-select untuk form add new data :begin ----------

// ---------- Handle form penambahan blood pack baru :begin ----------
function AddNewBloodPack() {
    // ---------- Validasi inputan form :begin ----------
    const AddNewBloodPackValidation = GlobalFormValidation.init(
        "#" + FormAddSelector,
        {
            blood_group: {
                validators: {
                    notEmpty: {
                        message: "Blood group is required",
                    },
                },
            },
            blood_rhesus: {
                validators: {
                    notEmpty: {
                        message: "Blood rhesus is required",
                    },
                },
            },
            blood_component: {
                validators: {
                    notEmpty: {
                        message: "Blood component is required",
                    },
                },
            },
            warning_quantity: {
                validators: {
                    notEmpty: {
                        message: "Warning quantity is required",
                    },
                    isNumber: {
                        message: "Warning quantity must be a number",
                    },
                },
            },
            danger_quantity: {
                validators: {
                    notEmpty: {
                        message: "Danger quantity is required",
                    },
                    isNumber: {
                        message: "Danger quantity must be a number",
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
        validator: AddNewBloodPackValidation,
        onSuccess: (data) => {
            notyf.success({
                message: "New blood pack added succesfully!",
            });
            console.log(data);
            window.dispatchEvent(new Event(ReloadDatatableSelector));
        },
        onError: (err) => {
            notyf.error({
                message: "New blood pack failed to insert!",
            });

            console.error(err);
        },

        resetOnSuccess: true,
    });
    // ---------- Submit form ke url :end ----------
}
// ---------- Handle form penambahan blood pack baru :begin ----------

// ---------- Handle form pembaharuan data  :begin ----------
function EditDataBloodPack() {
    // ---------- Validasi inputan form :begin ----------
    const EditDataBloodPackValidation = GlobalFormValidation.init(
        "#" + FormEditSelector,
        {
            blood_group: {
                validators: {
                    notEmpty: {
                        message: "Blood group is required",
                    },
                },
            },
            blood_rhesus: {
                validators: {
                    notEmpty: {
                        message: "Blood rhesus is required",
                    },
                },
            },
            blood_component: {
                validators: {
                    notEmpty: {
                        message: "Blood component is required",
                    },
                },
            },
            warning_quantity: {
                validators: {
                    notEmpty: {
                        message: "Warning quantity is required",
                    },
                    isNumber: {
                        message: "Warning quantity must be a number",
                    },
                },
            },
            danger_quantity: {
                validators: {
                    notEmpty: {
                        message: "Danger quantity is required",
                    },
                    isNumber: {
                        message: "Danger quantity must be a number",
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
        validator: EditDataBloodPackValidation,
        onSuccess: (data) => {
            notyf.success({
                message: "Data blood pack updated succesfully!",
            });
            window.dispatchEvent(new Event(ReloadDatatableSelector));
            const modalEl = document.getElementById(ModalEditSelector);
            const modal = bootstrap.Modal.getInstance(modalEl);
            modal.hide();
        },
        onError: (err) => {
            notyf.error({
                message: "Data blood pack failed to update!",
            });
        },

        resetOnSuccess: true,
    });
    // ---------- Submit form ke url :end ----------
}
// ---------- Handle form pembaharuan data  :begin ----------

document.addEventListener("DOMContentLoaded", function () {
    SelectBloodGroup();
    SelectBloodRhesus();
    SelectBloodComponent();
    AddNewBloodPack();
    EditDataBloodPack();
});
