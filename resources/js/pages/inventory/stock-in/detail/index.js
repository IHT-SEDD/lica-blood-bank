import { getDataFromURL } from "../../../../utility/application";
import {
    GlobalAdvanceDatatable,
    GlobalAdvanceFlatpickr,
    GlobalDeleteDataConfirmation,
    GlobalRestoreDataConfirmation,
    GlobalEditData,
    DateTimeFormatter,
    GlobalAdvanceTomselect,
} from "../../../../app";

// ---------- Global variable untuk memudahkan penyesuaian :begin ----------
// Datatable
const IncomingBloodTableSelector = "#incoming-blood-detail-table";
const OrderBloodTableSelector = "#order-blood-table";
const ReloadIncomingBloodTableSelector = "incoming-blood-detail-reload";
const ReloadOrderBloodTableSelector = "order-blood-reload";

// URL
const URLIncomingBloodTable = "/inventory/stock-in/detail/data/incoming-blood";
const URLOrderBloodTable = "/inventory/stock-in/detail/data/order-blood";
const URLOrderBloodData = "/inventory/stock-in/detail/data/order";
const URLIncomingBloodData = "/inventory/stock-in/detail/data/incoming-stock";
// ---------- Global variable untuk memudahkan penyesuaian :end ----------

// ---------- Global state :begin ----------
let incomingBloodTableInstance;
let orderBloodTableInstance;
let incomingBloodData = null;
let orderBloodData = null;
const id = getDataFromURL(1);
// ---------- Global state :end ----------

// ---------- Helper: Reload tabel ----------
function reloadTableIncomingBlood() {
    if (incomingBloodTableInstance?.instance) {
        incomingBloodTableInstance.instance.ajax.reload();
    }
}
function reloadTableOrderBlood() {
    if (orderBloodTableInstance?.instance) {
        orderBloodTableInstance.instance.ajax.reload();
    }
}

// ---------- Datatable----------
function IncomingBloodTable() {
    // ---------- Init kolom pada tabel ----------
    const IncomingBloodTableColumns = [
        { data: "bag_number", title: "Bag Number" },
        {
            data: null,
            title: "Blood Pack",
            render: (data, row) => {
                const bloodPacks = data.blood_packs;
                return `${bloodPacks.blood_group}${bloodPacks.blood_rhesus} ${bloodPacks.blood_component}`;
            },
        },
        { data: "blood_volume", title: "Volume" },
        {
            data: "aftap_date",
            title: "Aftap",
            render: (data) => {
                return DateTimeFormatter.dateOnly(data);
            },
        },
        {
            data: "expiry_date",
            title: "Expiry",
            render: (data) => {
                return DateTimeFormatter.dateOnly(data);
            },
        },
        {
            data: "process_date",
            title: "Process",
            render: (data) => {
                return DateTimeFormatter.dateOnly(data);
            },
        },
        {
            data: "ready_at",
            title: "Ready",
            render: (data) => {
                return DateTimeFormatter.dateOnly(data);
            },
        },
        {
            data: "created_at",
            title: "Registered",
            render: (data) => {
                return DateTimeFormatter.dateOnly(data);
            },
        },
    ];

    // ---------- Panggil GlobalAdvanceDatatable untuk menampilkan tabel ----------
    incomingBloodTableInstance = new GlobalAdvanceDatatable(
        IncomingBloodTableSelector,
        {
            ajax: { url: URLIncomingBloodTable + `/${id}` },
            columns: IncomingBloodTableColumns,
            useHideColumn: true,
            columnDefs: [
                {
                    targets: -1,
                    responsivePriority: 1,
                },
                {
                    targets: 0,
                    responsivePriority: 2,
                },
            ],
        },
    );
}
function OrderBloodTable() {
    // ---------- Init kolom pada tabel ----------
    const OrderBloodTableColumns = [
        {
            data: null,
            title: "Blood",
            render: (data, row) => {
                const bloodPacks = data.blood_packs;
                return `${bloodPacks.blood_group}${bloodPacks.blood_rhesus} ${bloodPacks.blood_component}`;
            },
        },
        { data: "quantity", title: "Qty" },
        { data: "note", title: "Note" },
        { data: "order_bloods.users.name", title: "Order By" },
        {
            data: "created_at",
            title: "Order At",
            render: (data) => {
                return DateTimeFormatter.dateOnly(data);
            },
        },
    ];

    // ---------- Panggil GlobalAdvanceDatatable untuk menampilkan tabel ----------
    orderBloodTableInstance = new GlobalAdvanceDatatable(
        OrderBloodTableSelector,
        {
            ajax: { url: URLOrderBloodTable + `/${id}` },
            columns: OrderBloodTableColumns,
            useHideColumn: true,
            columnDefs: [
                {
                    targets: -1,
                    responsivePriority: 1,
                },
                {
                    targets: 0,
                    responsivePriority: 2,
                },
            ],
        },
    );
}

// ---------- Helper: render badge status ----------
function renderIncomingStatus(status) {
    const map = {
        pending: { label: "Pending", color: "warning" },
        stock_received: { label: "Stock Received", color: "info" },
        stock_ready: { label: "Stock Ready", color: "success" },
        partially_received: { label: "Partially Received", color: "primary" },
    };
    const s = map[status] ?? { label: status ?? "-", color: "secondary" };
    return `<span class="badge badge-label badge-soft-${s.color}">${s.label}</span>`;
}
function renderOrderStatus(status) {
    const map = {
        pending: { label: "Pending", color: "warning" },
        approved: { label: "Approved", color: "info" },
        rejected: { label: "Rejected", color: "danger" },
        partially_stock_registered: {
            label: "Partially Registered",
            color: "primary",
        },
        all_order_stock_registered: {
            label: "All Stock Registered",
            color: "success",
        },
    };
    const s = map[status] ?? { label: status ?? "-", color: "secondary" };
    return `<span class="badge badge-label badge-soft-${s.color}">${s.label}</span>`;
}

// ---------- Populate data ke elemen blade ----------
function PopulateDetailData(incoming, order) {
    // ---------- Helper: set teks ke elemen berdasarkan selector & data-attribute ----------
    const fill = (selector, attr, attrValue, html) => {
        const el = document.querySelector(
            `${selector}[${attr}="${attrValue}"]`,
        );
        if (el) el.innerHTML = html ?? "-";
    };

    // ===== INCOMING STOCK DETAIL (incoming_stock_detail_wrapper) =====

    // -- data-order fields (id="incoming_data") --
    fill(
        "#incoming_data",
        "data-order",
        "po_number",
        incoming.po_number ?? "-",
    );
    fill(
        "#incoming_data",
        "data-order",
        "batch_number",
        incoming.batch_number ?? "-",
    );
    fill(
        "#incoming_data",
        "data-order",
        "status",
        renderIncomingStatus(incoming.status),
    );

    // registered_by → belum ada relasi user di response, tampilkan id sementara
    fill(
        "#incoming_data",
        "data-order",
        "registered_by",
        incoming.registered_by.name ?? "-",
    );
    fill(
        "#incoming_data",
        "data-order",
        "registered_at",
        incoming.created_at
            ? DateTimeFormatter.human(incoming.created_at)
            : "-",
    );

    // -- data-patient-detail fields (id="patient_detail") --
    fill(
        "#patient_detail",
        "data-patient-detail",
        "stock_received_by",
        incoming.received_by.name ?? "-",
    );
    fill(
        "#patient_detail",
        "data-patient-detail",
        "stock_received_at",
        incoming.received_at
            ? DateTimeFormatter.human(incoming.received_at)
            : "-",
    );
    fill(
        "#patient_detail",
        "data-patient-detail",
        "stock_ready_at",
        incoming.stock_ready_at
            ? DateTimeFormatter.human(incoming.stock_ready_at)
            : "-",
    );

    // ===== ORDER DETAIL (order_detail_wrapper) =====

    // -- data-order fields (id="order_data") --
    fill("#order_data", "data-order", "po_number", order.po_number ?? "-");
    fill(
        "#order_data",
        "data-order",
        "vendor_name",
        order.vendors?.name ?? "-",
    );
    fill(
        "#order_data",
        "data-order",
        "status",
        renderOrderStatus(order.status),
    );
    fill("#order_data", "data-order", "ordered_by", order.users?.name ?? "-");
    fill(
        "#order_data",
        "data-order",
        "ordered_at",
        order.created_at ? DateTimeFormatter.human(order.created_at) : "-",
    );
    fill("#order_data", "data-order", "description", order.description ?? "-");

    // -- PO File (data-patient-detail="po_file") --
    const poFileHtml = order.po_file_name
        ? `<a href="/inventory/history-order/po-file/preview/${order.po_number}" target="_blank" class="link-secondary text-decoration-underline link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover">${order.po_file_name}</a>`
        : "-";
    fill("#patient_detail", "data-patient-detail", "po_file", poFileHtml);

    // -- Update judul halaman dengan PO Number --
    const titleEl = document.getElementById("po_number_title");
    if (titleEl) titleEl.textContent = `#${order.po_number ?? ""}`;
}

// ---------- Fetch data detail ----------
async function fetchDataDetail() {
    showPageLoading();
    try {
        // Fetch incoming blood & order blood secara paralel
        const [incomingRes, orderRes] = await Promise.all([
            fetch(`${URLIncomingBloodData}/${id}`, {
                method: "GET",
                cache: "no-store",
                headers: { "Cache-Control": "no-cache", Pragma: "no-cache" },
            }),
            fetch(`${URLOrderBloodData}/${id}`, {
                method: "GET",
                cache: "no-store",
                headers: { "Cache-Control": "no-cache", Pragma: "no-cache" },
            }),
        ]);

        if (!incomingRes.ok)
            throw new Error(
                `Incoming blood error! status: ${incomingRes.status}`,
            );
        if (!orderRes.ok)
            throw new Error(`Order blood error! status: ${orderRes.status}`);

        // Parse response secara paralel
        const [incomingData, orderData] = await Promise.all([
            incomingRes.json(),
            orderRes.json(),
        ]);

        // Simpan ke global state
        incomingBloodData = incomingData;
        orderBloodData = orderData;

        // Populate semua data ke blade — loading baru hide setelah ini selesai
        PopulateDetailData(incomingBloodData, orderBloodData);

        return { incomingBloodData, orderBloodData };
    } catch (err) {
        notyf.error({ message: "Failed to fetch detail data!" });
        console.error(err);
    } finally {
        hidePageLoading();
    }
}

document.addEventListener("DOMContentLoaded", async () => {
    // Datatable
    IncomingBloodTable();
    OrderBloodTable();

    // Fetch data detail saat halaman pertama dibuka
    await fetchDataDetail();

    // Reload table listeners
    window.addEventListener(
        ReloadIncomingBloodTableSelector,
        reloadTableIncomingBlood,
    );
    window.addEventListener(
        ReloadOrderBloodTableSelector,
        reloadTableOrderBlood,
    );
});
