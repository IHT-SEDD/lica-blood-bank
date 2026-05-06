import { GlobalAdvanceDatatable } from "../../app";

// ---------- Blood Pack Datatable :begin ----------

// Global variables
let selectedBloodPacksInstance;
let availableBloodPacksInstance;
let selectedBloodPacks = [];

// Initialize Available Blood Packs Datatable
function initAvailableBloodPacksTable() {
    const tableSelector = "#available-blood-pack-table";

    availableBloodPacksInstance = new GlobalAdvanceDatatable(tableSelector, {
        serverSide: true,
        processing: true,
        autoWidth: false,
        searchDelay: 1000,
        paging: false,
        info: false,
        responsive: true,
        ajax: {
            url: "/blood-transfusion/datatable-blood-pack",
            type: "GET",
            dataSrc: "data",
        },
        columns: [
            {
                data: "blood_group",
                title: "Blood Group",
                width: "5%",
                className: "all",
            },
            {
                data: "blood_rhesus",
                title: "Rhesus",
                width: "5%",
                className: "all",
            },
            {
                data: "blood_component",
                title: "Component",
                width: "5%",
                className: "all",
            },
            {
                data: null,
                title: "Action",
                width: "15%",
                orderable: false,
                searchable: false,
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
        columnDefs: [
            {
                targets: -1,
                orderable: false,
                searchable: false,
                className: "all",
            },
        ],
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
    // Check if already selected
    // const exists = selectedBloodPacks.find((p) => p.id === id);
    // if (exists) {
    //     notyf.warning({
    //         message: "Blood pack sudah dipilih!",
    //     });
    //     return;
    // }

    selectedBloodPacks.push(newPack);
    updateSelectedBloodPacksTable();
    updateHiddenInput();

    notyf.success({
        message: "Blood pack berhasil ditambahkan!",
    });
}

// Remove blood pack from selected list
function removeBloodPack(index) {
    selectedBloodPacks.splice(index, 1);

    updateSelectedBloodPacksTable();
    updateHiddenInput();

    notyf.success({
        message: "Blood pack berhasil dihapus!",
    });
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
            '<tr><td colspan="3" class="text-center text-muted">Tidak ada blood pack yang dipilih</td></tr>';
        return;
    }
    console.log(selectedBloodPacks);
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
}

// Initialize on page load
document.addEventListener("DOMContentLoaded", function () {
    // Initialize datatable for available blood packs
    initAvailableBloodPacksTable();

    // Event delegation untuk button select yang di-render oleh datatable
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

    // Initial display of selected blood packs table
    updateSelectedBloodPacksTable();
});

// ---------- Blood Pack Datatable :end ----------
