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
            columnDefs: userColumnDefs = [],
            columns: userColumns = [],
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

        if (useHideColumn) this.initColumnToggle();
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
