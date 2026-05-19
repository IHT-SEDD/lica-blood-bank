import { data } from "jquery";
import { GlobalAdvanceDatatable } from "../../app";
import { initAvailableBloodComponentsTable } from "./analytic/datatables-helper";

// ---------- Blood Pack Datatable :begin ----------

// Global variables
window.__btSelectedBloodComponents = window.__btSelectedBloodComponents || [];
let selectedBloodComponents = window.__btSelectedBloodComponents;

// Select blood component and add to selected list
function selectBloodComponents(id, text) {
    const newPack = {
        id: id,
        text: text,
    };

    selectedBloodComponents.push(newPack);
    updateSelectedBloodComponentsTable();
    updateHiddenInput();

    notyf.success({
        message: "Blood component added successfully!",
    });
}

// Remove blood component from selected list
function removeBloodComponents(index) {
    selectedBloodComponents.splice(index, 1);

    updateSelectedBloodComponentsTable();
    updateHiddenInput();

    notyf.success({
        message: "Blood component successfully removed!",
    });
}

// Clear all selected blood packs
export function clearSelectedBloodComponents() {
    selectedBloodComponents.length = 0;
    updateSelectedBloodComponentsTable();
    updateHiddenInput();
}

// Update selected blood packs table
function updateSelectedBloodComponentsTable() {
    const tableBody = document.querySelector(
        "#selected-blood-pack-table tbody",
    );
    if (!tableBody) return;

    tableBody.innerHTML = "";
    if (selectedBloodComponents.length === 0) {
        tableBody.innerHTML =
            '<tr><td colspan="4" class="text-center text-muted">Tidak ada blood component yang dipilih</td></tr>';
        return;
    }

    selectedBloodComponents.forEach((pack, index) => {
        const row = document.createElement("tr");
        row.innerHTML = `
            <td>${pack.text || "-"} (${pack.id || "-"})</td>
            <td>
                <button class="btn btn-sm btn-danger remove-blood-components" type="button" data-index="${index}">
                    <i class="ti ti-trash"></i>
                </button>
            </td>
        `;
        tableBody.appendChild(row);
    });
}

// Update hidden input with selected blood packs
function updateHiddenInput() {
    const hiddenInput = document.getElementById("selected-blood-components");
    if (hiddenInput) {
        hiddenInput.value = JSON.stringify(selectedBloodComponents);
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
        const selectButton = e.target.closest(".select-blood-component");
        if (selectButton) {
            const btn = selectButton;
            const id = btn.dataset.id;
            const text = btn.dataset.text;
            selectBloodComponents(id, text);
            return;
        }

        const removeButton = e.target.closest(".remove-blood-components");
        if (removeButton) {
            const index = removeButton.dataset.index;
            if (index !== undefined) {
                removeBloodComponents(parseInt(index, 10));
            }
        }
    });
}

// Initialize on page load
document.addEventListener("DOMContentLoaded", function () {
    // Initial display of selected blood components table
    updateSelectedBloodComponentsTable();
    // Initialize datatable for available blood components
    initAvailableBloodComponentsTable();
});

// ---------- Blood Pack Datatable :end ----------
