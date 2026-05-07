import TomSelect from "tom-select";

// ---------- Default tom select config ----------
function createTomSelect(el, url, extraOptions = {}) {
    if (!el) return;

    if (el.tomselect) {
        el.tomselect.destroy();
    }

    return new TomSelect(el, {
        valueField: "id",
        labelField: "text",
        searchField: "text",
        preload: true,
        create: false,
        dropdownParent: "body",

        load(query, callback) {
            fetch(`${url}?q=${encodeURIComponent(query)}`)
                .then((res) => res.json())
                .then((json) => callback(json.results))
                .catch(() => callback());
        },

        ...extraOptions,
    });
}

// ---------- Init TomSelect tanpa default value ----------
export function TomSelectDefault(el, url, extraOptions = {}) {
    return createTomSelect(el, url, extraOptions);
}

// ---------- Init TomSelect dengan default value ----------
export function TomSelectWithValue(
    el,
    url,
    value = null,
    label = null,
    extraOptions = {},
) {
    const ts = createTomSelect(el, url, {
        options:
            value !== null && label !== null
                ? [{ id: value, text: label }]
                : [],

        ...extraOptions,
    });

    if (ts && value !== null) {
        ts.setValue(value, true);
    }

    return ts;
}
