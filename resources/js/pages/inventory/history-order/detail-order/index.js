import {
    TomSelectDefault,
    TomSelectWithValue,
} from "../../../../utility/select.js";

import { getDataFromURL } from "../../../../utility/application.js";

import {
    ToolbarWrapper,
    ToolbarButtons,
    ToolbarButtonUrls,
    ToolbarHandler,
    ToolbarState,
} from "./toolbar.js";

import {
    HandleTableBlood,
    AddRowButton,
    AddRowCountInput,
    TableRowBloodData,
} from "./table.js";

// ---------- Global variable untuk memudahkan penyesuaian :begin ----------
// URL
const UrlDetailOrder = "/inventory/history-order/detail/data";
const UrlUpdateOrder = "/inventory/history-order/detail";

// ORDER STATUS
const DONE_STATUS = "done";
const DRAFT_STATUS = "draft";
const ORDER_CREATED_STATUS = "order_created";
// ---------- Global variable untuk memudahkan penyesuaian :end ----------

// ---------- State global :begin ----------
let formValidation = null;
let currentOrderData = null;

// Deklarasi di level modul agar bisa diakses oleh refreshPageContent()
// dan di-assign satu kali dari DOMContentLoaded
let populateTableFromOrder = null;

const REQUIRED_FIELDS = ["blood_pack_id", "quantity"];

// Untuk parsing context ke toolbar.js
const toolbarContext = {
    getCurrentOrderData: () => currentOrderData,
    setCurrentOrderData: (data) => {
        currentOrderData = data;
    },
    fetchDataDetailOrder,
};
// ---------- State global :end ----------

// ---------- Handler tombol Edit Order Data ----------
function HandleEditOrderBtn() {
    const editBtn = document.getElementById(ToolbarButtons.BtnEditOrder);
    const cancelEditBtn = document.getElementById(
        ToolbarButtons.BtnCancelEditOrder,
    );
    const submitBtn = document.getElementById(ToolbarButtons.BtnSubmitChanges);

    if (!editBtn) return;

    editBtn.addEventListener("click", () => {
        submitBtn?.classList.remove("d-none");
        cancelEditBtn?.classList.remove("d-none");

        const fieldsToEnable = ["description"];
        fieldsToEnable.forEach((id) => {
            const el = document.getElementById(id);
            if (el) el.disabled = false;
        });

        const vendorEl = document.getElementById("select-vendor");
        if (vendorEl?.tomselect) vendorEl.tomselect.enable();

        const addRowBtn = document.querySelector(AddRowButton);
        const addRowCountEl = document.querySelector(AddRowCountInput);
        if (addRowBtn) addRowBtn.disabled = false;
        if (addRowCountEl) addRowCountEl.disabled = false;

        const tableBody = document.getElementById(TableRowBloodData);
        if (tableBody) {
            tableBody
                .querySelectorAll("input, textarea")
                .forEach((el) => (el.disabled = false));

            tableBody.querySelectorAll("select").forEach((el) => {
                if (el.tomselect) el.tomselect.enable();
            });

            tableBody
                .querySelectorAll(".delete_row_blood_data")
                .forEach((btn) => (btn.style.display = ""));
        }

        editBtn.disabled = true;
        editBtn?.classList.add("d-none");
    });

    if (!cancelEditBtn) return;
    cancelEditBtn.addEventListener("click", () => {
        editBtn.disabled = false;
        editBtn?.classList.remove("d-none");

        setFormDisabled(true);

        submitBtn?.classList.add("d-none");
        cancelEditBtn?.classList.add("d-none");
    });
}

// ---------- Helper: disable / enable seluruh form ----------
function setFormDisabled(disabled) {
    const staticFields = ["po_number", "description"];
    staticFields.forEach((id) => {
        const el = document.getElementById(id);
        if (el) el.disabled = disabled;
    });

    const vendorEl = document.getElementById("select-vendor");
    if (vendorEl?.tomselect) {
        disabled ? vendorEl.tomselect.disable() : vendorEl.tomselect.enable();
    }

    const addRowBtn = document.querySelector(AddRowButton);
    if (addRowBtn) addRowBtn.disabled = disabled;

    const addRowCountEl = document.querySelector(AddRowCountInput);
    if (addRowCountEl) addRowCountEl.disabled = disabled;

    const tableBody = document.getElementById(TableRowBloodData);
    if (tableBody) {
        tableBody
            .querySelectorAll("input, textarea")
            .forEach((el) => (el.disabled = disabled));

        tableBody.querySelectorAll("select").forEach((el) => {
            if (el.tomselect) {
                disabled ? el.tomselect.disable() : el.tomselect.enable();
            }
        });

        tableBody.querySelectorAll(".delete_row_blood_data").forEach((btn) => {
            btn.style.display = disabled ? "none" : "";
        });
    }
}

// ---------- Select vendor dari tom-select ----------
function SelectVendor(order) {
    const vendor = order?.vendors;
    const vendorEl = document.getElementById("select-vendor");

    // Destroy instance lama sebelum re-init agar tidak duplikat
    if (vendorEl?.tomselect) {
        vendorEl.tomselect.destroy();
    }

    const commonOptions = {
        sortField: {
            field: "id",
            direction: "asc",
        },
    };

    if (vendor) {
        TomSelectWithValue(
            vendorEl,
            "/utility/select/vendor",
            vendor.public_id,
            vendor.name,
            commonOptions,
        );
    } else {
        TomSelectDefault(vendorEl, "/utility/select/vendor", commonOptions);
    }
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
    const id = getDataFromURL(1);

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

// ---------- Reset toolbar ke state awal (non-edit mode) ----------
function resetToolbarToViewMode(order) {
    const editBtn = document.getElementById(ToolbarButtons.BtnEditOrder);
    const cancelEditBtn = document.getElementById(
        ToolbarButtons.BtnCancelEditOrder,
    );
    const submitBtn = document.getElementById(ToolbarButtons.BtnSubmitChanges);

    editBtn?.classList.remove("d-none");
    if (editBtn) editBtn.disabled = false;

    submitBtn?.classList.add("d-none");
    cancelEditBtn?.classList.add("d-none");

    ToolbarState(order);
}

// ---------- Refresh seluruh konten halaman dengan data terbaru ----------
async function refreshPageContent() {
    const data = await fetchDataDetailOrder();
    const order = data?.order;

    if (order) {
        populateOrderDetailForm(order);
    }

    // populateTableFromOrder sudah tersedia di module scope
    // karena di-assign saat DOMContentLoaded — aman dipanggil di sini
    if (populateTableFromOrder) {
        populateTableFromOrder(
            order?.order_blood_details?.length ? order.order_blood_details : [],
        );
    }

    SelectVendor(order);

    setFormDisabled(true);
    resetToolbarToViewMode(order);

    return data;
}

// ---------- Handler Submit Changes ----------
function HandleSubmitChanges() {
    const submitBtn = document.getElementById(ToolbarButtons.BtnSubmitChanges);
    if (!submitBtn) return;

    submitBtn.addEventListener("click", async () => {
        const id = getDataFromURL(1);
        const original = currentOrderData?.order;
        if (!original) return;

        const payload = {};

        // ---------- Cek perubahan vendor ----------
        const vendorEl = document.getElementById("select-vendor");
        const newVendorId = vendorEl?.tomselect?.getValue();
        const originalVendorId = original.vendors?.public_id;
        if (newVendorId && newVendorId !== originalVendorId) {
            payload.vendor_id = newVendorId;
        }

        // ---------- Cek perubahan description ----------
        const descEl = document.getElementById("description");
        const newDesc = descEl?.value ?? "";
        if (newDesc !== (original.description ?? "")) {
            payload.description = newDesc;
        }

        // ---------- Ambil blood_data dari tabel ----------
        const tableBody = document.getElementById(TableRowBloodData);
        const rows = tableBody?.querySelectorAll("tr") ?? [];
        const bloodData = [];
        let valid = true;

        rows.forEach((row, idx) => {
            const bloodPackEl = row.querySelector(
                `select[name*="blood_pack_id"]`,
            );
            const quantityEl = row.querySelector(`input[name*="quantity"]`);
            const noteEl = row.querySelector(`textarea[name*="note"]`);

            const bloodPackId = bloodPackEl?.tomselect?.getValue();
            const quantity = quantityEl?.value?.trim();

            if (!bloodPackId || !quantity) {
                valid = false;
                notyf.error({
                    message: `Row ${idx + 1}: Blood pack and quantity are required!`,
                });
                return;
            }

            bloodData.push({
                blood_pack_id: bloodPackId,
                quantity,
                note: noteEl?.value?.trim() ?? "",
            });
        });

        if (!valid) return;

        // ---------- Bandingkan blood_data dengan data original ----------
        const originalBloodData = (original.order_blood_details ?? []).map(
            (d) => ({
                blood_pack_id:
                    d.blood_packs?.public_id ?? String(d.blood_pack_id),
                quantity: String(d.quantity),
                note: d.note ?? "",
            }),
        );

        const bloodDataChanged =
            JSON.stringify(bloodData) !== JSON.stringify(originalBloodData);

        if (bloodDataChanged) {
            payload.blood_data = bloodData;
        }

        // ---------- Tidak ada yang berubah ----------
        if (Object.keys(payload).length === 0) {
            notyf.open({ type: "info", message: "No changes detected." });
            return;
        }

        submitBtn.disabled = true;
        showPageLoading();

        try {
            const res = await fetch(`${UrlUpdateOrder}/${id}`, {
                method: "PATCH",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        ?.getAttribute("content"),
                },
                body: JSON.stringify(payload),
            });

            if (!res.ok) {
                const err = await res.json().catch(() => ({}));
                throw new Error(
                    err?.message ?? `HTTP error! status: ${res.status}`,
                );
            }

            notyf.success({ message: "Order data updated successfully!" });

            await refreshPageContent();
        } catch (err) {
            notyf.error({
                message: err.message ?? "Failed to update order data!",
            });
            console.error(err);
        } finally {
            hidePageLoading();
            submitBtn.disabled = false;
        }
    });
}

// ---------- Entry point ----------
document.addEventListener("DOMContentLoaded", async () => {
    const data = await fetchDataDetailOrder();
    const order = data?.order;

    const tableHandler = HandleTableBlood();
    populateTableFromOrder = tableHandler.PopulateTableFromOrder;

    if (order) {
        populateOrderDetailForm(order);
    }
    if (order?.order_blood_details?.length) {
        populateTableFromOrder(order.order_blood_details);
    }

    SelectVendor(order);

    setFormDisabled(true);

    ToolbarState(order);
    HandleEditOrderBtn();
    HandleSubmitChanges();

    ToolbarHandler.GeneratePoFile(toolbarContext);
    ToolbarHandler.PreviewPoFile(toolbarContext);
    ToolbarHandler.DownloadPoFile(toolbarContext);
    ToolbarHandler.PrintPoFile(toolbarContext);
});
