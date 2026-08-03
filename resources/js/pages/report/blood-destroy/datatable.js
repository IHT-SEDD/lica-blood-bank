import { GlobalAdvanceYajraDatatable } from "../../../utility/datatable/datatables";
import { DateTimeFormatter } from "../../../utility/ui";

// ---------- Global variable untuk memudahkan penyesuaian ----------
let reportBloodDestroyTableInstance;
const DatatableSelector = "#report-blood-destroy-table";
const ReportDataURL = "/report/blood-destroy/data";

// ---------- HELPERS ----------
export function reloadTable() {
    if (reportBloodDestroyTableInstance?.instance) {
        reportBloodDestroyTableInstance.instance.ajax.reload();
    }
}

// ---------- Datatable ----------
export function ReportBloodDestroyTable(getFilters) {
    const ReportBloodDestroyTableColumns = [
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
            data: "created_at",
            title: "Tgl. Dimusnahkan",
            render: (data, type, row, meta) => {
                return DateTimeFormatter.datetime24(row.created_at);
            },
        },
        { data: "blood_stocks.bag_number", title: "No. Labu" },
        {
            data: null,
            title: "Detail",
            orderable: false,
            orderable: false,
            render: (data, type, row, meta) => {
                return `${row.blood_stocks.blood_packs.blood_component} ${row.blood_stocks.blood_packs.blood_group}${row.blood_stocks.blood_packs.blood_rhesus}`;
            },
        },
        { data: "reason", title: "Alasan" },
        { data: "destroy_by.name", title: "Dimusnahkan Oleh" },
    ];

    reportBloodDestroyTableInstance = new GlobalAdvanceYajraDatatable(
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
            columns: ReportBloodDestroyTableColumns,
            useHideColumn: true,
            columnDefs: [
                { targets: -1, responsivePriority: 1 },
                { targets: 0, responsivePriority: 2 },
            ],
        },
    );
}
