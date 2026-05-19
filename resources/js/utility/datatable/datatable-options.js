// ---- Language config ----
export const datatableLanguage = {
    paginate: {
        first: '<i class="ti ti-chevrons-left"></i>',
        previous: '<i class="ti ti-chevron-left"></i>',
        next: '<i class="ti ti-chevron-right"></i>',
        last: '<i class="ti ti-chevrons-right"></i>',
    },
    lengthMenu: "_MENU_ Entries per page",
    info: `Showing <span class="fw-semibold">_START_</span> to <span class="fw-semibold">_END_</span> of <span class="fw-semibold">_TOTAL_</span> Entries`,
};

// ---- Dom builder ----
export function buildDatatableDom({
    useHideColumn,
    removeSearch,
    removePagination,
    removePageInfo,
}) {
    const topLeft = [
        useHideColumn ? "<'columnToggleWrapper'>" : "",
        !removeSearch ? "f" : "",
    ].join("");

    const bottomLeft = [
        "l",
        !removePageInfo ? "i" : "",
        !removePagination ? "p" : "",
    ].join("");

    return (
        `<'d-lg-flex justify-content-between align-items-center mt-2 mb-3'${topLeft}>` +
        `rt` +
        `<'d-lg-flex justify-content-between align-items-center mt-2'${bottomLeft}>`
    );
}

// ---- Select config builder ----
export function buildSelectConfig({
    rowSelect,
    multiRowSelect,
    checkBoxSelect,
    cellSelect,
}) {
    if (rowSelect) {
        return typeof rowSelect === "object"
            ? { style: "single", ...rowSelect }
            : { style: "single" };
    }
    if (multiRowSelect) {
        return typeof multiRowSelect === "object"
            ? { style: "multi", ...multiRowSelect }
            : { style: "multi" };
    }
    if (checkBoxSelect) {
        return typeof checkBoxSelect === "object"
            ? { style: "multi", ...checkBoxSelect }
            : { style: "multi" };
    }
    if (cellSelect) {
        return typeof cellSelect === "object"
            ? { style: "os", items: "cell", ...cellSelect }
            : { style: "os", items: "cell" };
    }
    return undefined;
}

// ---- Base config builder ----
export function buildDatatableConfig({
    dom,
    columnDefs,
    removePagination,
    useHideColumn,
    selectConfig,
}) {
    const config = {
        processing: true,
        dom,
        columnDefs,
        language: datatableLanguage,
        paging: !removePagination,
    };

    if (useHideColumn) config.responsive = true;
    if (selectConfig) config.select = selectConfig;

    return config;
}
