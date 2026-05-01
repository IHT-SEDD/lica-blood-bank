import TomSelect from "tom-select";
import { GlobalSubmitForm, GlobalFormValidation } from "../../../app";

// ---------- Global variable untuk memudahkan penyesuaian :begin ----------
const ChoosePOSelector = "#select-purchase-order";
const ChooseAddMethodSelector = "#select-add-data-method";
const FormManualWrapper = "add_manually_method_wrapper";
const FormExcelWrapper = "add_excel_method_wrapper";
// ---------- Global variable untuk memudahkan penyesuaian :begin ----------

// ---------- Helper: toggle tampilan form berdasarkan method :begin ----------
function toggleFormMethod(methodId) {
    const manualEl = document.getElementById(FormManualWrapper);
    const excelEl = document.getElementById(FormExcelWrapper);

    if (methodId === "manual") {
        manualEl.classList.remove("d-none");
        excelEl.classList.add("d-none");
    } else if (methodId === "excel") {
        manualEl.classList.add("d-none");
        excelEl.classList.remove("d-none");
    }
}
// ---------- Helper: toggle tampilan form berdasarkan method :end ----------

// ---------- Select po dari tom-select untuk form add new data :begin ----------
function SelectPO() {
    new TomSelect(ChoosePOSelector, {
        valueField: "id",
        labelField: "text",
        searchField: "text",
        sortField: {
            field: "id",
            direction: "asc",
        },
        create: false,
        preload: true,
        load: function (query, callback) {
            fetch(
                `/utility/select/purchase-order?q=${encodeURIComponent(query)}`,
            )
                .then((response) => response.json())
                .then((json) => {
                    callback(json.results);
                })
                .catch(() => {
                    callback();
                });
        },
    });
}
// ---------- Select po dari tom-select untuk form add new data :begin ----------

// ---------- Select add method dari tom-select untuk form add new data :begin ----------
function SelectAddMethod() {
    const ts = new TomSelect(ChooseAddMethodSelector, {
        valueField: "id",
        labelField: "text",
        searchField: "text",
        sortField: { field: "id", direction: "asc" },
        create: false,
        preload: true,
        load: function (query, callback) {
            fetch(
                `/utility/select/add-incoming-stock-method?q=${encodeURIComponent(query)}`,
            )
                .then((response) => response.json())
                .then((json) => {
                    callback(json.results);

                    // ✅ Set default "manual" setelah data selesai dimuat
                    ts.setValue("manual", true); // true = silent (tidak trigger onChange)
                    toggleFormMethod("manual");
                })
                .catch(() => callback());
        },
        onChange: (methodId) => {
            toggleFormMethod(methodId);
        },
    });
}
// ---------- Select add method dari tom-select untuk form add new data :end ----------

// ---------- Fungsi untuk mengelola form add new incoming stock :begin ----------
// ---------- Fungsi untuk mengelola form add new incoming stock :end ----------

document.addEventListener("DOMContentLoaded", () => {
    SelectPO();
    SelectAddMethod();
});
