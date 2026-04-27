import TomSelect from "tom-select";
import { GlobalSubmitForm, GlobalFormValidation } from "../../../app";

// ---------- Init tom select ----------
function initSelect(el, url) {
    if (el.tomselect) return;

    new TomSelect(el, {
        valueField: "id",
        labelField: "text",
        searchField: "text",
        preload: true,
        load: function (query, callback) {
            fetch(`${url}?q=${encodeURIComponent(query)}`)
                .then((res) => res.json())
                .then((json) => callback(json.results))
                .catch(() => callback());
        },
    });
}

// ---------- Init select pertama kali ----------
function initAllSelects(container) {
    container.find(".select-blood-group").each(function () {
        initSelect(this, "/utility/select/blood-group");
    });

    container.find(".select-blood-rhesus").each(function () {
        initSelect(this, "/utility/select/blood-rhesus");
    });

    container.find(".select-blood-component").each(function () {
        initSelect(this, "/utility/select/blood-component");
    });
}

// ---------- Perbaharui index inputan blood data ----------
function reIndex(container) {
    container.find(".blood-data-card").each(function (i) {
        $(this)
            .find("input, select")
            .each(function () {
                let name = $(this).attr("name");
                if (!name) return;

                let newName = name.replace(
                    /blood_data\[\d+\]/,
                    `blood_data[${i}]`,
                );
                $(this).attr("name", newName);
            });
    });
}

// ---------- Kosongkan inputan pada card bloo data ----------
function resetCard(card) {
    card.find("input").val("");
    card.find("input[type=checkbox]").prop("checked", false);
    card.find("select").val("");
}

// ---------- Isi inputan total quantity ----------
function updateTotal() {
    let total = 0;

    $(".blood_quantity").each(function () {
        total += parseInt($(this).val()) || 0;
    });

    $("#total_quantity").val(total);
}

function validateBloodData() {
    let isValid = true;

    $("#blood-data-container .blood-data-card").each(function () {
        const fields = [
            ".select-blood-group",
            ".select-blood-rhesus",
            ".select-blood-component",
            ".blood_volume",
            ".blood_quantity",
        ];

        fields.forEach((selector) => {
            const el = $(this).find(selector);
            const val = el.val();

            if (!val) {
                isValid = false;
                el.addClass("is-invalid");
            } else {
                el.removeClass("is-invalid");
            }
        });
    });

    return isValid;
}

// ---------- Select vendor dari tom-select untuk form add new data :begin ----------
function SelectVendor() {
    new TomSelect("#select-vendor", {
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
            fetch(`/utility/select/vendor?q=${encodeURIComponent(query)}`)
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
// ---------- Select vendor dari tom-select untuk form add new data :begin ----------

function AddNewOrder() {
    // Ambil container blood data
    const container = $("#blood-data-container");

    // Panggil select
    initAllSelects(container);

    // ---------- Validasi inputan form :begin ----------
    const AddNewOrderValidation = GlobalFormValidation.init("#add_new_order", {
        po_number: {
            validators: {
                notEmpty: {
                    message: "PO number is required",
                },
            },
        },
        vendor_id: {
            validators: {
                notEmpty: {
                    message: "Vendor is required",
                },
            },
        },
        total_quantity: {
            validators: {
                notEmpty: {
                    message: "Total quantity is required",
                },
            },
        },
        blood_group: {
            validators: {
                notEmpty: {
                    message: "Blood group is required",
                },
            },
        },
        blood_rhesus: {
            validators: {
                notEmpty: {
                    message: "Blood rhesus is required",
                },
            },
        },
        blood_component: {
            validators: {
                notEmpty: {
                    message: "Blood component is required",
                },
            },
        },
        blood_volume: {
            validators: {
                notEmpty: {
                    message: "Blood volume is required",
                },
            },
        },
        blood_quantity: {
            validators: {
                notEmpty: {
                    message: "Blood quantity is required",
                },
            },
        },
    });
    // ---------- Validasi inputan form :end ----------

    // Tambah card bloo data
    $("#add_new_data").on("click", function (e) {
        e.preventDefault();

        let newCard = container.find(".blood-data-card").first().clone();

        newCard.find("select").each(function () {
            if (this.tomselect) {
                this.tomselect.destroy();
            }
        });

        resetCard(newCard);

        container.append(newCard);

        reIndex(container);

        initAllSelects(newCard);
    });

    // Delete card
    $(document).on("click", ".delete-card", function (e) {
        e.preventDefault();

        if ($(".blood-data-card").length > 1) {
            $(this).closest(".blood-data-card").remove();

            reIndex(container);
            updateTotal();
        } else {
            notyf.error({ message: "Minimum 1 blood data!" });
        }
    });

    // Update value total_quantity
    $(document).on("input", ".blood_quantity", function () {
        updateTotal();
    });

    new GlobalSubmitForm({
        formId: "add_new_order",
        url: "/inventory/history-order/new-order",
        validator: AddNewOrderValidation,
        beforeSubmit: () => {
            if (!validateBloodData()) {
                notyf.error({ message: "Please complete all blood data!" });
                return false;
            }
            return true;
        },
        onSuccess: () => {
            notyf.success({ message: "Success!" });
            window.dispatchEvent(new Event("history-order-reload"));
        },
        onError: () => {
            notyf.error({ message: "Failed!" });
        },
        resetOnSuccess: true,
    });
}

document.addEventListener("DOMContentLoaded", function () {
    SelectVendor();
    AddNewOrder();
});
