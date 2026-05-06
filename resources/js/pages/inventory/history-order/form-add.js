import TomSelect from "tom-select";
import { GlobalSubmitForm, GlobalFormValidation } from "../../../app";

// ---------- Global variable untuk memudahkan penyesuaian :begin ----------
const FormAddNewOrderSelector = "add_new_order";
const PoNumberInputSelector = "po_number";
const UrlPostNewOrder = "/inventory/history-order/new-order";
const UrlGetNewPoNumber = "/inventory/history-order/new-po-number";
const TableRowBloodData = "blood_data_row";
const AddRowButton = ".add_row_blood_data";
const AddRowCountInput = "#add_row_blood_data_count";
// ---------- Global variable untuk memudahkan penyesuaian :begin ----------

// ---------- State global :begin ----------
let formValidation = null;

const REQUIRED_FIELDS = ["blood_pack_id", "quantity"];

function f(idx, field) {
    return `blood_data[${idx}][${field}]`;
}
// ---------- State global :end ----------

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

// ---------- Helper: init TomSelect pada elemen dengan value default :begin ----------
function initTomSelect(el, url) {
    if (!el) return;
    if (el.tomselect) el.tomselect.destroy();

    return new TomSelect(el, {
        valueField: "id",
        labelField: "text",
        searchField: "text",
        preload: true,
        create: false,
        dropdownParent: "body",
        load: function (query, callback) {
            fetch(`${url}?q=${encodeURIComponent(query)}`)
                .then((res) => res.json())
                .then((json) => callback(json.results))
                .catch(() => callback());
        },
    });
}
// ---------- Helper: init TomSelect pada elemen dengan value default :end ----------

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

// ---------- Fungsi untuk mengelola form add new order :begin ----------
function HandleFormAddNewOrder() {
    const addRowBtn = document.querySelector(AddRowButton);
    const addRowCountInput = document.querySelector(AddRowCountInput);
    const tableBody = document.getElementById(TableRowBloodData);

    // ---------- Render Row ----------
    function RenderTableRow(idx) {
        return `
            <tr id="row_blood_data_${idx}">
                <td>
                    <select class="form-control form-control-sm"
                        id="${f(idx, "blood_pack_id")}" name="${f(idx, "blood_pack_id")}" placeholder="Choose blood"></select>
                </td>
                <td>
                    <input type="text" class="form-control form-control-sm"
                        id="${f(idx, "quantity")}" name="${f(idx, "quantity")}" placeholder="Quantity" />
                </td>
                <td>
                    <textarea autocomplete="off" class="form-control form-control-sm" id="${f(idx, "note")}" name="${f(idx, "note")}" placeholder="Note" type="text" rows="3"></textarea>
                </td>
                <td>
                    <button type="button" class="btn btn-sm btn-soft-danger delete_row_blood_data"
                        data-id="row_blood_data_${idx}">
                        <i class="ti ti-trash"></i>
                    </button>
                </td>
            </tr>
        `;
    }

    // ---------- Init TomSelect untuk 1 row (by idx) ----------
    function initTomSelectsForRow(idx) {
        initTomSelect(
            tableBody.querySelector(`[id="${f(idx, "blood_pack_id")}"]`),
            "/utility/select/blood-pack",
        );
    }

    // ---------- Tambah validasi per row :begin ----------
    function addRowValidation(idx) {
        REQUIRED_FIELDS.forEach((field) => {
            formValidation.addField(f(idx, field), {
                validators: {
                    notEmpty: { message: "This field is required" },
                },
            });
        });
    }
    // ---------- Tambah validasi per row :end ----------

    // ---------- Hapus validasi per row :begin ----------
    function removeRowValidation(idx) {
        REQUIRED_FIELDS.forEach((field) => {
            try {
                formValidation.removeField(f(idx, field));
            } catch (_) {}
        });
    }
    // ---------- Hapus validasi per row :end ----------

    // ---------- Reset seluruh tabel ke kondisi awal (1 baris kosong) :begin ----------
    function resetTable() {
        // Destroy semua TomSelect di tiap row sebelum clear innerHTML
        tableBody.querySelectorAll("select").forEach((el) => {
            if (el.tomselect) el.tomselect.destroy();
        });

        // Hapus validasi semua row yang ada
        const rowCount = tableBody.querySelectorAll("tr").length;
        for (let i = 0; i < rowCount; i++) {
            removeRowValidation(i);
        }

        // Kosongkan tabel, tambah 1 baris default
        tableBody.innerHTML = "";
        AddTableRow(1);
    }
    // ---------- Reset seluruh tabel ke kondisi awal (1 baris kosong) :end ----------

    // ---------- Add Row (dengan inisialisasi picker & select) ----------
    function AddTableRow(count = 1) {
        for (let i = 0; i < count; i++) {
            const idx = tableBody.children.length;
            tableBody.insertAdjacentHTML("beforeend", RenderTableRow(idx));
            initTomSelectsForRow(idx);
            addRowValidation(idx);
        }
    }

    // ---------- Delete Row ----------
    function DeleteTableRow(e) {
        const btn = e.target.closest(".delete_row_blood_data");
        if (!btn) return;

        // Jangan hapus jika hanya 1 baris tersisa
        if (tableBody.querySelectorAll("tr").length <= 1) {
            notyf.error({ message: "At least 1 row is required!" });
            return;
        }

        const rowId = btn.getAttribute("data-id");
        const row = document.getElementById(rowId);
        if (!row) return;

        const oldIdx = parseInt(rowId.replace("row_blood_data_", ""));
        removeRowValidation(oldIdx);

        // Destroy TomSelect di row ini sebelum dihapus
        row.querySelectorAll("select").forEach((el) => {
            if (el.tomselect) el.tomselect.destroy();
        });

        row.remove();
        ReorderTableRows();
    }

    // ---------- Reorder setelah delete ----------
    function ReorderTableRows() {
        const rows = tableBody.querySelectorAll("tr");

        rows.forEach((row, index) => {
            const oldIndex = parseInt(row.id.replace("row_blood_data_", ""));

            if (oldIndex === index) return; // sudah benar, skip

            // Hapus validasi index lama
            removeRowValidation(oldIndex);

            // Update row id
            row.id = `row_blood_data_${index}`;

            // Update name & id semua input/select
            row.querySelectorAll("input, select").forEach((el) => {
                if (!el.name) return;
                el.name = el.name.replace(
                    /blood_data\[\d+\]/,
                    `blood_data[${index}]`,
                );
                el.id = el.id.replace(
                    /blood_data\[\d+\]/,
                    `blood_data[${index}]`,
                );
            });

            // Update tombol delete
            const delBtn = row.querySelector(".delete_row_blood_data");
            if (delBtn)
                delBtn.setAttribute("data-id", `row_blood_data_${index}`);

            // Daftarkan ulang validasi dengan index baru
            addRowValidation(index);
        });
    }

    // ---------- Kumpulkan semua data blood dari card yang ada di DOM :begin ----------
    function getBloodData() {
        const rows = tableBody.querySelectorAll("tr");
        const result = [];

        rows.forEach((tr, index) => {
            const val = (field) => {
                const el = tr.querySelector(`[name="${f(index, field)}"]`);
                return el ? el.value : "";
            };

            result.push({
                blood_pack_id: val("blood_pack_id"),
                quantity: val("quantity"),
                note: val("note"),
            });
        });

        return result;
    }
    // ---------- Kumpulkan semua data blood dari card yang ada di DOM :end ----------

    // ---------- Event: Add Row Button ----------
    addRowBtn.addEventListener("click", () => {
        const count = parseInt(addRowCountInput?.value);
        if (!count || count < 1) {
            notyf.error({ message: "Fill the count row input first!" });
            return;
        }
        AddTableRow(count);
        if (addRowCountInput) addRowCountInput.value = "";
    });

    // ---------- Event: Delete Row ----------
    tableBody.addEventListener("click", DeleteTableRow);

    // ---------- Default: 1 baris saat pertama kali ----------
    AddTableRow(1);

    // ---------- Init GlobalSubmitForm untuk handle submit dan validasi :begin ----------
    new GlobalSubmitForm({
        formId: FormAddNewOrderSelector,
        url: UrlPostNewOrder,
        method: "POST",
        validator: formValidation,
        beforeSubmit: (formData) => {
            showPageLoading();

            for (const key of [...formData.keys()]) {
                if (key.startsWith("blood_data[")) formData.delete(key);
            }

            getBloodData().forEach((item, index) => {
                Object.entries(item).forEach(([field, value]) => {
                    formData.set(`blood_data[${index}][${field}]`, value);
                });
            });

            return formData;
        },
        onValidationError: () => {
            hidePageLoading();
            notyf.error({
                message: "There's a field that needs to be filled!",
            });
        },
        onSuccess: () => {
            resetTable();
            notyf.success({ message: "New order added successfully!" });
            setTimeout(() => {
                hidePageLoading();
                window.location.href = "/inventory/history-order/";
            }, 2000);
        },
        onError: (err) => {
            hidePageLoading();
            notyf.error({
                message: err?.message ?? "Failed to add new order!",
            });
            console.error(err);
        },
        resetOnSuccess: true,
    });
    // ---------- Init GlobalSubmitForm untuk handle submit dan validasi :end ----------
}
// ---------- Fungsi untuk mengelola form add new order :end ----------

document.addEventListener("DOMContentLoaded", () => {
    formValidation = GlobalFormValidation.init("#" + FormAddNewOrderSelector, {
        po_number: {
            validators: {
                notEmpty: { message: "Purchase Order is required" },
            },
        },
        vendor_id: {
            validators: {
                notEmpty: { message: "Vendor is required" },
            },
        },
    });
    SelectVendor();
    GeneratePoNumber();
    HandleFormAddNewOrder();
});
