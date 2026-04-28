import TomSelect from "tom-select";
import { GlobalSubmitForm, GlobalFormValidation } from "../../../app";

// ---------- Global variable untuk memudahkan penyesuaian :begin ----------
const BloodDataInputsContainer = "blood_data_container";
const AddBloodDataRowButton = "add_blood_data";
const DeleteBloodDataRowButton = ".btn-delete-blood";
const BloodDataRow = ".blood-data-row";
const FormAddNewOrderSelector = "add_new_order";
const PoNumberInputSelector = "po_number";
const UrlPostNewOrder = "/inventory/history-order/new-order";
const UrlGetNewPoNumber = "/inventory/history-order/new-po-number";
const orderForm = HandleFormAddNewOrder();
// ---------- Global variable untuk memudahkan penyesuaian :begin ----------

// ---------- Select vendor dari tom-select untuk form add new data :begin ----------
function SelectVendor() {
    new TomSelect("#select-vendor", {
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
            fetch(`/utility/select/vendor?q=${encodeURIComponent(query)}`)
                .then((response) => response.json())
                .then((json) => {
                    callback(json.results);
                })
                .catch(() => {
                    callback();
                });
        },
        onChange: async (vendorId) => {
            // ---------- Reset inputan jika vendor dikosongkan ----------
            if (!vendorId) {
                document.getElementById("vendor-name").value = "";
                document.getElementById("vendor-address").value = "";
                return;
            }

            // ---------- Ambil data vendor berdasarkan id ----------
            try {
                const res = await fetch(`/utility/get/vendor/${vendorId}`);
                const data = await res.json();

                document.getElementById("vendor-name").value = data.name ?? "";
                document.getElementById("vendor-address").value =
                    data.address ?? "";
            } catch (err) {
                notyf.error({ message: "Failed to fetch vendor data!" });
                console.error(err);
            }
        },
    });
}
// ---------- Select vendor dari tom-select untuk form add new data :begin ----------

// ---------- Fungsi untuk mengelola form add new order :begin ----------
function HandleFormAddNewOrder() {
    // Mulai index blood data dari 0
    let bloodIndex = 0;
    const bloodDataContainer = document.getElementById(
        BloodDataInputsContainer,
    );

    const formValidation = GlobalFormValidation.init(
        "#" + FormAddNewOrderSelector,
        {
            // ---------- Validasi field statis :begin ----------
            po_number: {
                validators: {
                    notEmpty: {
                        message: "PO Number is required",
                    },
                },
            },
            vendor_id: {
                validators: {
                    notEmpty: {
                        message: "Vendor is required",
                    },
                },
            },
            // ---------- Validasi field statis :end ----------
        },
    );

    // Kosongkan isi container
    bloodDataContainer.innerHTML = "";
    // Bikin 1 baris default inputan
    addBloodRow();

    // Tambahkan baris jika tombol add blood data di click
    document
        .getElementById(AddBloodDataRowButton)
        .addEventListener("click", () => {
            addBloodRow();
        });

    // ---------- Fungsi untuk menambahkan baris blood data :begin ----------
    function addBloodRow() {
        const idx = bloodIndex++;
        const row = document.createElement("div");
        const titleRow = `${idx}# Blood Data`;
        row.className = "card blood-data-row";
        row.innerHTML = `<div class="card-header justify-content-between align-items-center">
                <h6 class="card-title text-capitalize mb-0" id="blood_data[${idx}][title]">${titleRow}</h6>
                <button class="btn btn-sm btn-soft-danger btn-delete-blood" type="button">Delete</button>
            </div>
            <div class="card-body">
                <div class="col-12">
                    <div class="row g-2 align-items-end">
                        <div class="col-lg-2 col-6">
                            <label class="form-label text-muted" for="blood_data[${idx}][blood_group]">Group <span class="text-danger">*</span></label>
                            <select class="form-control" id="blood_data[${idx}][blood_group]" name="blood_data[${idx}][blood_group]" placeholder="Choose blood group..."></select>
                        </div>
                        <div class="col-lg-2 col-6">
                            <label class="form-label text-muted" for="blood_data[${idx}][blood_rhesus]">Rhesus <span class="text-danger">*</span></label>
                            <select class="form-control" id="blood_data[${idx}][blood_rhesus]" name="blood_data[${idx}][blood_rhesus]" placeholder="Choose blood rhesus..."></select>
                        </div>
                        <div class="col-lg-2 col-6">
                            <label class="form-label text-muted" for="blood_data[${idx}][blood_component]">Component <span class="text-danger">*</span></label>
                            <select class="form-control" id="blood_data[${idx}][blood_component]" name="blood_data[${idx}][blood_component]" placeholder="Choose blood component...">
                            </select>
                        </div>
                        <div class="col-lg-2 col-6">
                            <label class="form-label text-muted" for="blood_data[${idx}][blood_volume]">Volume <span class="text-danger">*</span></label>
                            <input class="form-control" id="blood_data[${idx}][blood_volume]" name="blood_data[${idx}][blood_volume]" placeholder="Blood volume" type="text" />
                        </div>
                        <div class="col-lg-2 col-6">
                            <label class="form-label text-muted" for="blood_data[${idx}][blood_quantity]">Quantity <span class="text-danger">*</span></label>
                            <input class="form-control" id="blood_data[${idx}][blood_quantity]" name="blood_data[${idx}][blood_quantity]" placeholder="Blood quantity" type="text" />
                        </div>
                        <div class="col-lg-2 col-6">
                            <div class="d-flex flex-wrap gap-1">
                                <div class="form-check form-check-danger">
                                    <input class="form-check-input" id="blood_data[${idx}][is_hiv]" name="blood_data[${idx}][is_hiv]" type="checkbox" />
                                    <label class="form-check-label" for="blood_data[${idx}][is_hiv]">HIV?</label>
                                </div>
                                <div class="form-check form-check-danger">
                                    <input class="form-check-input" id="blood_data[${idx}][is_hcv]" name="blood_data[${idx}][is_hcv]" type="checkbox" />
                                    <label class="form-check-label" for="blood_data[${idx}][is_hcv]">HCV?</label>
                                </div>
                                <div class="form-check form-check-danger">
                                    <input class="form-check-input" id="blood_data[${idx}][is_hbsag]" name="blood_data[${idx}][is_hbsag]" type="checkbox" />
                                    <label class="form-check-label" for="blood_data[${idx}][is_hbsag]">HbsAG?</label>
                                </div>
                                <div class="form-check form-check-danger">
                                    <input class="form-check-input" id="blood_data[${idx}][is_syphilis]" name="blood_data[${idx}][is_syphilis]" type="checkbox" />
                                    <label class="form-check-label" for="blood_data[${idx}][is_syphilis]">Syphilis?</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>`;

        initBloodDataSelects(row, idx);

        // Hapus baris jika tombol delete di click
        row.querySelector(DeleteBloodDataRowButton).addEventListener(
            "click",
            () => {
                deleteBloodRow(row);
            },
        );

        bloodDataContainer.appendChild(row);
        addBloodRowValidation(idx);
        syncDeleteButtons();
    }
    // ---------- Fungsi untuk menambahkan baris blood data :end ----------

    // ---------- Fungsi untuk inisialisasi Tom Select blood data :begin ----------
    function initBloodDataSelects(row, idx) {
        const selectConfigs = [
            {
                el: row.querySelector(
                    `[name="blood_data[${idx}][blood_group]"]`,
                ),
                url: "/utility/select/blood-group",
            },
            {
                el: row.querySelector(
                    `[name="blood_data[${idx}][blood_rhesus]"]`,
                ),
                url: "/utility/select/blood-rhesus",
            },
            {
                el: row.querySelector(
                    `[name="blood_data[${idx}][blood_component]"]`,
                ),
                url: "/utility/select/blood-component",
            },
        ];

        selectConfigs.forEach(({ el, url }) => {
            if (!el) return;
            new TomSelect(el, {
                valueField: "id",
                labelField: "text",
                searchField: "text",
                preload: true,
                load: function (query, callback) {
                    fetch(`${url}?q=${encodeURIComponent(query)}`)
                        .then((res) => res.json())
                        .then((json) => callback(json.results))
                        .catch(() => callback());
                },
            });
        });
    }
    // ---------- Fungsi untuk inisialisasi Tom Select blood data :end ----------

    // ---------- Fungsi untuk menambahkan validasi per baris blood data :begin ----------
    function addBloodRowValidation(idx) {
        const bloodFieldValidators = {
            notEmpty: { message: "This field is required" },
        };

        const fields = [
            `blood_data[${idx}][blood_group]`,
            `blood_data[${idx}][blood_rhesus]`,
            `blood_data[${idx}][blood_component]`,
            `blood_data[${idx}][blood_volume]`,
            `blood_data[${idx}][blood_quantity]`,
        ];

        fields.forEach((fieldName) => {
            formValidation.addField(fieldName, {
                validators: {
                    notEmpty: bloodFieldValidators.notEmpty,
                },
            });
        });
    }
    // ---------- Fungsi untuk menambahkan validasi per baris blood data :end ----------

    // ---------- Fungsi untuk menghapus validasi per baris blood data :begin ----------
    function removeBloodRowValidation(idx) {
        const fields = [
            `blood_data[${idx}][blood_group]`,
            `blood_data[${idx}][blood_rhesus]`,
            `blood_data[${idx}][blood_component]`,
            `blood_data[${idx}][blood_volume]`,
            `blood_data[${idx}][blood_quantity]`,
        ];

        fields.forEach((fieldName) => {
            formValidation.removeField(fieldName);
        });
    }
    // ---------- Fungsi untuk menghapus validasi per baris blood data :end ----------

    // ---------- Fungsi untuk menghapus baris blood data :begin ----------
    function deleteBloodRow(row) {
        const bloodDataRows = bloodDataContainer.querySelectorAll(BloodDataRow);
        if (bloodDataRows.length <= 1) {
            notyf.error({
                message: "Minimal 1 blood data!",
            });
            return;
        }
        const idx = row.dataset.idx;
        removeBloodRowValidation(idx);
        row.remove();
        syncDeleteButtons();
    }
    // ---------- Fungsi untuk menghapus baris blood data :end ----------

    // ---------- Fungsi untuk disable button delete jika sisa 1 :begin ----------
    function syncDeleteButtons() {
        const bloodDataRows = bloodDataContainer.querySelectorAll(BloodDataRow);
        bloodDataRows.forEach((row) => {
            const btn = row.querySelector(DeleteBloodDataRowButton);
            btn.disabled = bloodDataRows.length <= 1;
        });
    }
    // ---------- Fungsi untuk disable button delete jika sisa 1 :end ----------

    // ---------- Fungsi untuk membuat array blood data :begin ----------
    function getBloodData() {
        const rows = bloodDataContainer.querySelectorAll(BloodDataRow);
        const result = [];
        rows.forEach((row) => {
            result.push({
                blood_group: row.querySelector('[name*="blood_group"]').value,
                blood_rhesus: row.querySelector('[name*="blood_rhesus"]').value,
                blood_component: row.querySelector('[name*="blood_component"]')
                    .value,
                blood_volume: row.querySelector('[name*="blood_volume"]').value,
                blood_quantity: row.querySelector('[name*="blood_quantity"]')
                    .value,
                is_hiv: row.querySelector('[name*="is_hiv"]').checked,
                is_hcv: row.querySelector('[name*="is_hcv"]').checked,
                is_hbsag: row.querySelector('[name*="is_hbsag"]').checked,
                is_syphilis: row.querySelector('[name*="is_syphilis"]').checked,
            });
        });
        return result;
    }
    // ---------- Fungsi untuk membuat array blood data :end ----------

    return { formValidation, getBloodData };
}
// ---------- Fungsi untuk mengelola form add new order :end ----------

// ---------- Fungsi untuk generate PO Number :begin ----------
function GeneratePoNumber() {
    const poNumberInput = document.getElementById(PoNumberInputSelector);
    if (!poNumberInput) return;

    poNumberInput.addEventListener("click", async () => {
        // Hindari generate ulang jika sudah ada nilai
        if (poNumberInput.value) return;

        try {
            const res = await fetch(UrlGetNewPoNumber);
            const data = await res.json();
            poNumberInput.value = data;
            notyf.success({
                message: "PO Number Generated Successfully!",
            });
        } catch (err) {
            notyf.error({
                message: "Failed to generate PO Number!",
            });
            console.error(err);
        }
    });
}
// ---------- Fungsi untuk generate PO Number :end ----------

// ---------- Fungsi untuk submit data form add new order :begin ----------
function SubmitFormAddNewOrder() {
    new GlobalSubmitForm({
        formId: FormAddNewOrderSelector,
        url: UrlPostNewOrder,
        method: "POST",
        validator: orderForm.formValidation,
        beforeSubmit: (formData) => {
            const bloodData = orderForm.getBloodData();
            bloodData.forEach((item, index) => {
                formData.set(
                    `blood_data[${index}][blood_group]`,
                    item.blood_group,
                );
                formData.set(
                    `blood_data[${index}][blood_rhesus]`,
                    item.blood_rhesus,
                );
                formData.set(
                    `blood_data[${index}][blood_component]`,
                    item.blood_component,
                );
                formData.set(
                    `blood_data[${index}][blood_volume]`,
                    item.blood_volume,
                );
                formData.set(
                    `blood_data[${index}][blood_quantity]`,
                    item.blood_quantity,
                );
                formData.set(
                    `blood_data[${index}][is_hiv]`,
                    item.is_hiv ? 1 : 0,
                );
                formData.set(
                    `blood_data[${index}][is_hcv]`,
                    item.is_hcv ? 1 : 0,
                );
                formData.set(
                    `blood_data[${index}][is_hbsag]`,
                    item.is_hbsag ? 1 : 0,
                );
                formData.set(
                    `blood_data[${index}][is_syphilis]`,
                    item.is_syphilis ? 1 : 0,
                );
            });
            return formData;
        },
        onSuccess: (data) => {
            notyf.success({
                message: "New order added successfully!",
            });
            console.log(data);
            // contoh: reload datatable atau redirect
            // window.dispatchEvent(new Event(ReloadDatatableSelector));
        },
        onError: (err) => {
            notyf.error({
                message: "Failed to add new order!",
            });
            console.error(err);
        },
        resetOnSuccess: true,
    });
}
// ---------- Fungsi untuk submit data form add new order :end ----------

document.addEventListener("DOMContentLoaded", () => {
    SelectVendor();
    GeneratePoNumber();
    SubmitFormAddNewOrder(orderForm);
});
