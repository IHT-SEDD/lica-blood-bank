import {
    getPrintNotaBtn,
    SelectorBtnPrintIncompLetter,
    SelectorBtnPrintResult,
    SelectorBtnPrintResultPerBlood,
} from "./button-state";

// ---------- HELPERS ----------
export async function handlePrint(
    url,
    { onDone = null } = {},
    printType = "preview",
) {
    showPageLoading();
    try {
        const res = await fetch(url, {
            method: "GET",
        });
        if (!res.ok) {
            const err = await res.json().catch(() => ({}));
            notyf.error({
                message: err?.message ?? `HTTP error! status: ${res.status}`,
            });
            hidePageLoading();
            return;
        }

        let htmlText = await res.text();
        const blob = new Blob([htmlText], { type: "text/html" });
        const blobUrl = URL.createObjectURL(blob);

        let iframe = document.getElementById(`__print_${printType}_iframe__`);
        if (iframe) iframe.remove();
        iframe = document.createElement("iframe");
        iframe.id = `__print_${printType}_iframe__`;
        iframe.style.cssText =
            "position:fixed;top:0;left:0;width:0;height:0;border:none;opacity:0;pointer-events:none;";
        iframe.src = blobUrl;

        iframe.onload = () => {
            try {
                iframe.contentWindow.focus();
                iframe.contentWindow.print();
            } catch (printErr) {
                notyf.error({
                    message:
                        "Gagal membuka print browser! Silakan coba mendownload file nya",
                });
                console.error(printErr);
            } finally {
                hidePageLoading();
                if (typeof onDone === "function") onDone();
                setTimeout(() => window.URL.revokeObjectURL(blobUrl), 10_000);
            }
        };
        document.body.appendChild(iframe);
    } catch (err) {
        console.error("[Print] Network error:", err);
        notyf.error({
            message: "Gagal mengambil data.",
        });
        hidePageLoading();
    }
}

// ---------- Fungsi print surat incompatible ----------
export function initPrintIncompatibleLetter({
    PRINT_URL,
    onDone = null,
    printType = "preview",
}) {
    $(document)
        .off("click", "#" + SelectorBtnPrintIncompLetter)
        .on("click", "#" + SelectorBtnPrintIncompLetter, function (e) {
            e.preventDefault();
            handlePrint(
                `${PRINT_URL}/incompatible-letter/${window.currentTransfusionPublicId}`,
                { onDone },
                printType,
            );
        });
}

// ---------- Fungsi print hasil crossmatch ----------
export function initPrintCrossmatchResult({
    PRINT_URL,
    onDone = null,
    printType = "preview",
}) {
    $(document)
        .off("click", "#" + SelectorBtnPrintResult)
        .on("click", "#" + SelectorBtnPrintResult, function (e) {
            e.preventDefault();
            handlePrint(
                `${PRINT_URL}/crossmatch-result/${window.currentTransfusionPublicId}`,
                { onDone },
                printType,
            );
        });
}

// ---------- Fungsi print hasil crossmatch per labu darah ----------
export function initPrintCrossmatchResultPerBlood({
    PRINT_URL,
    onDone = null,
    printType = "preview",
    detailId,
}) {
    $(document)
        .off("click", "." + SelectorBtnPrintResultPerBlood)
        .on("click", "." + SelectorBtnPrintResultPerBlood, function (e) {
            e.preventDefault();
            const detailId = $(this).data("public-id");
            if (!detailId) {
                notyf.error({
                    message:
                        "ID detail tidak ditemukan untuk mencetak hasil per labu darah.",
                });
                return;
            }

            handlePrint(
                `${PRINT_URL}/crossmatch-result/${window.currentTransfusionPublicId}/${detailId}`,
                { onDone },
                printType,
            );
        });
}

// ---------- Fungsi print nota ----------
export function initPrintNota({
    PRINT_URL,
    onDone = null,
    printType = "preview",
}) {
    $(document)
        .off("click", "#btn-print-nota")
        .on("click", "#btn-print-nota", function (e) {
            e.preventDefault();
            const id = this.dataset.id;
            if (!id) return;
            handlePrint(`${PRINT_URL}/nota/${id}`, { onDone }, printType);
        });
}
