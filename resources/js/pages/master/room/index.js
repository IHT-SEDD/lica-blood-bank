import TomSelect from "tom-select";
import { GlobalSubmitForm, GlobalFormValidation } from "../../../app";

// ---------- Global variable untuk memudahkan penyesuaian :begin ----------
const FormAddSelector = "add_new_room"; // id selector untuk form add new
const FormAddURL = "/master/room"; // url submit form add
const FormEditSelector = "edit_data_room"; // id selector untuk form edit
const ReloadDatatableSelector = "master-room-reload"; // reload datatable index
const ModalEditSelector = "edit_data_master_room_modal"; // id selector untuk modal edit
// ---------- Global variable untuk memudahkan penyesuaian :end ----------

// ---------- Select role dari tom-select untuk form add new data :begin ----------
// function SelectRole() {
//     new TomSelect("#select-role", {
//         valueField: "id",
//         labelField: "text",
//         searchField: "text",
//         sortField: {
//             field: "id",
//             direction: "asc",
//         },
//         create: false,
//         preload: true,
//         load: function(query, callback) {
//             fetch(`/utility/select/role?q=${encodeURIComponent(query)}`)
//                 .then((response) => response.json())
//                 .then((json) => {
//                     callback(json.results);
//                 })
//                 .catch(() => {
//                     callback();
//                 });
//         },
//     });
// }
// ---------- Select role dari tom-select untuk form add new data :begin ----------

// ---------- Handle form penambahan user baru :begin ----------
function AddNewRoom() {
    // ---------- Validasi inputan form :begin ----------
    const AddNewRoomValidation = GlobalFormValidation.init(
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
        validator: AddNewRoomValidation,
        onSuccess: (data) => {
            notyf.success({
                message: "New Room added succesfully!",
            });
            console.log(data);
            window.dispatchEvent(new Event(ReloadDatatableSelector));
        },
        onError: (err) => {
            notyf.error({
                message: "New Room failed to insert!",
            });

            console.error(err);
        },

        resetOnSuccess: true,
    });
    // ---------- Submit form ke url :end ----------
}
// ---------- Handle form penambahan user baru :begin ----------

// ---------- Handle form pembaharuan data user :begin ----------
function EditDataRoom() {
    // ---------- Validasi inputan form :begin ----------
    const EditDataRoomValidation = GlobalFormValidation.init(
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
        validator: EditDataRoomValidation,
        onSuccess: (data) => {
            notyf.success({
                message: "Data Room updated succesfully!",
            });
            window.dispatchEvent(new Event(ReloadDatatableSelector));
            const modalEl = document.getElementById(ModalEditSelector);
            const modal = bootstrap.Modal.getInstance(modalEl);
            modal.hide();
        },
        onError: (err) => {
            notyf.error({
                message: "Data Room failed to update!",
            });
        },

        resetOnSuccess: true,
    });
    // ---------- Submit form ke url :end ----------
}
// ---------- Handle form pembaharuan data user :begin ----------

document.addEventListener("DOMContentLoaded", function () {
    // SelectRole();
    AddNewRoom();
    EditDataRoom();
});
