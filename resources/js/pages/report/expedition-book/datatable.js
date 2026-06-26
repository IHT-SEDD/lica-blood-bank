import { GlobalAdvanceYajraDatatable } from "../../../utility/datatable/datatables";
import { DateTimeFormatter } from "../../../utility/ui";

// ---------- Global variable untuk memudahkan penyesuaian ----------
let reportExpeditionBookTableInstance;
const DatatableSelector = "#report-expedition-book-table";
const ReportDataURL = "/report/expedition-book/data";

// ---------- HELPERS ----------
export function reloadTable() {
    if (reportExpeditionBookTableInstance?.instance) {
        reportExpeditionBookTableInstance.instance.ajax.reload();
    }
}

// ---------- Datatable ----------
export function ReportExpeditionBookTable(getFilters) {
    const ReportExpeditionBookTableColumns = [
        {
            data: null,
            title: "No",
            orderable: false,
            searchable: false,
            render: (data, type, row, meta) => {
                return meta.row + 1;
            },
        },
        {
            data: "tanggal",
            title: "Tanggal",
        },
        {
            data: "asal_labu",
            title: "Asal Labu",
        },
        {
            data: "nama_pasien",
            title: "Nama Pasien",
        },
        {
            data: "no_medrec",
            title: "No. Medrec",
        },
        {
            data: "goldar_rhesus",
            title: "Goldar + Rhesus",
        },
        {
            data: "ruangan",
            title: "Ruangan",
        },
        {
            data: "diagnosa",
            title: "Diagnosa",
        },
        {
            data: "jenis_pasien",
            title: "Jenis Pasien",
        },
        {
            data: "jenis_darah",
            title: "Jenis Darah",
        },
        {
            data: "jam_penerimaan",
            title: "Jam Penerimaan",
        },
        {
            data: "jam_mulai",
            title: "Jam Mulai",
        },
        {
            data: "jam_selesai",
            title: "Jam Selesai",
        },
        {
            data: "no_kantong_darah",
            title: "No. Kantong Darah",
        },
        {
            data: "result_mayor",
            title: "Result Mayor",
        },
        {
            data: "result_minor",
            title: "Result Minor",
        },
        {
            data: "result_auto_control",
            title: "Result Auto Control",
        },
    ];

    reportExpeditionBookTableInstance = new GlobalAdvanceYajraDatatable(
        DatatableSelector,
        {
            ajax: {
                url: ReportDataURL,
                data: function (d) {
                    const filters = getFilters();
                    d.month_year = filters.monthAndYear;
                    d.blood_component = filters.bloodComponent;
                },
            },
            columns: ReportExpeditionBookTableColumns,
            useHideColumn: true,
            columnDefs: [
                { targets: -1, responsivePriority: 1 },
                { targets: 0, responsivePriority: 2 },
            ],
        },
    );
}
