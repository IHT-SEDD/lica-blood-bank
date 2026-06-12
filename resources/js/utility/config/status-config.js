// ---------- Global variable untuk memudahkan penyesuaian :begin ----------
// ORDER STATUS
const ORDER_STATUS = window.AppEnum.orderBloodStatus;
// ---------- Global variable untuk memudahkan penyesuaian :end ----------

// ---------- ORDER STATUS ----------
export const OrderStatus = {
    DONE: ORDER_STATUS.DONE,
    DRAFT: ORDER_STATUS.DRAFT,
    ORDER_CREATED: ORDER_STATUS.ORDER_CREATED,

    isDone(status) {
        return status?.toLowerCase() === this.DONE;
    },

    isDraft(status) {
        return status?.toLowerCase() === this.DRAFT;
    },

    isOrderCreated(status) {
        return status?.toLowerCase() === this.ORDER_CREATED;
    },
};

// ---------- BLOOD STATUS ----------
export function BloodStatus(status) {
    const value = status?.value || status;

    switch (value) {
        case "expired":
            return `<span class="badge badge-label fw-semibold badge-soft-danger">
                <i class="ti ti-calendar-x align-middle me-2 fs-4"></i>
                Expired!
            </span>`;
        case "in_use":
            return `<span class="badge badge-label fw-semibold badge-soft-info">
                <i class="ti ti-droplet-heart align-middle me-2 fs-4"></i>
                Sedang Digunakan
            </span>`;
        case "available":
            return `<span class="badge badge-label fw-semibold badge-soft-success">
                <i class="ti ti-circle-check align-middle me-2 fs-4"></i>
                Tersedia
            </span>`;
        case "destroyed":
            return `<span class="badge badge-label fw-semibold badge-soft-danger">
                <i class="ti ti-heart-broken align-middle me-2 fs-4"></i>
                Dimusnahkan
            </span>`;
        case "taken_out":
            return `<span class="badge badge-label fw-semibold badge-soft-primary">
                <i class="ti ti-heart-up align-middle me-2 fs-4"></i>
                Dikeluarkan
            </span>`;
        default:
            return `<span class="badge badge-label fw-semibold badge-soft-secondary">
                <i class="ti ti-droplet align-middle me-2 fs-4"></i>
                ${value ?? "-"}
            </span>`;
    }
}

// ---------- TRANSACTION ORDER STATUS ----------
export function TransactionOrderStatus(status) {
    const value = status?.value || status;

    switch (value) {
        case "Blood Transfusion Checked In":
            return `<span style="font-size: 20px;" class="text-success" data-bs-title="Checked In" data-bs-toggle="tooltip" data-bs-trigger="hover">
                <i class="ti ti-user-check"></i>
            </span>`;
            break;
        case "Blood Transfusion Finished":
            return `<span style="font-size: 20px;" class="text-success" data-bs-title="Transaksi Selesai" data-bs-toggle="tooltip" data-bs-trigger="hover">
                <i class="ti ti-droplet-check"></i>
            </span>`;
            break;
        case "Blood Transfusion Completed":
            return `<span style="font-size: 20px;" class="text-success" data-bs-title="Transaksi Selesai" data-bs-toggle="tooltip" data-bs-trigger="hover">
                <i class="ti ti-shield-check"></i>
            </span>`;
            break;
        case "Blood Transfusion Registered":
            return `<span style="font-size: 20px;" class="text-info" data-bs-title="Terdaftar" data-bs-toggle="tooltip" data-bs-trigger="hover">
                <i class="ti ti-circle-dashed-check"></i>
            </span>`;
            break;
        case "Blood Transfusion Deleted":
            return `<span style="font-size: 20px;" class="text-danger" data-bs-title="Dihapus" data-bs-toggle="tooltip" data-bs-trigger="hover">
                <i class="ti ti-trash"></i>
            </span>`;
            break;
        default:
            return `<span class="fs-6 fw-semibold uppercase">
                ${value ?? "-"}
            </span>`;
    }
}
