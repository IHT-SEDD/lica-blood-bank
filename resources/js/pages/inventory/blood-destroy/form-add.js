import {
    GlobalSubmitForm,
    GlobalFormValidation,
    GlobalAdvanceTomselect,
} from "../../../app";

// ---------- Global variable untuk memudahkan penyesuaian :begin ----------
// Wrapper
const SelectBagNumberWrapper = "wrap-select-bag-number";
const BagNumberListWrapper = "wrap-textarea-bag-numbers";

// Selector
const FormAddSelector = "add_new_destroy_blood";
const ReloadDatatableSelector = "blood-destroy-reload";
const SelectBagNumberSelector = "#select-bag-number";

// URLS
const FormAddURL = "/inventory/destroy-blood/data/new";
const URLSelectBagNumber = "/inventory/destroy-blood/data/select/bag-number";
// ---------- Global variable untuk memudahkan penyesuaian :end ----------

// ---------- State gobal ----------
let tomSelectBagNumber = null;
let AddNewBloodDestroyValidation = null;
let hasDuplicates = false;

// ---------- Tom Select ----------
function SelectBagNumber() {
    const wrapperSelectBagNumber = new GlobalAdvanceTomselect(
        SelectBagNumberSelector,
        {
            valueField: "text",
            preload: true,
            maxItems: 9999,
            plugins: ["clear_button"],
            noResultsText: "Bag Number not found",
            blurOnItemAdd: false,
            closeAfterSelect: false,
            load: function (query, callback) {
                fetch(`${URLSelectBagNumber}/?q=${encodeURIComponent(query)}`)
                    .then((res) => res.json())
                    .then((json) => callback(json.results))
                    .catch(() => callback());
            },
        },
    );

    tomSelectBagNumber = wrapperSelectBagNumber.getInstances()[0];
}

// ---------- Toggle visibility berdasarkan method_add :begin ----------
function getSelectedMethod() {
    const selected = document.querySelector('input[name="method_add"]:checked');
    return selected ? selected.value : "manual";
}
function toggleFieldsByMethod(method) {
    const wrapSelectBag = document.getElementById(SelectBagNumberWrapper);
    const wrapTextareaBag = document.getElementById(BagNumberListWrapper);

    if (method === "manual") {
        // ---------- Tampilkan select, sembunyikan textarea ----------
        wrapSelectBag.classList.remove("d-none");
        wrapTextareaBag.classList.add("d-none");

        // ---------- Aktifkan field select, nonaktifkan textarea ----------
        document.getElementById("bag_numbers").removeAttribute("name");
        document
            .getElementById("select-bag-number")
            .setAttribute("name", "bag_numbers[]");

        if (tomSelectBagNumber) {
            tomSelectBagNumber.input.setAttribute("name", "bag_numbers[]");
        }

        // ---------- Aktifkan kembali validasi bag_numbers ----------
        if (AddNewBloodDestroyValidation) {
            AddNewBloodDestroyValidation.addField("bag_numbers", {
                validators: {
                    notEmpty: { message: "Bag Number is required" },
                },
            });
        }

        // ---------- Reset state duplikat ----------
        hasDuplicates = false;
    } else {
        // ---------- Tampilkan textarea, sembunyikan select ----------
        wrapSelectBag.classList.add("d-none");
        wrapTextareaBag.classList.remove("d-none");

        // ---------- Nonaktifkan field select agar tidak ikut ke backend ----------
        document.getElementById("select-bag-number").removeAttribute("name");
        if (tomSelectBagNumber) {
            tomSelectBagNumber.input.removeAttribute("name");
        }

        document
            .getElementById("bag_numbers")
            .setAttribute("name", "bag_numbers");

        // ---------- Hapus validasi bag_numbers saat mode scan ----------
        if (AddNewBloodDestroyValidation) {
            AddNewBloodDestroyValidation.removeField("bag_numbers");
        }

        // ---------- Reset state duplikat ----------
        hasDuplicates = false;
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

// ---------- Deteksi duplikat di textarea bag numbers (mode scan) ----------
function parseBagNumbersFromTextarea() {
    const textarea = document.getElementById("bag_numbers");
    if (!textarea) return [];

    return textarea.value
        .split("\n")
        .map((line) => line.trim())
        .filter((line) => line.length > 0);
}
function findDuplicates(items) {
    const seen = {};
    const duplicates = [];

    items.forEach((item) => {
        if (seen[item]) {
            if (!duplicates.includes(item)) {
                duplicates.push(item);
            }
        } else {
            seen[item] = true;
        }
    });

    return duplicates;
}
function initScanTextareaListener() {
    const textarea = document.getElementById("bag_numbers");
    if (!textarea) return;

    // ---------- Deteksi duplikat real-time setiap kali textarea berubah ----------
    textarea.addEventListener("input", function () {
        if (getSelectedMethod() !== "scan") return;

        const duplicates = findDuplicates(parseBagNumbersFromTextarea());
        if (duplicates.length > 0) {
            notyf.error({
                message: `Duplicate bag number detected: ${duplicates.join(", ")}`,
                duration: 5000,
            });
        }
    });
}

// ---------- Guard validasi scan dipasang SEBELUM GlobalSubmitForm mendengarkan submit ----------
function initScanGuard() {
    const form = document.getElementById(FormAddSelector);
    if (!form) return;

    form.addEventListener(
        "submit",
        function (e) {
            if (getSelectedMethod() !== "scan") return;

            const bagNumbers = parseBagNumbersFromTextarea();

            // ---------- Cek textarea tidak kosong ----------
            if (bagNumbers.length === 0) {
                e.preventDefault();
                e.stopImmediatePropagation();
                notyf.error({ message: "Bag number list cannot be empty!" });
                return;
            }

            // ---------- Cek duplikat ----------
            const duplicates = findDuplicates(bagNumbers);
            if (duplicates.length > 0) {
                e.preventDefault();
                e.stopImmediatePropagation();
                notyf.error({
                    message: `Bag number duplicate found, please fix it first: ${duplicates.join(", ")}`,
                    duration: 6000,
                });
                return;
            }
        },
        true, // capture: true → listener ini berjalan sebelum listener GlobalSubmitForm
    );
}

// ---------- Handle form ----------
function AddNewBloodDestroy() {
    AddNewBloodDestroyValidation = GlobalFormValidation.init(
        "#" + FormAddSelector,
        {
            method_add: {
                validators: {
                    notEmpty: { message: "Method add is required" },
                },
            },
            bag_numbers: {
                validators: {
                    notEmpty: { message: "Bag Number is required" },
                },
            },
            reason: {
                validators: {
                    notEmpty: { message: "Reason is required" },
                },
            },
        },
    );

    new GlobalSubmitForm({
        formId: FormAddSelector,
        url: FormAddURL,
        validator: AddNewBloodDestroyValidation,
        onSuccess: (data) => {
            notyf.success({ message: "Blood Destroy Successfully!" });
            window.dispatchEvent(new Event(ReloadDatatableSelector));
        },
        onError: (err) => {
            notyf.error({ message: "Blood Failed to Destroy!" });
            console.error(err);
        },
        resetOnSuccess: true,
    });
}

document.addEventListener("DOMContentLoaded", function () {
    initScanGuard();
    AddNewBloodDestroy();
    SelectBagNumber();
    initMethodToggle();
    initScanTextareaListener();
});
