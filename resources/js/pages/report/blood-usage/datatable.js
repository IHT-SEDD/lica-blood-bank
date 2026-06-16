import { GlobalAdvanceYajraDatatable } from "../../../utility/datatable/datatables";
import { GlobalAdvanceFlatpickr } from "../../../app";
import { DateTimeFormatter } from "../../../utility/ui";

// ---------- Global variable untuk memudahkan penyesuaian ----------
let reportBloodUsageTableInstance;
const DateFilterSelector = ".report-blood-usage-table-date-filter";
const DatatableSelector = "#report-blood-usage-table";
const ReloadDatatableSelector = "report-blood-usage-reload";
const ReportDataURL = "/report/blood-usage/data";

// ---------- HELPERS ----------
function getFilters() {
    const dateVal = document.querySelector(DateFilterSelector)?.value;
    let start_date = "";
    let end_date = "";

    if (dateVal) {
        const separator = dateVal.includes(" to ") ? " to " : " - ";
        const parts = dateVal.split(separator);
        start_date = parts[0] || "";
        end_date = parts[1] || "";
    }

    return { start_date, end_date };
}
function reloadTable() {
    if (reportBloodUsageTableInstance?.instance) {
        reportBloodUsageTableInstance.instance.ajax.reload();
    }
}

// ---------- Datatable ----------
function ReportBloodUsageTable() {
    // ---------- Init kolom pada tabel ----------
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
                return `${row.blood_group.name}${row.blood_component.name} ${row.blood_rhesus}`;
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
                },
            },
            columns: ReportBloodUsageTableColumns,
            useHideColumn: true,
            columnDefs: [
                {
                    targets: -1,
                    responsivePriority: 1,
                },
                {
                    targets: 0,
                    responsivePriority: 2,
                },
            ],
        },
    );
}

// ---------- Daterange Filter ----------
function DateRangeFilter() {
    new GlobalAdvanceFlatpickr(DateFilterSelector, {
        onClose: reloadTable,
    });
}

document.addEventListener("DOMContentLoaded", function () {
    ReportBloodUsageTable();
    DateRangeFilter();
    window.addEventListener(ReloadDatatableSelector, function () {
        reloadTable();
    });
});
