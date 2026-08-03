import {
    CrossmatchResult,
    CrossmatchTestResult,
} from "../../../utility/config/status-config";
import { GlobalAdvanceYajraDatatable } from "../../../utility/datatable/datatables";
import { DateTimeFormatter } from "../../../utility/ui";

// ---------- Global variable untuk memudahkan penyesuaian ----------
let reportIncompatibleTableInstance;
const DatatableSelector = "#report-incompatible-table";
const ReportDataURL = "/report/incompatible/data";

// ---------- HELPERS ----------
export function reloadTable() {
    if (reportIncompatibleTableInstance?.instance) {
        reportIncompatibleTableInstance.instance.ajax.reload();
    }
}

// ---------- Datatable ----------
export function ReporIncompatibleTable(getFilters) {
    const ReporIncompatibleTableColumns = [
        {
            data: null,
            title: "No",
            orderable: false,
            searchable: false,
            render: (data, type, row, meta) => {
                return meta.row + 1;
            },
        },
        { data: "lab_number", title: "No. BDRS" },
        { data: "order_number", title: "No. Order" },
        { data: "room_name", title: "Ruangan" },
        { data: "insurance_name", title: "Penjamin" },
        {
            data: "created_at",
            title: "Tgl. Dibuat",
            render: (data, type, row, meta) => {
                return DateTimeFormatter.datetime24(row.created_at);
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
        { data: "bag_number", title: "No. Labu" },
        {
            data: "mayor_result",
            title: "Mayor",
            render: function (_, __, row) {
                return CrossmatchTestResult(row.mayor_result);
            },
        },
        {
            data: "minor_result",
            title: "Minor",
            render: function (_, __, row) {
                return CrossmatchTestResult(row.minor_result);
            },
        },
        {
            data: "auto_control_result",
            title: "Auto Control",
            render: function (_, __, row) {
                return CrossmatchTestResult(row.auto_control_result);
            },
        },
        {
            data: "crossmatch_result",
            title: "Hasil",
            render: function (_, __, row) {
                return CrossmatchResult(row.crossmatch_result);
            },
        },
        {
            data: "finish_at",
            title: "Tgl. Selesai",
            render: (data, type, row, meta) => {
                return DateTimeFormatter.datetime24(row.finish_at);
            },
        },
    ];

    reportIncompatibleTableInstance = new GlobalAdvanceYajraDatatable(
        DatatableSelector,
        {
            ajax: {
                url: ReportDataURL,
                data: function (d) {
                    const filters = getFilters();
                    d.start_date = filters.start_date;
                    d.end_date = filters.end_date;
                    d.room_public_id = filters.room;
                },
            },
            columns: ReporIncompatibleTableColumns,
            useHideColumn: true,
            columnDefs: [
                { targets: -1, responsivePriority: 1 },
                { targets: 0, responsivePriority: 2 },
            ],
        },
    );
}
