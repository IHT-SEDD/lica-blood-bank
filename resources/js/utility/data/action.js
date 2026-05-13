export class GlobalDataAction {
    constructor(options = {}, defaults = {}) {
        this.buttonSelector = options.ButtonSelector || defaults.buttonSelector;
        this.dataAttributeID = options.DataAttributeID || "id";
        this.urlFetchData = options.UrlFetchData || null;
        this.modalID = options.ModalConfirmID || options.ModalEditID || null;
        this.formSelector = options.FormSelector || null;
        this.eventName = defaults.eventName;

        this.currentData = null;
        this.currentId = null;

        this.init();
    }

    _resolveUrl(template, id) {
        return typeof template === "function"
            ? template(id)
            : template.replace(":id", id);
    }

    async _fetchData(id) {
        if (!this.urlFetchData) return null;
        const res = await fetch(this._resolveUrl(this.urlFetchData, id));
        const json = await res.json();
        return json.data;
    }

    _showModal() {
        if (!this.modalID) return;
        const modalEl = document.getElementById(this.modalID);
        if (modalEl) new bootstrap.Modal(modalEl).show();
    }

    _dispatch(detail) {
        document.dispatchEvent(new CustomEvent(this.eventName, { detail }));
    }

    _onSuccess(data, id, btn) {}

    init() {
        document.addEventListener("click", async (e) => {
            const btn = e.target.closest(this.buttonSelector);
            if (!btn) return;

            const id = btn.dataset[this.dataAttributeID];
            if (!id) {
                window.notyf?.error({ message: "ID Data Not Found!" });
                return;
            }

            try {
                const data = await this._fetchData(id);
                this.currentData = data;
                this.currentId = id;
                this._dispatch({ data, id, button: btn });
                this._onSuccess(data, id, btn);
                this._showModal();
            } catch (err) {
                window.notyf?.error({ message: "Failed to load data!" });
                console.error(err);
            }
        });
    }
}
