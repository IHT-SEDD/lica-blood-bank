import {
    TomSelectDefault,
    TomSelectWithValue,
} from "../../../../utility/select";

// ---------- Global variable untuk memudahkan penyesuaian :begin ----------
export const TableRowBloodData = "blood_data_row";
export const AddRowButton = ".add_row_blood_data";
export const AddRowCountInput = "#add_row_blood_data_count";

// URLS
const SelectBloodPackUrl = "/utility/select/blood-pack";
// ---------- Global variable untuk memudahkan penyesuaian :end ----------

// ---------- State global :begin ----------
function f(idx, field) {
    return `blood_data[${idx}][${field}]`;
}
// ---------- State global :end ----------

// ---------- Handler form table blood ----------
export function HandleTableBlood() {
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
                    <input type="text" class="form-control"
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

    // ---------- Init TomSelect untuk 1 row (by idx), tanpa value ----------
    function initTomSelectsForRow(idx) {
        TomSelectDefault(
            tableBody.querySelector(`[id="${f(idx, "blood_pack_id")}"]`),
            SelectBloodPackUrl,
        );
    }

    // ---------- Init TomSelect untuk 1 row dengan value awal ----------
    function initTomSelectsForRowWithValue(idx, bloodPackId, label) {
        TomSelectWithValue(
            tableBody.querySelector(`[id="${f(idx, "blood_pack_id")}"]`),
            SelectBloodPackUrl,
            bloodPackId,
            label,
        );
    }

    // ---------- Reset seluruh tabel ke kondisi awal (1 baris kosong) ----------
    function resetTable() {
        tableBody.querySelectorAll("select").forEach((el) => {
            if (el.tomselect) el.tomselect.destroy();
        });
        tableBody.innerHTML = "";
        AddTableRow(1);
    }

    // ---------- Add Row kosong ----------
    function AddTableRow(count = 1) {
        for (let i = 0; i < count; i++) {
            const idx = tableBody.children.length;
            tableBody.insertAdjacentHTML("beforeend", RenderTableRow(idx));
            initTomSelectsForRow(idx);
        }
    }

    // ---------- Populate tabel dari data order ----------
    function PopulateTableFromOrder(details) {
        // Bersihkan baris default yang dibuat AddTableRow(1)
        tableBody.querySelectorAll("select").forEach((el) => {
            if (el.tomselect) el.tomselect.destroy();
        });
        tableBody.innerHTML = "";

        if (!details || details.length === 0) {
            // Fallback: tetap tampilkan 1 baris kosong
            AddTableRow(1);
            return;
        }

        details.forEach((detail, idx) => {
            tableBody.insertAdjacentHTML("beforeend", RenderTableRow(idx));

            // Buat label dari data blood_packs: "A+ WB"
            const bp = detail.blood_packs;
            const label = bp
                ? `${bp.blood_group}${bp.blood_rhesus} ${bp.blood_component}`
                : String(detail.blood_pack_id);

            // Init TomSelect dengan value (blood_pack_id) & label
            initTomSelectsForRowWithValue(idx, detail.blood_pack_id, label);

            // Isi quantity & note
            const quantityEl = tableBody.querySelector(
                `[id="${f(idx, "quantity")}"]`,
            );
            const noteEl = tableBody.querySelector(`[id="${f(idx, "note")}"]`);

            if (quantityEl) quantityEl.value = detail.quantity ?? "";
            if (noteEl) noteEl.value = detail.note ?? "";
        });
    }

    // ---------- Delete Row ----------
    function DeleteTableRow(e) {
        const btn = e.target.closest(".delete_row_blood_data");
        if (!btn) return;

        if (tableBody.querySelectorAll("tr").length <= 1) {
            notyf.error({ message: "At least 1 row is required!" });
            return;
        }

        const rowId = btn.getAttribute("data-id");
        const row = document.getElementById(rowId);
        if (!row) return;

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
            if (oldIndex === index) return;

            row.id = `row_blood_data_${index}`;

            row.querySelectorAll("input, select, textarea").forEach((el) => {
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

            const delBtn = row.querySelector(".delete_row_blood_data");
            if (delBtn)
                delBtn.setAttribute("data-id", `row_blood_data_${index}`);
        });
    }

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

    // Ekspos fungsi populate agar bisa dipanggil dari luar
    return { PopulateTableFromOrder };
}
