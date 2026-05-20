// -----------------------------------------------------------------------
// Print Preview Handler
// -----------------------------------------------------------------------
function initPrintPreview() {
    const PRINT_ORIENTATIONS = {
        "blood-patient-card": "landscape",
    };

    function setLoading(btn, isLoading) {
        if (isLoading) {
            btn.dataset.originalText = btn.innerHTML;
            btn.innerHTML =
                '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Loading…';
            btn.disabled = true;
        } else {
            btn.innerHTML = btn.dataset.originalText ?? "Print Preview";
            btn.disabled = false;
        }
    }

    function buildPreviewUrl(btn) {
        const printSlug = btn.dataset.print;

        if (!printSlug) {
            notyf.error({ message: "Missing data-print attribute on button!" });
            return null;
        }

        const template = btn.dataset.previewUrl;

        if (template) {
            return template.replace(":print", encodeURIComponent(printSlug));
        }

        return `/testing/preview/${encodeURIComponent(printSlug)}`;
    }

    async function validateAndOpen(btn) {
        const url = buildPreviewUrl(btn);
        if (!url) {
            notyf.error({ message: "Missing URL print preview!" });
            return;
        }

        setLoading(btn, true);

        try {
            const res = await fetch(url, {
                method: "GET",
            });
            if (!res.ok) {
                const err = await res.json().catch(() => ({}));
                notyf.error({
                    message:
                        err?.message ?? `HTTP error! status: ${res.status}`,
                });
                return;
            }

            const printSlug = btn.dataset.print;
            const orientation = PRINT_ORIENTATIONS[printSlug];

            let htmlText = await res.text();
            if (orientation) {
                const pageStyle = `<style>@page { size: ${orientation} !important; }</style>`;
                htmlText = htmlText.includes("</head>")
                    ? htmlText.replace("</head>", `${pageStyle}</head>`)
                    : pageStyle + htmlText;
            }

            const blob = await new Blob([htmlText], { type: "text/html" });
            const blobUrl = window.URL.createObjectURL(blob);

            let iframe = document.getElementById("__print_preview_iframe__");
            if (iframe) iframe.remove();

            iframe = document.createElement("iframe");
            iframe.id = "__print_preview_iframe__";
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
                            "Failed to open print dialog. Try downloading instead.",
                    });
                    console.error(printErr);
                } finally {
                    setTimeout(
                        () => window.URL.revokeObjectURL(blobUrl),
                        10_000,
                    );
                }
            };

            document.body.appendChild(iframe);
        } catch (err) {
            console.error("[PrintPreview] Network error:", err);
            notyf.error({
                message: "[PrintPreview] Network error",
            });
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

// -----------------------------------------------------------------------
// DOMContentLoaded
// -----------------------------------------------------------------------
document.addEventListener("DOMContentLoaded", () => {
    initPrintPreview();
});
