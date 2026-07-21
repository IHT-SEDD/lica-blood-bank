import { GlobalAdvanceYajraDatatable } from "../../../utility/datatable/datatables";
import { DateTimeFormatter } from "../../../utility/ui";

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
        {
            data: "created_at",
            title: "Tgl. Order",
            render: (data, type, row, meta) => {
                return DateTimeFormatter.datetime24(row.created_at);
            },
        },
        { data: "po_number", title: "No. PO" },
        { data: "vendor_name", title: "PMI" },
        {
            data: null,
            title: "Detail",
            orderable: false,
            searchable: false,
            render: (data, type, row, meta) => {
                if (
                    !Array.isArray(row.order_blood_detail) ||
                    row.order_blood_detail.length === 0
                ) {
                    return "-";
                }

                const items = row.order_blood_detail.map((detail) => {
                    const component = detail.blood_component?.name ?? "";
                    const group = detail.blood_group?.name ?? "";
                    const rhesus = detail.blood_rhesus ?? "";
                    const quantity = detail.quantity ?? 0;

                    return `${component} ${group}${rhesus} : ${quantity}`;
                });

                return `<ul class="mb-0 ps-3">${items
                    .map((item) => `<li>${item}</li>`)
                    .join("")}</ul>`;
            },
        },
        {
            data: "total_quantity",
            title: "Total",
        },
    ];

    reportBloodRequestTableInstance = new GlobalAdvanceYajraDatatable(
        DatatableSelector,
        {
            ajax: {
                url: ReportDataURL,
                data: function (d) {
                    const filters = getFilters();
                    d.month_year = filters.monthAndYear;
                    d.vendor = filters.vendor;
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
