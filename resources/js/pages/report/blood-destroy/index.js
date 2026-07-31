import { GlobalAdvanceFlatpickr, GlobalAdvanceTomselect } from "../../../app";
import { DateTimeFormatter } from "../../../utility/ui";
import { ReportBloodDestroyTable, reloadTable } from "./datatable";

// ---------- Global variable untuk memudahkan penyesuaian ----------
const ReloadDatatableSelector = "report-blood-destroy-reload";

const MonthFilterSelector = ".report-blood-destroy-table-month-filter";
const FilterBloodComponentSelector = "#filter-blood-destroy-blood-component";

const ExportURL = "/report/export/";
const ExportBtnSelector = "excel_blood_destroy_btn";

// ---------- HELPERS ----------
function getFilters() {
    const monthAndYear = document.querySelector(MonthFilterSelector)?.value;
    const bloodComponent =
        document.querySelector(FilterBloodComponentSelector)?.value || "";
    return { bloodComponent, monthAndYear };
}

// ---------- FILTERS ----------
function MonthFilter() {
    new GlobalAdvanceFlatpickr(MonthFilterSelector, {
        onClose: reloadTable,
    });
}
function FilterBloodComponent() {
    new GlobalAdvanceTomselect(FilterBloodComponentSelector, {
        valueField: "id",
        preload: true,
        load: function (query, callback) {
            fetch(
                `/utility/select/blood-component?q=${encodeURIComponent(query)}`,
            )
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
        if (filters.bloodComponent)
            params.append("blood_component", filters.bloodComponent);

        const url = ExportURL + `blood-destroy/excel/?${params.toString()}`;

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
                : `Laporan Darah Dimusnahkan.xlsx`;

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
    ReportBloodDestroyTable(getFilters);
    MonthFilter();
    FilterBloodComponent();
    ExportToExcel();

    window.addEventListener(ReloadDatatableSelector, function () {
        reloadTable();
    });
});
