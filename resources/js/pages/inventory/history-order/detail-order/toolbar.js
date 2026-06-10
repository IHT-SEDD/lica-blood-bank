import { OrderStatus } from "../../../../utility/config/status-config";
import { setHidden } from "../../../../utility/ui";

// ---------- Global variable untuk memudahkan penyesuaian ----------
export const ToolbarWrapper = "toolbar_wrapper";

const BtnPrintPO = "print_po_btn";
const BtnDownloadPO = "download_po_btn";
const BtnPreviewPO = "preview_po_btn";
const BtnDone = "update_to_done_btn";
const BtnEditOrder = "edit_order_btn";
const BtnCancelEditOrder = "cancel_edit_order_btn";
const BtnSubmitChanges = "submit_order_btn";

// URLS
const UrlPreviewPO = "/inventory/history-order/po-file/preview";
const UrlDownloadPO = "/inventory/history-order/po-file/download";
const UrlPrintPO = "/inventory/history-order/po-file/print";
const UrlSetOrderToDone = "/inventory/history-order/detail/set-done";

// ---------- Global Scope ----------
// Export URLS
export const ToolbarButtonUrls = {
    UrlPreviewPO,
    UrlDownloadPO,
    UrlPrintPO,
};
// Export Buttons
export const ToolbarButtons = {
    BtnPrintPO,
    BtnDownloadPO,
    BtnPreviewPO,
    BtnDone,
    BtnEditOrder,
    BtnCancelEditOrder,
    BtnSubmitChanges,
};

// ---------- Handler tombol-tombol pada toolbar ----------
export function ToolbarState(order) {
    const hasPOFile = !!order?.po_file_path;
    const isDone = OrderStatus.isDone(order?.status);
    const isDraft = OrderStatus.isDraft(order?.status);
    const isOrderCreated = OrderStatus.isOrderCreated(order?.status);
    const isDeleted = !!order?.deleted_at;

    // Print menghilang jika order dihapus
    setHidden(BtnPrintPO, isDeleted);
    // Download PDF PO menghilang jika order dihapus
    setHidden(BtnDownloadPO, isDeleted);
    // Done menghilang jika order sudah done
    setHidden(BtnDone, isDone);

    // Edit Order: hanya aktif jika draft / order_created dan tidak deleted
    const editBtn = document.getElementById(BtnEditOrder);
    const canEdit = (isDraft || isOrderCreated) && !isDeleted;
    if (editBtn) editBtn.disabled = !canEdit;

    // Submit Changes: selalu tersembunyi saat awal (toggle lewat HandleEditOrderBtn)
    setHidden(BtnSubmitChanges, true);
    setHidden(BtnCancelEditOrder, true);

    const toolbarEl = document.getElementById(ToolbarWrapper);
    if (toolbarEl) {
        const excludedFromCount = [
            BtnEditOrder,
            BtnSubmitChanges,
            BtnCancelEditOrder,
        ];

        const hasVisibleBtn = Array.from(
            toolbarEl.querySelectorAll("button[id]"),
        ).some(
            (btn) =>
                !excludedFromCount.includes(btn.id) &&
                !btn.classList.contains("d-none"),
        );

        const editOrderUsable = !!order && !isDeleted;
        const shouldShow = hasVisibleBtn || editOrderUsable;

        toolbarEl.classList.toggle("d-none", !shouldShow);
    }
}

export const ToolbarHandler = {
    // ---------- Handler tombol Preview PO File ----------
    PreviewPoFile(context) {
        const previewBtn = document.getElementById(BtnPreviewPO);
        if (!previewBtn) return;

        previewBtn.addEventListener("click", () => {
            const currentOrderData = context.getCurrentOrderData();

            if (!currentOrderData?.order?.po_number) {
                notyf.error({ message: "Nomor PO tidak boleh kosong!" });
                return;
            }

            const poNumber = currentOrderData.order.po_number;
            window.open(`${UrlPreviewPO}/${poNumber}`, "_blank");
        });
    },

    // ---------- Handler tombol Download PO File ----------
    DownloadPoFile(context) {
        const downloadBtn = document.getElementById(BtnDownloadPO);
        if (!downloadBtn) return;

        downloadBtn.addEventListener("click", async () => {
            const currentOrderData = context.getCurrentOrderData();
            const poNumber = currentOrderData?.order?.po_number;
            if (!poNumber) {
                notyf.error({ message: "Nomor PO tidak boleh kosong!" });
                return;
            }

            downloadBtn.disabled = true;
            showPageLoading();

            try {
                const res = await fetch(`${UrlDownloadPO}/${poNumber}`, {
                    method: "GET",
                });
                if (!res.ok) {
                    const err = await res.json().catch(() => ({}));
                    throw new Error(
                        err?.message ?? `HTTP error! status: ${res.status}`,
                    );
                }

                const blob = await res.blob();
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement("a");
                a.href = url;
                a.download = `PO_FILE-${poNumber}.pdf`;
                document.body.appendChild(a);
                a.click();
                a.remove();
                window.URL.revokeObjectURL(url);

                notyf.success({ message: "File PO berhasil didownload!" });
                await context.refreshPageContent();
            } catch (err) {
                notyf.error({
                    message: err.message ?? "File PO gagal didownload!",
                });
                console.error(err);
            } finally {
                hidePageLoading();
                downloadBtn.disabled = false;
            }
        });
    },

    // ---------- Handler tombol Print PO File ----------
    PrintPoFile(context) {
        const printBtn = document.getElementById(BtnPrintPO);
        if (!printBtn) return;

        printBtn.addEventListener("click", async () => {
            const currentOrderData = context.getCurrentOrderData();
            const poNumber = currentOrderData?.order?.po_number;
            if (!poNumber) {
                notyf.error({ message: "Nomor PO tidak boleh kosong!" });
                return;
            }

            printBtn.disabled = true;
            showPageLoading();

            try {
                const res = await fetch(`${UrlPrintPO}/${poNumber}`, {
                    method: "GET",
                });
                if (!res.ok) {
                    const err = await res.json().catch(() => ({}));
                    throw new Error(
                        err?.message ?? `HTTP error! status: ${res.status}`,
                    );
                }

                let htmlText = await res.text();
                const blob = new Blob([htmlText], { type: "text/html" });
                const blobUrl = window.URL.createObjectURL(blob);

                let iframe = document.getElementById("__print_po_iframe__");
                if (iframe) iframe.remove();

                iframe = document.createElement("iframe");
                iframe.id = "__print_po_iframe__";
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
                        setTimeout(
                            () => window.URL.revokeObjectURL(blobUrl),
                            10_000,
                        );
                    }
                };

                document.body.appendChild(iframe);
                await context.refreshPageContent();
            } catch (err) {
                notyf.error({
                    message: err.message ?? "Gagal mencetak file PO!",
                });
                console.error(err);
            } finally {
                hidePageLoading();
                printBtn.disabled = false;
            }
        });
    },

    // ---------- Handler tombol Set Order to Done ----------
    SetOrderToDone(context) {
        const setToDoneBtn = document.getElementById(BtnDone);
        if (!setToDoneBtn) return;

        setToDoneBtn.addEventListener("click", async () => {
            const currentOrderData = context.getCurrentOrderData();

            if (!currentOrderData?.order?.po_number) {
                notyf.error({ message: "Nomor PO tidak boleh kosong!" });
                return;
            }

            const poNumber = currentOrderData.order.po_number;
            setToDoneBtn.disabled = true;
            showPageLoading();

            try {
                const res = await fetch(`${UrlSetOrderToDone}/${poNumber}`, {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": document
                            .querySelector('meta[name="csrf-token"]')
                            ?.getAttribute("content"),
                    },
                });

                if (!res.ok) {
                    const err = await res.json().catch(() => ({}));
                    throw new Error(
                        err?.message ?? `HTTP error! status: ${res.status}`,
                    );
                }

                notyf.success({
                    message: "Permintaan darah berhasil diselesaikan!",
                });

                // Refresh data agar toolbar update
                const data = await context.fetchDataDetailOrder();
                context.setCurrentOrderData(data);
                ToolbarState(data?.order);
            } catch (err) {
                notyf.error({
                    message:
                        err.message ?? "Permintaan darah gagal diselesaikan!",
                });
                console.error(err);
            } finally {
                hidePageLoading();
                setToDoneBtn.disabled = false;
            }
        });
    },
};
