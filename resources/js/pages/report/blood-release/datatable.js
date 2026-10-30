import { GlobalAdvanceYajraDatatable } from "../../../utility/datatable/datatables";
import { DateTimeFormatter } from "../../../utility/ui";

// ---------- Global variable untuk memudahkan penyesuaian ----------
let reportBloodReleaseTableInstance;
const DatatableSelector = "#report-blood-release-table";
const ReportDataURL = "/report/blood-release/data";

// ---------- HELPERS ----------
export function reloadTable() {
    if (reportBloodReleaseTableInstance?.instance) {
        reportBloodReleaseTableInstance.instance.ajax.reload();
    }
}
function sumBloodTypes(components = []) {
    const sum = {};
    components.forEach((c) => {
        Object.entries(c.blood_types).forEach(([type, qty]) => {
            sum[type] = (sum[type] || 0) + qty;
        });
    });
    return sum;
}

// ---------- Datatable ----------
export function ReportBloodReleaseTable(getFilters) {
    const ReportBloodReleaseTableColumns = [
        {
            data: null,
            title: "No",
            orderable: false,
            searchable: false,
            render: (data, type, row, meta) =>
                meta.row + meta.settings._iDisplayStart + 1,
        },
        {
            data: "date",
            title: "Tanggal",
        },
        {
            data: "components",
            title: "Detail",
            orderable: false,
            searchable: false,
            render: (components) => {
                const html = (components || [])
                    .filter((c) => c.total > 0)
                    .map((c) => {
                        const types = Object.entries(c.blood_types)
                            .filter(([, qty]) => qty > 0)
                            .map(([type, qty]) => `${type}: ${qty}`)
                            .join(", ");
                        return `<div><strong>${c.label}</strong></div>`;
                    })
                    .join("");
                return html || "-";
            },
        },
        {
            data: "components",
            name: "blood_group",
            title: "Blood Group",
            orderable: false,
            searchable: false,
            render: (components) => {
                const html = Object.entries(sumBloodTypes(components))
                    .filter(([, qty]) => qty > 0)
                    .map(([type, qty]) => `${type}: ${qty}`)
                    .join(", ");
                return html || "-";
            },
        },
        { data: "total", title: "Total" },
    ];

    reportBloodReleaseTableInstance = new GlobalAdvanceYajraDatatable(
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
            columns: ReportBloodReleaseTableColumns,
            order: [[1, "asc"]], // urut berdasarkan tanggal
            useHideColumn: true,
            columnDefs: [
                { targets: -1, responsivePriority: 1 },
                { targets: 0, responsivePriority: 2 },
            ],
        },
    );
}
