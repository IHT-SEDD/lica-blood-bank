export function initPortletCard() {
    $('[data-action="card-close"]').on("click", function (e) {
        e.preventDefault();
        const $card = $(this).closest(".card");
        $card.fadeOut(300, () => $card.remove());
    });

    $(document)
        .off("click", '[data-action="card-toggle"]')
        .on("click", '[data-action="card-toggle"]', function (e) {
            e.preventDefault();
            e.stopPropagation();
            const $card = $(this).closest(".card");
            const $icon = $(this).find("i").eq(0);
            const $body = $card.find(".card-body");
            const $footer = $card.find(".card-footer");
            $body.stop(true, true).slideToggle(300);
            $footer.stop(true, true).slideToggle(200);
            $icon.toggleClass("ti-chevron-up ti-chevron-down");
            $card.toggleClass("card-collapse");
        });

    document.querySelectorAll('[data-action="card-refresh"]').forEach((btn) => {
        btn.addEventListener("click", (e) => {
            e.preventDefault();
            const cardBody = e.target
                .closest(".card")
                .querySelector(".card-body");
            cardBody.style.position = "relative";

            let overlay = cardBody.querySelector(".card-overlay");
            if (!overlay) {
                overlay = document.createElement("div");
                overlay.className = "card-overlay";
                const spinner = document.createElement("div");
                spinner.className = "spinner-border text-primary";
                overlay.appendChild(spinner);
                cardBody.appendChild(overlay);
            }

            overlay.style.display = "flex";
            setTimeout(() => (overlay.style.display = "none"), 1500);
        });
    });

    $('[data-action="code-collapse"]').on("click", function (e) {
        e.preventDefault();
        const $card = $(this).closest(".card");
        $card.find(".code-body").slideToggle(300);
        $(this).find("i").eq(0).toggleClass("ti-chevron-up ti-chevron-down");
    });
}
