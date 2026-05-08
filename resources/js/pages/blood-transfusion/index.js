// ---------- Import Libraries ----------
import { GlobalAdvanceFlatpickr } from "../../app";
import { DatatableRequestBlood } from "./analytic/datatables-helper";
import TomSelect from "tom-select";

// ---------- Global variable untuk memudahkan penyesuaian :begin ----------
const DateFilterSelector = ".blood-transfusion-date-filter";
// ---------- Global variable untuk memudahkan penyesuaian :end ----------

// ---------- Filter tanggal dari flatpickr untuk data di tabel :begin ----------
function DateRangeFilter() {
    new GlobalAdvanceFlatpickr(DateFilterSelector, {
        maxDate: "today",
    });
}
// ---------- Filter tanggal dari flatpickr untuk data di tabel :end ----------

document.addEventListener("DOMContentLoaded", function () {
    // Date range picker
    DateRangeFilter();
    DatatableRequestBlood();
});
