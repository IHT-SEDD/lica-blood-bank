import TomSelect from "tom-select";
import {
    GlobalSubmitForm,
    GlobalFormValidation,
    GlobalAdvanceFlatpickr,
} from "../../../app";

// ---------- Global variable untuk memudahkan penyesuaian :begin ----------
// Static Inputs
const ChoosePOSelector = "#select-purchase-order";
const ChooseAddMethodSelector = "#select-add-data-method";

// Wrapper Form
const FormAddIncomingStockSelector = "add_new_incoming_stock";
const MainFormWrapper = "main_form_wrapper";
const FormManualWrapper = "add_manually_method_wrapper";
const FormExcelWrapper = "add_excel_method_wrapper";

// Form Utilities
const LoadingForm = "loading_form_add_new_incoming_stock";
const UrlPostIncomingStock = "/inventory/stock-in/data/new";

// Form Manual
const TableRowBloodData = "blood_data_row";
const AddRowButton = ".add_row_blood_data";
const AddRowCountInput = "#add_row_blood_data_count";

// Form Excel
const TemplateExcelPath = "/assets/files/Template Add Incoming Stock.xlsx";
const TemplateExcelFileName = "Template_Add_Incoming_Stock.xlsx";
// ---------- Global variable untuk memudahkan penyesuaian :begin ----------

// ---------- State global :begin ----------
let currentMethod = "manual";
let formValidation = null;
// ---------- State global :end ----------

const REQUIRED_FIELDS = [
    "bag_number",
    "blood_pack_id",
    "blood_volume",
    "aftap_date",
    "expiry_date",
    "process_date",
];

function f(idx, field) {
    return `blood_data[${idx}][${field}]`;
}

// ---------- Helper: toggle tampilan form berdasarkan method :begin ----------
function toggleFormMethod(methodId) {
    const formLoading = document.getElementById(LoadingForm);
    const formManual = document.getElementById(FormManualWrapper);
    const formExcel = document.getElementById(FormExcelWrapper);

    currentMethod = methodId;

    if (methodId === "manual") {
        formLoading.classList.add("d-none");
        formManual.classList.remove("d-none");
        formExcel.classList.add("d-none");

        // Daftarkan kembali validasi semua row blood_data yang ada di DOM
        if (formValidation) {
            const rows =
                document
                    .getElementById(TableRowBloodData)
                    ?.querySelectorAll("tr") ?? [];
            rows.forEach((_, idx) => {
                REQUIRED_FIELDS.forEach((field) => {
                    formValidation.addField(f(idx, field), {
                        validators: {
                            notEmpty: { message: "This field is required" },
                        },
                    });
                });
            });
        }
    } else if (methodId === "excel") {
        formLoading.classList.add("d-none");
        formManual.classList.add("d-none");
        formExcel.classList.remove("d-none");

        // Hapus validasi semua row blood_data dari rules agar tidak ikut divalidasi
        if (formValidation) {
            const rows =
                document
                    .getElementById(TableRowBloodData)
                    ?.querySelectorAll("tr") ?? [];
            rows.forEach((_, idx) => {
                REQUIRED_FIELDS.forEach((field) => {
                    try {
                        formValidation.removeField(f(idx, field));
                    } catch (_) {}
                });
            });
        }
    }
}
// ---------- Helper: toggle tampilan form berdasarkan method :end ----------

// ---------- Helper: init flatpickr pada elemen :begin ----------
function initFlatpickr(el) {
    if (!el) return;
    const escapedId = el.id.replace(/\[/g, "\\[").replace(/\]/g, "\\]");
    new GlobalAdvanceFlatpickr(`#${escapedId}`, { allowInput: true });
}
// ---------- Helper: init flatpickr pada elemen :end ----------

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

// ---------- Select po dari tom-select untuk form add new data :begin ----------
function SelectPO() {
    new TomSelect(ChoosePOSelector, {
        valueField: "text",
        labelField: "text",
        searchField: "text",
        sortField: { field: "id", direction: "asc" },
        create: false,
        preload: true,
        load: function (query, callback) {
            fetch(
                `/utility/select/purchase-order?q=${encodeURIComponent(query)}`,
            )
                .then((res) => res.json())
                .then((json) => callback(json.results))
                .catch(() => callback());
        },
    });
}
// ---------- Select po dari tom-select untuk form add new data :begin ----------

// ---------- Select add method dari tom-select :begin ----------
function SelectAddMethod() {
    const ts = new TomSelect(ChooseAddMethodSelector, {
        valueField: "id",
        labelField: "text",
        searchField: "text",
        sortField: { field: "id", direction: "asc" },
        create: false,
        preload: true,
        load: function (query, callback) {
            fetch(
                `/utility/select/add-incoming-stock-method?q=${encodeURIComponent(query)}`,
            )
                .then((res) => res.json())
                .then((json) => {
                    callback(json.results);
                    ts.setValue("manual", true);
                    toggleFormMethod("manual");
                })
                .catch(() => callback());
        },
        onChange: (methodId) => toggleFormMethod(methodId),
    });
}
// ---------- Select add method dari tom-select :end ----------

// ---------- Download Template Excel :begin ----------
/**
 * Menggunakan fetch + streaming ke Blob agar file langsung didownload
 * tanpa membuka tab baru, dan bekerja meski path mengandung spasi.
 */
function HandleDownloadTemplate() {
    const btn = document.getElementById("download_template_excel");
    if (!btn) return;
    btn.addEventListener("click", async () => {
        // Tampilkan loading state di tombol
        const originalHTML = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = `<span class="spinner-border spinner-border-sm me-2" role="status"></span>Downloading...`;

        try {
            const response = await fetch(TemplateExcelPath);

            if (!response.ok) {
                throw new Error(`File not found (${response.status})`);
            }

            // Stream response ke Blob — lebih cepat untuk file besar
            const blob = await response.blob();
            const url = URL.createObjectURL(blob);

            const a = document.createElement("a");
            a.href = url;
            a.download = TemplateExcelFileName;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);

            // Revoke object URL setelah download selesai (cleanup memory)
            setTimeout(() => URL.revokeObjectURL(url), 10_000);
        } catch (err) {
            console.error("Download template failed:", err);
            notyf.error({ message: "Gagal mengunduh template excel!" });
        } finally {
            btn.disabled = false;
            btn.innerHTML = originalHTML;
        }
    });
}
// ---------- Download Template Excel :end ----------

// ---------- Validasi file excel saat submit :begin ----------
/**
 * Validasi input file saat method adalah excel.
 * Dipanggil di dalam beforeSubmit sebelum dikirim ke server.
 * Return: true jika valid, false jika tidak.
 */
function validateExcelFile() {
    const fileInput = document.getElementById("incoming_stock_excel");

    if (!fileInput || !fileInput.files || fileInput.files.length === 0) {
        notyf.error({ message: "File must be uploaded!" });
        return false;
    }

    const file = fileInput.files[0];
    const allowedTypes = [
        "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet", // xlsx
        "application/vnd.ms-excel", // xls
        "text/csv",
        "application/csv",
    ];
    const allowedExtensions = /\.(xlsx|xls|csv)$/i;
    const maxSizeBytes = 10 * 1024 * 1024; // 10MB

    if (!allowedExtensions.test(file.name)) {
        notyf.error({ message: "File must be a .xlxs, .xls, or .csv!" });
        return false;
    }

    if (!allowedTypes.includes(file.type) && file.type !== "") {
        // Beberapa browser melaporkan type kosong untuk csv — skip check jika empty
        if (file.type !== "") {
            notyf.error({ message: "File type not valid!" });
            return false;
        }
    }

    if (file.size > maxSizeBytes) {
        notyf.error({ message: "Maximum file size is 10MB!" });
        return false;
    }

    return true;
}
// ---------- Validasi file excel saat submit :end ----------

// ---------- Fungsi untuk form method manual :begin ----------
function HandleFormManual() {
    const addRowBtn = document.querySelector(AddRowButton);
    const addRowCountInput = document.querySelector(AddRowCountInput);
    const tableBody = document.getElementById(TableRowBloodData);

    // ---------- Render Row ----------
    function RenderTableRow(idx) {
        return `
            <tr id="row_blood_data_${idx}">
                <td>
                    <input type="text" class="form-control form-control-sm"
                        id="${f(idx, "bag_number")}" name="${f(idx, "bag_number")}" placeholder="Bag number" />
                </td>
                <td>
                    <select class="form-control form-control-sm"
                        id="${f(idx, "blood_pack_id")}" name="${f(idx, "blood_pack_id")}" placeholder="Blood pack"></select>
                </td>
                <td>
                    <input type="text" class="form-control form-control-sm"
                        id="${f(idx, "blood_volume")}" name="${f(idx, "blood_volume")}" placeholder="mL" />
                </td>
                <td>
                    <input type="text" class="form-control form-control-sm"
                        id="${f(idx, "aftap_date")}" name="${f(idx, "aftap_date")}"
                        data-date-format="d-m-Y" data-provider="flatpickr" />
                </td>
                <td>
                    <input type="text" class="form-control form-control-sm"
                        id="${f(idx, "expiry_date")}" name="${f(idx, "expiry_date")}"
                        data-date-format="d-m-Y" data-provider="flatpickr" />
                </td>
                <td>
                    <input type="text" class="form-control form-control-sm"
                        id="${f(idx, "process_date")}" name="${f(idx, "process_date")}"
                        data-date-format="d-m-Y" data-provider="flatpickr" />
                </td>
                <td>
                    <div class="form-check form-check-danger">
                        <input type="checkbox" class="form-check-input"
                            id="${f(idx, "is_hiv")}" name="${f(idx, "is_hiv")}" />
                    </div>
                </td>
                <td>
                    <div class="form-check form-check-danger">
                        <input type="checkbox" class="form-check-input"
                            id="${f(idx, "is_hcv")}" name="${f(idx, "is_hcv")}" />
                    </div>
                </td>
                <td>
                    <div class="form-check form-check-danger">
                        <input type="checkbox" class="form-check-input"
                            id="${f(idx, "is_hbsag")}" name="${f(idx, "is_hbsag")}" />
                    </div>
                </td>
                <td>
                    <div class="form-check form-check-danger">
                        <input type="checkbox" class="form-check-input"
                            id="${f(idx, "is_syphilis")}" name="${f(idx, "is_syphilis")}" />
                    </div>
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

    // ---------- Init Flatpickr untuk 1 row (by idx) ----------
    function initDatePickersForRow(idx) {
        ["aftap_date", "expiry_date", "process_date"].forEach((field) => {
            const el = tableBody.querySelector(`[id="${f(idx, field)}"]`);
            initFlatpickr(el);
        });
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
        // Destroy semua TomSelect & Flatpickr di tiap row sebelum clear innerHTML
        tableBody.querySelectorAll("select").forEach((el) => {
            if (el.tomselect) el.tomselect.destroy();
        });
        tableBody
            .querySelectorAll("input[data-provider='flatpickr']")
            .forEach((el) => {
                if (el._flatpickr) el._flatpickr.destroy();
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
            initDatePickersForRow(idx);
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

        // Destroy TomSelect & Flatpickr di row ini sebelum dihapus
        row.querySelectorAll("select").forEach((el) => {
            if (el.tomselect) el.tomselect.destroy();
        });
        row.querySelectorAll("input[data-provider='flatpickr']").forEach(
            (el) => {
                if (el._flatpickr) el._flatpickr.destroy();
            },
        );

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
            const checked = (field) => {
                const el = tr.querySelector(`[name="${f(index, field)}"]`);
                return el?.checked ? 1 : 0;
            };

            result.push({
                bag_number: val("bag_number"),
                blood_pack_id: val("blood_pack_id"),
                blood_volume: val("blood_volume"),
                aftap_date: val("aftap_date"),
                expiry_date: val("expiry_date"),
                process_date: val("process_date"),
                is_hiv: checked("is_hiv"),
                is_hcv: checked("is_hcv"),
                is_hbsag: checked("is_hbsag"),
                is_syphilis: checked("is_syphilis"),
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
        formId: FormAddIncomingStockSelector,
        url: UrlPostIncomingStock,
        method: "POST",
        validator: formValidation,
        beforeSubmit: (formData) => {
            if (currentMethod === "excel") {
                if (!validateExcelFile()) {
                    hidePageLoading();
                    return null; // Batalkan submit
                }

                // Bersihkan blood_data manual dari formData
                for (const key of [...formData.keys()]) {
                    if (key.startsWith("blood_data[")) formData.delete(key);
                }

                // Ganti nama field file agar sesuai dengan yang diharapkan backend
                const fileInput = document.getElementById(
                    "incoming_stock_excel",
                );
                if (fileInput?.files?.[0]) {
                    formData.delete("incoming_stock_excel");
                    formData.append("excel_file", fileInput.files[0]);
                }
            }

            if (currentMethod === "manual") {
                showPageLoading();
                formData.delete("incoming_stock_excel");

                for (const key of [...formData.keys()]) {
                    if (key.startsWith("blood_data[")) formData.delete(key);
                }

                getBloodData().forEach((item, index) => {
                    Object.entries(item).forEach(([field, value]) => {
                        formData.set(`blood_data[${index}][${field}]`, value);
                    });
                });
            } else if (currentMethod === "excel") {
                showPageLoading();
            }

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
            notyf.success({ message: "Incoming stock added successfully!" });
            setTimeout(() => {
                hidePageLoading();
                window.location.href = "/inventory/stock-in/";
            }, 2000);
        },
        onError: (err) => {
            if (err?.duplicates && err.duplicates.length > 0) {
                const dupList = err.duplicates.join(", ");
                notyf.error({
                    message: `Bag number already used: <strong>${dupList}</strong>`,
                    duration: 6000,
                });
                return;
            }

            notyf.error({
                message: err?.message ?? "Failed to add incoming stock!",
            });
            hidePageLoading();
            console.error(err);
        },
        resetOnSuccess: true,
    });
    // ---------- Init GlobalSubmitForm untuk handle submit dan validasi :end ----------
}
// ---------- Fungsi untuk form method manual :end ----------

document.addEventListener("DOMContentLoaded", () => {
    document.getElementById(LoadingForm).classList.remove("d-none");

    formValidation = GlobalFormValidation.init(
        "#" + FormAddIncomingStockSelector,
        {
            po_number: {
                validators: {
                    notEmpty: { message: "Purchase Order is required" },
                },
            },
            method_add: {
                validators: {
                    notEmpty: { message: "Method is required" },
                },
            },
        },
    );

    SelectPO();
    SelectAddMethod();
    HandleFormManual();

    HandleDownloadTemplate();
});
