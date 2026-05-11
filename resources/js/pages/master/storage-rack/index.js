import TomSelect from "tom-select";
import {
    GlobalSubmitForm,
    GlobalFormValidation,
    GlobalAdvanceTomselect,
} from "../../../app";

// ---------- Global variable untuk memudahkan penyesuaian :begin ----------
const FormAddSelector = "add_new_storage_rack"; // id selector untuk form add new
const FormAddURL = "/master/storage-rack"; // url submit form add
const FormEditSelector = "edit_data_storage_rack"; // id selector untuk form edit
const ReloadDatatableSelector = "master-storage-rack-reload"; // reload datatable index
const ModalEditSelector = "edit_data_master_storage_rack_modal"; // id selector untuk modal edit
// ---------- Global variable untuk memudahkan penyesuaian :end ----------

// ---------- State gobal :begin ----------
let selectStorage = null;
let selectRackType = null;
let selectBloodGroup = null;
// ---------- State gobal :end ----------

// ---------- Select tom-select untuk form add new data :begin ----------
function SelectStorage() {
    const wrapperSelectStorage = new GlobalAdvanceTomselect("#select-storage", {
        valueField: "id",
        preload: true,
        noResultsText: "Storage not found",
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
    selectStorage = wrapperSelectStorage.getInstances()[0];
}
function SelectRackType() {
    const wrapperSelectRackType = new GlobalAdvanceTomselect(
        "#select-rack-type",
        {
            valueField: "id",
            preload: true,
            noResultsText: "Rack type not found",
            load: function (query, callback) {
                fetch(
                    `/utility/select/rack-type?q=${encodeURIComponent(query)}`,
                )
                    .then((response) => response.json())
                    .then((json) => {
                        callback(json.results);
                    })
                    .catch(() => {
                        callback();
                    });
            },
        },
    );
    selectRackType = wrapperSelectRackType.getInstances()[0];
}
function SelectBloodGroup() {
    const wrapperSelectBloodGroup = new GlobalAdvanceTomselect(
        "#select-blood-group",
        {
            valueField: "id",
            preload: true,
            noResultsText: "Blood group not found",
            load: function (query, callback) {
                fetch(
                    `/utility/select/blood-group?q=${encodeURIComponent(query)}`,
                )
                    .then((response) => response.json())
                    .then((json) => {
                        callback(json.results);
                    })
                    .catch(() => {
                        callback();
                    });
            },
        },
    );
    selectBloodGroup = wrapperSelectBloodGroup.getInstances()[0];
}
// ---------- Select tom-select untuk form add new data :end ----------

// ---------- Dynamic rack type handler ----------
function HandleRackTypeBehavior(validationInstance, formSelector) {
    const form = document.getElementById(formSelector);
    if (!form) return;

    const rackTypeSelect = form.querySelector("#select-rack-type");
    const bloodGroupWrapper = form.querySelector("#blood-group-wrapper");
    const bloodGroupSelect = form.querySelector("#select-blood-group");

    if (!bloodGroupWrapper || !bloodGroupSelect || !rackTypeSelect) {
        console.warn(
            `HandleRackTypeBehavior: required elements not found in form #${formSelector}`,
        );
        return;
    }

    function toggleBloodGroup() {
        const rackTypeValue = selectRackType?.getValue()?.toLowerCase();
        const isBlood = rackTypeValue === "blood";

        bloodGroupWrapper.classList.toggle("d-none", !isBlood);

        if (isBlood) {
            bloodGroupSelect.disabled = false;
            validationInstance.addField("blood_group", {
                validators: {
                    notEmpty: { message: "Blood group is required" },
                },
            });
        } else {
            validationInstance.removeField("blood_group");
            selectBloodGroup?.clear();
            bloodGroupSelect.disabled = true;
        }
    }

    selectRackType.on("change", function () {
        toggleBloodGroup();
    });

    toggleBloodGroup();
}

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
            rack_type: {
                validators: {
                    notEmpty: {
                        message: "Rack type is required",
                    },
                },
            },
        },
    );
    // ---------- Validasi inputan form :end ----------

    HandleRackTypeBehavior(AddNewStorageRackValidation, FormAddSelector);

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
            rack_type: {
                validators: {
                    notEmpty: {
                        message: "Rack type is required",
                    },
                },
            },
        },
    );
    // ---------- Validasi inputan form :end ----------

    HandleRackTypeBehavior(EditDataStorageRackValidation, FormEditSelector);

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
    SelectRackType();
    SelectBloodGroup();

    AddNewStorageRack();
    EditDataStorageRack();
});
