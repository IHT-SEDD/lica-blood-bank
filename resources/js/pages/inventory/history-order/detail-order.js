import TomSelect from "tom-select";
import { DateTimeFormatter } from "../../../app";

// ---------- Global variable untuk memudahkan penyesuaian :begin ----------
const FetchDataUrl = "/inventory/history-order/data/detail";
const PoNumberTitleSelector = "po_number_title";

// Order Data
const OrderDataTotalQtySelector = "total_quantity";
const OrderDataContainerSelector = "order_data_container";
const UrlSelectVendor = "/utility/select/vendor";

// Blood Data
const BloodDataContainerSelector = "blood_data_container";
const UrlSelectBloodGroup = "/utility/select/blood-group";
const UrlSelectBloodRhesus = "/utility/select/blood-rhesus";
const UrlSelectBloodComponent = "/utility/select/blood-component";
// ---------- Global variable untuk memudahkan penyesuaian :end ----------

// ---------- Helper: Ambil public_id dari URL saat ini :begin ----------
function getPublicIdFromUrl() {
    const segments = window.location.pathname.split("/").filter(Boolean);
    return segments[segments.length - 1] ?? null;
}
// ---------- Helper: Ambil public_id dari URL saat ini :end ----------

// ---------- Helper: Render badge berdasarkan value boolean :begin ----------
function renderBooleanBadge(value) {
    return parseInt(value) === 1
        ? `<span class="badge badge-label badge-soft-danger">Reactive</span>`
        : `<span class="badge badge-label badge-soft-success">Non Reactive</span>`;
}
// ---------- Helper: Render badge berdasarkan value boolean :end ----------

// ---------- Helper: Init TomSelect + setValue setelah data load :begin ----------
function initAndSetTomSelect(el, url, value, isDraft) {
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
        onLoad: function () {
            this.setValue(value, true);

            // ---------- Lock jika bukan draft, cukup lock() tanpa disabled :begin ----------
            if (!isDraft) this.lock();
            // ---------- Lock jika bukan draft, cukup lock() tanpa disabled :end ----------
        },
    });
}
// ---------- Helper: Init TomSelect + setValue setelah data load :end ----------

// ---------- Helper: Ambil config icon & title berdasarkan status log :begin ----------
function getLogStatusConfig(status) {
    const config = {
        order_created: {
            icon: "ti-file-text",
            colorClass: "text-info fill-info",
            tooltip: "Order Created",
            title: "Order Created",
        },
        draft_created: {
            icon: "ti-archive",
            colorClass: "text-warning fill-warning",
            tooltip: "Order Drafted",
            title: "Order Drafted",
        },
        draft_cancelled: {
            icon: "ti-archive-off",
            colorClass: "text-danger fill-warning",
            tooltip: "Order Draft Cancelled",
            title: "Order Draft Cancelled",
        },
        order_cancelled: {
            icon: "ti-file-x",
            colorClass: "text-danger fill-info",
            tooltip: "Order Cancelled",
            title: "Order Cancelled",
        },
        po_file_generated: {
            icon: "ti-file-type-pdf",
            colorClass: "text-secondary fill-secondary",
            tooltip: "PO Generated",
            title: "PO File Generated",
        },
        deleted: {
            icon: "ti-trash",
            colorClass: "text-danger fill-danger",
            tooltip: "Order Deleted",
            title: "Order Deleted",
        },
    };

    return (
        config[status] ?? {
            icon: "ti-info-circle",
            colorClass: "text-muted fill-muted",
            tooltip: "Unknown Status",
            title: status ?? "-",
        }
    );
}
// ---------- Helper: Ambil config icon & title berdasarkan status log :end ----------

// ---------- Fungsi untuk fetch data order dan log :begin ----------
async function FetchDataOrderAndLog() {
    const id = getPublicIdFromUrl();

    if (!id) {
        console.error("Public ID not found in URL");
        // FIX: salah pakai notyf.success untuk kasus error
        notyf.error({ message: "Failed to fetch data, public ID not found!" });
        return null;
    }

    try {
        const res = await fetch(`${FetchDataUrl}/${id}`);

        if (!res.ok) throw new Error(`HTTP error! status: ${res.status}`);

        // FIX: hapus console.log(data) — tidak perlu di production
        return await res.json();
    } catch (err) {
        console.error("Failed to fetch order detail:", err);
        notyf.error({ message: "Failed to fetch order data!" });
        return null;
    }
}
// ---------- Fungsi untuk fetch data order dan log :end ----------

// ---------- Fungsi untuk populate blood data ke tampilan :begin ----------
function PopulateBloodData(orderBloods, isDraft) {
    const container = document.getElementById(BloodDataContainerSelector);
    if (!container) return;

    container.innerHTML = "";

    orderBloods.forEach((blood, index) => {
        const cardNumber = index + 1;
        const disabledAttr = isDraft ? "" : "disabled";

        const card = document.createElement("div");
        card.className = "card blood-data-row";
        card.innerHTML = `
            <div class="card-header justify-content-between align-items-center">
                <h6 class="card-title text-capitalize mb-0">#${cardNumber} Blood Data</h6>
                <div class="card-action gap-2">
                    <h5 class="text-capitalize fw-semibold mb-0">Quantity :</h5>
                    <h5 class="text-capitalize fw-semibold mb-0 text-bg-primary px-2">
                        ${blood.quantity} Pcs
                    </h5>
                    <a class="card-action-item" data-action="card-toggle" href="#!">
                        <i class="ti ti-chevron-up"></i>
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="col-12">
                    <div class="row g-2 align-items-end">
                        <div class="col-lg-2 col-6">
                            <label class="form-label text-muted">Group</label>
                            <select class="form-control" id="blood_group_${index}" placeholder="Blood group..."></select>
                        </div>
                        <div class="col-lg-2 col-6">
                            <label class="form-label text-muted">Rhesus</label>
                            <select class="form-control" id="blood_rhesus_${index}" placeholder="Blood rhesus..."></select>
                        </div>
                        <div class="col-lg-3 col-6">
                            <label class="form-label text-muted">Component</label>
                            <select class="form-control" id="blood_component_${index}" placeholder="Blood component..."></select>
                        </div>
                        <div class="col-lg-2 col-6">
                            <label class="form-label text-muted">Volume</label>
                            <input class="form-control" type="text" value="${blood.blood_volume}" ${disabledAttr} />
                        </div>
                        <div class="col-lg-3 col-6">
                            <label class="form-label text-muted">Quantity</label>
                            <input class="form-control" type="text" value="${blood.quantity}" ${disabledAttr} />
                        </div>
                        <div class="col-3">
                            <div class="d-flex flex-wrap gap-1">
                                ${
                                    isDraft
                                        ? `
                                    <div class="form-check form-check-danger">
                                        <input class="form-check-input" type="checkbox" ${parseInt(blood.is_hiv) ? "checked" : ""} />
                                        <label class="form-check-label">HIV?</label>
                                    </div>
                                    <div class="form-check form-check-danger">
                                        <input class="form-check-input" type="checkbox" ${parseInt(blood.is_hcv) ? "checked" : ""} />
                                        <label class="form-check-label">HCV?</label>
                                    </div>
                                    <div class="form-check form-check-danger">
                                        <input class="form-check-input" type="checkbox" ${parseInt(blood.is_hbsag) ? "checked" : ""} />
                                        <label class="form-check-label">HbsAG?</label>
                                    </div>
                                    <div class="form-check form-check-danger">
                                        <input class="form-check-input" type="checkbox" ${parseInt(blood.is_syphilis) ? "checked" : ""} />
                                        <label class="form-check-label">Syphilis?</label>
                                    </div>
                                `
                                        : `
                                    <div class="d-flex flex-column gap-1">
                                        <span>HIV: ${renderBooleanBadge(blood.is_hiv)}</span>
                                        <span>HCV: ${renderBooleanBadge(blood.is_hcv)}</span>
                                        <span>HbsAG: ${renderBooleanBadge(blood.is_hbsag)}</span>
                                        <span>Syphilis: ${renderBooleanBadge(blood.is_syphilis)}</span>
                                    </div>
                                `
                                }
                            </div>
                        </div>
                        <div class="col-9">
                            <label class="form-label text-muted">Note</label>
                            <textarea class="form-control" rows="5" placeholder="Note" ${disabledAttr}>${blood.note ?? ""}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        `;

        container.appendChild(card);

        // ---------- Init TomSelect per baris setelah card di-append :begin ----------
        initAndSetTomSelect(
            document.getElementById(`blood_group_${index}`),
            UrlSelectBloodGroup,
            blood.blood_group,
            isDraft,
        );
        initAndSetTomSelect(
            document.getElementById(`blood_rhesus_${index}`),
            UrlSelectBloodRhesus,
            blood.rhesus,
            isDraft,
        );
        initAndSetTomSelect(
            document.getElementById(`blood_component_${index}`),
            UrlSelectBloodComponent,
            blood.blood_component,
            isDraft,
        );
        // ---------- Init TomSelect per baris setelah card di-append :end ----------

        // ---------- Auto collapse card selain card pertama :begin ----------
        if (index > 0) {
            const toggleBtn = card.querySelector('[data-action="card-toggle"]');
            if (toggleBtn) toggleBtn.click();
        }
        // ---------- Auto collapse card selain card pertama :end ----------
    });
}
// ---------- Fungsi untuk populate blood data ke tampilan :end ----------

// ---------- Fungsi untuk populate order data ke tampilan :begin ----------
function PopulateOrderData(orderData, isDraft) {
    const poNumberTitle = document.getElementById(PoNumberTitleSelector);
    if (poNumberTitle) poNumberTitle.textContent = orderData.po_number;

    const container = document.getElementById(OrderDataContainerSelector);
    if (!container) return;

    container.innerHTML = "";

    const disabledAttr = isDraft ? "" : "disabled";

    const card = document.createElement("div");
    card.className = "row order-data-row";
    card.innerHTML = `
        <div class="col-12 my-1">
            <label class="form-label" for="po_number">PO Number <span class="text-danger">*</span></label>
            <input autocomplete="off" class="form-control" id="po_number" name="po_number" type="text"
                placeholder="PO Number" value="${orderData.po_number}" disabled />
        </div>
        <div class="col-lg-6 col-12 my-1">
            <label class="form-label" for="select-vendor">Vendor <span class="text-danger">*</span></label>
            <select class="form-control" id="select-vendor" name="vendor_id" placeholder="Choose vendor..."></select>
        </div>
        <div class="col-lg-6 col-12 my-1">
            <label class="form-label" for="vendor-name">Vendor Name</label>
            <input autocomplete="off" class="form-control" id="vendor-name" type="text"
                placeholder="Vendor name" value="${orderData.vendors?.name ?? ""}" disabled />
        </div>
        <div class="col-12 my-1">
            <label class="form-label" for="vendor-address">Vendor Address</label>
            <textarea autocomplete="off" class="form-control" id="vendor-address" rows="5"
                placeholder="Vendor address" disabled>${orderData.vendors?.address ?? ""}</textarea>
        </div>
        <div class="col-12 my-1">
            <label class="form-label" for="description">Description</label>
            <textarea autocomplete="off" class="form-control" id="description" name="description" rows="5"
                placeholder="Order description" ${disabledAttr}>${orderData.description ?? ""}</textarea>
        </div>
    `;

    container.appendChild(card);

    initAndSetTomSelect(
        document.getElementById("select-vendor"),
        UrlSelectVendor,
        orderData.vendors?.public_id,
        isDraft,
    );
}
// ---------- Fungsi untuk populate order data ke tampilan :end ----------

// ---------- Fungsi untuk populate order log activity :begin ----------
function PopulateOrderLogActivityData(log, order) {
    const container = document.querySelector(".order-log-data-container");
    if (!container) return;

    container.innerHTML = "";

    const logs = Array.isArray(log) ? log : [log];

    const timeline = document.createElement("div");
    timeline.className = "timeline timeline-icon-bordered";

    logs.forEach((logItem, index) => {
        const statusConfig = getLogStatusConfig(logItem.status);
        const formattedDate = DateTimeFormatter.datetime12(logItem.created_at);

        const userName = order?.users?.name ?? "-";

        // FIX: roles[0] rawan crash jika array kosong, gunakan optional chaining
        const userRole = order?.users?.roles?.[0]?.name ?? "User";

        const item = document.createElement("div");
        item.className = "timeline-item d-flex align-items-stretch";
        item.id = `order_log_${index + 1}`;
        item.innerHTML = `
            <div class="timeline-time pe-3 text-muted" id="order_log_created_at_${index + 1}">
                ${formattedDate}
            </div>
            <div class="timeline-dot">
                <i class="ti ${statusConfig.icon} fs-4 ${statusConfig.colorClass}"
                    data-bs-title="${statusConfig.tooltip}"
                    data-bs-toggle="tooltip"
                    data-bs-trigger="hover"
                    id="order_log_icon_${index + 1}">
                </i>
            </div>
            <div class="timeline-content ps-3 pb-4">
                <h5 class="mb-1" id="order_log_title_${index + 1}">${statusConfig.title}</h5>
                <p class="mb-1 text-muted" id="order_log_description_${index + 1}">${logItem.description ?? "-"}</p>
                <div class="d-flex align-items-center justify-content-start gap-1">
                    <span class="badge badge-default" id="order_log_by_user_role_${index + 1}">${userRole}</span>
                    <span class="text-primary fw-medium" id="order_log_by_user_name_${index + 1}">${userName}</span>
                </div>
            </div>
        `;

        timeline.appendChild(item);
    });

    container.appendChild(timeline);

    // ---------- Re-init tooltip Bootstrap setelah elemen di-render :begin ----------
    container.querySelectorAll('[data-bs-toggle="tooltip"]').forEach((el) => {
        new bootstrap.Tooltip(el);
    });
    // ---------- Re-init tooltip Bootstrap setelah elemen di-render :end ----------
}
// ---------- Fungsi untuk populate order log activity :end ----------

document.addEventListener("DOMContentLoaded", async () => {
    const data = await FetchDataOrderAndLog();
    if (!data) return;

    const { order, log } = data;

    const isDraft = order.status === "draft";

    // ---------- Populate total quantity ----------
    const totalQtyEl = document.getElementById(OrderDataTotalQtySelector);
    if (totalQtyEl) totalQtyEl.textContent = `${order.total_quantity} Pcs`;

    // ---------- Populate semua section ----------
    PopulateBloodData(order.order_bloods, isDraft);
    PopulateOrderData(order, isDraft);
    PopulateOrderLogActivityData(log, order);
});
