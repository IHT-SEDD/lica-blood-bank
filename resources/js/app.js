// JQUERY
import $ from "jquery";
window.$ = $;
window.jQuery = $;

// BOOTSTRAP
import bootstrap from "bootstrap/dist/js/bootstrap";
window.bootstrap = bootstrap;

// CHART JS
import { Chart } from "chart.js/auto";
import ChartDataLabels from "chartjs-plugin-datalabels";
Chart.register(ChartDataLabels);

// DATATABLES
import DataTable from "datatables.net-bs5";
import "datatables.net-bs5";
import "datatables.net-buttons";
import "datatables.net-buttons-bs5";
import "datatables.net-buttons/js/buttons.html5.js";
import "datatables.net-buttons/js/buttons.print.js";
import "datatables.net-responsive-bs5";
import "datatables.net-responsive";
import "datatables.net-select";
import "datatables.net-select-bs5";
import "jszip/dist/jszip.min.js";
import "pdfmake/build/pdfmake.js";
import "pdfmake/build/vfs_fonts.js";

// UTILITIES
import "./utility/ui";
import {
    buildDatatableDom,
    buildDatatableConfig,
    buildSelectConfig,
} from "./utility/datatable/datatable-options";
import { LayoutCustomizer } from "./utility/layout/customizer";
import { Plugins } from "./utility/plugin/plugin";
export { ins, debounce, CustomChartJs } from "./utility/chart/chart";
import { GlobalDataAction } from "./utility/data/action";

// UTILITY -> APP INIT
import { initComponents } from "./utility/app/components";
import { initPortletCard } from "./utility/app/portlet-card";
import { initSidenav } from "./utility/app/sidenav";
import { initTitleTextAnimation } from "./utility/app/title-animation";
import { initBloodStockStatusLabel } from "./utility/app/blood-stock";
import { initLanguageSwitcher } from "./utility/app/language-switcher";
import { initPreloader } from "./utility/app/preloader";
import { initMultiDropdown } from "./utility/app/multi-dropdown";
import { initCounter } from "./utility/app/counter";
import { initToggle } from "./utility/app/toggle";
import { initPassword } from "./utility/app/password";
import { initDismissible } from "./utility/app/dismissable";

// OTHERS
import "simplebar";
import flatpickr from "flatpickr";
import TomSelect from "tom-select";
import Choices from "choices.js";

// ---------------------- Import init function for app ----------------------

// Common
class App {
    init() {
        initComponents();
        initPreloader();
        initPortletCard();
        initMultiDropdown();
        initCounter();
        initToggle();
        initPassword();
        initDismissible();
        initSidenav();
        initTitleTextAnimation();
        initBloodStockStatusLabel();
        initLanguageSwitcher();
    }
}

document.addEventListener("DOMContentLoaded", () => {
    window.app = new App();
    window.app.init();

    window.layoutCustomizer = new LayoutCustomizer();
    window.layoutCustomizer.init();

    window.plugins = new Plugins();
    window.plugins.init();
});

// ------------------------------ Advance Datatable for global config ------------------------------
export class GlobalAdvanceDatatable {
    // Mulai constructor untuk global config datatable
    constructor(selector, options = {}) {
        this.tableElement = document.querySelector(selector);
        if (!this.tableElement) {
            console.error("DataTable element not found:", selector);
            return;
        }

        // ---- Destructure options ----
        const {
            useHideColumn = false,
            rowSelect = false,
            multiRowSelect = false,
            checkBoxSelect = false,
            cellSelect = false,
            removeSearch = false,
            removePagination = false,
            removePageInfo = false,
            columnDefs: userColumnDefs = [],
            ...restOptions
        } = options;

        this.useHideColumn = useHideColumn;
        this.rowSelect = rowSelect;
        this.multiRowSelect = multiRowSelect;
        this.checkBoxSelect = checkBoxSelect;
        this.cellSelect = cellSelect;

        // ---- Checkbox class & columnDefs ----
        const columnDefs = [...userColumnDefs];
        if (
            checkBoxSelect &&
            !this.tableElement.classList.contains("checkbox-select-datatable")
        ) {
            this.tableElement.classList.add("checkbox-select-datatable");
            columnDefs.unshift({
                orderable: false,
                render: DataTable.render.select(),
                targets: 0,
            });
        }

        // ---- Build config dari datatable-options ----
        const dom = buildDatatableDom({
            useHideColumn,
            removeSearch,
            removePagination,
            removePageInfo,
        });
        const selectConfig = buildSelectConfig({
            rowSelect,
            multiRowSelect,
            checkBoxSelect,
            cellSelect,
        });
        const config = buildDatatableConfig({
            dom,
            columnDefs,
            removePagination,
            useHideColumn,
            selectConfig,
        });

        // ---- Init DataTable ----
        this.instance = new DataTable(this.tableElement, {
            ...config,
            ...restOptions,
        });
        this.tableElement._datatable = this.instance;

        if (useHideColumn) this.initColumnToggle();
    }

    // Ambil label column dari thead tabel HTML
    getColumnLabels() {
        return Array.from(this.tableElement.querySelectorAll("thead th")).map(
            (th) => th.textContent.trim(),
        );
    }

    // Generate dropdown show/hide column
    initColumnToggle() {
        const wrapper = this.instance
            .table()
            .container()
            .querySelector(".columnToggleWrapper");
        if (!wrapper) return;

        const dropdown = document.createElement("div");
        dropdown.className = "dropdown";
        dropdown.innerHTML = `
            <button class="btn btn-sm btn-primary" type="button" data-bs-toggle="dropdown" data-bs-auto-close="outside">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye-off"><path d="M10.733 5.076a10.744 10.744 0 0 1 11.205 6.575 1 1 0 0 1 0 .696 10.747 10.747 0 0 1-1.444 2.49"/><path d="M14.084 14.158a3 3 0 0 1-4.242-4.242"/><path d="M17.479 17.499a10.75 10.75 0 0 1-15.417-5.151 1 1 0 0 1 0-.696 10.75 10.75 0 0 1 4.446-5.143"/><path d="m2 2 20 20"/></svg>
            </button>
            <ul class="dropdown-menu">
                ${this.getColumnLabels()
                    .map(
                        (label, i) => `
                    <li class="dropdown-item">
                        <div class="form-check">
                            <input class="form-check-input toggle-vis" type="checkbox"
                                data-column="${i}" id="colToggle${i}" checked>
                            <label class="form-check-label" for="colToggle${i}">${label}</label>
                        </div>
                    </li>`,
                    )
                    .join("")}
            </ul>`;

        wrapper.appendChild(dropdown);
        dropdown.addEventListener("change", (e) => {
            if (e.target.classList.contains("toggle-vis")) {
                const col = parseInt(e.target.dataset.column, 10);
                this.instance.column(col).visible(e.target.checked);
            }
        });
    }

    // Method untuk mendapatkan data per baris
    getRowData(rowSelector) {
        return this.instance?.row(rowSelector).data() ?? null;
    }
}

// ------------------------------ Advance Flatpickr for global config ------------------------------
export class GlobalAdvanceFlatpickr {
    // Mulai constructor untuk global config flatpickr
    constructor(selector, options = {}) {
        // Ambil selector HTML untuk element
        this.elements = document.querySelectorAll(selector);

        // Lempar error jika element tidak ditemukan
        if (!this.elements.length) {
            console.error("Flatpickr element not found:", selector);
            return;
        }

        // Ambil options dari client
        this.options = options;
        // Bikin init untuk instance
        this.init();
    }

    // Bangun init untuk flatpickr
    init() {
        // Terapkan config pada tiap element
        this.elements.forEach((item) => {
            // Ambil tipe flatpickr dari attribut data-provider
            const type = item.getAttribute("data-provider");
            const attrs = item.attributes;

            // Bikin default config
            let config = {
                disableMobile: true,
                defaultDate: new Date(),
            };

            // ---------- FLATPICKR ----------
            if (type === "flatpickr") {
                if (attrs["data-date-format"])
                    config.dateFormat = attrs["data-date-format"].value;

                if (attrs["data-enable-time"]) {
                    config.enableTime = true;
                    config.dateFormat = (config.dateFormat || "Y-m-d") + " H:i";
                }

                if (attrs["data-altformat"]) {
                    config.altInput = true;
                    config.altFormat = attrs["data-altformat"].value;
                }

                if (attrs["data-mindate"])
                    config.minDate = attrs["data-mindate"].value;

                if (attrs["data-maxdate"])
                    config.maxDate = attrs["data-maxdate"].value;

                if (attrs["data-default-date"])
                    config.defaultDate = attrs["data-default-date"].value;

                if (attrs["data-multiple-date"]) config.mode = "multiple";

                if (attrs["data-range-date"]) config.mode = "range";

                if (attrs["data-inline-date"]) {
                    config.inline = true;
                    config.defaultDate = attrs["data-default-date"]?.value;
                }

                if (attrs["data-disable-date"]) {
                    config.disable =
                        attrs["data-disable-date"].value.split(",");
                }

                if (attrs["data-week-number"]) {
                    config.weekNumbers = true;
                }

                // ---------- merge event dari luar ----------
                config = {
                    ...config,
                    ...this.options,
                };

                const instance = flatpickr(item, config);

                // simpan instance
                item._flatpickrInstance = instance;
            }

            // ---------- TIMEPICKR ----------
            else if (type === "timepickr") {
                let configTime = {
                    enableTime: true,
                    noCalendar: true,
                    dateFormat: "H:i",
                    defaultDate: new Date(),
                };

                if (attrs["data-time-hrs"]) configTime.time_24hr = true;
                if (attrs["data-min-time"])
                    configTime.minTime = attrs["data-min-time"].value;
                if (attrs["data-max-time"])
                    configTime.maxTime = attrs["data-max-time"].value;
                if (attrs["data-default-time"])
                    configTime.defaultDate = attrs["data-default-time"].value;
                if (attrs["data-time-inline"]) {
                    configTime.inline = true;
                    configTime.defaultDate = attrs["data-time-inline"].value;
                }

                configTime = {
                    ...configTime,
                    ...this.options,
                };

                const instance = flatpickr(item, configTime);

                item._flatpickrInstance = instance;
            }
        });
    }
}

// ------------------------------ Advance Tomselect for global config ------------------------------
export class GlobalAdvanceTomselect {
    // Mulai constructor untuk global config
    constructor(selector, options = {}) {
        // ---------- Handle selector ----------
        if (typeof selector === "string") {
            this.elements = document.querySelectorAll(selector);
        } else if (selector instanceof HTMLElement) {
            this.elements = [selector];
        } else if (selector instanceof NodeList || Array.isArray(selector)) {
            this.elements = selector;
        } else {
            this.elements = [];
        }

        if (!this.elements.length) {
            console.error("TomSelect element not found:", selector);
            return;
        }

        this.options = options;
        this.instances = [];

        this.init();
    }

    // Bangun init
    init() {
        this.elements.forEach((element) => {
            // Hindari double init
            if (element.tomselect) {
                element.tomselect.destroy();
            }

            const noResultsText =
                this.options.noResultsText || "No options available";
            const blurOnItemAdd = this.options.blurOnItemAdd !== false;

            const loadOnClick = this.options.loadOnClick ?? false;
            const loadFn = this.options.load;

            // ---------- Default global config ----------
            const defaultConfig = {
                labelField: "text",
                searchField: "text",
                maxOptions: 500,
                closeAfterSelect: true,
                allowEmptyOption: true,
                create: false,
                plugins: ["remove_button"],
                render: {
                    no_results: () => {
                        return `<div class="no-results">${noResultsText}</div>`;
                    },
                },
                onItemAdd: function () {
                    if (blurOnItemAdd) {
                        this.blur();
                    }
                },
            };

            // ---------- Sisipkan onDropdownOpen jika loadOnClick true ----------
            if (loadOnClick && typeof loadFn === "function") {
                defaultConfig.onDropdownOpen = function () {
                    // Jangan fetch ulang jika sudah ada options
                    if (this.hasOptions) return;

                    // Panggil load dengan query kosong untuk ambil initial data
                    loadFn.call(this, "", (results) => {
                        if (!results) return;
                        this.addOptions(results);
                        this.refreshOptions(false);
                    });
                };
            }

            // ---------- Merge config ----------
            const { loadOnClick: _omit, ...restOptions } = this.options;

            const config = {
                ...defaultConfig,
                ...restOptions,
                plugins: [
                    ...new Set([
                        ...(defaultConfig.plugins || []),
                        ...(this.options.plugins || []),
                    ]),
                ],
            };

            // ---------- Create instance ----------
            const instance = new TomSelect(element, config);
            element._tomselectInstance = instance;
            this.instances.push(instance);
        });
    }

    // ---------- Ambil semua instance ----------
    getInstances() {
        return this.instances;
    }

    // ---------- Destroy semua ----------
    destroy() {
        this.instances.forEach((instance) => {
            instance.destroy();
        });

        this.instances = [];
    }
}

// ------------------------------ Global Config for submit data Form ------------------------------
export class GlobalSubmitForm {
    constructor({
        formId,
        url,
        method = "POST",
        onSuccess = null,
        onError = null,
        onValidationError = null,
        beforeSubmit = null,
        resetOnSuccess = null,
        validator = null,
    }) {
        this.form = document.getElementById(formId);
        this.url = url;
        this.method = method;
        this.onSuccess = onSuccess;
        this.onError = onError;
        this.onValidationError = onValidationError;
        this.beforeSubmit = beforeSubmit;
        this.resetOnSuccess = resetOnSuccess;
        this.validator = validator;

        if (!this.form) {
            console.error(`Form with ID ${formId} not found`);
            return;
        }
        this.init();
    }

    init() {
        this.form.addEventListener("submit", async (e) => {
            e.preventDefault();

            if (this.validator) {
                const status = await this.validator.validate();
                if (status !== "Valid") {
                    this.onValidationError?.();
                    return;
                }
            }

            let formData = this._buildFormData();
            if (this.beforeSubmit)
                formData = this.beforeSubmit(formData) || formData;
            this.submit(formData);
        });
    }

    // ---- Bangun FormData + normalisasi checkbox ----
    _buildFormData() {
        const formData = new FormData(this.form);
        this.form.querySelectorAll('input[type="checkbox"]').forEach((el) => {
            formData.set(el.name, el.checked ? 1 : 0);
        });
        return formData;
    }

    // ---- Cek apakah ada file di FormData ----
    _hasFile(formData) {
        for (const value of formData.values()) {
            if (value instanceof File && value.size > 0) return true;
        }
        return false;
    }

    // ---- Reset form setelah sukses ----
    _resetForm() {
        this.form.reset();
        this.form.querySelectorAll("select").forEach((el) => {
            if (el.tomselect) el.tomselect.clear();
        });
        this.form.querySelectorAll('input[type="file"]').forEach((el) => {
            el.value = "";
        });
    }

    submit(formData) {
        if (this.method !== "POST") formData.append("_method", this.method);

        fetch(typeof this.url === "function" ? this.url() : this.url, {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": document
                    .querySelector('meta[name="csrf-token"]')
                    ?.getAttribute("content"),
                Accept: "application/json",
            },
            body: formData,
        })
            .then(async (res) => {
                const data = await res.json();
                if (!res.ok) throw data;
                return data;
            })
            .then((data) => {
                this.onSuccess?.(data);
                if (this.resetOnSuccess) this._resetForm();
            })
            .catch((err) => {
                if (this.onError) this.onError(err);
                else console.error("GlobalSubmitForm Error:", err);
            });
    }
}

// ------------------------------ Global Config for Form Validation ------------------------------
export class GlobalFormValidation {
    // ------------------------------ Mulai static init ------------------------------
    static init(formSelector, initialRules = {}) {
        // ------------------------------ Ambil form element dengan selector ------------------------------
        const form = document.querySelector(formSelector);

        // ------------------------------ Return error jika form tidak ditemukan ------------------------------
        if (!form) {
            console.error(`Form ${formSelector} not found`);
            return;
        }

        // Simpan rules di variabel yang bisa dimutasi
        let rules = { ...initialRules };

        return {
            // ---------- Tambah field validasi secara dinamis :begin ----------
            addField(fieldName, fieldRules) {
                rules[fieldName] = fieldRules;
            },
            // ---------- Tambah field validasi secara dinamis :end ----------

            // ---------- Hapus field validasi secara dinamis :begin ----------
            removeField(fieldName) {
                delete rules[fieldName];
            },
            // ---------- Hapus field validasi secara dinamis :end ----------

            validate: () => {
                let isValid = true;

                // ------------------------------ Hapus semua class is-invalid terlebih dahulu ------------------------------
                form.querySelectorAll(".is-invalid").forEach((el) => {
                    el.classList.remove("is-invalid");
                });
                form.querySelectorAll(".ts-wrapper.is-invalid").forEach(
                    (el) => {
                        el.classList.remove("is-invalid");
                    },
                );

                // ------------------------------ Hapus semua elemen invalid-feedback terlebih dahulu ------------------------------
                form.querySelectorAll(".invalid-feedback").forEach((el) => {
                    el.remove();
                });

                // ------------------------------ Loop tiap field untuk menambahkan validasi ------------------------------
                Object.keys(rules).forEach((fieldName) => {
                    const input = form.querySelector(`[name="${fieldName}"]`);
                    if (!input) return;

                    // ------------------------------ Hapus validasi jika inputan berubah ------------------------------
                    input.addEventListener("input", () => {
                        input.classList.remove("is-invalid");
                        const feedback =
                            input.parentNode.querySelector(".invalid-feedback");
                        if (feedback) feedback.remove();
                    });

                    // ------------------------------ Hapus validasi pada inputan tom-select jika ada perubahan ------------------------------
                    if (input.classList.contains("tomselected")) {
                        input.addEventListener("change", () => {
                            const wrapper = input.closest(".ts-wrapper");
                            if (wrapper) wrapper.classList.remove("is-invalid");
                            input.classList.remove("is-invalid");
                            const feedback =
                                input.parentNode.querySelector(
                                    ".invalid-feedback",
                                );
                            if (feedback) feedback.remove();
                        });
                    }

                    // ------------------------------ Bikin variabel value inputa & peraturan validasi inputan ------------------------------
                    const value = input.value.trim();
                    const fieldRules = rules[fieldName].validators;

                    for (let rule in fieldRules) {
                        // ------------------------------ Bikin variabel pesan error ------------------------------
                        let message = fieldRules[rule].message;

                        // ------------------------------ Tampilkan error jika value input kosong ------------------------------
                        if (rule === "notEmpty" && value === "") {
                            this.showError(input, message);
                            isValid = false;
                            break;
                        }

                        // ------------------------------ Tampilkan error jika panjang value input tidak sesuai ------------------------------
                        if (rule === "stringLength") {
                            const min = fieldRules[rule].min || 0;
                            if (value.length < min) {
                                this.showError(input, message);
                                isValid = false;
                                break;
                            }
                        }

                        // ------------------------------ Tampilkan error jika value inputan bukan angka ------------------------------
                        if (rule === "isNumber") {
                            if (value === "" || isNaN(value)) {
                                this.showError(input, message);
                                isValid = false;
                                break;
                            }
                        }
                    }
                });

                // ------------------------------ Kembalikan valid atau tidak ------------------------------
                return isValid ? "Valid" : "Invalid";
            },
        };
    }

    // ------------------------------ Mulai static showError ------------------------------
    static showError(input, message) {
        let target = input;

        // ------------------------------ Tambahkan class is-invalid pada input tom-select ------------------------------
        if (input.classList.contains("tomselected")) {
            const wrapper = input.nextElementSibling;
            if (wrapper && wrapper.classList.contains("ts-wrapper")) {
                wrapper.classList.add("is-invalid");
                target = wrapper;
            }
        } else {
            // ------------------------------ Tambahkan class is-invalid pada input normal yang error ------------------------------
            input.classList.add("is-invalid");
        }

        // ------------------------------ Buat div dengan class invalid-feedback untuk menampilkan pesan ------------------------------
        const div = document.createElement("div");
        div.className = "invalid-feedback";
        div.innerText = message;

        // ------------------------------ Letakkan didalam div pembungkus inputan ------------------------------
        input.parentNode.appendChild(div);
    }
}

// ------------------------------ Global Config for Delete Data Confirmation ------------------------------
export class GlobalDeleteDataConfirmation extends GlobalDataAction {
    constructor(options = {}) {
        super(options, {
            buttonSelector: ".btn-delete",
            eventName: "delete:open",
        });
    }
}

// ------------------------------ Global Config for Edit Data ------------------------------
export class GlobalEditData extends GlobalDataAction {
    constructor(options = {}) {
        super(options, { buttonSelector: ".btn-edit", eventName: "edit:open" });
        this.modalID = options.ModalEditID || null;
    }

    _onSuccess(data, id, btn) {
        if (this.formSelector && data) {
            const form = document.querySelector(this.formSelector);
            if (form) form.dataset.id = data.public_id;
        }
    }
}

// ------------------------------ Global Config for Restore Data Confirmation ------------------------------
export class GlobalRestoreDataConfirmation extends GlobalDataAction {
    constructor(options = {}) {
        super(options, {
            buttonSelector: ".btn-restore",
            eventName: "restore:open",
        });
    }
}
