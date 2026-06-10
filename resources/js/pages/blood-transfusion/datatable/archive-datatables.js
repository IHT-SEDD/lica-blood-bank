import { GlobalAdvanceYajraDatatable } from "../../../utility/datatable/datatables";
import { GlobalAdvanceDatatable } from "../../../app";
import { setHidden, TextFormatter } from "../../../utility/ui";
import { DateTimeFormatter } from "../../../utility/ui";

// ---------- GLOBAL VARIABLES ----------
const BASE_URL = "/blood-transfusion";
const DATATABLE_URL = `${BASE_URL}/datatable`;
const TABLE = {
    archiveRequest: "#list-archive-table",
    archiveBagRequest: "#list-archive-bag-request-table",
    archiveTest: "#list-archive-test-table",
};

// ---------- INSTANCES ----------
export let listArchiveTableInstance;
export let listArchiveBagRequestTableInstance;
export let listArchiveTestTableInstance;

// ---------- HELPERS ----------
const csrfToken = () =>
    document.querySelector('meta[name="csrf-token"]').content;
const isTableInitialized = (selector) => $.fn.DataTable.isDataTable(selector);

// ---------- ARCHIVE TABLE ----------
export function DatatableArchiveRequestBlood() {
    if (isTableInitialized(TABLE.request)) return;
    const ARCHIVECOLUMNS = [
        {
            data: "blood_request_at",
            title: "Tgl. Permintaan",
            render: (data) => {
                const bloodRequestAt = DateTimeFormatter.shortDateTime(data);
                return `<span class="fs-6 fw-semibold">${bloodRequestAt}</span>`;
            },
        },
        {
            data: "patient.name",
            title: "Nama",
            render: (data) => {
                return `<span class="fs-6 fw-semibold">${data}</span>`;
            },
        },
        {
            data: "patient.medrec",
            title: "Medrec",
            render: (data) => {
                return `<span class="fs-6 fw-semibold">${data ?? ""}</span>`;
            },
        },
        {
            data: "lab_number",
            title: "No. BDRS",
            render: (data) => {
                return `<span class="fs-6 fw-semibold">${data ?? ""}</span>`;
            },
        },
        {
            data: "order_number",
            title: "No. Order",
            render: (data) => {
                return `<span class="fs-6 fw-semibold">${data ?? ""}</span>`;
            },
        },
        {
            data: "room.name",
            title: "Ruangan",
            render: (data) => {
                return `<span class="fs-6 fw-semibold">${data}</span>`;
            },
        },
        {
            data: null,
            defaultContent: "",
            orderable: false,
            searchable: false,
            render: (row, data) => {
                const status = TextFormatter.format(row.status);
                switch (status) {
                    case "Blood Transfusion Checked In":
                        return `<span style="font-size: 20px;" class="text-success" data-bs-title="Checked In" data-bs-toggle="tooltip" data-bs-trigger="hover">
                            <i class="ti ti-user-check"></i>
                        </span>`;
                        break;
                    case "Blood Transfusion Finished":
                        return `<span style="font-size: 20px;" class="text-success" data-bs-title="Transaksi Selesai" data-bs-toggle="tooltip" data-bs-trigger="hover">
                            <i class="ti ti-droplet-check"></i>
                        </span>`;
                        break;
                    case "Blood Transfusion Completed":
                        return `<span style="font-size: 20px;" class="text-success" data-bs-title="Transaksi Selesai" data-bs-toggle="tooltip" data-bs-trigger="hover">
                            <i class="ti ti-shield-check"></i>
                        </span>`;
                        break;
                    case "Blood Transfusion Registered":
                        return `<span style="font-size: 20px;" class="text-info" data-bs-title="Terdaftar" data-bs-toggle="tooltip" data-bs-trigger="hover">
                                <i class="ti ti-circle-dashed-check"></i>
                            </span>`;
                        break;
                    case "Blood Transfusion Deleted":
                        return `<span style="font-size: 20px;" class="text-danger" data-bs-title="Dihapus" data-bs-toggle="tooltip" data-bs-trigger="hover">
                                <i class="ti ti-trash"></i>
                            </span>`;
                        break;
                    default:
                        return `<span class="fs-6 fw-semibold uppercase">-</span>`;
                        break;
                }
            },
        },
    ];

    listArchiveTableInstance = new GlobalAdvanceYajraDatatable(
        TABLE.archiveRequest,
        {
            searchDelay: 1000,
            rowSelect: true,
            ajax: {
                url: `${DATATABLE_URL}/blood-request-archive`,
                dataSrc: "data",
                data: (d) => {
                    d.date_range = document.querySelector(
                        ".blood-transfusion-date-filter",
                    )?.value;
                },
            },
            columns: ARCHIVECOLUMNS,
            useHideColumn: true,
            columnDefs: [
                {
                    targets: 0,
                    responsivePriority: 1,
                },
            ],
            drawCallback: function () {
                const tooltipTriggerList = document.querySelectorAll(
                    '[data-bs-toggle="tooltip"]',
                );

                [...tooltipTriggerList].forEach((tooltipTriggerEl) => {
                    new bootstrap.Tooltip(tooltipTriggerEl);
                });
            },
        },
    );
}
