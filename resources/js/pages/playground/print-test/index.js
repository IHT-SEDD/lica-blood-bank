// ---------- Global Mapping ----------
const FILE_ORIENTATIONS = {
    "blood-patient-card": "landscape",
};

// ---------- Helpers ----------
function setLoading(btn, isLoading) {
    if (isLoading) {
        btn.dataset.originalText = btn.innerHTML;
        btn.innerHTML =
            '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Loading…';
        btn.disabled = true;
    } else {
        btn.innerHTML = btn.dataset.originalText ?? btn.textContent;
        btn.disabled = false;
    }
}
function buildPrintUrl(btn) {
    const slug = btn.dataset.print;
    if (!slug) {
        notyf.error({ message: "Missing data-print attribute on button!" });
        return null;
    }
    const template = btn.dataset.previewUrl;
    return template
        ? template.replace(":print", encodeURIComponent(slug))
        : `/playground/print/preview/${encodeURIComponent(slug)}`;
}
function buildPDFUrl(btn) {
    const slug = btn.dataset.pdf;
    if (!slug) {
        notyf.error({ message: "Missing data-pdf attribute on button!" });
        return null;
    }
    const template = btn.dataset.pdfUrl;
    return template
        ? template.replace(":print", encodeURIComponent(slug))
        : `/playground/print/pdf/${encodeURIComponent(slug)}`;
}

// ---------- Fungsi print (iframe) ----------
function initPrint() {
    async function validateAndOpen(btn) {
        const url = buildPrintUrl(btn);
        if (!url) return;

        setLoading(btn, true);

        try {
            const res = await fetch(url, { method: "GET" });
            if (!res.ok) {
                const err = await res.json().catch(() => ({}));
                notyf.error({
                    message:
                        err?.message ?? `HTTP error! status: ${res.status}`,
                });
                return;
            }

            const slug = btn.dataset.print;
            const orientation = FILE_ORIENTATIONS[slug];
            let htmlText = await res.text();

            if (orientation) {
                const pageStyle = `<style>@page { size: ${orientation} !important; }</style>`;
                htmlText = htmlText.includes("</head>")
                    ? htmlText.replace("</head>", `${pageStyle}</head>`)
                    : pageStyle + htmlText;
            }

            const blob = new Blob([htmlText], { type: "text/html" });
            const blobUrl = URL.createObjectURL(blob);

            let iframe = document.getElementById("__print_iframe__");
            if (iframe) iframe.remove();

            iframe = document.createElement("iframe");
            iframe.id = "__print_iframe__";
            iframe.style.cssText =
                "position:fixed;top:0;left:0;width:0;height:0;border:none;opacity:0;pointer-events:none;";
            iframe.src = blobUrl;

            iframe.onload = () => {
                try {
                    iframe.contentWindow.focus();
                    iframe.contentWindow.print();
                } catch (err) {
                    notyf.error({ message: "Failed to open print dialog." });
                    console.error(err);
                } finally {
                    setTimeout(() => URL.revokeObjectURL(blobUrl), 10_000);
                }
            };

            document.body.appendChild(iframe);
        } catch (err) {
            console.error("[Print] Network error:", err);
            notyf.error({ message: "[Print] Network error" });
        } finally {
            setLoading(btn, false);
        }
    }

    document.body.addEventListener("click", (e) => {
        const btn = e.target.closest("[data-print]");
        if (!btn) return;
        e.preventDefault();
        validateAndOpen(btn);
    });
}

// ---------- Fungsi download PDF (anchor download) ----------
function initDownloadPDF() {
    async function validateAndDownload(btn) {
        const url = buildPDFUrl(btn);
        if (!url) return;

        setLoading(btn, true);

        try {
            const res = await fetch(url, { method: "GET" });
            if (!res.ok) {
                const err = await res.json().catch(() => ({}));
                notyf.error({
                    message:
                        err?.message ?? `HTTP error! status: ${res.status}`,
                });
                return;
            }

            const blob = await res.blob();
            const blobUrl = URL.createObjectURL(blob);
            const slug = btn.dataset.pdf;

            const anchor = document.createElement("a");
            anchor.href = blobUrl;
            anchor.download = `${slug.toUpperCase()}.pdf`;
            document.body.appendChild(anchor);
            anchor.click();
            anchor.remove();

            setTimeout(() => URL.revokeObjectURL(blobUrl), 10_000);
        } catch (err) {
            console.error("[DownloadPDF] Network error:", err);
            notyf.error({ message: "[DownloadPDF] Network error" });
        } finally {
            setLoading(btn, false);
        }
    }

    document.body.addEventListener("click", (e) => {
        const btn = e.target.closest("[data-pdf]");
        if (!btn) return;
        e.preventDefault();
        validateAndDownload(btn);
    });
}

// -----------------------------------------------------------------------
// DOMContentLoaded
// -----------------------------------------------------------------------
document.addEventListener("DOMContentLoaded", () => {
    initPrint();
    initDownloadPDF();
});
