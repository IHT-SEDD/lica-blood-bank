import { GlobalAdvanceYajraDatatable } from "../../../utility/datatable/datatables";
import { DateTimeFormatter } from "../../../utility/ui";

// ---------- Global variable untuk memudahkan penyesuaian ----------
let reportBloodStockTableInstance;
const DatatableSelector = "#report-blood-stock-table";
const ReportDataURL = "/report/blood-stock/data";
const BLOOD_GROUP_RHESUS_LIST = [
    "A+",
    "A-",
    "B+",
    "B-",
    "O+",
    "O-",
    "AB+",
    "AB-",
];

// ---------- HELPERS ----------
export function reloadTable() {
    if (reportBloodStockTableInstance?.instance) {
        reportBloodStockTableInstance.instance.ajax.reload();
    }
}
function renderStockDetailColumn(key) {
    return (data, type, row) => {
        const items = row.stock_detail?.[key];
        if (!items || items.length === 0) return "-";

        return items
            .map(
                (item) =>
                    `<span class="fw-medium">${item.blood_component?.name ?? "-"} : <span class="fw-bold">${item.quantity}</span></span>`,
            )
            .join(", ");
    };
}

// ---------- Datatable ----------
export function ReportBloodStockTable(getFilters) {
    const ReportBloodStockTableColumns = [
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
            title: "Tgl. Ditambahkan",
            render: (data, type, row, meta) => {
                return DateTimeFormatter.datetime24(row.created_at);
            },
        },
        ...BLOOD_GROUP_RHESUS_LIST.map((key) => ({
            data: null,
            title: key,
            orderable: false,
            searchable: false,
            render: renderStockDetailColumn(key),
        })),
        { data: "total", title: "Total" },
    ];

    reportBloodStockTableInstance = new GlobalAdvanceYajraDatatable(
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
            columns: ReportBloodStockTableColumns,
            useHideColumn: true,
            columnDefs: [
                { targets: -1, responsivePriority: 1 },
                { targets: 0, responsivePriority: 2 },
            ],
        },
    );
}
