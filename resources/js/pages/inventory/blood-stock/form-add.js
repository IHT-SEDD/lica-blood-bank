import { GlobalSubmitForm, GlobalFormValidation } from "../../../app";
import TomSelect from "tom-select";

// ---------- Global variable untuk memudahkan penyesuaian :begin ----------
const FormAddSelector = "add_new_blood_stock";
const FormAddURL = "/inventory/blood-stock/data/new";
const SelectBagNumberWrapper = "wrap-select-bag-number";
const BagNumberListWrapper = "wrap-textarea-bag-numbers";
const ReloadDatatableSelector = "blood-stock-reload";
// ---------- Global variable untuk memudahkan penyesuaian :end ----------

// ---------- State gobal :begin ----------
let tomSelectBagNumber = null;
let tomSelectPONumber = null;
let AddNewBloodStockValidation = null;
let selectedPoNumber = null;
// ---------- State gobal :end ----------

// ---------- Tom Select :begin ----------
function SelectPONumber() {
    tomSelectPONumber = new TomSelect("#select-purchase-order", {
        valueField: "text",
        labelField: "text",
        searchField: "text",
        sortField: { field: "id", direction: "asc" },
        create: false,
        preload: true,
        load: function (query, callback) {
            fetch(
                `/utility/select/purchase-order-registered?q=${encodeURIComponent(query)}`,
            )
                .then((res) => res.json())
                .then((json) => callback(json.results))
                .catch(() => callback());
        },
        onChange: function (value) {
            selectedPoNumber = value || null;

            if (tomSelectBagNumber) {
                tomSelectBagNumber.clear();
                tomSelectBagNumber.clearOptions();
                tomSelectBagNumber.load("");
            }
        },
    });
}

function SelectBagNumber() {
    tomSelectBagNumber = new TomSelect("#select-bag-number", {
        valueField: "id",
        labelField: "text",
        searchField: "text",
        sortField: {
            field: "id",
            direction: "asc",
        },
        create: false,
        preload: false,
        maxItems: 9999,
        plugins: ["remove_button", "clear_button"],
        load: function (query, callback) {
            if (!selectedPoNumber) {
                callback([]);
                return;
            }

            fetch(
                `/utility/select-special/bag-number-by-po/${selectedPoNumber}?q=${encodeURIComponent(query)}`,
            )
                .then((response) => response.json())
                .then((json) => callback(json.results))
                .catch(() => callback());
        },
    });
}
// ---------- Tom Select :begin ----------

// ---------- Toggle visibility berdasarkan method_add :begin ----------
function getSelectedMethod() {
    const selected = document.querySelector('input[name="method_add"]:checked');
    return selected ? selected.value : "manual";
}

function toggleFieldsByMethod(method) {
    const wrapSelectBag = document.getElementById(SelectBagNumberWrapper);
    const wrapTextareaBag = document.getElementById(BagNumberListWrapper);

    if (method === "manual") {
        wrapSelectBag.classList.remove("d-none");
        wrapTextareaBag.classList.add("d-none");

        document.getElementById("bag_numbers").removeAttribute("name");
        document
            .getElementById("select-bag-number")
            .setAttribute("name", "bag_numbers[]");

        if (tomSelectBagNumber) {
            tomSelectBagNumber.input.setAttribute("name", "bag_numbers[]");
        }
    } else {
        wrapSelectBag.classList.add("d-none");
        wrapTextareaBag.classList.remove("d-none");

        document.getElementById("select-bag-number").removeAttribute("name");
        if (tomSelectBagNumber) {
            tomSelectBagNumber.input.removeAttribute("name");
        }

        document
            .getElementById("bag_numbers")
            .setAttribute("name", "bag_numbers");
    }
}

function initMethodToggle() {
    const radios = document.querySelectorAll('input[name="method_add"]');
    radios.forEach((radio) => {
        radio.addEventListener("change", function () {
            toggleFieldsByMethod(this.value);
        });
    });

    // Jalankan saat load sesuai default checked
    toggleFieldsByMethod(getSelectedMethod());
}
// ---------- Toggle visibility berdasarkan method_add :end ----------

// ---------- Handle form penambahan storage baru :begin ----------
function AddNewBloodStock() {
    AddNewBloodStockValidation = GlobalFormValidation.init(
        "#" + FormAddSelector,
        {
            method_add: {
                validators: {
                    notEmpty: { message: "Method add is required" },
                },
            },
            po_number: {
                validators: {
                    notEmpty: { message: "PO Number is required" },
                },
            },
            bag_numbers: {
                validators: {
                    notEmpty: { message: "Bag Number is required" },
                },
            },
        },
    );

    new GlobalSubmitForm({
        formId: FormAddSelector,
        url: FormAddURL,
        validator: AddNewBloodStockValidation,
        onSuccess: (data) => {
            notyf.success({ message: "New blood stock added succesfully!" });
            window.dispatchEvent(new Event(ReloadDatatableSelector));
        },
        onError: (err) => {
            notyf.error({ message: "New blood stock failed to insert!" });
            console.error(err);
        },
        resetOnSuccess: true,
    });
}
// ---------- Handle form penambahan storage baru :end ----------

document.addEventListener("DOMContentLoaded", function () {
    AddNewBloodStock();
    SelectBagNumber();
    SelectPONumber();
    initMethodToggle();
});
