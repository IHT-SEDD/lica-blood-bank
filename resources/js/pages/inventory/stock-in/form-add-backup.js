import TomSelect from "tom-select";
import {
    GlobalSubmitForm,
    GlobalFormValidation,
    GlobalAdvanceFlatpickr,
} from "../../../app";

// ---------- Global variable untuk memudahkan penyesuaian :begin ----------
// Static Inputs
const ChoosePOSelector = "#select-purchase-order";
const ChooseAddMethodSelector = "#select-add-data-method";

// Wrapper Form
const FormManualWrapper = "add_manually_method_wrapper";
const FormExcelWrapper = "add_excel_method_wrapper";

// Form Utilities
const LoadingForm = "loading_form_add_new_incoming_stock";
const FormHint = "form_add_new_incoming_stock_hint";

// Form Manual Add
const AddRowBloodTable = "add_row_blood_data";
const UrlGetOrderData = "/inventory/history-order/get-data";
const LoadingRenderFormManualSelector = "loading_form_manual";
const HintFormManualSelector = "form_manual_hint";
const FormAddIncomingStockSelector = "add_new_incoming_stock";
const UrlPostIncomingStock = "/inventory/stock-in/new-incoming-stock";
const BloodDataCardClass = ".blood-data-card";
// ---------- Global variable untuk memudahkan penyesuaian :begin ----------

// ---------- State global :begin ----------
let currentMethod = "manual";
let currentPONumber = null;
let incomingStockForm = null;
// ---------- State global :end ----------

// ---------- Helper: toggle tampilan form berdasarkan method :begin ----------
function toggleFormMethod(methodId) {
    const manualEl = document.getElementById(FormManualWrapper);
    const manualHintEl = document.getElementById(HintFormManualSelector);
    const excelEl = document.getElementById(FormExcelWrapper);

    if (methodId === "manual") {
        // Tampilkan wrapper manual & hint, sembunyikan excel
        manualEl.classList.remove("d-none");
        manualHintEl.classList.remove("d-none");
        excelEl.classList.add("d-none");
    } else if (methodId === "excel") {
        // Sembunyikan wrapper manual & hint, tampilkan excel
        manualEl.classList.add("d-none");
        manualHintEl.classList.add("d-none");
        excelEl.classList.remove("d-none");
    }

    currentMethod = methodId;

    // Jika beralih ke manual dan PO sudah dipilih sebelumnya, langsung fetch ulang
    if (methodId === "manual" && currentPONumber) {
        fetchAndRenderBloodCards(currentPONumber);
    }
}
// ---------- Helper: toggle tampilan form berdasarkan method :end ----------

// ---------- Helper: init flatpickr pada elemen :begin ----------
function initFlatpickr(el) {
    if (!el) return;

    // GlobalAdvanceFlatpickr memerlukan selector string (#id), bukan DOM element langsung
    const selector = el.id ? `#${el.id}` : null;
    if (!selector) return;

    new GlobalAdvanceFlatpickr(selector, {
        allowInput: true,
    });
}
// ---------- Helper: init flatpickr pada elemen :end ----------

// ---------- Helper: init TomSelect pada elemen dengan value default :begin ----------
function initTomSelectWithValue(el, url, defaultText, isReadonly = false) {
    if (!el) return;

    // Destroy instance lama jika ada agar tidak duplikat
    if (el.tomselect) {
        el.tomselect.destroy();
    }

    const ts = new TomSelect(el, {
        valueField: "id",
        labelField: "text",
        searchField: "text",
        preload: true,
        create: false,
        // Jika readonly, cegah dropdown terbuka saat fokus
        openOnFocus: !isReadonly,
        load: function (query, callback) {
            fetch(`${url}?q=${encodeURIComponent(query)}`)
                .then((res) => res.json())
                .then((json) => {
                    callback(json.results);

                    if (defaultText) {
                        const matched = json.results.find(
                            (item) => String(item.id) === String(defaultText),
                        );

                        if (matched) {
                            // Set value secara silent (tidak trigger onChange)
                            ts.setValue(String(matched.id), true);
                        }
                    }

                    // Lock setelah data dimuat dan value di-set agar tidak bisa diubah user
                    if (isReadonly) {
                        ts.lock();
                    }
                })
                .catch(() => callback());
        },
    });

    return ts; // kembalikan instance untuk keperluan luar jika dibutuhkan
}
// ---------- Helper: init TomSelect pada elemen dengan value default :end ----------

// ---------- Helper: collapse semua card blood data :begin ----------
function collapseAllBloodCards(container) {
    container.querySelectorAll(BloodDataCardClass).forEach((card) => {
        const toggle = card.querySelector('[data-action="card-toggle"]');
        const cardBody = card.querySelector(".card-body");

        if (!toggle || !cardBody) return;

        const isCollapsed = card.dataset.collapsed === "true";

        if (!isCollapsed) {
            toggle.click(); // trigger collapse bawaan framework
            card.dataset.collapsed = "true"; // tandai sebagai collapsed
        }
    });
}
// ---------- Helper: collapse semua card blood data :end ----------

// ---------- Helper: expand semua card blood data yang sedang ter-collapse :begin ----------
function expandAllBloodCards(container) {
    container.querySelectorAll(BloodDataCardClass).forEach((card) => {
        const toggle = card.querySelector('[data-action="card-toggle"]');
        const cardBody = card.querySelector(".card-body");

        if (!toggle || !cardBody) return;

        // Hanya expand jika card sedang dalam kondisi collapsed
        const isCollapsed = card.dataset.collapsed === "true";
        if (isCollapsed) {
            toggle.click();
            card.dataset.collapsed = "false";
        }
    });
}
// ---------- Helper: expand semua card blood data yang sedang ter-collapse :end ----------

// ---------- Helper: disable tombol delete jika hanya 1 card :begin ----------
function syncDeleteButtons(container) {
    const cards = container.querySelectorAll(BloodDataCardClass);

    cards.forEach((card) => {
        const btn = card.querySelector(".btn-delete-blood");
        if (btn) {
            // Disable jika hanya tersisa 1 card agar minimal 1 data selalu ada
            btn.disabled = cards.length <= 1;
        }
    });
}
// ---------- Helper: disable tombol delete jika hanya 1 card :end ----------

// ---------- Helper: hapus card blood data :begin ----------
function deleteBloodCard(card) {
    const container = document.getElementById(FormManualWrapper);
    if (!container) return;

    const cards = container.querySelectorAll(BloodDataCardClass);

    // Cegah penghapusan jika hanya tersisa 1 card
    if (cards.length <= 1) {
        notyf.error({ message: "Minimum is 1 blood data!" });
        return;
    }

    // Destroy semua instance TomSelect di dalam card agar tidak memory leak
    card.querySelectorAll("select").forEach((select) => {
        if (select.tomselect) {
            select.tomselect.destroy();
        }
    });

    // Hapus validasi field yang terdaftar untuk card ini
    const idx = card.dataset.idx;
    if (incomingStockForm && idx !== undefined) {
        incomingStockForm.removeBloodCardValidation(idx);
    }

    card.remove();

    // Sinkronisasi ulang tombol delete setelah card dihapus
    syncDeleteButtons(container);
}
// ---------- Helper: hapus card blood data :end ----------

// ---------- Helper: bersihkan semua card lama beserta validasinya sebelum render ulang :begin ----------
function clearBloodCards(container) {
    // agar tidak ada validasi orphan yang menghambat submit
    container.querySelectorAll(BloodDataCardClass).forEach((card) => {
        const idx = card.dataset.idx;

        // Hapus validasi field yang terdaftar untuk card ini
        if (incomingStockForm && idx !== undefined) {
            incomingStockForm.removeBloodCardValidation(idx);
        }

        // Destroy TomSelect instance di dalam card agar tidak memory leak
        card.querySelectorAll("select").forEach((select) => {
            if (select.tomselect) {
                select.tomselect.destroy();
            }
        });

        card.remove();
    });
}
// ---------- Helper: bersihkan semua card lama beserta validasinya sebelum render ulang :end ----------

// ---------- Helper: reset tampilan form manual ke state awal (loading tersembunyi, card bersih) :begin ----------
function resetManualFormState() {
    const container = document.getElementById(FormManualWrapper);
    const loadingEl = document.getElementById(LoadingRenderFormManualSelector);
    const hintEl = document.getElementById(HintFormManualSelector);

    // Bersihkan semua card lama beserta validasi dan TomSelect instance-nya
    if (container) clearBloodCards(container);

    // Sembunyikan loading spinner jika sedang tampil
    if (loadingEl) loadingEl.classList.add("d-none");

    // Sembunyikan hint jika sedang tampil
    if (hintEl) hintEl.classList.add("d-none");
}
// ---------- Helper: reset tampilan form manual ke state awal :end ----------

// ---------- Select po dari tom-select untuk form add new data :begin ----------
function SelectPO() {
    new TomSelect(ChoosePOSelector, {
        valueField: "text", // pakai text (po_number) sebagai value agar bisa langsung dipakai sebagai URL param
        labelField: "text",
        searchField: "text",
        sortField: { field: "id", direction: "asc" },
        create: false,
        preload: true,
        load: function (query, callback) {
            fetch(
                `/utility/select/purchase-order?q=${encodeURIComponent(query)}`,
            )
                .then((response) => response.json())
                .then((json) => callback(json.results))
                .catch(() => callback());
        },
        onChange: async (poNumber) => {
            if (!poNumber) {
                // Jika PO dikosongkan, reset seluruh tampilan form manual
                currentPONumber = null;
                resetManualFormState();

                // Tampilkan kembali hint saat PO dikosongkan
                const hintEl = document.getElementById(HintFormManualSelector);
                if (hintEl) hintEl.classList.remove("d-none");
                return;
            }

            currentPONumber = poNumber;

            // Hanya fetch dan render jika method yang aktif adalah manual
            if (currentMethod === "manual") {
                await fetchAndRenderBloodCards(poNumber);
            }
        },
    });
}
// ---------- Select po dari tom-select untuk form add new data :begin ----------

// ---------- Select add method dari tom-select :begin ----------
function SelectAddMethod() {
    const ts = new TomSelect(ChooseAddMethodSelector, {
        valueField: "id",
        labelField: "text",
        searchField: "text",
        sortField: { field: "id", direction: "asc" },
        create: false,
        preload: true,
        load: function (query, callback) {
            fetch(
                `/utility/select/add-incoming-stock-method?q=${encodeURIComponent(query)}`,
            )
                .then((response) => response.json())
                .then((json) => {
                    callback(json.results);
                    // Set default ke "manual" secara silent setelah data dimuat
                    ts.setValue("manual", true);
                    toggleFormMethod("manual");
                })
                .catch(() => callback());
        },
        onChange: (methodId) => {
            toggleFormMethod(methodId);
        },
    });
}
// ---------- Select add method dari tom-select :end ----------

// ---------- Fungsi untuk render card blood row dari data API :begin ----------
function renderBloodCards(orderBloodDetails) {
    const container = document.getElementById(FormManualWrapper);
    if (!container) return;

    clearBloodCards(container);

    orderBloodDetails.forEach((detail, idx) => {
        const blood_group = detail.blood_group ?? "";
        const blood_rhesus = detail.rhesus ?? "";
        const blood_component = detail.blood_component ?? "";
        const quantity = parseInt(detail.quantity ?? 0);

        const is_hiv = detail.is_hiv ?? 0;
        const is_hbsag = detail.is_hbsag ?? 0;
        const is_hcv = detail.is_hcv ?? 0;
        const is_syphilis = detail.is_syphilis ?? 0;

        // Label header: #1 AB+ WB 100 Packs
        const cardTitle = `${idx + 1}# ${blood_group} ${blood_rhesus} ${blood_component} ${quantity} Packs`;

        const card = document.createElement("div");
        card.className = "card mb-2 blood-data-card";
        card.dataset.idx = idx; // simpan idx untuk referensi validasi
        card.dataset.collapsed = "false";

        let rowsHTML = "";

        for (let i = 0; i < quantity; i++) {
            rowsHTML += `
                <tr>
                    <td>${i + 1}</td>

                    <td>
                        <input class="form-control" 
                            name="blood_data[${idx}][items][${i}][bag_number]" />
                    </td>

                    <td>
                        <select class="form-control"
                            id="blood_group_${idx}_${i}"
                            name="blood_data[${idx}][items][${i}][blood_group]"></select>
                    </td>

                    <td>
                        <select class="form-control"
                            id="blood_rhesus_${idx}_${i}"
                            name="blood_data[${idx}][items][${i}][blood_rhesus]"></select>
                    </td>

                    <td>
                        <select class="form-control"
                            id="blood_component_${idx}_${i}"
                            name="blood_data[${idx}][items][${i}][blood_component]"></select>
                    </td>

                    <td>
                        <input class="form-control"
                            name="blood_data[${idx}][items][${i}][volume]" />
                    </td>

                    <td>
                        <input class="form-control"
                            id="aftap_${idx}_${i}"
                            name="blood_data[${idx}][items][${i}][aftap_date]" />
                    </td>

                    <td>
                        <input class="form-control"
                            id="expiry_${idx}_${i}"
                            name="blood_data[${idx}][items][${i}][expiry_date]" />
                    </td>

                    <td>
                        <input class="form-control"
                            id="process_${idx}_${i}"
                            name="blood_data[${idx}][items][${i}][process_date]" />
                    </td>

                    <td><input type="checkbox" name="blood_data[${idx}][items][${i}][is_hiv]" ${is_hiv ? "checked" : ""}></td>
                    <td><input type="checkbox" name="blood_data[${idx}][items][${i}][is_hcv]" ${is_hcv ? "checked" : ""}></td>
                    <td><input type="checkbox" name="blood_data[${idx}][items][${i}][is_hbsag]" ${is_hbsag ? "checked" : ""}></td>
                    <td><input type="checkbox" name="blood_data[${idx}][items][${i}][is_syphilis]" ${is_syphilis ? "checked" : ""}></td>
                    <td><input type="checkbox" name="blood_data[${idx}][items][${i}][blood_status]" checked></td>
                </tr>
            `;
        }

        card.innerHTML = `
            <div class="card-header justify-content-between align-items-center">
                <h5 class="card-title text-capitalize mb-0">${cardTitle}</h5>
                <button class="btn btn-sm btn-soft-danger btn-delete-blood" type="button"
                    data-bs-title="Delete blood data card" data-bs-toggle="tooltip" data-bs-trigger="hover">
                    <i class="ti ti-trash fs-4"></i>
                </button>
                <div class="card-action">
                    <a class="card-action-item" data-action="card-toggle" href="#!">
                        <i class="ti ti-chevron-up"></i>
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm align-middle mb-0">
                        <thead class="align-middle">
                            <tr class="text-uppercase fs-xxs">
                                <th>No</th>
                                <th>Bag Number <span class="text-danger">*</span></th>
                                <th>Group <span class="text-danger">*</span></th>
                                <th>Rhesus <span class="text-danger">*</span></th>
                                <th>Component <span class="text-danger">*</span></th>
                                <th style="width: 6%;">Volume <span class="text-danger">*</span></th>
                                <th style="width: 8%;">Aftap <span class="text-danger">*</span></th>
                                <th style="width: 8%;">Expiry <span class="text-danger">*</span></th>
                                <th style="width: 8%;">Process <span class="text-danger">*</span></th>
                                <th>HIV?</th>
                                <th>HCV?</th>
                                <th>HbsAG?</th>
                                <th>Syphilis?</th>
                                <th>In storage?</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${rowsHTML}
                        </tbody>
                    </table>
                </div>
                
            </div>
        `;

        container.appendChild(card);

        for (let i = 0; i < quantity; i++) {
            initTomSelectWithValue(
                card.querySelector(`#blood_group_${idx}_${i}`),
                "/utility/select/blood-group",
                blood_group,
                true,
            );

            initTomSelectWithValue(
                card.querySelector(`#blood_rhesus_${idx}_${i}`),
                "/utility/select/blood-rhesus",
                blood_rhesus,
                true,
            );

            initTomSelectWithValue(
                card.querySelector(`#blood_component_${idx}_${i}`),
                "/utility/select/blood-component",
                blood_component,
                true,
            );

            initFlatpickr(card.querySelector(`#aftap_${idx}_${i}`));
            initFlatpickr(card.querySelector(`#expiry_${idx}_${i}`));
            initFlatpickr(card.querySelector(`#process_${idx}_${i}`));
        }

        // Pasang event listener tombol delete untuk card ini
        card.querySelector(".btn-delete-blood").addEventListener(
            "click",
            () => {
                deleteBloodCard(card);
            },
        );
    });

    // Collapse semua card setelah seluruh render selesai
    collapseAllBloodCards(container);

    // Sinkronisasi state tombol delete (disable jika hanya 1 card)
    syncDeleteButtons(container);

    // Refresh ikon lucide jika library tersedia di window
    if (window.lucide) {
        lucide.createIcons();
    }
}
// ---------- Fungsi untuk render card blood row dari data API :end ----------

// ---------- Fungsi fetch data order dan render card :begin ----------
async function fetchAndRenderBloodCards(poNumber) {
    resetManualFormState();

    const loadingEl = document.getElementById(LoadingRenderFormManualSelector);
    const hintEl = document.getElementById(HintFormManualSelector);

    // Null-guard: hentikan jika elemen kritis tidak ditemukan di DOM
    if (!loadingEl) {
        console.error(`Element #${LoadingRenderFormManualSelector} not found!`);
        return;
    }

    try {
        // Tampilkan loading spinner
        loadingEl.classList.remove("d-none");

        const res = await fetch(`${UrlGetOrderData}/${poNumber}`);

        if (!res.ok) throw new Error(`HTTP error! status: ${res.status}`);

        const data = await res.json();

        if (!data.order_bloods || data.order_bloods.length === 0) {
            notyf.error({ message: "No blood data found for this PO!" });
            loadingEl.classList.add("d-none");

            // Tampilkan kembali hint jika data kosong
            if (hintEl) hintEl.classList.remove("d-none");
            return;
        }

        // Beri jeda 300ms agar animasi loading selesai sebelum DOM dimanipulasi
        await new Promise((resolve) => setTimeout(resolve, 300));

        loadingEl.classList.add("d-none");

        // Tunggu satu frame agar browser selesai repaint sebelum render card dimulai
        await new Promise((resolve) => requestAnimationFrame(resolve));

        renderBloodCards(data.order_bloods);
    } catch (err) {
        // Pastikan loading selalu disembunyikan meski terjadi error
        loadingEl.classList.add("d-none");

        // Tampilkan kembali hint jika terjadi error
        if (hintEl) hintEl.classList.remove("d-none");

        notyf.error({ message: "Failed to fetch order data!" });
        console.error(err);
    }
}
// ---------- Fungsi fetch data order dan render card :end ----------

// ---------- Fungsi untuk mengelola validasi dan submit form :begin ----------
function HandleFormAddIncomingStock() {
    // Init validasi untuk field statis (PO & method)
    const formValidation = GlobalFormValidation.init(
        "#" + FormAddIncomingStockSelector,
        {
            select_purchase_order: {
                validators: {
                    notEmpty: { message: "Purchase Order is required" },
                },
            },
            select_add_data_method: {
                validators: {
                    notEmpty: { message: "Method is required" },
                },
            },
        },
    );

    // ---------- Tambah validasi notEmpty untuk semua field wajib pada card dengan idx tertentu :begin ----------
    function addBloodCardValidation(idx) {
        const fields = [
            `blood_data[${idx}][bag_number]`,
            `blood_data[${idx}][blood_group]`,
            `blood_data[${idx}][blood_rhesus]`,
            `blood_data[${idx}][blood_component]`,
            `blood_data[${idx}][volume]`,
            `blood_data[${idx}][aftap_date]`,
            `blood_data[${idx}][expiry_date]`,
            `blood_data[${idx}][process_date]`,
            `blood_data[${idx}][quantity]`,
            `blood_data[${idx}][blood_status]`,
        ];

        fields.forEach((fieldName) => {
            formValidation.addField(fieldName, {
                validators: {
                    notEmpty: { message: "This field is required" },
                },
            });
        });
    }
    // ---------- Tambah validasi notEmpty untuk semua field wajib pada card dengan idx tertentu :end ----------

    // ---------- Hapus validasi untuk semua field pada card dengan idx tertentu :begin ----------
    function removeBloodCardValidation(idx) {
        const fields = [
            `blood_data[${idx}][bag_number]`,
            `blood_data[${idx}][blood_group]`,
            `blood_data[${idx}][blood_rhesus]`,
            `blood_data[${idx}][blood_component]`,
            `blood_data[${idx}][volume]`,
            `blood_data[${idx}][aftap_date]`,
            `blood_data[${idx}][expiry_date]`,
            `blood_data[${idx}][process_date]`,
            `blood_data[${idx}][quantity]`,
            `blood_data[${idx}][blood_status]`,
        ];

        fields.forEach((fieldName) => {
            formValidation.removeField(fieldName);
        });
    }
    // ---------- Hapus validasi untuk semua field pada card dengan idx tertentu :end ----------

    // ---------- Kumpulkan semua data blood dari card yang ada di DOM :begin ----------
    function getBloodData() {
        const container = document.getElementById(FormManualWrapper);
        const cards = container.querySelectorAll(BloodDataCardClass);
        const result = [];

        cards.forEach((card) => {
            // Untuk select yang di-lock TomSelect, ambil value melalui native select element
            // TomSelect yang di-lock tetap meng-update value pada element <select> aslinya
            result.push({
                bag_number: card.querySelector('[name*="bag_number"]').value,
                blood_group: card.querySelector('[name*="blood_group"]').value,
                blood_rhesus: card.querySelector('[name*="blood_rhesus"]')
                    .value,
                blood_component: card.querySelector('[name*="blood_component"]')
                    .value,
                volume: card.querySelector('[name*="volume"]').value,
                aftap_date: card.querySelector('[name*="aftap_date"]').value,
                expiry_date: card.querySelector('[name*="expiry_date"]').value,
                process_date: card.querySelector('[name*="process_date"]')
                    .value,
                quantity: card.querySelector('[name*="quantity"]').value,
                blood_status: card.querySelector('[name*="blood_status"]')
                    .value,
                is_hiv: card.querySelector('[name*="is_hiv"]').checked ? 1 : 0,
                is_hcv: card.querySelector('[name*="is_hcv"]').checked ? 1 : 0,
                is_hbsag: card.querySelector('[name*="is_hbsag"]').checked
                    ? 1
                    : 0,
                is_syphilis: card.querySelector('[name*="is_syphilis"]').checked
                    ? 1
                    : 0,
            });
        });

        return result;
    }
    // ---------- Kumpulkan semua data blood dari card yang ada di DOM :end ----------

    // ---------- Init GlobalSubmitForm untuk handle submit dan validasi :begin ----------
    new GlobalSubmitForm({
        formId: FormAddIncomingStockSelector,
        url: UrlPostIncomingStock,
        method: "POST",
        validator: formValidation,
        beforeSubmit: (formData) => {
            if (currentMethod === "manual") {
                // ---------- Kirim hanya data manual, buang field excel :begin ----------
                formData.delete("excel_file");

                const bloodData = getBloodData();

                // Masukkan data blood ke formData dengan index yang berurutan
                bloodData.forEach((item, index) => {
                    formData.set(
                        `blood_data[${index}][bag_number]`,
                        item.bag_number,
                    );
                    formData.set(
                        `blood_data[${index}][blood_group]`,
                        item.blood_group,
                    );
                    formData.set(
                        `blood_data[${index}][blood_rhesus]`,
                        item.blood_rhesus,
                    );
                    formData.set(
                        `blood_data[${index}][blood_component]`,
                        item.blood_component,
                    );
                    formData.set(`blood_data[${index}][volume]`, item.volume);
                    formData.set(
                        `blood_data[${index}][aftap_date]`,
                        item.aftap_date,
                    );
                    formData.set(
                        `blood_data[${index}][expiry_date]`,
                        item.expiry_date,
                    );
                    formData.set(
                        `blood_data[${index}][process_date]`,
                        item.process_date,
                    );
                    formData.set(
                        `blood_data[${index}][quantity]`,
                        item.quantity,
                    );
                    formData.set(
                        `blood_data[${index}][blood_status]`,
                        item.blood_status,
                    );
                    formData.set(`blood_data[${index}][is_hiv]`, item.is_hiv);
                    formData.set(`blood_data[${index}][is_hcv]`, item.is_hcv);
                    formData.set(
                        `blood_data[${index}][is_hbsag]`,
                        item.is_hbsag,
                    );
                    formData.set(
                        `blood_data[${index}][is_syphilis]`,
                        item.is_syphilis,
                    );
                });

                // ---------- Kirim hanya data manual, buang field excel :end ----------
            } else if (currentMethod === "excel") {
                // ---------- Kirim hanya file excel, buang semua field blood_data ----------
                // Hapus semua key blood_data[*] yang mungkin tersisa di formData
                for (const key of [...formData.keys()]) {
                    if (key.startsWith("blood_data[")) {
                        formData.delete(key);
                    }
                }
            }

            return formData;
        },
        onValidationError: () => {
            // ---------- Saat validasi gagal dan method adalah manual :begin ----------
            if (currentMethod === "manual") {
                const container = document.getElementById(FormManualWrapper);

                // Expand semua card agar user bisa melihat field mana yang error
                if (container) expandAllBloodCards(container);

                // Tampilkan notifikasi error
                notyf.error({ message: "There's a field that need to fill!" });
            }
            // ---------- Saat validasi gagal dan method adalah manual :end ----------
        },
        onSuccess: () => {
            notyf.success({ message: "Incoming stock added successfully!" });

            // Bersihkan semua card dan reset state PO setelah submit berhasil
            const container = document.getElementById(FormManualWrapper);
            if (container) clearBloodCards(container);
            currentPONumber = null;
        },
        onError: (err) => {
            notyf.error({ message: "Failed to add incoming stock!" });
            console.error(err);
        },
        resetOnSuccess: true,
    });
    // ---------- Init GlobalSubmitForm untuk handle submit dan validasi :end ----------

    // Kembalikan fungsi yang perlu diakses dari luar scope ini
    return {
        formValidation,
        addBloodCardValidation,
        removeBloodCardValidation,
    };
}
// ---------- Fungsi untuk mengelola validasi dan submit form :end ----------

document.addEventListener("DOMContentLoaded", () => {
    SelectPO();
    SelectAddMethod();
    // Init form handler setelah DOM siap — hasilnya disimpan di state global
    incomingStockForm = HandleFormAddIncomingStock();
});
