// ---------- Import Libraries ----------
import { updatePatientDetailUI } from "../index";
import {
    GlobalFormValidation,
    GlobalSubmitForm,
    GlobalAdvanceFlatpickr,
    GlobalDeleteDataConfirmation,
    GlobalAdvanceTomselect,
} from "../../../app";
import { DatatableBloodPackModal } from "../datatable/datatables-helper";

// ---------- Global variables ----------
const FormEditSelector = "edit_data_blood_transfusion";
const FormEditURL = "/blood-transfusion";

const ModalDeleteSelector = "delete_data_blood_request_modal";
const ActionDeleteSelector = ".btn-delete-blood-transfusion";
const AttributeDelete = "publicId";
const ConfirmDeleteSelector = "#confirm_delete";
const DeleteURLBloodTransfusion = "/blood-transfusion";

let selectedEditBloodPacks = [];
let currentEditTransfusionId = null;

// ---------- Helper ----------
async function fetchSelectBatch(keys = []) {
    const params = new URLSearchParams();
    keys.forEach((key) => params.append("keys[]", key));

    const res = await fetch(`/utility/select-batch?${params.toString()}`);
    const json = await res.json();
    return json;
}
function destroyAndInitWithOptions(selector, options = []) {
    const el = document.querySelector(selector);
    if (!el) return null;
    if (el.tomselect) {
        el.tomselect.destroy();
    }

    const wrapper = new GlobalAdvanceTomselect(selector, {
        valueField: "id",
        plugins: ["clear_button"],
        preload: false,
        options: options,
    });

    return wrapper.getInstances()[0] ?? null;
}
function waitForOptions(instance, timeout = 5000) {
    if (!instance) return Promise.resolve();
    if (Object.keys(instance.options).length > 0) {
        return Promise.resolve();
    }

    return new Promise((resolve) => {
        const timer = setTimeout(() => {
            instance.off("load", handler);
            resolve(); // safety net tetap resolve meski timeout
        }, timeout);

        function handler() {
            clearTimeout(timer);
            instance.off("load", handler);
            resolve();
        }

        instance.on("load", handler);
        instance.load("");
    });
}
function handleBloodPackClick(e) {
    const btn = e.target.closest(".select-edit-blood-component");
    if (btn) {
        selectedEditBloodPacks.push({
            public_id: btn.dataset.publicId ?? null,
            component_id: btn.dataset.id,
            component_text: btn.dataset.text,
        });
        renderEditSelectedTable();
        console.log(selectedEditBloodPacks);
        notyf.success({ message: "Darah berhasil ditambahkan!" });
    }

    const removeBtn = e.target.closest(".remove-edit-blood-component");
    if (removeBtn) {
        selectedEditBloodPacks.splice(parseInt(removeBtn.dataset.index, 10), 1);
        renderEditSelectedTable();
    }
}
function renderEditSelectedTable() {
    const tbody = document.querySelector(
        "#edit-blood-pack-selected-table tbody",
    );
    if (!tbody) return;

    tbody.innerHTML =
        selectedEditBloodPacks.length === 0
            ? `<tr><td colspan="4" class="text-center text-muted">No blood pack selected</td></tr>`
            : "";

    selectedEditBloodPacks.forEach((pack, index) => {
        const isReleased =
            pack.blood_release_status === true ||
            pack.blood_release_status === 1;
        const tr = document.createElement("tr");
        tr.innerHTML = `
            <td class="text-start">
                ${pack.component_text || "-"} (${pack.component_id || "-"})
                ${pack.public_id ? '<i class="ti ti-square-rounded-check text-success"></i>' : ""}
                ${isReleased ? '<span class="badge bg-danger ms-1">Sudah Dikeluarkan</span>' : ""}
            </td>
            <td class="text-end">
                <button 
                    class="btn btn-sm btn-danger remove-edit-blood-component" 
                    type="button" 
                    data-index="${index}"
                    ${isReleased ? "disabled title='Darah sudah dikeluarkan, tidak dapat dihapus'" : ""}
                >
                    <i class="ti ti-trash"></i>
                </button>
            </td>
        `;
        tbody.appendChild(tr);
    });
}

// ---------- Named handler untuk save blood pack ----------
async function handleSaveBloodPack() {
    if (selectedEditBloodPacks.length === 0) {
        notyf.error({ message: "Satu jenis darah wajib dimiliki!" });
        return;
    }

    const btn = document.getElementById("btn-save-edit-blood-pack");
    const originalText = btn.innerHTML;
    btn.innerHTML =
        '<span class="spinner-border spinner-border-sm" role="status"></span> Menyimpan...';
    btn.disabled = true;

    try {
        const response = await fetch(
            `/blood-transfusion/${currentEditTransfusionId}/update-blood-packs`,
            {
                method: "PATCH",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content"),
                },
                body: JSON.stringify({
                    blood_packs: selectedEditBloodPacks,
                }),
            },
        );

        const result = await response.json();

        if (!response.ok) {
            notyf.error({
                message: result.message || "Gagal menyimpan perubahan!",
            });
            return;
        }

        notyf.success({
            message: result.message || "Jenis darah berhasil disimpan!",
        });

        const modalEl = document.getElementById("edit_blood_pack_modal");
        bootstrap.Modal.getInstance(modalEl)?.hide();

        if ($.fn.DataTable.isDataTable("#list-bag-request-table")) {
            $("#list-bag-request-table").DataTable().ajax.reload(null, false);
        }
    } catch (error) {
        console.error(error);
        notyf.error({ message: "An error occurred while saving." });
    } finally {
        btn.innerHTML = originalText;
        btn.disabled = false;
    }
}

// ---------- Export: dipanggil dari index.js ----------
export function initFormEdit() {
    new GlobalAdvanceFlatpickr("#edit_data_blood_required_at", {
        dateFormat: "Y-m-d H:i",
        static: true,
        enableTime: true,
    });

    // ---------- Toggle DCT Value ----------
    const dctCheckbox = document.getElementById("edit_data_is_dct");
    const dctWrapper = document.getElementById("edit_data_dct_value_wrapper");

    function toggleDctValue(checked) {
        if (dctWrapper) dctWrapper.style.display = checked ? "" : "none";
    }
    if (dctCheckbox) {
        dctCheckbox.addEventListener("change", function () {
            toggleDctValue(this.checked);
        });
    }

    // ---------- Validation ----------
    const EditDataValidation = GlobalFormValidation.init(
        "#" + FormEditSelector,
        {
            insurance_id: {
                validators: { notEmpty: { message: "Asuransi wajib diisi!" } },
            },
            room_id: {
                validators: { notEmpty: { message: "Ruangan wajib diisi!" } },
            },
            doctor_id: {
                validators: { notEmpty: { message: "Dokter wajib diisi!" } },
            },
        },
    );

    // ---------- Handle Edit Button Click ----------
    $(document).on("click", ".btn-edit-blood-transfusion", async function (e) {
        e.preventDefault();
        if ($(this).data("loading")) return;
        $(this).data("loading", true);

        const publicId = $(this).data("public-id");

        // Bersihkan feedback validasi sebelumnya
        document
            .querySelectorAll("#" + FormEditSelector + " .is-invalid")
            .forEach((el) => el.classList.remove("is-invalid"));
        document
            .querySelectorAll("#" + FormEditSelector + " .invalid-feedback")
            .forEach((el) => el.remove());

        showPageLoading();
        try {
            const [transRes, selectData] = await Promise.all([
                fetch(`/blood-transfusion/${publicId}`).then((r) => r.json()),
                fetchSelectBatch([
                    "blood-group",
                    "blood-rhesus",
                    "relation-type",
                    "insurance",
                    "room",
                    "doctor",
                    "dct-value",
                ]),
            ]);

            if (transRes.status !== "success")
                throw new Error("Failed to load data");
            const trans = transRes.data;

            // Init (atau re-init) TomSelect setiap modal dibuka
            const EditBloodGroupSelectInstance = destroyAndInitWithOptions(
                "#edit_data_select-blood-group",
                selectData["blood-group"]?.results ?? [],
            );
            const EditBloodRhesusSelectInstance = destroyAndInitWithOptions(
                "#edit_data_select-blood-rhesus",
                selectData["blood-rhesus"]?.results ?? [],
            );
            const EditRelationTypeSelectInstance = destroyAndInitWithOptions(
                "#edit_data_select-relation-type",
                selectData["relation-type"]?.results ?? [],
            );
            const EditInsuranceSelectInstance = destroyAndInitWithOptions(
                "#edit_data_select-insurance",
                selectData["insurance"]?.results ?? [],
            );
            const EditRoomSelectInstance = destroyAndInitWithOptions(
                "#edit_data_select-room",
                selectData["room"]?.results ?? [],
            );
            const EditDoctorSelectInstance = destroyAndInitWithOptions(
                "#edit_data_select-doctor",
                selectData["doctor"]?.results ?? [],
            );
            const EditDCTSelectInstance = destroyAndInitWithOptions(
                "#edit_data_select-dct-value",
                selectData["dct-value"]?.results ?? [],
            );

            document.getElementById("edit_data_blood_transfusion_id").value =
                trans.id;
            document.getElementById("edit_data_relation_name").value =
                trans.relation_name || "";
            document.getElementById("edit_data_diagnosis").value =
                trans.diagnosis || "";

            const dateEl = document.getElementById(
                "edit_data_blood_required_at",
            );
            if (dateEl?._flatpickrInstance) {
                trans.blood_request_at
                    ? dateEl._flatpickrInstance.setDate(trans.blood_request_at)
                    : dateEl._flatpickrInstance.clear();
            }

            dctCheckbox.checked = !!trans.is_dct;
            toggleDctValue(!!trans.is_dct);

            if (trans.patient_blood_group && EditBloodGroupSelectInstance) {
                EditBloodGroupSelectInstance.setValue(
                    trans.patient_blood_group,
                );
            }
            if (trans.patient_blood_rhesus && EditBloodRhesusSelectInstance) {
                EditBloodRhesusSelectInstance.setValue(
                    trans.patient_blood_rhesus,
                );
            }
            if (trans.relation_type && EditRelationTypeSelectInstance) {
                EditRelationTypeSelectInstance.setValue(trans.relation_type);
            }
            if (trans.insurance_public_id && EditInsuranceSelectInstance) {
                EditInsuranceSelectInstance.setValue(trans.insurance_public_id);
            }
            if (trans.room_public_id && EditRoomSelectInstance) {
                EditRoomSelectInstance.setValue(trans.room_public_id);
            }
            if (trans.doctor_public_id && EditDoctorSelectInstance) {
                EditDoctorSelectInstance.setValue(trans.doctor_public_id);
            }

            hidePageLoading();

            const modalEl = document.getElementById(
                "edit_data_blood_transfusion_modal",
            );
            if (modalEl) {
                bootstrap.Modal.getOrCreateInstance(modalEl).show();
            }
        } catch (err) {
            notyf.error({ message: "Failed to fetch data!" });
            console.error(err);
            hidePageLoading();
        } finally {
            $(this).data("loading", false); // reset flag
        }
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
            onSuccess: (response) => {
                notyf.success({
                    message: "Blood Request updated successfully!",
                });

                const modalEl = document.getElementById(
                    "edit_data_blood_transfusion_modal",
                );
                if (modalEl) {
                    bootstrap.Modal.getInstance(modalEl)?.hide();
                }

                updatePatientDetailUI(response.data);

                if ($.fn.DataTable.isDataTable("#list-request-table")) {
                    $("#list-request-table")
                        .DataTable()
                        .ajax.reload(null, false);
                }
            },
            onError: (err) => {
                notyf.error({
                    message: err.message || "Failed to update data!",
                });
                console.error(err);
            },
        });
    }

    // ---------- Handle Delete ----------
    new GlobalDeleteDataConfirmation({
        ButtonSelector: ActionDeleteSelector,
        DataAttributeID: AttributeDelete,
        UrlFetchData: (id) => `${DeleteURLBloodTransfusion}/${id}`,
        ModalConfirmID: ModalDeleteSelector,
    });
    document.addEventListener("delete:open", function (e) {
        const { data } = e.detail;
        if (!data) return;
        document.querySelector("#deleted_data").textContent =
            `${data.patient_name} with ID ${data.id}`;
        document.querySelector(ConfirmDeleteSelector).dataset.id = data.id;
    });
    const confirmBtn = document.querySelector(ConfirmDeleteSelector);
    if (confirmBtn) {
        confirmBtn.addEventListener("click", async function () {
            const id = this.dataset.id;
            if (!id) return;

            try {
                const response = await fetch(
                    `${DeleteURLBloodTransfusion}/${id}`,
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
                    return;
                }

                notyf.success({
                    message: result.message || "Data deleted successfully!",
                });

                const modalEl = document.getElementById(ModalDeleteSelector);
                (
                    bootstrap.Modal.getInstance(modalEl) ??
                    new bootstrap.Modal(modalEl)
                ).hide();
                this.dataset.id = "";

                if ($.fn.DataTable.isDataTable("#list-request-table")) {
                    $("#list-request-table")
                        .DataTable()
                        .ajax.reload(null, false);
                }
            } catch (error) {
                console.error(error);
                notyf.error({ message: "Failed to delete data!" });
            }
        });
    }

    document
        .getElementById("btn-edit-blood-pack")
        ?.addEventListener("click", async function () {
            if (!window.currentTransfusionPublicId) {
                notyf.error({ message: "Harap pilih pasien terlebih dahulu!" });
                return;
            }

            currentEditTransfusionId = window.currentTransfusionPublicId;
            selectedEditBloodPacks = [];

            try {
                const response = await fetch(
                    `/blood-transfusion/${currentEditTransfusionId}/bag-requests`,
                );
                const result = await response.json();

                if (result.data?.length > 0) {
                    selectedEditBloodPacks = result.data
                        .map((row) => ({
                            public_id: row.row_data?.public_id,
                            component_id: row.row_data?.component_id,
                            component_text: row.row_data?.component_text,
                            blood_release_status:
                                row.row_data?.blood_release_status ?? false,
                        }))
                        .filter((p) => p.component_id);
                }
            } catch (e) {
                console.warn("Could not load existing blood packs:", e);
            }

            renderEditSelectedTable();
            DatatableBloodPackModal();
        });

    document.removeEventListener("click", handleBloodPackClick);
    document.addEventListener("click", handleBloodPackClick);

    // ---------- Register save blood pack handler (remove dulu agar tidak dobel) ----------
    const saveBtnEl = document.getElementById("btn-save-edit-blood-pack");
    if (saveBtnEl) {
        saveBtnEl.removeEventListener("click", handleSaveBloodPack);
        saveBtnEl.addEventListener("click", handleSaveBloodPack);
    }
}
