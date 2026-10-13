import {
    GlobalSubmitForm,
    GlobalFormValidation,
    GlobalAdvanceTomselect,
} from "../../../app";

// ---------- Global variable untuk memudahkan penyesuaian :begin ----------
const FormAddSelector = "add_new_transfusion-reaction"; // id selector untuk form add new
const FormAddURL = "/master/transfusion-reaction"; // url submit form add
const FormEditSelector = "edit_data_transfusion-reaction"; // id selector untuk form edit
const ReloadDatatableSelector = "master-transfusion-reaction-reload"; // reload datatable index
const ModalEditSelector = "edit_data_master_transfusion-reaction_modal"; // id selector untuk modal edit
// ---------- Global variable untuk memudahkan penyesuaian :end ----------

// ---------- State gobal :begin ----------
let selectLevel = null;
// ---------- State gobal :end ----------

function SelectLevel() {
    const wrapperSelectLevel = new GlobalAdvanceTomselect("#select-level", {
        valueField: "id",
        preload: true,
        noResultsText: "Tingkatan tidak ditemukan",
        load: function (query, callback) {
            fetch(
                `/utility/select/level-reaction?q=${encodeURIComponent(query)}`,
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
    selectLevel = wrapperSelectLevel.getInstances()[0];
}

// ---------- Handle form penambahan user baru :begin ----------
function AddNewTransfusionReaction() {
    // ---------- Validasi inputan form :begin ----------
    const AddNewTransfusionReactionValidation = GlobalFormValidation.init(
        "#" + FormAddSelector,
        {
            name: {
                validators: {
                    notEmpty: {
                        message: "Nama reaksi wajib diisi",
                    },
                },
            },
            category: {
                validators: {
                    notEmpty: {
                        message: "Kategori reaksi wajib diisi",
                    },
                },
            },
            level: {
                validators: {
                    notEmpty: {
                        message: "Tingkatan reaksi wajib dipilih",
                    },
                },
            },
            indicator: {
                validators: {
                    notEmpty: {
                        message: "Indikasi reaksi wajib diisi",
                    },
                },
            },
            time_begin: {
                validators: {
                    isNumberIfPresent: {
                        message: "Waktu awal harus berupa angka",
                    },
                    compareNumber: {
                        field: "time_end",
                        operator: "lte",
                        message:
                            "Waktu awal tidak boleh lebih dari waktu akhir",
                    },
                },
            },
            time_end: {
                validators: {
                    isNumberIfPresent: {
                        message: "Waktu akhir harus berupa angka",
                    },
                    compareNumber: {
                        field: "time_begin",
                        operator: "gte",
                        message:
                            "Waktu akhir tidak boleh kurang dari waktu awal",
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
        validator: AddNewTransfusionReactionValidation,
        onSuccess: (data) => {
            notyf.success({
                message: "New Transfusion Reaction added succesfully!",
            });
            console.log(data);
            window.dispatchEvent(new Event(ReloadDatatableSelector));
        },
        onError: (err) => {
            notyf.error({
                message: "New Transfusion Reaction failed to insert!",
            });

            console.error(err);
        },

        resetOnSuccess: true,
    });
    // ---------- Submit form ke url :end ----------
}
// ---------- Handle form penambahan user baru :begin ----------

// ---------- Handle form pembaharuan data user :begin ----------
function EditDataTransfusionReaction() {
    // ---------- Validasi inputan form :begin ----------
    const EditDataTransfusionReactionValidation = GlobalFormValidation.init(
        "#" + FormEditSelector,
        {
            name: {
                validators: {
                    notEmpty: {
                        message: "Nama reaksi wajib diisi",
                    },
                },
            },
            category: {
                validators: {
                    notEmpty: {
                        message: "Kategori reaksi wajib diisi",
                    },
                },
            },
            level: {
                validators: {
                    notEmpty: {
                        message: "Tingkatan reaksi wajib dipilih",
                    },
                },
            },
            indicator: {
                validators: {
                    notEmpty: {
                        message: "Indikasi reaksi wajib diisi",
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
        validator: EditDataTransfusionReactionValidation,
        onSuccess: (data) => {
            notyf.success({
                message: "Data Transfusion Reaction updated succesfully!",
            });
            window.dispatchEvent(new Event(ReloadDatatableSelector));
            const modalEl = document.getElementById(ModalEditSelector);
            const modal = bootstrap.Modal.getInstance(modalEl);
            modal.hide();
        },
        onError: (err) => {
            notyf.error({
                message: "Data Transfusion Reaction failed to update!",
            });
        },

        resetOnSuccess: true,
    });
    // ---------- Submit form ke url :end ----------
}
// ---------- Handle form pembaharuan data user :begin ----------

function bindCrossFieldClear(formSelector, fieldA, fieldB) {
    const form = document.querySelector(formSelector);
    if (!form) return;
    const inputA = form.querySelector(`[name="${fieldA}"]`);
    const inputB = form.querySelector(`[name="${fieldB}"]`);
    if (!inputA || !inputB) return;

    const clear = (el) => {
        el.classList.remove("is-invalid");
        el.parentNode.querySelector(".invalid-feedback")?.remove();
    };

    inputA.addEventListener("input", () => clear(inputB));
    inputB.addEventListener("input", () => clear(inputA));
}

document.addEventListener("DOMContentLoaded", function () {
    SelectLevel();
    AddNewTransfusionReaction();
    EditDataTransfusionReaction();
    bindCrossFieldClear("#" + FormAddSelector, "time_begin", "time_end");
    bindCrossFieldClear("#" + FormEditSelector, "time_begin", "time_end");
});
