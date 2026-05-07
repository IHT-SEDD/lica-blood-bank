import { OrderStatus } from "./utility";
import { setHidden } from "../../../../utility/ui";

// ---------- Global variable untuk memudahkan penyesuaian :begin ----------
// Toolbar button IDs
export const ToolbarWrapper = "toolbar_wrapper";

const BtnPrintPO = "print_po_btn";
const BtnDownloadPO = "download_po_btn";
const BtnGeneratePO = "generate_po_btn";
const BtnPreviewPO = "preview_po_btn";
const BtnDone = "update_to_done_btn";
const BtnEditOrder = "edit_order_btn";
const BtnCancelEditOrder = "cancel_edit_order_btn";
const BtnSubmitChanges = "submit_order_btn";

// URLS
const UrlGeneratePO = "/inventory/history-order/po-file/generate";
const UrlPreviewPO = "/inventory/history-order/po-file/preview";
const UrlDownloadPO = "/inventory/history-order/po-file/download";
const UrlPrintPO = "/inventory/history-order/po-file/print";

// ---------- Global variable untuk memudahkan penyesuaian :end ----------

// ---------- Global Scope :begin ----------
// Export URLS
export const ToolbarButtonUrls = {
    UrlGeneratePO,
    UrlPreviewPO,
    UrlDownloadPO,
    UrlPrintPO,
};

// Export Buttons
export const ToolbarButtons = {
    BtnPrintPO,
    BtnDownloadPO,
    BtnGeneratePO,
    BtnPreviewPO,
    BtnDone,
    BtnEditOrder,
    BtnCancelEditOrder,
    BtnSubmitChanges,
};
// ---------- Global Scope :begin ----------

// ---------- Handler tombol-tombol pada toolbar ----------
export function ToolbarState(order) {
    const hasPOFile = !!order?.po_file_path;
    const isDone = OrderStatus.isDone(order?.status);
    const isDraft = OrderStatus.isDraft(order?.status);
    const isOrderCreated = OrderStatus.isOrderCreated(order?.status);
    const isDeleted = !!order?.deleted_at;

    // Print & Download PO: tampil kalau ada file PO
    setHidden(BtnPrintPO, !hasPOFile);
    setHidden(BtnDownloadPO, !hasPOFile);

    // Generate PO: tampil kalau BELUM ada file PO
    setHidden(BtnGeneratePO, hasPOFile);
    const generateBtn = document.getElementById(BtnGeneratePO);
    if (generateBtn) generateBtn.disabled = isDraft;

    // Done: sembunyikan kalau status sudah done
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
    // ---------- Handler tombol Generate PO File ----------
    GeneratePoFile(context) {
        const generateBtn = document.getElementById(BtnGeneratePO);
        if (!generateBtn) return;

        generateBtn.addEventListener("click", async () => {
            const currentOrderData = context.getCurrentOrderData();

            if (!currentOrderData?.order?.po_number) {
                notyf.error({ message: "PO Number not found!" });
                return;
            }

            const poNumber = currentOrderData.order.po_number;
            generateBtn.disabled = true;
            showPageLoading();

            try {
                const res = await fetch(`${UrlGeneratePO}/${poNumber}`, {
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

                const blob = await res.blob();
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement("a");
                a.href = url;
                a.download = `PO_FILE-${poNumber}.pdf`;
                document.body.appendChild(a);
                a.click();
                a.remove();
                window.URL.revokeObjectURL(url);

                notyf.success({
                    message: "PO File generated and downloaded successfully!",
                });

                // Refresh data agar toolbar update
                const data = await context.fetchDataDetailOrder();
                context.setCurrentOrderData(data);
                ToolbarState(data?.order);
            } catch (err) {
                notyf.error({
                    message: err.message ?? "Failed to generate PO File!",
                });
                console.error(err);
            } finally {
                hidePageLoading();
                generateBtn.disabled = false;
            }
        });
    },

    // ---------- Handler tombol Preview PO File ----------
    PreviewPoFile(context) {
        const previewBtn = document.getElementById(BtnPreviewPO);
        if (!previewBtn) return;

        previewBtn.addEventListener("click", () => {
            const currentOrderData = context.getCurrentOrderData();

            if (!currentOrderData?.order?.po_number) {
                notyf.error({ message: "PO Number not found!" });
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
            const poFilePath = currentOrderData?.order?.po_file_path;
            const poNumber = currentOrderData?.order?.po_number;

            if (!poFilePath) {
                notyf.error({
                    message:
                        "PO File not found! Please generate the PO File first.",
                });
                return;
            }

            if (!poNumber) {
                notyf.error({ message: "PO Number not found!" });
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

                notyf.success({ message: "PO File downloaded successfully!" });
            } catch (err) {
                notyf.error({
                    message: err.message ?? "Failed to download PO File!",
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
            const poFilePath = currentOrderData?.order?.po_file_path;
            const poNumber = currentOrderData?.order?.po_number;

            if (!poFilePath) {
                notyf.error({
                    message:
                        "PO File not found! Please generate the PO File first.",
                });
                return;
            }

            if (!poNumber) {
                notyf.error({ message: "PO Number not found!" });
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

                const blob = await res.blob();
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
                notyf.error({
                    message: err.message ?? "Failed to print PO File!",
                });
                console.error(err);
            } finally {
                hidePageLoading();
                printBtn.disabled = false;
            }
        });
    },
};
