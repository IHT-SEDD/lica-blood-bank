export function initMultiDropdown() {
    $(".dropdown-menu a.dropdown-toggle").on("click", function () {
        const dropdown = $(this).next(".dropdown-menu");
        const otherDropdown = $(this)
            .parent()
            .parent()
            .find(".dropdown-menu")
            .not(dropdown);
        otherDropdown.removeClass("show");
        otherDropdown.parent().find(".dropdown-toggle").removeClass("show");
        return false;
    });
}
