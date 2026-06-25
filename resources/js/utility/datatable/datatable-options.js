// ---- Language config ----
export const datatableLanguage = {
    paginate: {
        first: '<i class="ti ti-chevrons-left"></i>',
        previous: '<i class="ti ti-chevron-left"></i>',
        next: '<i class="ti ti-chevron-right"></i>',
        last: '<i class="ti ti-chevrons-right"></i>',
    },
    search: "",
    searchPlaceholder: "Search data...",
    // lengthMenu: `_MENU_ <span style="font-size: 12px;">Jumlah data per halaman</span>`,
    info: `<span style="font-size: 12px;">
            Menampilkan <span class="fw-semibold">_START_</span> - <span class="fw-semibold">_END_</span> dari <span class="fw-semibold">_TOTAL_</span> Data
        </span>`,
    infoEmpty: `<span style="font-size: 12px;">
            Menampilkan <span class="fw-semibold">0</span> - <span class="fw-semibold">0</span> dari <span class="fw-semibold">0</span> Data
        </span>`,
    zeroRecords: `<span class="fw-semibold" style="font-size: 13px;">Data tidak ditemukan</span>`,
    emptyTable: `<span class="fw-medium" style="font-size: 13px;">Tidak ada yang tersedia</span>`,
};

// ---- Dom builder ----
export function buildDatatableDom({
    useHideColumn,
    removeSearch,
    removePagination,
    removePageInfo,
    removePageLength = false,
}) {
    const leftControls = [
        useHideColumn ? "<'columnToggleWrapper'>" : "",
        !removePageLength ? "<'pageLengthWrapper'>" : "",
    ].some(Boolean)
        ? `<'d-flex align-items-center gap-2'${[
              useHideColumn ? "<'columnToggleWrapper'>" : "",
              !removePageLength ? "<'pageLengthWrapper'>" : "",
          ].join("")}>`
        : "";

    const searchControl = !removeSearch ? "f" : "";

    // const topLeft = [
    //     useHideColumn ? "<'columnToggleWrapper'>" : "",
    //     !removePageLength ? "<'pageLengthWrapper'>" : "",
    //     !removeSearch ? "f" : "",
    // ].join("");

    const bottomLeft = [
        !removePageInfo ? "i" : "",
        !removePagination ? "p" : "",
    ].join("");

    return (
        `<'d-lg-flex justify-content-between align-items-center mt-2 mb-3'${leftControls}${searchControl}>` +
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
