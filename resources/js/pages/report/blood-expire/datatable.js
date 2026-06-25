import { GlobalAdvanceYajraDatatable } from "../../../utility/datatable/datatables";
import { DateTimeFormatter } from "../../../utility/ui";

// ---------- Global variable untuk memudahkan penyesuaian ----------
let reportBloodExpireTableInstance;
const DatatableSelector = "#report-blood-expire-table";
const ReportDataURL = "/report/blood-expire/data";

// ---------- HELPERS ----------
export function reloadTable() {
    if (reportBloodExpireTableInstance?.instance) {
        reportBloodExpireTableInstance.instance.ajax.reload();
    }
}

// ---------- Datatable ----------
export function ReportBloodExpireTable(getFilters) {
    const ReportBloodExpireTableColumns = [
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
            data: "expiry_date",
            title: "Tgl. Expire",
            render: (data, type, row, meta) => {
                return DateTimeFormatter.datetime24(row.expiry_date);
            },
        },
        {
            data: null,
            title: "Detail",
            orderable: false,
            searchable: false,
            render: (data, type, row, meta) => {
                return `${row.blood_component.name} ${row.blood_group.name}${row.blood_rhesus}`;
            },
        },
        { data: "total", title: "Total" },
    ];

    reportBloodExpireTableInstance = new GlobalAdvanceYajraDatatable(
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
            columns: ReportBloodExpireTableColumns,
            useHideColumn: true,
            columnDefs: [
                { targets: -1, responsivePriority: 1 },
                { targets: 0, responsivePriority: 2 },
            ],
        },
    );
}
