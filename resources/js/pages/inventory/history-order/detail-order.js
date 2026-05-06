import TomSelect from "tom-select";

// ---------- Global variable untuk memudahkan penyesuaian :begin ----------
const LoadingOverlaySelector = "fullscreen_loading_overlay";

// URL
const UrlDetailOrder = "/inventory/history-order/detail/data";
const UrlGeneratePO = "/inventory/history-order/po-file/generate";
const UrlPreviewPO = "/inventory/history-order/po-file/preview";
const UrlDownloadPO = "/inventory/history-order/po-file/download";
const UrlPrintPO = "/inventory/history-order/po-file/print";

// Table Blood
const TableRowBloodData = "blood_data_row";
const AddRowButton = ".add_row_blood_data";
const AddRowCountInput = "#add_row_blood_data_count";

const DONE_STATUS = "done";
const DRAFT_STATUS = "draft";
const ORDER_CREATED_STATUS = "order_created";

// Toolbar button IDs
const ToolbarWrapper = "toolbar_wrapper";
const BtnPrintPO = "print_po_btn";
const BtnDownloadPO = "download_po_btn";
const BtnGeneratePO = "generate_po_btn";
const BtnPreviewPO = "preview_po_btn";
const BtnDone = "update_to_done_btn";
const BtnDelete = "delete_order_btn";
const BtnEditOrder = "edit_order_btn";
const BtnCancelEditOrder = "cancel_edit_order_btn";
const BtnSubmitChanges = "submit_order_btn";
// ---------- Global variable untuk memudahkan penyesuaian :begin ----------

// ---------- State global :begin ----------
let formValidation = null;
let currentOrderData = null;

const REQUIRED_FIELDS = ["blood_pack_id", "quantity"];

function f(idx, field) {
    return `blood_data[${idx}][${field}]`;
}
// ---------- State global :end ----------

// ---------- Helper: ambil ID dari URL saat ini----------
function getIdFromUrl() {
    const segments = window.location.pathname.split("/");
    return segments[segments.length - 1];
}

// ---------- Helper: init TomSelect pada elemen (tanpa value) ----------
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

// ---------- Helper: init TomSelect pada elemen dengan value default----------
function initTomSelectWithValue(el, url, value, label) {
    if (!el) return;
    if (el.tomselect) el.tomselect.destroy();

    const ts = new TomSelect(el, {
        valueField: "id",
        labelField: "text",
        searchField: "text",
        preload: true,
        create: false,
        dropdownParent: "body",
        // Inject option awal agar value bisa di-set sebelum load selesai
        options: value && label ? [{ id: value, text: label }] : [],
        load: function (query, callback) {
            fetch(`${url}?q=${encodeURIComponent(query)}`)
                .then((res) => res.json())
                .then((json) => callback(json.results))
                .catch(() => callback());
        },
    });

    // Set value setelah instance siap (silent = true agar onChange tidak terpicu)
    if (value !== undefined && value !== null) {
        ts.setValue(value, true);
    }

    return ts;
}

// ---------- Helper: cek apakah status adalah done ----------
function isStatusDone(status) {
    return status?.toLowerCase() === DONE_STATUS;
}

// ---------- Helper: cek apakah status adalah draft ----------
function isStatusDraft(status) {
    return status?.toLowerCase() === DRAFT_STATUS;
}

// ---------- Helper: cek apakah status adalah order_created ----------
function isStatusOrderCreated(status) {
    return status?.toLowerCase() === ORDER_CREATED_STATUS;
}

// ---------- Helper: toggle d-none pada sebuah elemen ----------
function setHidden(id, hidden) {
    const el = document.getElementById(id);
    if (!el) return;
    hidden ? el.classList.add("d-none") : el.classList.remove("d-none");
}

// ---------- Toolbar: atur visibilitas tombol sesuai kondisi order ----------
function applyToolbarState(order) {
    const hasPOFile = !!order?.po_file_path;
    const isDone = isStatusDone(order?.status);
    const isDraft = isStatusDraft(order?.status);
    const isOrderCreated = isStatusOrderCreated(order?.status);
    const isDeleted = !!order?.deleted_at;

    // Print & Download PO: tampil kalau ada file PO
    setHidden(BtnPrintPO, !hasPOFile);
    setHidden(BtnDownloadPO, !hasPOFile);

    // Generate PO: tampil kalau BELUM ada file PO
    setHidden(BtnGeneratePO, hasPOFile);
    const generateBtn = document.getElementById(BtnGeneratePO);
    if (generateBtn) generateBtn.disabled = isDraft;

    // Done: sembunyikan kalau status sudah done
    setHidden(BtnDone, isDone);

    // Delete: sembunyikan kalau status done
    const deleteBtn = document.getElementById(BtnDelete);
    const canDelete = (isDraft || isOrderCreated) && !isDeleted;
    if (deleteBtn) deleteBtn.disabled = !canDelete;

    // Edit Order: hanya aktif jika draft / order_created dan tidak deleted
    const editBtn = document.getElementById(BtnEditOrder);
    const canEdit = (isDraft || isOrderCreated) && !isDeleted;
    if (editBtn) editBtn.disabled = !canEdit;

    // Submit Changes: selalu tersembunyi saat awal (toggle lewat HandleEditOrderBtn)
    setHidden(BtnSubmitChanges, true);
    setHidden(BtnCancelEditOrder, true);

    const toolbarEl = document.getElementById(ToolbarWrapper);
    if (toolbarEl) {
        const excludedFromCount = [
            BtnEditOrder,
            BtnSubmitChanges,
            BtnCancelEditOrder,
        ];

        const hasVisibleBtn = Array.from(
            toolbarEl.querySelectorAll("button[id]"),
        ).some(
            (btn) =>
                !excludedFromCount.includes(btn.id) &&
                !btn.classList.contains("d-none"),
        );

        const editOrderUsable = !!order && !isDeleted;
        const shouldShow = hasVisibleBtn || editOrderUsable;

        toolbarEl.classList.toggle("d-none", !shouldShow);
    }
}

// ---------- Handler tombol Edit Order Data ----------
function HandleEditOrderBtn() {
    const editBtn = document.getElementById(BtnEditOrder);
    const cancelEditBtn = document.getElementById(BtnCancelEditOrder);
    const submitBtn = document.getElementById(BtnSubmitChanges);

    if (!editBtn) return;

    editBtn.addEventListener("click", () => {
        // Munculkan tombol Submit Changes
        submitBtn?.classList.remove("d-none");

        // Munculkan tombol Cancel Edit
        cancelEditBtn?.classList.remove("d-none");

        // Aktifkan semua inputan KECUALI po_number
        const fieldsToEnable = ["description"];
        fieldsToEnable.forEach((id) => {
            const el = document.getElementById(id);
            if (el) el.disabled = false;
        });

        // Aktifkan vendor TomSelect
        const vendorEl = document.getElementById("select-vendor");
        if (vendorEl?.tomselect) vendorEl.tomselect.enable();

        // Aktifkan add-row controls
        const addRowBtn = document.querySelector(AddRowButton);
        const addRowCountEl = document.querySelector(AddRowCountInput);
        if (addRowBtn) addRowBtn.disabled = false;
        if (addRowCountEl) addRowCountEl.disabled = false;

        // Aktifkan semua input/textarea & TomSelect di tabel blood
        const tableBody = document.getElementById(TableRowBloodData);
        if (tableBody) {
            tableBody
                .querySelectorAll("input, textarea")
                .forEach((el) => (el.disabled = false));

            tableBody.querySelectorAll("select").forEach((el) => {
                if (el.tomselect) el.tomselect.enable();
            });

            // Tampilkan kembali tombol delete di setiap baris
            tableBody
                .querySelectorAll(".delete_row_blood_data")
                .forEach((btn) => (btn.style.display = ""));
        }

        // Nonaktifkan tombol Edit agar tidak bisa diklik ulang
        editBtn.disabled = true;
        editBtn?.classList.add("d-none");
    });

    if (!cancelEditBtn) return;
    cancelEditBtn.addEventListener("click", () => {
        // Munculkan tombol Edit
        editBtn.disabled = false;
        editBtn?.classList.remove("d-none");

        setFormDisabled(true);

        // Hilangkan tombol
        submitBtn?.classList.add("d-none");
        cancelEditBtn?.classList.add("d-none");
    });
}

// ---------- Helper: disable / enable seluruh form ----------
function setFormDisabled(disabled) {
    // ----- Input statis di card Order Data -----
    const staticFields = ["po_number", "description"];
    staticFields.forEach((id) => {
        const el = document.getElementById(id);
        if (el) el.disabled = disabled;
    });

    // ----- Vendor TomSelect -----
    const vendorEl = document.getElementById("select-vendor");
    if (vendorEl?.tomselect) {
        disabled ? vendorEl.tomselect.disable() : vendorEl.tomselect.enable();
    }

    // ----- Toolbar: add-row -----
    const addRowBtn = document.querySelector(AddRowButton);
    if (addRowBtn) addRowBtn.disabled = disabled;

    const addRowCountEl = document.querySelector(AddRowCountInput);
    if (addRowCountEl) addRowCountEl.disabled = disabled;

    // ----- Semua input / textarea di tabel blood -----
    const tableBody = document.getElementById(TableRowBloodData);
    if (tableBody) {
        tableBody
            .querySelectorAll("input, textarea")
            .forEach((el) => (el.disabled = disabled));

        // Disable TomSelect di setiap baris
        tableBody.querySelectorAll("select").forEach((el) => {
            if (el.tomselect) {
                disabled ? el.tomselect.disable() : el.tomselect.enable();
            }
        });

        // Sembunyikan tombol delete di setiap baris
        tableBody.querySelectorAll(".delete_row_blood_data").forEach((btn) => {
            btn.style.display = disabled ? "none" : "";
        });
    }
}

// ---------- Select vendor dari tom-select untuk form add new data ----------
function SelectVendor(order) {
    const vendor = order?.vendors;

    // Jika ada data vendor dari order, inject option & set value langsung
    if (vendor) {
        initTomSelectWithValue(
            document.getElementById("select-vendor"),
            "/utility/select/vendor",
            vendor.public_id,
            vendor.name,
        );
    } else {
        // Tidak ada data vendor — init biasa tanpa value
        new TomSelect("#select-vendor", {
            valueField: "id",
            labelField: "text",
            searchField: "text",
            sortField: { field: "id", direction: "asc" },
            create: false,
            preload: true,
            load: function (query, callback) {
                fetch(`/utility/select/vendor?q=${encodeURIComponent(query)}`)
                    .then((response) => response.json())
                    .then((json) => callback(json.results))
                    .catch(() => callback());
            },
            onChange: async (vendorId) => {
                if (!vendorId) {
                    document.getElementById("vendor-name").value = "";
                    document.getElementById("vendor-address").value = "";
                    return;
                }
                try {
                    const res = await fetch(`/utility/get/vendor/${vendorId}`);
                    const data = await res.json();
                    document.getElementById("vendor-name").value =
                        data.name ?? "";
                    document.getElementById("vendor-address").value =
                        data.address ?? "";
                } catch (err) {
                    notyf.error({ message: "Failed to fetch vendor data!" });
                    console.error(err);
                }
            },
        });
    }
}

// ---------- Fungsi untuk mengelola form tabel blood ----------
function HandleTableBlood() {
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
        initTomSelect(
            tableBody.querySelector(`[id="${f(idx, "blood_pack_id")}"]`),
            "/utility/select/blood-pack",
        );
    }

    // ---------- Init TomSelect untuk 1 row dengan value awal ----------
    // label: string yang ditampilkan (mis. "A+ WB")
    function initTomSelectsForRowWithValue(idx, bloodPackId, label) {
        initTomSelectWithValue(
            tableBody.querySelector(`[id="${f(idx, "blood_pack_id")}"]`),
            "/utility/select/blood-pack",
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

// ---------- Populate order detail form ----------
function populateOrderDetailForm(order) {
    const poNumberEl = document.getElementById("po_number");
    const descriptionEl = document.getElementById("description");
    const poNumberTitleEl = document.getElementById("po_number_title");

    if (poNumberEl) poNumberEl.value = order.po_number ?? "";
    if (descriptionEl) descriptionEl.value = order.description ?? "";
    if (poNumberTitleEl) poNumberTitleEl.textContent = order.po_number ?? "";
}

// ---------- Fetch data detail order ----------
async function fetchDataDetailOrder() {
    const id = getIdFromUrl();

    showPageLoading();

    try {
        const res = await fetch(`${UrlDetailOrder}/${id}`);
        if (!res.ok) throw new Error(`HTTP error! status: ${res.status}`);

        const data = await res.json();
        currentOrderData = data;
        return data;
    } catch (err) {
        notyf.error({ message: "Failed to fetch order data!" });
        console.error(err);
    } finally {
        hidePageLoading();
    }
}

// ---------- Handler tombol Generate PO File ----------
function HandleGeneratePoFile() {
    const generateBtn = document.getElementById(BtnGeneratePO);
    if (!generateBtn) return;

    generateBtn.addEventListener("click", async () => {
        if (!currentOrderData?.order?.po_number) {
            notyf.error({ message: "PO Number not found!" });
            return;
        }

        const poNumber = currentOrderData.order.po_number;

        generateBtn.disabled = true;

        showPageLoading();

        try {
            const res = await fetch(UrlGeneratePO + `/${poNumber}`, {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        ?.getAttribute("content"),
                },
            });

            if (!res.ok) {
                const err = await res.json().catch(() => ({}));
                throw new Error(
                    err?.message ?? `HTTP error! status: ${res.status}`,
                );
            }

            // Trigger download dari blob response
            const blob = await res.blob();
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement("a");
            a.href = url;
            a.download = `PO_FILE-${poNumber}.pdf`;
            document.body.appendChild(a);
            a.click();
            a.remove();
            window.URL.revokeObjectURL(url);

            notyf.success({
                message: "PO File generated and downloaded successfully!",
            });

            // Refresh data agar toolbar update (tombol print/download muncul)
            const data = await fetchDataDetailOrder();
            currentOrderData = data;
            applyToolbarState(data?.order);
        } catch (err) {
            notyf.error({
                message: err.message ?? "Failed to generate PO File!",
            });
            console.error(err);
        } finally {
            hidePageLoading();
            generateBtn.disabled = false;
        }
    });
}

// ---------- Handler tombol Preview PO File ----------
function HandlePreviewPoFile() {
    const previewBtn = document.getElementById(BtnPreviewPO);
    if (!previewBtn) return;

    previewBtn.addEventListener("click", () => {
        if (!currentOrderData?.order?.po_number) {
            notyf.error({ message: "PO Number not found!" });
            return;
        }

        const poNumber = currentOrderData.order.po_number;

        window.open(UrlPreviewPO + `/${poNumber}`, "_blank");
    });
}

// ---------- Handler tombol Download PO File ----------
function HandleDownloadPoFile() {
    const downloadBtn = document.getElementById(BtnDownloadPO);
    if (!downloadBtn) return;

    downloadBtn.addEventListener("click", async () => {
        const poFilePath = currentOrderData?.order?.po_file_path;
        const poNumber = currentOrderData?.order?.po_number;

        // Validasi: PO File harus sudah ada, jangan generate baru
        if (!poFilePath) {
            notyf.error({
                message:
                    "PO File not found! Please generate the PO File first.",
            });
            return;
        }

        if (!poNumber) {
            notyf.error({ message: "PO Number not found!" });
            return;
        }

        downloadBtn.disabled = true;

        showPageLoading();

        try {
            const res = await fetch(`${UrlDownloadPO}/${poNumber}`, {
                method: "GET",
            });

            if (!res.ok) {
                const err = await res.json().catch(() => ({}));
                throw new Error(
                    err?.message ?? `HTTP error! status: ${res.status}`,
                );
            }

            // Trigger download dari blob response
            const blob = await res.blob();
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement("a");
            a.href = url;
            a.download = `PO_FILE-${poNumber}.pdf`;
            document.body.appendChild(a);
            a.click();
            a.remove();
            window.URL.revokeObjectURL(url);

            notyf.success({ message: "PO File downloaded successfully!" });
        } catch (err) {
            notyf.error({
                message: err.message ?? "Failed to download PO File!",
            });
            console.error(err);
        } finally {
            hidePageLoading();
            downloadBtn.disabled = false;
        }
    });
}

// ---------- Handler tombol Print PO File ----------
function HandlePrintPoFile() {
    const printBtn = document.getElementById(BtnPrintPO);
    if (!printBtn) return;

    printBtn.addEventListener("click", async () => {
        const poFilePath = currentOrderData?.order?.po_file_path;
        const poNumber = currentOrderData?.order?.po_number;

        // Validasi: PO File harus sudah ada, jangan generate baru
        if (!poFilePath) {
            notyf.error({
                message:
                    "PO File not found! Please generate the PO File first.",
            });
            return;
        }

        if (!poNumber) {
            notyf.error({ message: "PO Number not found!" });
            return;
        }

        printBtn.disabled = true;

        showPageLoading();

        try {
            const res = await fetch(`${UrlPrintPO}/${poNumber}`, {
                method: "GET",
            });

            if (!res.ok) {
                const err = await res.json().catch(() => ({}));
                throw new Error(
                    err?.message ?? `HTTP error! status: ${res.status}`,
                );
            }

            // Fetch PDF sebagai blob, buat object URL, inject ke iframe tersembunyi
            // lalu trigger print dialog — tidak membuka tab/halaman baru
            const blob = await res.blob();
            const blobUrl = window.URL.createObjectURL(blob);

            // Gunakan iframe tersembunyi agar print dialog terbuka di tab ini
            let iframe = document.getElementById("__print_po_iframe__");
            if (iframe) iframe.remove(); // Bersihkan iframe lama jika ada

            iframe = document.createElement("iframe");
            iframe.id = "__print_po_iframe__";
            iframe.style.cssText =
                "position:fixed;top:0;left:0;width:0;height:0;border:none;opacity:0;pointer-events:none;";
            iframe.src = blobUrl;

            iframe.onload = () => {
                try {
                    iframe.contentWindow.focus();
                    iframe.contentWindow.print();
                } catch (printErr) {
                    // Fallback jika browser memblokir akses contentWindow
                    // (jarang terjadi untuk blob URL di origin yang sama)
                    notyf.error({
                        message:
                            "Failed to open print dialog. Try downloading instead.",
                    });
                    console.error(printErr);
                } finally {
                    // Revoke setelah jeda singkat agar print dialog sempat terbuka
                    setTimeout(
                        () => window.URL.revokeObjectURL(blobUrl),
                        10_000,
                    );
                }
            };

            document.body.appendChild(iframe);
        } catch (err) {
            notyf.error({ message: err.message ?? "Failed to print PO File!" });
            console.error(err);
        } finally {
            hidePageLoading();
            printBtn.disabled = false;
        }
    });
}

// ---------- Entry point ----------
document.addEventListener("DOMContentLoaded", async () => {
    const data = await fetchDataDetailOrder();
    const order = data?.order;

    if (order) {
        populateOrderDetailForm(order);
    }

    SelectVendor(order);

    const { PopulateTableFromOrder } = HandleTableBlood();

    if (order?.order_blood_details?.length) {
        PopulateTableFromOrder(order.order_blood_details);
    }

    setFormDisabled(true);

    applyToolbarState(order);
    HandleEditOrderBtn();
    HandleGeneratePoFile();
    HandlePreviewPoFile();
    HandleDownloadPoFile();
    HandlePrintPoFile();
});
