import { DateTimeFormatter } from "../../../utility/ui";
import { GlobalAdvanceYajraDatatable } from "../../../utility/datatable/datatables";
import { GlobalAdvanceTomselect } from "../../../app";

// ---------- Global variable untuk memudahkan penyesuaian ----------
let listStockTableInstance;

// Table List Stock
const ListStockTableSelector = "#list-stock-table";
const ListStockTableDataURL = "/inventory/dashboard/data/blood-stock";
const ReloadListStockTableSelector = "list-stock-reload";

// See Detail Action
const ActionDetailSelector = ".btn-see-detail-blood-stock";
const AttributeSeeDetail = "seeDetailId";

// ---------- Global State ----------
let activeBloodGroup = "a";
let activeBloodRhesus = "+";

// ---------- Helper: Reload tabel ----------
function reloadTable() {
    if (listStockTableInstance?.instance) {
        listStockTableInstance.instance.ajax.reload();
    }
}

// ---------- Helper: Set filter aktif dan reload ----------
export function setBloodFilter(bloodGroup, bloodRhesus) {
    activeBloodGroup = bloodGroup;
    activeBloodRhesus = bloodRhesus;
    reloadTable();
}

function getFilters() {
    const status = document.querySelector("#filter-status-darah")?.value || "";
    return { status };
}
function FilterStatus() {
    const filterStatus = new GlobalAdvanceTomselect("#filter-status-darah", {
        valueField: "id",
        preload: true,
        load: function (query, callback) {
            fetch(
                `/utility/select/blood-stock-status?q=${encodeURIComponent(query)}`,
            )
                .then((res) => res.json())
                .then((json) => callback(json.results))
                .catch(() => callback());
        },
        onChange: function () {
            reloadTable();
        },
    });
}

// ---------- Datatable Blood Stock ----------
function ListStockTable() {
    // ---------- Init kolom pada tabel ----------
    const ListStockTableColumns = [
        {
            data: null,
            title: "No.",
            defaultContent: "",
            orderable: false,
            render: (data, type, row, meta) => {
                return meta.row + 1;
            },
        },
        { data: "bag_number", title: "No. Kantong" },
        {
            data: null,
            defaultContent: "",
            orderable: false,
            title: "Detail",
            render: (data, type, row) => {
                const bloodPacks = row.blood_packs;
                const bloodGroup = bloodPacks.blood_group || "";
                const bloodRhesus = bloodPacks.blood_rhesus || "";
                const bloodComponent = bloodPacks.blood_component || "";
                const bloodPack =
                    bloodGroup && bloodRhesus
                        ? `${bloodGroup}${bloodRhesus} ${bloodComponent}`
                        : bloodGroup || "-";

                return `<span class="fw-semibold fs-5">${bloodPack}</span>`;
            },
        },
        {
            data: null,
            defaultContent: "",
            orderable: false,
            title: "Status",
            render: (data, type, row) => {
                switch (row.blood_status) {
                    case "expired":
                        return `<span class="badge badge-label fw-semibold badge-soft-danger">
                            <i class="ti ti-calendar-x align-middle me-2 fs-4"></i>
                            Expire!
                        </span>`;
                        break;
                    case "in_use":
                        return `<span class="badge badge-label fw-semibold badge-soft-info">
                            <i class="ti ti-droplet-heart align-middle me-2 fs-4"></i>
                            Sedang Digunakan
                        </span>`;
                        break;
                    case "available":
                        return `<span class="badge badge-label fw-semibold badge-soft-success">
                            <i class="ti ti-circle-check align-middle me-2 fs-4"></i>
                            Tersedia
                        </span>`;
                        break;
                    case "destroyed":
                        return `<span class="badge badge-label fw-semibold badge-soft-danger">
                            <i class="ti ti-heart-broken align-middle me-2 fs-4"></i>
                            Dimusnahkan
                        </span>`;
                        break;

                    default:
                        return `<span class="badge badge-label fw-semibold badge-soft-primary">
                            <i class="ti ti-droplet align-middle me-2 fs-4"></i>
                            ${row.blood_status}
                        </span>`;
                        break;
                }
            },
        },
        { data: "patient_name", title: "Nama Pasien" },
        {
            data: "created_at",
            title: "Tgl. Diterima",
            render: (data) => {
                return DateTimeFormatter.human(data);
            },
        },
        {
            data: "expiry_date",
            title: "Tgl. Expire",
            render: (data) => {
                return DateTimeFormatter.dateOnly(data);
            },
        },
        {
            data: null,
            title: "Sisa Umur",
            render: (data, type, row) => {
                const now = new Date();
                const expiry = new Date(row.expiry_date);

                // Jika sudah expired
                if (expiry <= now) {
                    return `<span class="text-danger fw-semibold">
                    Expired
                </span>`;
                }

                let diff = expiry - now;

                const minute = 1000 * 60;
                const hour = minute * 60;
                const day = hour * 24;
                const month = day * 30;
                const year = day * 365;

                const years = Math.floor(diff / year);
                diff %= year;

                const months = Math.floor(diff / month);
                diff %= month;

                const days = Math.floor(diff / day);
                diff %= day;

                const hours = Math.floor(diff / hour);
                diff %= hour;

                const minutes = Math.floor(diff / minute);

                let result = [];

                // Tampilkan hanya jika > 0
                if (years > 0) result.push(`${years} Tahun`);
                if (months > 0) result.push(`${months} Bulan`);
                if (days > 0) result.push(`${days} Hari`);
                if (hours > 0) result.push(`${hours} Jam`);
                if (minutes > 0) result.push(`${minutes} Menit`);

                return result.join(" ");
            },
        },
        {
            data: "updated_at",
            title: "Tgl. Update",
            render: (data) => {
                return DateTimeFormatter.human(data);
            },
        },
        {
            data: null,
            title: "Aksi",
            render: (data, type, row, meta) => {
                const isDeleted = row.deleted_at !== null;
                return `<button aria-expanded="false" class="btn btn-sm btn-soft-primary datatable-action-toggle" data-bs-toggle="dropdown" data-bs-auto-close="true" type="button">
                  <i class="ti ti-dots align-middle"></i>
                 </button>
                 <ul class="dropdown-menu">
                     <li>
                        <button id="see-detail-${row.blood_packs.public_id}" class="dropdown-item fw-medium btn-see-detail-blood-stock text-primary" data-see-detail-id="${row.blood_packs.public_id}" type="button">
                        <i class="ti ti-heart-search align-middle me-2 fs-4"></i>
                        Detail
                        </button>
                    </li>
                 </ul>
                `;
            },
        },
    ];

    // ---------- Panggil GlobalAdvanceDatatable untuk menampilkan tabel ----------
    listStockTableInstance = new GlobalAdvanceYajraDatatable(
        ListStockTableSelector,
        {
            ajax: {
                url: ListStockTableDataURL,
                data: (d) => {
                    const filters = getFilters();
                    d.blood_group = activeBloodGroup;
                    d.blood_rhesus = activeBloodRhesus;
                    d.status = filters.status;
                    return d;
                },
            },
            columns: ListStockTableColumns,
            useHideColumn: true,
            columnDefs: [
                { targets: -1, responsivePriority: 1 },
                { targets: 0, responsivePriority: 2 },
            ],
        },
    );
}

// ---------- Handle see detail ----------
function SeeDetailBloodStockAction() {
    document.addEventListener("click", function (e) {
        const btn = e.target.closest(ActionDetailSelector);
        if (!btn) return;

        const id = btn.dataset[AttributeSeeDetail];
        if (!id) return;

        window.location.href = `/inventory/blood-stock/detail/${id}`;
    });
}

export { ListStockTable, SeeDetailBloodStockAction, FilterStatus };
