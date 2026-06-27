import { DateTimeFormatter } from "../../../utility/ui";
import { GlobalAdvanceYajraDatatable } from "../../../utility/datatable/datatables";
import { GlobalAdvanceTomselect } from "../../../app";
import { BloodStockStatus } from "../../../utility/config/status-config";

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
                return `<span class="fs-6">${meta.row + 1}</span>`;
            },
        },
        {
            data: "bag_number",
            title: "No. Labu",
            render: (data, type, row, meta) => {
                return `<span class="fw-semibold fs-6">${data}</span>`;
            },
        },
        {
            data: null,
            title: "Detail",
            render: (data, row) => {
                const bloodPacks = data.blood_packs;
                return `<span class="fw-medium fs-6">${bloodPacks.blood_group}${bloodPacks.blood_rhesus} ${bloodPacks.blood_component}</span>`;
            },
        },
        {
            data: null,
            title: "Status",
            defaultContent: "",
            render: (data, type, row) => {
                return BloodStockStatus(row.blood_status);
            },
        },
        {
            data: "patient_name",
            title: "Nama Pasien",
            render: (data, type, row, meta) => {
                return `<span class="fw-semibold fs-6">${data ?? "-"}</span>`;
            },
        },
        {
            data: "created_at",
            title: "Tgl. Diterima",
            render: (data) => {
                return `<span class="fw-medium fs-6">${DateTimeFormatter.datetime24(data)}</span>`;
            },
        },
        {
            data: "expiry_date",
            title: "Tgl. Expire",
            render: (data) => {
                return `<span class="fw-medium fs-6">${DateTimeFormatter.datetime24(data)}</span>`;
            },
        },
        {
            data: null,
            title: "Sisa Umur",
            render: (data, type, row) => {
                const now = new Date();
                const expiry = new Date(row.expiry_date);
                if (expiry <= now) {
                    return `<span class="badge badge-label fw-semibold badge-soft-danger">
                        <i class="ti ti-calendar-x align-middle me-2 fs-4"></i>
                        Expired!
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

                if (years > 0) result.push(`${years} Tahun`);
                if (months > 0) result.push(`${months} Bulan`);
                if (days > 0) result.push(`${days} Hari`);
                if (hours > 0) result.push(`${hours} Jam`);
                if (minutes > 0) result.push(`${minutes} Menit`);

                return `<span class="fw-medium fs-6">${result.join(" ")}</span>`;
            },
        },
        {
            data: "updated_at",
            title: "Tgl. Update",
            render: (data) => {
                return `<span class="fw-medium fs-6">${DateTimeFormatter.datetime24(data)}</span>`;
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
            pageLengthOptions: [10, 25, 50, 100],
            pageLength: 25,
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
