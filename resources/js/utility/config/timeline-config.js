// ---------- TIMELINE ORDER LOG CONFIG ----------
export const OrderLogConfigTL = {
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

// ---------- TIMELINE BLOOD STOCK LOG CONFIG ----------
export const BloodStockLogConfigTL = {
    blood_stock_created_by_manual: {
        icon: "droplet-plus",
        colorClass: "text-info fill-info",
        title: "Blood Stock Created by Manual Method",
        tooltipTitle: "Blood Stock Created by Manual Method",
    },
    blood_stock_created_by_scan: {
        icon: "droplet-plus",
        colorClass: "text-info fill-info",
        title: "Blood Stock Created by Scan Method",
        tooltipTitle: "Blood Stock Created by Scan Method",
    },

    blood_stock_deleted: {
        icon: "trash-x",
        colorClass: "text-danger fill-danger",
        title: "Blood Stock Deleted",
        tooltipTitle: "Blood Stock Deleted",
    },
    blood_stock_restored: {
        icon: "droplets",
        colorClass: "text-secondary fill-secondary",
        title: "Blood Stock Restored",
        tooltipTitle: "Blood Stock Restored",
    },
    blood_stock_in_use: {
        icon: "droplet-heart",
        colorClass: "text-primary fill-primary",
        title: "Blood Stock Currently In Use",
        tooltipTitle: "Blood Stock Currently In Use",
    },
    blood_stock_taken_out: {
        icon: "droplet-minus",
        colorClass: "text-warning fill-warning",
        title: "Blood Stock Has Taken Out From Storage",
        tooltipTitle: "Blood Stock Has Taken Out From Storage",
    },

    blood_stock_expired: {
        icon: "droplet-x",
        colorClass: "text-danger fill-danger",
        title: "Blood Stock Expired",
        tooltipTitle: "Blood Stock Expired",
    },
    blood_stock_destroyed: {
        icon: "trash",
        colorClass: "text-danger fill-danger",
        title: "Blood Stock Destroyed",
        tooltipTitle: "Blood Stock Destroyed",
    },

    fallback: {
        icon: "activity",
        colorClass: "text-secondary fill-secondary",
        title: "Activity",
        tooltipTitle: "Activity",
    },
};
