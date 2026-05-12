// ---------- Import Libraries ----------
import { log } from "handlebars";
import {
    GlobalFormValidation,
    GlobalSubmitForm,
    GlobalAdvanceFlatpickr,
    GlobalDeleteDataConfirmation,
} from "../../app";
import TomSelect from "tom-select";
import { DatatableBloodPackModal } from "./analytic/datatables-helper";

// ---------- Global variables ----------
let FormEditSelector = "edit_data_blood_transfusion";
let FormEditURL = "/blood-transfusion";

let EditBloodGroupSelectInstance;
let EditBloodRhesusSelectInstance;
let EditRelationTypeSelectInstance;
let EditInsuranceSelectInstance;
let EditRoomSelectInstance;
let EditDoctorSelectInstance;

// Delete Action
const ModalDeleteSelector = "delete_data_blood_request_modal";
const ActionDeleteSelector = ".btn-delete-blood-transfusion";
const AttributeDelete = "publicId";
const ConfirmDeleteSelector = "#confirm_delete";
const DeleteURLBloodTransfusion = "/blood-transfusion";

// Edit Blood Pack modal state
let selectedEditBloodPacks = [];
let currentEditTransfusionId = null;

// Helper function to initialize tom select
function initTomSelect(selector, url, loadData = true) {
    if (!document.querySelector(selector)) return null;
    return new TomSelect(selector, {
        valueField: "id",
        labelField: "text",
        searchField: "text",
        plugins: ["clear_button"],
        preload: loadData,
        load: loadData
            ? function (query, callback) {
                  fetch(`${url}?q=${encodeURIComponent(query)}`)
                      .then((res) => res.json())
                      .then((json) => callback(json.results))
                      .catch(() => callback());
              }
            : null,
    });
}

document.addEventListener("DOMContentLoaded", function () {
    // ---------- Initialize Flatpickr ----------
    new GlobalAdvanceFlatpickr("#edit_data_blood_required_at", {
        dateFormat: "Y-m-d H:i",
        static: true,
        enableTime: true,
    });

    // ---------- Initialize Tom Selects ----------

    EditRelationTypeSelectInstance = initTomSelect(
        "#edit_data_select-relation-type",
        "/utility/select/relation-type",
    );
    EditInsuranceSelectInstance = initTomSelect(
        "#edit_data_select-insurance",
        "/utility/select/insurance",
    );
    EditRoomSelectInstance = initTomSelect(
        "#edit_data_select-room",
        "/utility/select/room",
    );
    EditDoctorSelectInstance = initTomSelect(
        "#edit_data_select-doctor",
        "/utility/select/doctor",
    );

    EditBloodGroupSelectInstance = initTomSelect(
        "#edit_data_select-blood-group",
        "/utility/select/blood-group",
    );

    EditBloodRhesusSelectInstance = initTomSelect(
        "#edit_data_select-blood-rhesus",
        "/utility/select/blood-rhesus",
    );

    // ---------- Validation ----------
    const EditDataValidation = GlobalFormValidation.init(
        "#" + FormEditSelector,
        {
            insurance_id: {
                validators: { notEmpty: { message: "Insurance is required" } },
            },
            room_id: {
                validators: { notEmpty: { message: "Room is required" } },
            },
            doctor_id: {
                validators: { notEmpty: { message: "Doctor is required" } },
            },
        },
    );

    // ---------- Handle Edit Button Click ----------
    $(document).on("click", ".btn-edit-blood-transfusion", function (e) {
        e.preventDefault();
        const publicId = $(this).data("public-id");

        // Clear existing feedback
        document
            .querySelectorAll("#" + FormEditSelector + " .is-invalid")
            .forEach((el) => el.classList.remove("is-invalid"));
        document
            .querySelectorAll("#" + FormEditSelector + " .invalid-feedback")
            .forEach((el) => el.remove());

        // Fetch data
        fetch(`/blood-transfusion/${publicId}`)
            .then((res) => res.json())
            .then((data) => {
                if (data.status !== "success")
                    throw new Error("Failed to load data");
                const trans = data.data;

                document.getElementById(
                    "edit_data_blood_transfusion_id",
                ).value = trans.id;
                document.getElementById("edit_data_relation_name").value =
                    trans.relation_name || "";
                document.getElementById("edit_data_diagnosis").value =
                    trans.diagnosis || "";

                const dateEl = document.getElementById(
                    "edit_data_blood_required_at",
                );
                if (dateEl && dateEl._flatpickrInstance) {
                    if (trans.blood_request_at) {
                        dateEl._flatpickrInstance.setDate(
                            trans.blood_request_at,
                        );
                    } else {
                        dateEl._flatpickrInstance.clear();
                    }
                }

                if (EditBloodGroupSelectInstance && trans.patient_blood_group) {
                    EditBloodGroupSelectInstance.clear();
                    EditBloodGroupSelectInstance.setValue(
                        trans.patient_blood_group,
                    );
                }

                if (
                    EditBloodRhesusSelectInstance &&
                    trans.patient_blood_rhesus
                ) {
                    EditBloodRhesusSelectInstance.clear();
                    EditBloodRhesusSelectInstance.setValue(
                        trans.patient_blood_rhesus,
                    );
                }

                if (EditRelationTypeSelectInstance) {
                    EditRelationTypeSelectInstance.clear();
                    if (trans.relation_type)
                        EditRelationTypeSelectInstance.setValue(
                            trans.relation_type,
                        );
                }

                if (EditInsuranceSelectInstance && trans.insurance_public_id) {
                    EditInsuranceSelectInstance.clear();
                    EditInsuranceSelectInstance.setValue(
                        trans.insurance_public_id,
                    );
                }

                if (EditRoomSelectInstance && trans.room_public_id) {
                    EditRoomSelectInstance.clear();
                    EditRoomSelectInstance.setValue(trans.room_public_id);
                }

                if (EditDoctorSelectInstance && trans.doctor_public_id) {
                    EditDoctorSelectInstance.clear();
                    EditDoctorSelectInstance.setValue(trans.doctor_public_id);
                }
            })
            .catch((err) => {
                notyf.error({ message: "Failed to fetch data!" });
                console.error(err);
            });
    });

    // ---------- Handle Submit Update ----------
    const editFormEl = document.getElementById(FormEditSelector);
    if (editFormEl) {
        new GlobalSubmitForm({
            formId: FormEditSelector,
            url: () => {
                const id = document.getElementById(
                    "edit_data_blood_transfusion_id",
                ).value;
                return `${FormEditURL}/${id}`;
            },
            method: "PATCH",
            validator: EditDataValidation,
            onSuccess: (data) => {
                notyf.success({
                    message: "Blood Request updated successfully!",
                });
                // Hide Modal
                const modalEl = document.getElementById(
                    "edit_data_blood_transfusion_modal",
                );

                if (modalEl) {
                    const modal = bootstrap.Modal.getInstance(modalEl);

                    if (modal) {
                        modal.hide();
                    }
                }
                window.dispatchEvent(new Event("#list-request-table"));
            },
            onError: (err) => {
                notyf.error({
                    message: err.message || "Failed to update data!",
                });
                console.error(err);
            },
        });
    }

    // ---------- Handle Delete Button Click ----------
    // Panggil dan setup delete data
    new GlobalDeleteDataConfirmation({
        ButtonSelector: ActionDeleteSelector,
        DataAttributeID: AttributeDelete,
        UrlFetchData: (id) => DeleteURLBloodTransfusion + `/${id}`,
        ModalConfirmID: ModalDeleteSelector,
    });

    // Custom isi modal
    document.addEventListener("delete:open", function (e) {
        const { data } = e.detail;
        if (!data) return;

        // Isi text ke modal
        document.querySelector("#deleted_data").textContent =
            `${data.patient_name} with ID ${data.id}`;

        // Berikan attribute button delete dengan id data
        document.querySelector(ConfirmDeleteSelector).dataset.id = data.id;
    });

    const confirmBtn = document.querySelector(ConfirmDeleteSelector);

    if (confirmBtn) {
        confirmBtn.addEventListener("click", async function () {
            const id = this.dataset.id;

            if (!id) return;

            try {
                const response = await fetch(
                    DeleteURLBloodTransfusion + `/${id}`,
                    {
                        method: "DELETE",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": document
                                .querySelector('meta[name="csrf-token"]')
                                .getAttribute("content"),
                        },
                    },
                );

                const result = await response.json();

                if (!response.ok) {
                    notyf.error({
                        message: result.message || "Failed to delete data!",
                    });
                }

                notyf.success({
                    message: result.message || "Data deleted successfully!",
                });

                const modalEl = document.getElementById(ModalDeleteSelector);
                const modal =
                    bootstrap.Modal.getInstance(modalEl) ||
                    new bootstrap.Modal(modalEl);

                modal.hide();

                this.dataset.id = "";

                window.dispatchEvent(new Event("#list-request-table"));
            } catch (error) {
                console.error(error);
                notyf.error({
                    message: error || "Failed to delete data!",
                });
            }
        });
    }

    // ---------- Edit Blood Pack Modal ----------

    // Render selected blood pack table inside the edit modal
    function renderEditSelectedTable() {
        const tbody = document.querySelector("#edit-blood-pack-selected-table tbody");
        if (!tbody) return;

        tbody.innerHTML = "";

        if (selectedEditBloodPacks.length === 0) {
            tbody.innerHTML = `<tr><td colspan="4" class="text-center text-muted">No blood pack selected</td></tr>`;
            return;
        }

        selectedEditBloodPacks.forEach((pack, index) => {
            const tr = document.createElement("tr");
            tr.innerHTML = `
                <td>${pack.group || "-"}</td>
                <td class="text-center">${pack.rhesus || "-"}</td>
                <td class="text-center">${pack.component || "-"}</td>
                <td class="text-end">
                    <button class="btn btn-sm btn-danger remove-edit-blood-pack" type="button" data-index="${index}">
                        <i class="ti ti-trash"></i>
                    </button>
                </td>
            `;
            tbody.appendChild(tr);
        });
    }

    // Open modal: initialize datatable and load existing selected packs
    document.getElementById("btn-edit-blood-pack")?.addEventListener("click", async function () {
        if (!window.currentTransfusionPublicId) {
            notyf.error({ message: "Please select a patient row first!" });
            return;
        }

        currentEditTransfusionId = window.currentTransfusionPublicId;
        selectedEditBloodPacks = [];

        // Load existing blood packs from the current transfusion's bag requests
        try {
            const response = await fetch(`/blood-transfusion/${currentEditTransfusionId}/bag-requests`);
            const result = await response.json();
            if (result.data && result.data.length > 0) {
                selectedEditBloodPacks = result.data.map((row) => ({
                    publicId: row.blood_pack_public_id,
                    group: row.blood_group,
                    rhesus: row.blood_rhesus,
                    component: row.blood_component,
                })).filter((p) => p.publicId);
            }
        } catch (e) {
            console.warn("Could not load existing blood packs:", e);
        }

        renderEditSelectedTable();

        // Initialize (or re-init) blood pack datatable in modal
        DatatableBloodPackModal();
    });

    // Delegate click: select blood pack from left table
    document.addEventListener("click", function (e) {
        const btn = e.target.closest(".select-edit-blood-pack");
        if (btn) {
            const pack = {
                publicId: btn.dataset.publicId,
                group: btn.dataset.group,
                rhesus: btn.dataset.rhesus,
                component: btn.dataset.component,
            };
            selectedEditBloodPacks.push(pack);
            renderEditSelectedTable();
            notyf.success({ message: "Blood pack added!" });
        }

        const removeBtn = e.target.closest(".remove-edit-blood-pack");
        if (removeBtn) {
            const index = parseInt(removeBtn.dataset.index, 10);
            selectedEditBloodPacks.splice(index, 1);
            renderEditSelectedTable();
        }
    });

    // Save button: send PATCH to backend
    document.getElementById("btn-save-edit-blood-pack")?.addEventListener("click", async function () {
        if (selectedEditBloodPacks.length === 0) {
            notyf.error({ message: "Please select at least one blood pack!" });
            return;
        }

        const originalText = this.innerHTML;
        this.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span> Saving...';
        this.disabled = true;

        try {
            const response = await fetch(`/blood-transfusion/${currentEditTransfusionId}/update-blood-packs`, {
                method: "PATCH",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
                },
                body: JSON.stringify({
                    blood_packs: selectedEditBloodPacks.map((p) => p.publicId),
                }),
            });

            const result = await response.json();

            if (!response.ok) {
                notyf.error({ message: result.message || "Failed to save!" });
            } else {
                notyf.success({ message: result.message || "Blood packs saved successfully!" });

                // Close modal
                const modalEl = document.getElementById("edit_blood_pack_modal");
                if (modalEl) {
                    const modal = bootstrap.Modal.getInstance(modalEl);
                    if (modal) modal.hide();
                }

                // Reload bag request datatable
                if ($.fn.DataTable.isDataTable("#list-bag-request-table")) {
                    $("#list-bag-request-table").DataTable().ajax.reload(null, false);
                }
            }
        } catch (error) {
            console.error(error);
            notyf.error({ message: "An error occurred while saving." });
        } finally {
            this.innerHTML = originalText;
            this.disabled = false;
        }
    });
});
