import { GlobalAdvanceFlatpickr, GlobalAdvanceTomselect } from "../../../app";
import { DateTimeFormatter } from "../../../utility/ui";
import { ReportBloodUsageTable, reloadTable } from "./datatable";

// ---------- Global variable untuk memudahkan penyesuaian ----------
const ReloadDatatableSelector = "report-blood-usage-reload";

const DateFilterSelector = ".report-blood-usage-table-date-filter";
const FilterRoomSelector = "#filter-blood-usage-room";
const FilterBloodPackSelector = "#filter-blood-usage-blood-pack";

const ExportURL = "/report/export/";
const ExportBtnSelector = "excel_blood_usage_btn";

// ---------- HELPERS ----------
function getFilters() {
    const dateVal = document.querySelector(DateFilterSelector)?.value;
    const room = document.querySelector(FilterRoomSelector)?.value || "";
    const bloodPack =
        document.querySelector(FilterBloodPackSelector)?.value || "";

    let start_date = "";
    let end_date = "";

    if (dateVal) {
        const separator = dateVal.includes(" to ") ? " to " : " - ";
        const parts = dateVal.split(separator);
        start_date = parts[0] || "";
        end_date = parts[1] || "";
    }

    return { room, bloodPack, start_date, end_date };
}

// ---------- FILTERS ----------
function DateRangeFilter() {
    new GlobalAdvanceFlatpickr(DateFilterSelector, {
        onClose: reloadTable,
        static: false,
    });
}
function FilterRoom() {
    new GlobalAdvanceTomselect(FilterRoomSelector, {
        valueField: "id",
        preload: true,
        load: function (query, callback) {
            fetch(`/utility/select/room?q=${encodeURIComponent(query)}`)
                .then((res) => res.json())
                .then((json) => callback(json.results))
                .catch(() => callback());
        },
        onChange: function () {
            reloadTable();
        },
    });
}
function FilterBloodPack() {
    new GlobalAdvanceTomselect(FilterBloodPackSelector, {
        valueField: "id",
        preload: true,
        load: function (query, callback) {
            fetch(`/utility/select/blood-pack?q=${encodeURIComponent(query)}`)
                .then((res) => res.json())
                .then((json) => callback(json.results))
                .catch(() => callback());
        },
        onChange: function () {
            reloadTable();
        },
    });
}

// ---------- Fungsi export excel ----------
function ExportToExcel() {
    const btn = document.getElementById(ExportBtnSelector);
    if (!btn) return;

    btn.addEventListener("click", async function () {
        const filters = getFilters();

        const params = new URLSearchParams();
        if (filters.start_date) params.append("start_date", filters.start_date);
        if (filters.end_date) params.append("end_date", filters.end_date);
        if (filters.room) params.append("room_public_id", filters.room);
        if (filters.bloodPack)
            params.append("blood_pack_public_id", filters.bloodPack);

        const url = ExportURL + `blood-usage/excel/?${params.toString()}`;

        showPageLoading();
        try {
            const response = await fetch(url);
            const contentType = response.headers.get("Content-Type") || "";
            const isJson = contentType.includes("application/json");

            if (!response.ok || isJson) {
                const result = await response.json();
                console.error(result);
                notyf.error({
                    message: result.message || "Gagal membuat file excel!",
                });
                hidePageLoading();
                return;
            }

            // Ambil nama file dari header Content-Disposition yang dikirim Laravel
            const disposition =
                response.headers.get("Content-Disposition") || "";
            const filenameMatch =
                disposition.match(/filename\*=UTF-8''(.+)/) ||
                disposition.match(/filename="?([^"]+)"?/);
            const fileName = filenameMatch
                ? decodeURIComponent(filenameMatch[1])
                : `Laporan Penggunaan Darah.xlsx`;

            const blob = await response.blob();
            const blobUrl = URL.createObjectURL(blob);
            const link = document.createElement("a");
            link.href = blobUrl;
            link.setAttribute("download", fileName);
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            URL.revokeObjectURL(blobUrl);

            notyf.success({ message: "File excel berhasil dibuat!" });
            hidePageLoading();
        } catch (error) {
            console.error(error);
            notyf.error({ message: "Gagal membuat file excel!" });
            hidePageLoading();
        }
    });
}

// ---------- Init ----------
document.addEventListener("DOMContentLoaded", function () {
    ReportBloodUsageTable(getFilters);
    DateRangeFilter();
    FilterRoom();
    FilterBloodPack();
    ExportToExcel();

    window.addEventListener(ReloadDatatableSelector, function () {
        reloadTable();
    });
});
