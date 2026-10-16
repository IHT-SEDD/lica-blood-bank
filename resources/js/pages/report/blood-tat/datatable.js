import { GlobalAdvanceYajraDatatable } from "../../../utility/datatable/datatables";
import { DateTimeFormatter } from "../../../utility/ui";

// ---------- Global variable untuk memudahkan penyesuaian ----------
let reportBloodTATTableInstance;
const DatatableSelector = "#report-blood-tat-table";
const ReportDataURL = "/report/blood-tat/data";

// ---------- HELPERS ----------
export function reloadTable() {
    if (reportBloodTATTableInstance?.instance) {
        reportBloodTATTableInstance.instance.ajax.reload();
    }
}
function formatTAT(start, end) {
    if (!start || !end) return "-";

    const startMs = new Date(start).getTime();
    const endMs = new Date(end).getTime();
    if (isNaN(startMs) || isNaN(endMs)) return "-";

    let diff = Math.floor((endMs - startMs) / 1000); // selisih dalam detik
    if (diff < 0) return "-"; // tanggal keluar lebih awal dari tanggal masuk (data tidak valid)

    const pad = (n) => String(n).padStart(2, "0");

    const hours = Math.floor(diff / 3600);
    const minutes = Math.floor((diff % 3600) / 60);
    const seconds = diff % 60;

    return `${pad(hours)} Jam ${pad(minutes)} Menit ${pad(seconds)} Detik`;
}

// ---------- Datatable ----------
export function ReportBloodTATTable(getFilters) {
    const ReportBloodTATTableColumns = [
        {
            data: null,
            title: "No",
            orderable: false,
            searchable: false,
            render: (data, type, row, meta) =>
                meta.row + meta.settings._iDisplayStart + 1,
        },
        {
            data: "blood_stock.bag_number",
            title: "No. Labu",
        },
        {
            data: null,
            title: "Detail",
            orderable: false,
            searchable: false,
            render: (data, type, row) => {
                const pack = row.blood_stock?.blood_packs;
                if (!pack) return "-";
                return `${pack.blood_component} ${pack.blood_group}${pack.blood_rhesus}`;
            },
        },
        {
            data: "blood_stock.created_at",
            title: "Tgl. Masuk",
            render: (data) => {
                return DateTimeFormatter.human(data);
            },
        },
        {
            data: "blood_stock.used_at",
            title: "Tgl. Digunakan",
            render: (data) => {
                return DateTimeFormatter.human(data);
            },
        },
        {
            data: "blood_released_at",
            title: "Tgl. Dikeluarkan",
            render: (data) => {
                return DateTimeFormatter.human(data);
            },
        },
        {
            data: null,
            title: "TAT",
            orderable: false,
            searchable: false,
            render: (data, type, row) =>
                formatTAT(row.blood_stock?.created_at, row.blood_released_at),
        },
    ];

    reportBloodTATTableInstance = new GlobalAdvanceYajraDatatable(
        DatatableSelector,
        {
            ajax: {
                url: ReportDataURL,
                data: function (d) {
                    const filters = getFilters();
                    d.start_date = filters.start_date;
                    d.end_date = filters.end_date;
                    d.blood_component = filters.bloodComponent;
                },
            },
            columns: ReportBloodTATTableColumns,
            useHideColumn: true,
            columnDefs: [
                { targets: -1, responsivePriority: 1 },
                { targets: 0, responsivePriority: 2 },
            ],
        },
    );
}
