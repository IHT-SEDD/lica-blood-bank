import { GlobalAdvanceYajraDatatable } from "../../../utility/datatable/datatables";

// ---------- Global variable untuk memudahkan penyesuaian ----------
let reportBloodRequestTableInstance;
const DatatableSelector = "#report-blood-request-table";
const ReportDataURL = "/report/blood-request/data";

// ---------- HELPERS ----------
export function reloadTable() {
    if (reportBloodRequestTableInstance?.instance) {
        reportBloodRequestTableInstance.instance.ajax.reload();
    }
}

// ---------- Datatable ----------
export function ReportBloodRequestTable(getFilters) {
    const ReportBloodRequestTableColumns = [
        {
            data: null,
            title: "No",
            orderable: false,
            searchable: false,
            render: (data, type, row, meta) => {
                return meta.row + 1;
            },
        },
        { data: "room_name", title: "Ruangan" },
        { data: "PRC_A", title: "A (PRC)" },
        { data: "PRC_B", title: "B (PRC)" },
        { data: "PRC_O", title: "O (PRC)" },
        { data: "PRC_AB", title: "AB (PRC)" },
        { data: "TC_A", title: "A (TC)" },
        { data: "TC_B", title: "B (TC)" },
        { data: "TC_O", title: "O (TC)" },
        { data: "TC_AB", title: "AB (TC)" },
        { data: "LP_A", title: "A (LP)" },
        { data: "LP_B", title: "B (LP)" },
        { data: "LP_O", title: "O (LP)" },
        { data: "LP_AB", title: "AB (LP)" },
        { data: "WB_A", title: "A (WB)" },
        { data: "WB_B", title: "B (WB)" },
        { data: "WB_O", title: "O (WB)" },
        { data: "WB_AB", title: "AB (WB)" },
        { data: "total", title: "Total" },
    ];

    reportBloodRequestTableInstance = new GlobalAdvanceYajraDatatable(
        DatatableSelector,
        {
            ajax: {
                url: ReportDataURL,
                data: function (d) {
                    const filters = getFilters();
                    d.room_public_id = filters.room;
                    d.month_year = filters.monthAndYear;
                },
            },
            columns: ReportBloodRequestTableColumns,
            useHideColumn: true,
            columnDefs: [
                { targets: -1, responsivePriority: 1 },
                { targets: 0, responsivePriority: 2 },
            ],
        },
    );
}
