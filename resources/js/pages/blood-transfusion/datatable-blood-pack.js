import { GlobalAdvanceDatatable } from "../../app";

// ---------- Blood Pack Datatable :begin ----------

// Global variables
let selectedBloodPacksInstance;
let availableBloodPacksInstance;
let listRequestTableInstance;
window.__btSelectedBloodPacks = window.__btSelectedBloodPacks || [];
let selectedBloodPacks = window.__btSelectedBloodPacks;
const DatatableUrl = "/blood-transfusion/datatable";

// Initialize Available Blood Packs Datatable
function initAvailableBloodPacksTable() {
    const tableSelector = "#available-blood-pack-table";

    if ($.fn.DataTable.isDataTable(tableSelector)) {
        return;
    }

    availableBloodPacksInstance = new GlobalAdvanceDatatable(tableSelector, {
        serverSide: true,
        removePagination: true,
        removePageInfo: true,
        removeSearch: true,
        useHideColumn: false,
        scrollY: "350px",
        scrollCollapse: true,
        ajax: {
            url: DatatableUrl + "/blood-pack",
            type: "GET",
            data: function (d) {
                d.blood_group = $("#blood_group_filter").val();
                d.blood_rhesus = $("#blood_rhesus_filter").val();
            },
            dataSrc: "data",
        },
        columns: [
            {
                data: "blood_group",
                title: "Blood",
                className: "all text-start",
                width: "100%",
            },
            {
                data: "blood_rhesus",
                title: "Rhesus",
                className: "all text-center",
                width: "100%",
            },
            {
                data: "blood_component",
                title: "Component",
                className: "all text-center",
                width: "100%",
            },
            {
                data: null,
                title: "Action",
                width: "100%",
                orderable: false,
                searchable: false,
                className: "all text-end",
                render: (data) => {
                    return `<button class="btn btn-sm btn-soft-primary select-blood-pack" type="button"
                        data-id="${data.id}"
                        data-public-id="${data.public_id}"
                        data-label="${data.blood_group}"
                        data-rhesus="${data.blood_rhesus}"
                        data-component="${data.blood_component}">
                        <i class="ti ti-arrow-right"></i>
                    </button>`;
                },
            },
        ],
        order: [[0, "asc"]],
    });
}

// Select blood pack and add to selected list
function selectBloodPack(id, publicId, label, component, rhesus) {
    const newPack = {
        id: id,
        public_id: publicId,
        blood_label: label,
        blood_component: component,
        blood_rhesus: rhesus,
        requested_quantity: 1,
    };

    selectedBloodPacks.push(newPack);
    updateSelectedBloodPacksTable();
    updateHiddenInput();

    notyf.success({
        message: "Blood pack added successfully!",
    });
}

// Remove blood pack from selected list
function removeBloodPack(index) {
    selectedBloodPacks.splice(index, 1);

    updateSelectedBloodPacksTable();
    updateHiddenInput();

    notyf.success({
        message: "Blood pack successfully removed!",
    });
}

// Clear all selected blood packs
export function clearSelectedBloodPacks() {
    selectedBloodPacks.length = 0;
    updateSelectedBloodPacksTable();
    updateHiddenInput();
}

// Update selected blood packs table
function updateSelectedBloodPacksTable() {
    const tableBody = document.querySelector(
        "#selected-blood-pack-table tbody",
    );
    if (!tableBody) return;

    tableBody.innerHTML = "";
    if (selectedBloodPacks.length === 0) {
        tableBody.innerHTML =
            '<tr><td colspan="4" class="text-center text-muted">Tidak ada blood pack yang dipilih</td></tr>';
        return;
    }

    selectedBloodPacks.forEach((pack, index) => {
        const row = document.createElement("tr");
        row.innerHTML = `
            <td>${pack.blood_label || "-"}</td>
            <td>${pack.blood_rhesus || "-"}</td>
            <td>${pack.blood_component || "-"}</td>
            <td>
                <button class="btn btn-sm btn-danger remove-blood-pack" type="button" data-index="${index}">
                    <i class="ti ti-trash"></i>
                </button>
            </td>
        `;
        tableBody.appendChild(row);
    });
}

// Update hidden input with selected blood packs
function updateHiddenInput() {
    const hiddenInput = document.getElementById("selected-blood-packs");
    if (hiddenInput) {
        hiddenInput.value = JSON.stringify(selectedBloodPacks);
    }

    // Clear existing feedback
    document
        .querySelectorAll("#add_new_blood_request .is-invalid")
        .forEach((el) => el.classList.remove("is-invalid"));
    document
        .querySelectorAll("#add_new_blood_request .invalid-feedback")
        .forEach((el) => el.remove());
    document
        .querySelectorAll("#add_new_blood_request .is-valid")
        .forEach((el) => el.classList.remove("is-valid"));
}

// Event delegation untuk button select yang di-render oleh datatable
if (!window.__btEventDelegated) {
    window.__btEventDelegated = true;
    document.addEventListener("click", function (e) {
        const selectButton = e.target.closest(".select-blood-pack");
        if (selectButton) {
            console.log(selectButton);
            const btn = selectButton;
            const id = parseInt(btn.dataset.id);
            const publicId = btn.dataset.publicId;
            const label = btn.dataset.label;
            const component = btn.dataset.component;
            const rhesus = btn.dataset.rhesus;
            selectBloodPack(id, publicId, label, component, rhesus);
            return;
        }

        const removeButton = e.target.closest(".remove-blood-pack");
        if (removeButton) {
            const index = removeButton.dataset.index;
            console.log(removeButton);
            if (index !== undefined) {
                removeBloodPack(parseInt(index, 10));
            }
        }
    });
}

// Initialize on page load
document.addEventListener("DOMContentLoaded", function () {
    // Initialize datatable for available blood packs
    initAvailableBloodPacksTable();

    // Initial display of selected blood packs table
    updateSelectedBloodPacksTable();

    // Reload datatable on flatpickr change
    const dateFilter = document.querySelector(".blood-transfusion-date-filter");
    // if (dateFilter) {
    //     dateFilter.addEventListener("change", function () {
    //         if ($.fn.DataTable.isDataTable(tableSelector)) {
    //             $(tableSelector).DataTable().ajax.reload();
    //         }
    //     });
    // }
});

// ---------- Blood Pack Datatable :end ----------
