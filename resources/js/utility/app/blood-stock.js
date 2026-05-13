export async function initBloodStockStatusLabel() {
    if (!window.location.pathname.startsWith("/inventory")) return;

    const warningIcon = document.querySelector("#warning_stock_alert_icon");
    const dangerIcon = document.querySelector("#danger_stock_alert_icon");
    if (!warningIcon || !dangerIcon) return;

    try {
        const response = await fetch("/inventory/blood-stock/status/label", {
            method: "GET",
            headers: { "Content-Type": "application/json" },
        });

        const result = await response.json();
        if (!response.ok)
            throw new Error(result.message || "Failed to fetch data");

        const { is_danger, is_warning } = result.data;
        warningIcon.classList.toggle("d-none", !is_warning || is_danger);
        dangerIcon.classList.toggle("d-none", !is_danger);
    } catch (error) {
        console.error("Blood Stock Status Error:", error);
        notyf.error({ message: "Failed to load blood stock status!" });
    }
}
