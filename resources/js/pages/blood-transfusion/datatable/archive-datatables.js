import { GlobalAdvanceYajraDatatable } from "../../../utility/datatable/datatables";
import { GlobalAdvanceDatatable } from "../../../app";
import { setHidden, TextFormatter } from "../../../utility/ui";
import { DateTimeFormatter } from "../../../utility/ui";
import {
    CrossmatchResult,
    TransactionOrderStatus,
    TransfusionBloodStatus,
} from "../../../utility/config/status-config";
import { getArchiveBloodTransfusionLog } from "../archive-index";

// ---------- GLOBAL VARIABLES ----------
const BASE_URL = "/blood-transfusion";
const DATATABLE_URL = `${BASE_URL}/datatable/archive`;
const TABLE = {
    archiveRequest: "#list-archive-table",
    archiveBagRequest: "#list-archive-bag-request-table",
    archiveTest: "#list-archive-test-table",
};
const TABLE_ROW = {
    archiveRequestRow: "#list-archive-table tbody tr",
    archiveBagRequestRow: "#list-archive-bag-request-table tbody tr",
};
const ArchiveDateFilterSelector = ".archive-blood-transfusion-date-filter";

// ---------- INSTANCES ----------
export let listArchiveTableInstance;
export let listArchiveBagRequestTableInstance;
export let listArchiveTestTableInstance;

// ---------- VARIABLES ----------
export let currentTransfusionPublicId = null;
export let currentBagDetailPublicId = null;
export let currentBagCrossmatchResult = null;
export let currentBagData = null;

// ---------- HELPERS ----------
const csrfToken = () =>
    document.querySelector('meta[name="csrf-token"]').content;
const isTableInitialized = (selector) => $.fn.DataTable.isDataTable(selector);
export const setElementText = (selector, text) => {
    const el = document.querySelector(`[data-patient-detail="${selector}"]`);
    if (el) el.textContent = text || "-";
};

// ---------- ARCHIVE REQUEST TABLE ----------
export function DatatableArchiveRequestBlood() {
    if (isTableInitialized(TABLE.archiveRequest)) return;
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
                return TransactionOrderStatus(status);
            },
        },
    ];

    listArchiveTableInstance = new GlobalAdvanceYajraDatatable(
        TABLE.archiveRequest,
        {
            searchDelay: 1000,
            rowSelect: true,
            ajax: {
                url: `${DATATABLE_URL}/blood-request`,
                dataSrc: "data",
                data: (d) => {
                    d.date_range = document.querySelector(
                        ArchiveDateFilterSelector,
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

// ---------- ARCHIVE BAG REQUEST TABLE ----------
export function DatatableArchiveBagRequest() {
    if (isTableInitialized(TABLE.archiveBagRequest)) return;

    const BAGREQUESTCOLUMNS = [
        {
            data: null,
            title: "No. Labu",
            orderable: false,
            searchable: false,
            render: function (_, data, row) {
                return `<span class="text-dark fs-6 fw-semibold">${row.blood_stock.bag_number ?? "-"}</span>`;
            },
        },
        {
            data: null,
            title: "Status",
            render: function (_, data, row) {
                return TransfusionBloodStatus(row.blood_stock.blood_status);
            },
        },
        {
            data: null,
            title: "Detail",
            render: function (_, __, row) {
                const rowData = row.row_data;
                return `<span class="text-danger fs-6 fw-semibold">${rowData.blood_pack_label ?? "-"}</span>`;
            },
        },
        {
            data: null,
            orderable: false,
            title: "Tgl. Expire",
            searchable: false,
            render: (_, __, row) => {
                const expiry = new Date(row.blood_stock.expiry_date);
                const today = new Date();
                today.setHours(0, 0, 0, 0);
                const diffDays = Math.ceil(
                    (expiry - today) / (1000 * 60 * 60 * 24),
                );
                const formatted = expiry.toLocaleDateString("id-ID", {
                    day: "2-digit",
                    month: "short",
                    year: "numeric",
                });
                const badgeClass =
                    diffDays <= 0
                        ? "text-danger"
                        : diffDays <= 7
                          ? "text-warning"
                          : diffDays <= 30
                            ? "text-info"
                            : "text-success";

                return `<span class="${badgeClass} fw-semibold fs-6">${formatted}</span>`;
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
            data: "blood_received_by",
            title: "Nama Penerima Darah",
            render: function (_, data, row) {
                return `<span class="text-dark fs-6 fw-semibold">${row.blood_received_by ?? "-"}</span>`;
            },
        },
    ];

    listArchiveBagRequestTableInstance = new GlobalAdvanceYajraDatatable(
        TABLE.archiveBagRequest,
        {
            removeSearch: true,
            removePageInfo: true,
            removePagination: true,
            rowSelect: true,
            ajax: {
                url: `${DATATABLE_URL}/bag-request`,
                data: function (d) {
                    d.transfusion_public_id = currentTransfusionPublicId;
                },
            },
            columns: BAGREQUESTCOLUMNS,
            columnDefs: [
                {
                    targets: 0,
                    width: "200px",
                },
            ],
        },
    );
}

// ---------- ARCHIVE TEST TABLE ----------
export function DatatableArchiveTest() {
    if (isTableInitialized(TABLE.archiveTest)) return;
    const TESTCOLUMNS = [
        {
            data: "bag_number",
            title: "No. Labu",
            render: function (_, data, row) {
                return `<span class="text-dark fw-semibold fs-6">${row.bag_number}</span>`;
            },
        },
        {
            data: "test_name",
            title: "Test",
            render: function (_, data, row) {
                return `<span class="text-dark fw-semibold fs-6">${row.test_name}</span>`;
            },
        },
        {
            data: "result_value",
            title: "Hasil",
            render: (_, data, row) => {
                if (!row.detail_test_public_id) return "-";
                const resultText = TextFormatter.capitalize(row.result_value);
                return `<span class="text-dark fw-semibold fs-6">${resultText}</span>`;
            },
        },
        {
            data: "result_by_user_name",
            title: "Hasil Oleh",
            render: (_, data, row) => {
                return `<span class="text-dark fw-semibold fs-6">${row.result_by_user_name}</span>`;
            },
        },
    ];

    listArchiveTestTableInstance = new GlobalAdvanceYajraDatatable(
        TABLE.archiveTest,
        {
            removeSearch: true,
            removePageInfo: true,
            removePagination: true,
            ajax: {
                url: `${DATATABLE_URL}/test`,
                data: function (d) {
                    d.transfusion_public_id = currentTransfusionPublicId;
                    d.transfusion_detail_public_id = currentBagDetailPublicId;
                },
            },
            columns: TESTCOLUMNS,
        },
    );
}

// ---------- Fungsi on select archive request table ----------
export function OnSelectTransaction() {
    function PopulatePatientDetail(data) {
        if (!data) return;
        setElementText("name", data.patient?.name);
        setElementText("gender", data.patient?.gender);
        setElementText("email", data.patient?.email);
        setElementText("age", data.patient?.patient_age);
        setElementText("insurance", data.insurance?.name);
        setElementText("room", data.room?.name);
        setElementText("doctor", data.doctor?.name);
        setElementText("type_patient", data.room?.type);
        setElementText("diagnosis", data.diagnosis);
        setElementText("blood_group", data.patient?.blood_group);
        setElementText("blood_rhesus", data.patient?.blood_rhesus);

        const hasLabNumber =
            data.lab_number?.toString().trim() &&
            data.lab_number?.toString().trim() !== "-";
        const isCompleted =
            data.status && data.status === "blood_transfusion_finished";
        const isCanceled =
            data.status && data.status === "blood_transfusion_canceled";
    }

    $(document)
        .off("click", TABLE_ROW.archiveRequestRow)
        .on("click", TABLE_ROW.archiveRequestRow, function (e) {
            if (!listArchiveTableInstance) return;
            const data = listArchiveTableInstance.getRowData(this);
            if (!data) return;

            // Isi global variable
            currentTransfusionPublicId = data.public_id;
            console.log(currentTransfusionPublicId);

            // Reload tabel bag request
            if (listArchiveBagRequestTableInstance) {
                listArchiveBagRequestTableInstance.reload();
            }

            // Isi detail pasien
            PopulatePatientDetail(data);
            // Generate timeline
            getArchiveBloodTransfusionLog(currentTransfusionPublicId);
        });
}

// ---------- Fungsi on select archive bag request table ----------
export function OnSelectBagTransaction() {
    $(document)
        .off("click", TABLE_ROW.archiveBagRequestRow)
        .on("click", TABLE_ROW.archiveBagRequestRow, function (e) {
            if (!listArchiveBagRequestTableInstance) return;
            const data = listArchiveBagRequestTableInstance.getRowData(this);
            if (!data) return;

            $(TABLE_ROW.archiveBagRequestRow).removeClass("table-active");
            $(this).addClass("table-active");

            // Isi global variable
            currentBagDetailPublicId = data.public_id;
            currentBagCrossmatchResult = data.crossmatch_result;
            currentBagData = data;
            console.log(currentBagDetailPublicId, currentBagCrossmatchResult);

            // Reload tabel test
            if (listArchiveTestTableInstance) {
                listArchiveTestTableInstance.reload();
            }
        });
}
