// ---------- TIMELINE ORDER LOG CONFIG ----------
export const OrderLogConfigTL = {
    draft_created: {
        icon: "clipboard-plus",
        colorClass: "text-secondary fill-secondary",
        title: "Draft Permintaan Disimpan",
        tooltipTitle: "Permintaan Berhasil Disimpan Sebagai Draft",
    },
    draft_cancelled: {
        icon: "clipboard-x",
        colorClass: "text-warning fill-warning",
        title: "Draft Permintaan Dibatalkan",
        tooltipTitle: "Draft Permintaan Berhasil Dibatalkan",
    },
    draft_deleted: {
        icon: "trash-x",
        colorClass: "text-danger fill-danger",
        title: "Draft Permintaan Dihapus",
        tooltipTitle: "Draft Permintaan Berhasil Dihapus",
    },

    po_file_generated: {
        icon: "file-spark",
        colorClass: "text-info fill-info",
        title: "File PO Dibuat",
        tooltipTitle: "File PO Berhasil Dibuat",
    },
    po_file_printed: {
        icon: "printer",
        colorClass: "text-primary fill-primary",
        title: "File PO Dicetak",
        tooltipTitle: "File PO Berhasil Di cetak/Di print",
    },
    po_file_downloaded: {
        icon: "file-download",
        colorClass: "text-primary fill-primary",
        title: "File PO Didownload",
        tooltipTitle: "File PO Berhasil Di download/Di unduh",
    },

    order_created: {
        icon: "file-plus",
        colorClass: "text-info fill-info",
        title: "Permintaan Baru Dibuat",
        tooltipTitle: "Permintaan Baru Berhasil Dibuat",
    },
    order_updated: {
        icon: "file-pencil",
        colorClass: "text-primary fill-primary",
        title: "Permintaan Diperbaharui",
        tooltipTitle: "Data Permintaan Berhasil Diperbaharui",
    },
    order_edited: {
        icon: "file-pencil",
        colorClass: "text-warning fill-warning",
        title: "Permintaan Diubah",
        tooltipTitle: "Data Permintaan Berhasil Diubah",
    },
    order_cancelled: {
        icon: "file-x",
        colorClass: "text-warning fill-warning",
        title: "Permintaan Dibatalkan",
        tooltipTitle: "Permintaan Berhasil Dibatalkan",
    },
    order_deleted: {
        icon: "trash-x",
        colorClass: "text-danger fill-danger",
        title: "Permintaan Dihapus",
        tooltipTitle: "Permintaan Berhasil Dihapus",
    },
    order_stock_registered: {
        icon: "package",
        colorClass: "text-success fill-success",
        title: "Beberapa Darah Permintaan Didaftarkan",
        tooltipTitle: "Beberapa Darah Permintaan Telah Berhasil Didaftarkan",
    },
    all_order_stock_registered: {
        icon: "packages",
        colorClass: "text-success fill-success",
        title: "Semua Darah Permintaan Terdaftar",
        tooltipTitle: "Semua Darah Permintaan Telah Berhasil Didaftarkan",
    },

    done: {
        icon: "circle-check",
        colorClass: "text-success fill-success",
        title: "Permintaan Selesai",
        tooltipTitle: "Permintaan Berhasil Diselesaikan",
    },
    fallback: {
        icon: "activity",
        colorClass: "text-secondary fill-secondary",
        title: "Aktivitas",
        tooltipTitle: "Aktivitas",
    },
};

// ---------- TIMELINE BLOOD STOCK LOG CONFIG ----------
export const BloodStockLogConfigTL = {
    blood_stock_created_by_manual: {
        icon: "droplet-plus",
        colorClass: "text-info fill-info",
        title: "[Manual] Penambahan Stok Darah Baru",
        tooltipTitle: "Stok darah telah ditambahkan dengan metode manual",
    },
    blood_stock_created_by_scan: {
        icon: "droplet-plus",
        colorClass: "text-info fill-info",
        title: "[Scan] Penambahan Stok Darah Baru",
        tooltipTitle: "Stok darah telah ditambahkan dengan metode scan",
    },

    blood_stock_deleted: {
        icon: "trash-x",
        colorClass: "text-danger fill-danger",
        title: "Stok Darah Dihapus",
        tooltipTitle: "Stok darah telah dihapus",
    },
    blood_stock_updated: {
        icon: "pencil",
        colorClass: "text-secondary fill-secondary",
        title: "Data Stok Darah Diperbaharui",
        tooltipTitle: "Data stok darah telah diperbaharui",
    },
    blood_stock_restored: {
        icon: "droplets",
        colorClass: "text-secondary fill-secondary",
        title: "Stok Darah Dipulihkan",
        tooltipTitle: "Stok darah telah dipulihkan",
    },
    blood_stock_in_use: {
        icon: "droplet-heart",
        colorClass: "text-primary fill-primary",
        title: "Stok Darah Sedang Digunakan",
        tooltipTitle: "Stok darah sedang digunakan",
    },
    blood_stock_taken_out: {
        icon: "droplet-minus",
        colorClass: "text-warning fill-warning",
        title: "Stok Darah Dikeluarkan",
        tooltipTitle: "Stok darah telah dikeluarkan",
    },

    blood_stock_expired: {
        icon: "calendar-x",
        colorClass: "text-danger fill-danger",
        title: "Stok Darah Expire",
        tooltipTitle: "Stok darah telah expire atau kadaluarsa",
    },
    blood_stock_destroyed: {
        icon: "trash",
        colorClass: "text-danger fill-danger",
        title: "Stok Darah Dimusnahkan",
        tooltipTitle: "Stok darah telah dimusnahkan",
    },
    expired: {
        icon: "calendar-x",
        colorClass: "text-danger fill-danger",
        title: "Stok Darah Expire",
        tooltipTitle: "Stok darah telah expire atau kadaluarsa",
    },

    fallback: {
        icon: "activity",
        colorClass: "text-secondary fill-secondary",
        title: "Aktivitas",
        tooltipTitle: "Aktivitas",
    },
};

// ---------- TIMELINE BLOOD TRANSFUSION LOG CONFIG ----------
export const BloodTransfusionLogConfigTL = {
    blood_transfusion_registered: {
        icon: "circle-dashed-check",
        colorClass: "text-info fill-info",
        title: "Transaksi Baru",
        tooltipTitle: "Transaksi permintaan darah baru berhasil dibuat",
    },
    blood_transfusion_finished: {
        icon: "droplet-check",
        colorClass: "text-success fill-success",
        title: "Transaksi Selesai",
        tooltipTitle: "Transaksi pasien ini berhasil diselesaikan",
    },
    crossmatch_finished: {
        icon: "droplet-check",
        colorClass: "text-success fill-success",
        title: "Crossmatch Selesai",
        tooltipTitle: "Pemeriksaan crossmatch berhasil diselesaikan",
    },
    crossmatch_result_updated: {
        icon: "pencil",
        colorClass: "text-info fill-info",
        title: "Hasil Crossmatch Diperbaharui",
        tooltipTitle: "Hasil crossmatch telah berhasil diperbaharui",
    },
    blood_transfusion_completed: {
        icon: "shield-check",
        colorClass: "text-success fill-success",
        title: "Transaksi Selesai",
        tooltipTitle: "Transaksi berhasil diselesaikan",
    },
    blood_transfusion_checked_in: {
        icon: "user-check",
        colorClass: "text-success fill-success",
        title: "Pasien Checkin",
        tooltipTitle: "Pasien berhasil di checkin/di terima",
    },
    blood_transfusion_updated: {
        icon: "pencil",
        colorClass: "text-info fill-info",
        title: "Data Transaksi/Patient Diperbaharui",
        tooltipTitle: "Data transaksi/pasien berhasil diperbaharui",
    },

    blood_transfusion_deleted: {
        icon: "trash",
        colorClass: "text-danger fill-danger",
        title: "Transaksi Dihapus",
        tooltipTitle: "Transaksi permintaan darah berhasil dihapus",
    },
    blood_transfusion_archived: {
        icon: "archive",
        colorClass: "text-secondary fill-secondary",
        title: "Transaksi Diarsip",
        tooltipTitle: "Transaksi permintaan darah berhasil diarsip",
    },

    blood_stock_hold: {
        icon: "heart-pause",
        colorClass: "text-warning fill-warning",
        title: "Labu Darah Ditahan",
        tooltipTitle: "Labu darah berhasil ditahan",
    },
    blood_stock_released: {
        icon: "heart-up",
        colorClass: "text-danger fill-danger",
        title: "Labu Darah Dikeluarkan",
        tooltipTitle: "Labu darah berhasil dikeluarkan",
    },
    blood_stock_deleted: {
        icon: "trash",
        colorClass: "text-danger fill-danger",
        title: "Labu Darah Dihapus",
        tooltipTitle: "Labu darah berhasil dihapus",
    },
    blood_stock_not_released: {
        icon: "heart-x",
        colorClass: "text-danger fill-danger",
        title: "Labu Darah Tidak Dikeluarkan",
        tooltipTitle: "Labu darah berhasil tidak dikeluarkan",
    },
    blood_stock_approved_incompatible: {
        icon: "clipboard-check",
        colorClass: "text-success fill-success",
        title: "Labu Darah Incompatible Disetujui",
        tooltipTitle: "Labu darah dengan hasil incompatible berhasil disetujui",
    },

    fallback: {
        icon: "activity",
        colorClass: "text-secondary fill-secondary",
        title: "Aktivitas",
        tooltipTitle: "Aktivitas",
    },
};
