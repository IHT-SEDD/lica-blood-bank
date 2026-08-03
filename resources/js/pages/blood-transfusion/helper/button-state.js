// ---------- Selector Constants ----------
export const SelectorBtnCheckin = "btn-checkin-lab";
export const SelectorBtnCrossmatchSelesai = "btn-test-done";
export const SelectorBtnPrintNota = "btn-print-nota";
export const SelectorBtnPrintResult = "btn-print-result";
export const SelectorBtnComplete = "btn-complete-transaction";
export const SelectorBtnSendResult = "btn-send-result";
export const SelectorBtnPrintResultPerBlood = "btn-print-result-per-blood";
export const SelectorBtnPrintBarcodePerBlood = "btn-print-barcode-per-blood";
export const SelectorBtnDeletePerBlood = "btn-delete-per-blood";
export const SelectorBtnPrintIncompLetter = "btn-print-incompletter";

export const SelectorBtnRelease = "btn-release-blood-pack";
export const SelectorBtnUnrelease = "btn-unrelease-blood-pack";
export const SelectorBtnReleaseAll = "btn-release-all-blood-pack";
export const SelectorBtnAccept = "btn-accept-blood-pack";
export const SelectorBtnConfirm = "confirm_action";
export const SelectorBtnConfimDelete = "confirm_delete_blood_pack";
export const SelectorBtnHold = "btn-hold-blood-pack";
export const SelectorBtnEditBloodPack = "btn-edit-blood-pack";

// ---------- DOM Getters ----------
export const getCheckinBtn = () => document.getElementById(SelectorBtnCheckin);
export const getCompleteBtn = () =>
    document.getElementById(SelectorBtnComplete);
export const getSendResultBtn = () =>
    document.getElementById(SelectorBtnSendResult);
export const getCrossmatchSelesaiBtn = () =>
    document.getElementById(SelectorBtnCrossmatchSelesai);
export const getPrintNotaBtn = () =>
    document.getElementById(SelectorBtnPrintNota);

/**
 * ---------- Cek apakah transaksi sedang aktif sudah punya lab number ----------
 * Membaca dari window.currentTransfusionLabNumber yang di-set saat
 * updatePatientDetailUI() dipanggil.
 *
 * @returns {boolean}
 */
export function hasLabNumber() {
    const labNumber = window.currentTransfusionLabNumber;
    return Boolean(
        labNumber?.toString().trim() && labNumber.toString().trim() !== "-",
    );
}

/**
 * ---------- Generic Button State Applicator ----------
 * Menerapkan state (show/hide/enable/disable/toggle) pada sekumpulan button
 * berdasarkan konfigurasi `buttons` dan data konteks.
 *
 * @param {string} tableID - ID/selector tabel terkait (untuk konteks onReady)
 * @param {object} data - Data baris/konteks yang dipakai untuk evaluasi conditions
 * @param {object} options - { buttons: [], onReady: fn, ...restOptions }
 */
export function applyButtonState(tableID, data, options = {}) {
    const { buttons = [], onReady = null, ...restOptions } = options;
    if (!data) return;

    buttons.forEach(({ selector, conditions, action = "show", className }) => {
        const el =
            document.getElementById(selector) ??
            document.querySelector(selector);
        if (!el) return;

        const conditionMet =
            typeof conditions === "function"
                ? conditions(data, { tableID, ...restOptions })
                : true;

        console.log(
            `[${selector}]`,
            "action:",
            action,
            "matched:",
            conditionMet,
        );

        switch (action) {
            case "show":
                conditionMet
                    ? el.classList.remove("d-none")
                    : el.classList.add("d-none");
                break;
            case "hide":
                conditionMet
                    ? el.classList.add("d-none")
                    : el.classList.remove("d-none");
                break;
            case "enable":
                el.disabled = !conditionMet;
                break;
            case "disable":
                el.disabled = conditionMet;
                break;
            case "toggle":
                el.classList.toggle(className ?? "d-none", !conditionMet);
                break;
        }
    });

    if (typeof onReady === "function") {
        onReady(data, { tableID, ...restOptions });
    }
}

/**
 * ---------- Konfigurasi Button Detail Pasien ----------
 * Dipakai oleh updatePatientDetailUI() di index.js melalui applyButtonState().
 *
 * @param {boolean} hasLabNumber - Apakah transaksi sudah punya lab number
 * @param {boolean} isCompleted - Apakah transaksi sudah selesai (status finished)
 * @returns {Array} konfigurasi buttons untuk applyButtonState
 */
export function getPatientDetailButtonConfig(
    hasLabNumber,
    isCompleted,
    isCanceled,
) {
    return [
        // btn-checkin-lab: tampil jika belum ada lab number
        {
            selector: SelectorBtnCheckin,
            action: "show",
            conditions: () => !hasLabNumber && !isCanceled,
        },
        // btn-complete-transaction
        {
            selector: SelectorBtnComplete,
            action: "hide",
            conditions: () => !isCompleted || !hasLabNumber || isCanceled,
        },
        {
            selector: SelectorBtnComplete,
            action: "show",
            conditions: () => !isCompleted && hasLabNumber && !isCanceled,
        },
        {
            selector: SelectorBtnComplete,
            action: "disable",
            conditions: () => isCanceled,
        },
        // btn-send-result
        {
            selector: SelectorBtnSendResult,
            action: "hide",
            conditions: () => !isCompleted || !hasLabNumber || isCanceled,
        },
        {
            selector: SelectorBtnSendResult,
            action: "show",
            conditions: () => isCompleted && hasLabNumber && !isCanceled,
        },
        {
            selector: SelectorBtnSendResult,
            action: "disable",
            conditions: () => isCanceled,
        },
        // btn-print-nota: tampil & enable jika sudah ada lab number
        {
            selector: SelectorBtnPrintNota,
            action: "show",
            conditions: () => hasLabNumber,
        },
        {
            selector: SelectorBtnPrintNota,
            action: "enable",
            conditions: () => hasLabNumber,
        },
        // btn-edit-blood-pack: tampil & enable jika sudah ada lab number
        {
            selector: SelectorBtnEditBloodPack,
            action: "show",
            conditions: () => hasLabNumber && !isCanceled && !isCompleted,
        },
        {
            selector: SelectorBtnEditBloodPack,
            action: "enable",
            conditions: () => hasLabNumber && !isCanceled && !isCompleted,
        },
        {
            selector: SelectorBtnEditBloodPack,
            action: "hide",
            conditions: () => isCanceled || isCompleted,
        },
        {
            selector: SelectorBtnEditBloodPack,
            action: "disable",
            conditions: () => isCanceled || isCompleted,
        },
        // // btn-release-all-blood-pack: tampil jika sudah ada lab number
        // {
        //     selector: SelectorBtnReleaseAll,
        //     action: "show",
        //     conditions: () => hasLabNumber,
        // },
        // // btn-print-result: tampil jika sudah ada lab number
        // {
        //     selector: SelectorBtnPrintResult,
        //     action: "show",
        //     conditions: () => hasLabNumber,
        // },
    ];
}

/**
 * ---------- Evaluasi State dari List Bag Request ----------
 * Menghitung flag-flag yang dibutuhkan untuk menentukan disabled state
 * pada btn-complete-transaction, btn-release-all-blood-pack, btn-print-result.
 *
 * @param {Array} bagData - array data bag request (json.data dari datatable)
 * @returns {{allHaveCrossmatch: boolean, bloodReleased: boolean, hasUnapprovedIncompatible: boolean}}
 */
export function evaluateBagListState(bagData = []) {
    const allHaveCrossmatch =
        bagData.length > 0 &&
        bagData.every(
            (bag) =>
                bag.crossmatch_result &&
                bag.crossmatch_result.toString().trim() !== "",
        );

    const bloodReleased =
        bagData.length > 0 &&
        bagData.every(
            (bag) =>
                bag.blood_release_status === 1 ||
                ["taken_out", "used"].includes(
                    bag.bag_status
                        ? bag.bag_status
                        : (bag.row_data?.blood_stock_status ?? null),
                ),
        );

    const hasUnapprovedIncompatible = bagData.some(
        (bag) =>
            bag.crossmatch_result?.toString().toLowerCase() ===
                "incompatible" &&
            Number(bag.is_approval_incompatible) !== 1 &&
            bag.blood_release_status === 1,
    );

    return { allHaveCrossmatch, bloodReleased, hasUnapprovedIncompatible };
}

/**
 * ---------- Terapkan State Button berdasarkan hasil evaluateBagListState ----------
 * Mengatur disabled/hidden state untuk:
 * - btn-complete-transaction (disable)
 * - btn-release-all-blood-pack (hide)
 * - btn-print-result (disable)
 *
 * @param {{allHaveCrossmatch: boolean, bloodReleased: boolean, hasUnapprovedIncompatible: boolean}} state
 * @param {object} [options]
 * @param {boolean} [options.labNumberExists] - Apakah transaksi sudah punya lab number.
 *        Default: hasil dari hasLabNumber() (window.currentTransfusionLabNumber).
 */
export function applyBagListButtonState(
    { allHaveCrossmatch, bloodReleased, hasUnapprovedIncompatible },
    { labNumberExists = hasLabNumber() } = {},
) {
    const btnComplete = document.getElementById(SelectorBtnComplete);
    if (btnComplete) {
        btnComplete.disabled =
            !allHaveCrossmatch || !bloodReleased || hasUnapprovedIncompatible;
    }
    const btnSendResult = document.getElementById(SelectorBtnSendResult);
    if (btnSendResult) {
        btnSendResult.disabled =
            !allHaveCrossmatch || !bloodReleased || hasUnapprovedIncompatible;
    }

    const btnReleaseAll = document.getElementById(SelectorBtnReleaseAll);
    if (btnReleaseAll) {
        const shouldHide =
            !allHaveCrossmatch ||
            bloodReleased ||
            hasUnapprovedIncompatible ||
            !labNumberExists;
        btnReleaseAll.classList.toggle("d-none", shouldHide);
    }

    const btnPrintResult = document.getElementById(SelectorBtnPrintResult);
    if (btnPrintResult) {
        btnPrintResult.disabled = !allHaveCrossmatch;
    }
}

/**
 * ---------- Update Workflow Buttons State (per-bag) ----------
 * Mengatur show/hide untuk:
 * - btn-hold-blood-pack
 * - btn-release-blood-pack
 * - btn-unrelease-blood-pack
 * - btn-accept-blood-pack
 * - btn-print-incompletter
 *
 * berdasarkan crossmatch_result & bag_status dari bag yang sedang aktif.
 *
 * @param {object|null} data - window.currentBagData
 */
export function updateWorkflowButtonsState(data) {
    const btnHold = document.getElementById(SelectorBtnHold);
    const btnRelease = document.getElementById(SelectorBtnRelease);
    const btnUnrelease = document.getElementById(SelectorBtnUnrelease);
    const btnAccept = document.getElementById(SelectorBtnAccept);
    const btnPrintIncomp = document.getElementById(
        SelectorBtnPrintIncompLetter,
    );

    const showButtons = (...btns) =>
        btns.forEach((btn) => btn?.classList.remove("d-none"));
    const hideButtons = (...btns) =>
        btns.forEach((btn) => btn?.classList.add("d-none"));

    hideButtons(btnHold, btnRelease, btnUnrelease, btnAccept, btnPrintIncomp);

    if (!data || !data.crossmatch_result) return;

    const {
        crossmatch_result,
        is_print_incompatible_letter,
        blood_stock_status,
        is_approval_incompatible,
    } = data.row_data;

    const bagStatus = data.bag_status ?? blood_stock_status ?? null;

    if (crossmatch_result === "Incompatible") {
        if (bagStatus === "in_use") {
            // Sudah print incompatible letter tetapi belum approve incompatible
            if (is_print_incompatible_letter && !is_approval_incompatible) {
                showButtons(btnAccept);
            }
            // Sudah approve incompatible
            if (is_approval_incompatible) {
                showButtons(btnRelease);
            }

            showButtons(btnHold, btnUnrelease);
        }

        if (bagStatus === "hold") {
            hideButtons(btnHold);
            showButtons(btnPrintIncomp, btnUnrelease);

            // Sudah print incompatible letter
            if (is_print_incompatible_letter) {
                showButtons(btnAccept, btnUnrelease);
            }

            // Sudah approve incompatible
            if (is_approval_incompatible) {
                showButtons(btnUnrelease, btnRelease);
                hideButtons(btnAccept);
            }
        }
    } else if (crossmatch_result === "Compatible") {
        if (bagStatus === "in_use") {
            showButtons(btnHold, btnRelease, btnUnrelease);
        }

        if (bagStatus === "hold") {
            hideButtons(btnHold);
            showButtons(btnRelease, btnUnrelease);
        }
    }
}

/**
 * ---------- Update Done Button State (btn-test-done) ----------
 * Menentukan apakah tombol "selesai test" ditampilkan, berdasarkan apakah
 * semua baris pada tabel test sudah memiliki hasil.
 */
export function updateDoneButtonState() {
    const btn = getCrossmatchSelesaiBtn();
    if (!btn) return;

    const table = document.querySelector("#list-test-table");
    const rows = table?.querySelectorAll("tbody tr") ?? [];

    // Sembunyikan jika salah satu kondisi early-exit terpenuhi
    const shouldHide =
        !window.currentBagDetailPublicId ||
        (window.currentBagCrossmatchResult &&
            window.currentBagCrossmatchResult.toString().trim() !== "") ||
        !table ||
        rows.length === 0;

    if (shouldHide) {
        btn.classList.add("d-none");
        return;
    }

    // Tambahkan {namatest}|{namakomponen} untuk pengecualian
    const OPTIONAL_CROSSMATCH = ["mayor|tc", "minor|tc", "auto control|tc"];

    // Minimal 2 test harus memiliki hasil
    let filledCount = 0;
    rows.forEach((row) => {
        const resultSelect = row.querySelector(".select-test-result");
        if (!resultSelect) return;

        // console.log(
        //     resultSelect.dataset.testName?.toLowerCase() +
        //         "|" +
        //         resultSelect.dataset.component?.toLowerCase(),
        // );

        // Uncomment jika ada pengecualian component dan test
        // const testName = resultSelect.dataset.testName?.toLowerCase() ?? "";
        // const component = resultSelect.dataset.component?.toLowerCase() ?? "";
        // const key = `${testName}|${component}`;
        // if (OPTIONAL_CROSSMATCH.includes(key)) {
        //     return;
        // }

        if (resultSelect.value?.trim()) {
            filledCount++;
        }
    });
    btn.classList.toggle("d-none", filledCount < 2);

    // Semua tes harus memiliki hasil kecuali yang optional
    // let allComplete = true;
    // rows.forEach((row) => {
    //     const resultSelect = row.querySelector(".select-test-result");
    //     if (!resultSelect) {
    //         allComplete = false;
    //         return;
    //     }
    //     const isOptional =
    //         resultSelect.dataset.testName?.toLowerCase() === "mayor" &&
    //         resultSelect.dataset.component?.toLowerCase() === "tc";
    //     if (!isOptional && !resultSelect.value?.trim()) {
    //         allComplete = false;
    //     }
    // });

    // btn.classList.toggle("d-none", !allComplete);
}
