import { GlobalAdvanceYajraDatatable } from "../../../utility/datatable/datatables";

// ---------- Global variable untuk memudahkan penyesuaian ----------
let reportBloodUsageTableInstance;
const DatatableSelector = "#report-blood-usage-table";
const ReportDataURL = "/report/blood-usage/data";

// ---------- HELPERS ----------
export function reloadTable() {
    if (reportBloodUsageTableInstance?.instance) {
        reportBloodUsageTableInstance.instance.ajax.reload();
    }
}

// ---------- Datatable ----------
export function ReportBloodUsageTable(getFilters) {
    const ReportBloodUsageTableColumns = [
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
        {
            data: null,
            title: "Detail",
            orderable: false,
            searchable: false,
            render: (data, type, row, meta) => {
                return `${row.blood_component.name} ${row.blood_group.name}${row.blood_rhesus}`;
            },
        },
        { data: "total_per_room_per_pack", title: "Total" },
    ];

    reportBloodUsageTableInstance = new GlobalAdvanceYajraDatatable(
        DatatableSelector,
        {
            ajax: {
                url: ReportDataURL,
                data: function (d) {
                    const filters = getFilters();
                    d.start_date = filters.start_date;
                    d.end_date = filters.end_date;
                    d.room_public_id = filters.room;
                    d.blood_pack_public_id = filters.bloodPack;
                },
            },
            columns: ReportBloodUsageTableColumns,
            useHideColumn: true,
            columnDefs: [
                { targets: -1, responsivePriority: 1 },
                { targets: 0, responsivePriority: 2 },
            ],
        },
    );
}
