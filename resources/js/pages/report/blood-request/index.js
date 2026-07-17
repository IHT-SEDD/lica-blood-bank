import { GlobalAdvanceFlatpickr, GlobalAdvanceTomselect } from "../../../app";
import { DateTimeFormatter } from "../../../utility/ui";
import { ReportBloodRequestTable, reloadTable } from "./datatable";

// ---------- Global variable untuk memudahkan penyesuaian ----------
const ReloadDatatableSelector = "report-blood-request-reload";

const MonthFilterSelector = ".report-blood-request-table-month-filter";
const FilterRoomSelector = "#filter-blood-request-room";

const ExportURL = "/report/export/";
const ExportBtnSelector = "excel_blood_request_btn";

// ---------- HELPERS ----------
function getFilters() {
    const monthAndYear = document.querySelector(MonthFilterSelector)?.value;
    const room = document.querySelector(FilterRoomSelector)?.value || "";
    return { room, monthAndYear };
}

// ---------- FILTERS ----------
function MonthFilter() {
    new GlobalAdvanceFlatpickr(MonthFilterSelector, {
        onClose: reloadTable,
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

// ---------- Fungsi export excel ----------
function ExportToExcel() {
    const btn = document.getElementById(ExportBtnSelector);
    if (!btn) return;

    btn.addEventListener("click", async function () {
        const filters = getFilters();
        const params = new URLSearchParams();
        if (filters.monthAndYear)
            params.append("month_year", filters.monthAndYear);
        if (filters.room) params.append("room_public_id", filters.room);
        const url = ExportURL + `blood-request/excel/?${params.toString()}`;

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
                : `Laporan Buku Pengiriman Darah.xlsx`;

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
    ReportBloodRequestTable(getFilters);
    MonthFilter();
    FilterRoom();
    ExportToExcel();

    window.addEventListener(ReloadDatatableSelector, function () {
        reloadTable();
    });
});
