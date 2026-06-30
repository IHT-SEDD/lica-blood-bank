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
import {
    buildDatatableConfig,
    buildDatatableDom,
    buildSelectConfig,
} from "./datatable-options";

/**
 * GlobalAdvanceYajraDatatable
 * Refactored to support Yajra DataTables JSON response
 * with column mapping via `columns` option.
 */
export class GlobalAdvanceYajraDatatable {
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
            removePageLength = false,
            pageLengthOptions = [10, 25, 50, 100],
            columnDefs: userColumnDefs = [],
            columns: userColumns = [],
            bodyFontSize = null,
            bodyFontStyle = null,
            ...restOptions
        } = options;

        this.useHideColumn = useHideColumn;
        this.rowSelect = rowSelect;
        this.multiRowSelect = multiRowSelect;
        this.checkBoxSelect = checkBoxSelect;
        this.cellSelect = cellSelect;

        // ---- Checkbox select ----
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

        // ---- Build DOM string ----
        const dom = buildDatatableDom({
            useHideColumn,
            removeSearch,
            removePagination,
            removePageInfo,
            removePageLength,
        });

        // ---- Build select config ----
        const selectConfig = buildSelectConfig({
            rowSelect,
            multiRowSelect,
            checkBoxSelect,
            cellSelect,
        });

        // ---- Build base config ----
        const config = buildDatatableConfig({
            dom,
            columnDefs,
            removePagination,
            useHideColumn,
            selectConfig,
        });

        // ---- Kolom: generate <thead> otomatis jika kosong ----
        const resolvedColumns = this._resolveColumns(
            userColumns,
            checkBoxSelect,
        );
        if (resolvedColumns.length) {
            this._buildThead(resolvedColumns);
        }

        // ---- Init DataTable ----
        this.instance = new DataTable(this.tableElement, {
            ...config,
            processing: true,
            serverSide: true,
            ...(resolvedColumns.length ? { columns: resolvedColumns } : {}),
            ...restOptions,
        });
        this.tableElement._datatable = this.instance;

        if (bodyFontSize || bodyFontStyle) {
            this.instance.on("draw.dt", () => {
                const tbody = this.tableElement.querySelector("tbody");
                if (!tbody) return;

                if (bodyFontSize) {
                    tbody.style.fontSize = bodyFontSize;
                }
                if (bodyFontStyle === "semibold") {
                    tbody.style.fontWeight = "600";
                } else if (bodyFontStyle === "bold") {
                    tbody.style.fontWeight = "700";
                } else if (bodyFontStyle === "medium") {
                    tbody.style.fontWeight = "500";
                } else if (bodyFontStyle) {
                    tbody.style.fontWeight = bodyFontStyle;
                }
            });
        }

        if (useHideColumn) this.initColumnToggle();
        if (!removePageLength) this.initCustomPageLength(pageLengthOptions);
    }

    // ------------------------------------------------------------------ //
    // Private helpers
    // ------------------------------------------------------------------ //
    /**
     * Jika checkBoxSelect aktif, sisipkan kolom checkbox di index 0.
     * Kolom checkbox tidak perlu `data` karena di-render oleh DataTables.render.select().
     */
    _resolveColumns(userColumns, checkBoxSelect) {
        if (!userColumns.length) return [];

        if (checkBoxSelect) {
            return [
                {
                    data: null,
                    defaultContent: "",
                    title: "",
                    orderable: false,
                    render: DataTable.render.select(),
                },
                ...userColumns,
            ];
        }
        return userColumns;
    }

    /**
     * Generate <thead><tr><th>...</th></tr></thead> dari array columns
     * agar DataTables punya header yang cocok dengan column mapping.
     * Hanya dijalankan jika <thead> belum ada atau masih kosong.
     */
    _buildThead(columns) {
        let thead = this.tableElement.querySelector("thead");
        if (!thead) {
            thead = document.createElement("thead");
            this.tableElement.prepend(thead);
        }
        if (thead.querySelector("th")) return; // sudah ada, skip

        const tr = document.createElement("tr");
        columns.forEach((col) => {
            const th = document.createElement("th");
            th.textContent = col.title ?? "";
            tr.appendChild(th);
        });
        thead.appendChild(tr);
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
                <i class="ti ti-eye-off align-middle fs-4"></i>
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

    // Generate dropdown page length
    initCustomPageLength(lengths = [10, 25, 50, 100]) {
        const wrapper = this.instance
            .table()
            .container()
            .querySelector(".pageLengthWrapper");
        if (!wrapper || !this.instance) return;

        const currentLength = this.instance.page.len();

        wrapper.innerHTML = `
        <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" data-bs-auto-close="true" aria-expanded="false">
            <span class="dt-page-length-label fs-6">${currentLength === -1 ? "All" : currentLength}</span>
        </button>
        <ul class="dropdown-menu shadow-sm">
            ${lengths
                .map(
                    (val) => `<li>
                    <button class="dropdown-item dt-page-length-option ${val === currentLength ? "active" : ""}" type="button" data-value="${val}">
                        ${val === -1 ? "All" : val} baris
                    </button>
                </li>`,
                )
                .join("")}
        </ul>`;

        const label = wrapper.querySelector(".dt-page-length-label");
        const options = wrapper.querySelectorAll(".dt-page-length-option");

        options.forEach((btn) => {
            btn.addEventListener("click", () => {
                const val = parseInt(btn.dataset.value, 10);
                this.instance.page.len(val).draw();
                if (label) label.textContent = val === -1 ? "All" : val;
                options.forEach((b) => b.classList.remove("active"));
                btn.classList.add("active");
            });
        });

        this.instance.on("length.dt", (e, settings, len) => {
            if (label) label.textContent = len === -1 ? "All" : len;
            options.forEach((b) => {
                b.classList.toggle(
                    "active",
                    parseInt(b.dataset.value, 10) === len,
                );
            });
        });
    }

    // Method untuk mendapatkan data per baris
    getRowData(rowSelector) {
        return this.instance?.row(rowSelector).data() ?? null;
    }

    /** Reload data tanpa reset halaman */
    reload(resetPage = false) {
        this.instance?.ajax.reload(null, resetPage);
    }

    /** Update parameter ajax lalu reload */
    reloadWithParams(params = {}, resetPage = true) {
        const settings = this.instance.ajax.params() ?? {};
        Object.assign(settings, params);
        this.instance.ajax.reload(null, resetPage);
    }
}
