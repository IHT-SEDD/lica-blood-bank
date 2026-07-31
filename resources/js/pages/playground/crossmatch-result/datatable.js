import {
    GlobalAdvanceTomselect,
    GlobalEditData,
    GlobalSubmitForm,
} from "../../../app";
import { GlobalAdvanceYajraDatatable } from "../../../utility/datatable/datatables";
import { GetInstance } from "../../../utility/ui";

// ---------- GLOBAL VARIABLES ----------
const TestTableURL = "/playground/fixing/crossmatch-result/test-table";
const SelectorTestsTable = "#list-tests-table";
const URLResultOptions = "/utility/select/result-test";
const URLGetData = "/playground/fixing/crossmatch-result/get-data";
const URLUpdate = "/playground/fixing/crossmatch-result";

const FormEditSelector = "edit_data_crossmatch";
const ModalEditSelector = "edit_data_crossmatch_modal";
const ActionEditSelector = ".btn-edit-crossmatch";
const AttributeEdit = "editId";

let listTestsTableInstance = null;
const tomSelectRegistry = {};

// ---------- HELPERS ----------
const isTableInitialized = (selector) => $.fn.DataTable.isDataTable(selector);
export function DestroyDatatableTests() {
    if (isTableInitialized(SelectorTestsTable)) {
        Object.values(tomSelectRegistry).forEach((ts) => {
            try {
                ts.destroy();
            } catch (_) {}
        });
        Object.keys(tomSelectRegistry).forEach(
            (k) => delete tomSelectRegistry[k],
        );
        $(SelectorTestsTable).DataTable().destroy();
        listTestsTableInstance = null;
    }
}
function renderCrossmatchResult(result) {
    switch (result) {
        case "Compatible":
            return `<span class="badge badge-label text-bg-success">Compatible</span>`;
        case "Incompatible":
            return `<span class="badge badge-label text-bg-danger">Incompatible</span>`;
        default:
            return `<span class="badge badge-label text-bg-info">Belum Dilakukan</span>`;
    }
}
function setTomSelectValue(selector, value) {
    const el = document.querySelector(selector);
    if (!el?.tomselect) return;

    const ts = el.tomselect;

    if (Object.keys(ts.options).length > 0) {
        // Options sudah ada → langsung set
        ts.clear(true);
        if (value) ts.setValue(value, true);
    } else {
        // Options belum di-load → trigger load dulu, baru set setelah callback
        ts.load("", function () {
            ts.clear(true);
            if (value) ts.setValue(value, true);
        });
    }
}

// ---------- TESTS TABLE ----------
export function DatatableTests(transfusionPublicId) {
    if (isTableInitialized(SelectorTestsTable)) {
        DestroyDatatableTests();
    }

    const TESTCOLUMNS = [
        {
            data: "bag_number",
            title: "No. Kantong",
            render: (_val, _type, row) =>
                `<span class="fw-semibold fs-6">${row.bag_number ?? "-"}</span>`,
        },
        {
            data: "mayor",
            title: "Mayor",
            render: (_val, _type, row) =>
                `<span class="fw-semibold fs-6">${row.mayor ?? "-"}</span>`,
        },
        {
            data: "minor",
            title: "Minor",
            render: (_val, _type, row) =>
                `<span class="fw-semibold fs-6">${row.minor ?? "-"}</span>`,
        },
        {
            data: "auto_control",
            title: "Auto Control",
            render: (_val, _type, row) =>
                `<span class="fw-semibold fs-6">${row.auto_control ?? "-"}</span>`,
        },
        {
            data: "crossmatch_result",
            title: "Hasil",
            render: (_val, _type, row) =>
                renderCrossmatchResult(row.crossmatch_result),
        },
        {
            data: null,
            title: "Aksi",
            defaultContent: "",
            orderable: false,
            render: (data, type, row, meta) => {
                return `<button aria-expanded="false" class="btn btn-sm btn-soft-primary datatable-action-toggle" data-bs-toggle="dropdown" data-bs-auto-close="true" type="button">
                  <i class="ti ti-dots align-middle"></i>
                 </button>
                 <ul class="dropdown-menu">
                     <li>
                         <button id="edit-data-${row.detail_public_id}" class="dropdown-item fw-medium btn-edit-crossmatch" data-edit-id="${row.detail_public_id}" type="button">
                         <i class="ti ti-pencil align-middle me-2 fs-4"></i>
                         Edit
                         </button>
                     </li
                 </ul>
                `;
            },
        },
    ];

    listTestsTableInstance = new GlobalAdvanceYajraDatatable(
        SelectorTestsTable,
        {
            removeSearch: true,
            removePageInfo: true,
            removePagination: true,
            removePageLength: true,
            ajax: {
                url: TestTableURL,
                data(d) {
                    d.transfusion_public_id = transfusionPublicId;
                },
            },
            columns: TESTCOLUMNS,
        },
    );
}

// ---------- EDIT ----------
export function EditCrossmatchAction() {
    new GlobalEditData({
        ButtonSelector: ActionEditSelector,
        DataAttributeID: AttributeEdit,
        UrlFetchData: (publicID) => URLGetData + `/${publicID}`,
        ModalEditID: ModalEditSelector,
        FormSelector: FormEditSelector,
    });

    document.addEventListener("edit:open", function (e) {
        const { data } = e.detail;
        if (!data) return;
        const resultMap = {};
        data.forEach((item) => {
            if (item.test?.name) {
                resultMap[item.test.name] = item.result;
            }
        });

        setTomSelectValue(
            "#edit_data_crossmatch_mayor_result",
            resultMap["Mayor"] ?? null,
        );
        setTomSelectValue(
            "#edit_data_crossmatch_minor_result",
            resultMap["Minor"] ?? null,
        );
        setTomSelectValue(
            "#edit_data_crossmatch_auto_control_result",
            resultMap["Auto Control"] ?? null,
        );

        const firstItem = data[0];
        if (firstItem) {
            document.querySelector(`#${FormEditSelector}`).dataset.id =
                firstItem.bt_detail_id;
        }
    });

    new GlobalSubmitForm({
        formId: FormEditSelector,
        url: () => {
            const form = document.getElementById(FormEditSelector);
            return URLUpdate + `/${form.dataset.id}`;
        },
        method: "PATCH",
        onSuccess: (response) => {
            notyf.success({
                message: "Data crossmatch updated succesfully!",
            });
            const modalEl = document.getElementById(ModalEditSelector);
            if (modalEl) {
                bootstrap.Modal.getInstance(modalEl)?.hide();
            }
            if (listTestsTableInstance) {
                listTestsTableInstance.reload();
            }
        },
        onError: (err) => {
            notyf.error({
                message: err.message || "Failed to update data!",
            });
            console.error(err);
        },
        resetOnSuccess: true,
    });
}
