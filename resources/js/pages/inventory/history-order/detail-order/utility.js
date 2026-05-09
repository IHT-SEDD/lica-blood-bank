// ---------- Global variable untuk memudahkan penyesuaian :begin ----------
// ORDER STATUS
const ORDER_STATUS = window.AppEnum.orderBloodStatus;
// ---------- Global variable untuk memudahkan penyesuaian :end ----------

// ---------- Helper: ORDER STATUS CHECKER ----------
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

// ---------- Helper: TIMELINE ORDER LOG ----------
export const TimelineStatusConfig = {
    draft_created: {
        icon: "clipboard-plus",
        colorClass: "text-secondary fill-secondary",
        title: "Draft Created",
        tooltipTitle: "Draft Created",
    },
    draft_cancelled: {
        icon: "clipboard-x",
        colorClass: "text-warning fill-warning",
        title: "Draft Cancelled",
        tooltipTitle: "Draft Cancelled",
    },
    draft_deleted: {
        icon: "trash-x",
        colorClass: "text-danger fill-danger",
        title: "Draft Deleted",
        tooltipTitle: "Draft Deleted",
    },

    po_file_generated: {
        icon: "file-spark",
        colorClass: "text-info fill-info",
        title: "PO File Generated",
        tooltipTitle: "PO File Generated",
    },
    po_file_printed: {
        icon: "printer",
        colorClass: "text-primary fill-primary",
        title: "PO File Printed",
        tooltipTitle: "PO File Printed",
    },
    po_file_downloaded: {
        icon: "file-download",
        colorClass: "text-primary fill-primary",
        title: "PO File Downloaded",
        tooltipTitle: "PO File Downloaded",
    },

    order_created: {
        icon: "file-plus",
        colorClass: "text-info fill-info",
        title: "Order Created",
        tooltipTitle: "Order Created",
    },
    order_updated: {
        icon: "file-pencil",
        colorClass: "text-primary fill-primary",
        title: "Order Updated",
        tooltipTitle: "Order Updated",
    },
    order_edited: {
        icon: "file-pencil",
        colorClass: "text-warning fill-warning",
        title: "Order Edited",
        tooltipTitle: "Order Edited",
    },
    order_cancelled: {
        icon: "file-x",
        colorClass: "text-warning fill-warning",
        title: "Order Cancelled",
        tooltipTitle: "Order Cancelled",
    },
    order_deleted: {
        icon: "trash-x",
        colorClass: "text-danger fill-danger",
        title: "Order Deleted",
        tooltipTitle: "Order Deleted",
    },
    order_stock_registered: {
        icon: "package",
        colorClass: "text-success fill-success",
        title: "Stock Partially Registered",
        tooltipTitle: "Some Stock Registered",
    },
    all_order_stock_registered: {
        icon: "packages",
        colorClass: "text-success fill-success",
        title: "All Stock Registered",
        tooltipTitle: "All Stock Registered",
    },

    done: {
        icon: "circle-check",
        colorClass: "text-success fill-success",
        title: "Order Done",
        tooltipTitle: "Order Done",
    },
    fallback: {
        icon: "activity",
        colorClass: "text-secondary fill-secondary",
        title: "Activity",
        tooltipTitle: "Activity",
    },
};
