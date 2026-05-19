import {
    GlobalAdvanceFlatpickr,
    GlobalDeleteDataConfirmation,
    GlobalRestoreDataConfirmation,
    GlobalEditData,
    GlobalAdvanceTomselect,
    GlobalFormValidation,
    GlobalSubmitForm,
    GlobalDataConfirmation,
} from "../../../app";

// ---------- Global variable :begin ----------
const DestroyBloodDataURL = "/inventory/destroy-blood/data/get";

const ModalDeleteSelector = "delete_data_destroy_blood_modal";
const ActionDeleteSelector = ".btn-delete-destroy-blood";
const AttributeDelete = "deleteId";
const ConfirmDeleteSelector = "#confirm_delete";

const ModalPermanentDeleteSelector =
    "permanent_delete_data_destroy_blood_modal";
const ActionPermanentDeleteSelector = ".btn-permanent-delete-destroy-blood";
const AttributePermanentDelete = "permanentDeleteId";
const ConfirmPermanentDeleteSelector = "#confirm_permanent_delete";

const ModalUndestroySelector = "confirmation_data_destroy_blood_modal";
const ActionUndestroySelector = ".btn-undestroy-destroy-blood";
const AttributeUndestroy = "undestroyId";
const ConfirmUndestroySelector = "#confirm_action";

const ModalRestoreSelector = "restore_data_destroy_blood_modal";
const ActionRestoreSelector = ".btn-restore-destroy-blood";
const AttributeRestore = "restoreId";
const ConfirmRestoreSelector = "#confirm_restore";
// ---------- Global variable :end ----------

export class TableActionHandler {
    constructor(reloadTable) {
        this.reloadTable = reloadTable;
    }

    #getCsrfToken() {
        return document
            .querySelector('meta[name="csrf-token"]')
            ?.getAttribute("content");
    }

    #getModalInstance(selectorId) {
        const modalEl = document.getElementById(selectorId);
        if (!modalEl) return null;
        return (
            bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl)
        );
    }

    DeleteDataDestroyBloodActionModal() {
        new GlobalDeleteDataConfirmation({
            ButtonSelector: ActionDeleteSelector,
            DataAttributeID: AttributeDelete,
            UrlFetchData: (id) => DestroyBloodDataURL + `/${id}`,
            ModalConfirmID: ModalDeleteSelector,
        });

        document.addEventListener("delete:open", (e) => {
            const { data } = e.detail;
            if (!data) return;

            document.querySelector("#deleted_data").textContent =
                `${data.blood_stocks.bag_number} with ID ${data.public_id}`;
            document.querySelector(ConfirmDeleteSelector).dataset.id =
                data.public_id;
        });

        const confirmBtn = document.querySelector(ConfirmDeleteSelector);
        if (!confirmBtn) return;

        confirmBtn.addEventListener("click", async () => {
            const id = confirmBtn.dataset.id;
            if (!id) return;

            try {
                const response = await fetch(DestroyBloodDataURL + `/${id}`, {
                    method: "DELETE",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": this.#getCsrfToken(),
                    },
                });

                const result = await response.json();

                if (!response.ok) {
                    notyf.error({
                        message: result.message || "Failed to delete data!",
                    });
                    return;
                }
                notyf.success({
                    message: result.message || "Data deleted successfully!",
                });
                this.#getModalInstance(ModalDeleteSelector)?.hide();
                confirmBtn.dataset.id = "";
                this.reloadTable();
            } catch (error) {
                console.error(error);
                notyf.error({ message: "Failed to delete data!" });
            }
        });
    }

    PermanentDeleteDataDestroyBloodActionModal() {
        new GlobalDeleteDataConfirmation({
            ButtonSelector: ActionPermanentDeleteSelector,
            DataAttributeID: AttributePermanentDelete,
            UrlFetchData: (id) => DestroyBloodDataGetDataURL + `/${id}`,
            ModalConfirmID: ModalPermanentDeleteSelector,
        });

        document.addEventListener("delete:open", (e) => {
            const { data } = e.detail;
            if (!data) return;

            document.querySelector("#permanent_deleted_data").textContent =
                `${data.bag_number} with ID ${data.public_id}`;
            document.querySelector(ConfirmPermanentDeleteSelector).dataset.id =
                data.public_id;
        });

        const confirmBtn = document.querySelector(
            ConfirmPermanentDeleteSelector,
        );
        if (!confirmBtn) return;

        confirmBtn.addEventListener("click", async () => {
            const id = confirmBtn.dataset.id;
            if (!id) return;

            try {
                const response = await fetch(
                    DestroyBloodDataURL + `/${id}/permanent`,
                    {
                        method: "DELETE",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": this.#getCsrfToken(),
                        },
                    },
                );

                const result = await response.json();

                if (!response.ok) {
                    notyf.error({
                        message:
                            result.message ||
                            "Failed to permanent delete data!",
                    });
                    return;
                }
                notyf.success({
                    message:
                        result.message ||
                        "Data permanent deleted successfully!",
                });
                this.#getModalInstance(ModalDeleteSelector)?.hide();
                confirmBtn.dataset.id = "";
                this.reloadTable();
            } catch (error) {
                console.error(error);
                notyf.error({ message: "Failed to permanent data!" });
            }
        });
    }

    RestoreDataDestroyBloodActionModal() {
        new GlobalRestoreDataConfirmation({
            ButtonSelector: ActionRestoreSelector,
            DataAttributeID: AttributeRestore,
            UrlFetchData: (id) => DestroyBloodDataGetDataURL + `/${id}`,
            ModalConfirmID: ModalRestoreSelector,
        });

        document.addEventListener("restore:open", (e) => {
            const { data } = e.detail;
            if (!data) return;

            document.querySelector("#restored_data").textContent =
                `${data.bag_number} with ID ${data.public_id}`;
            document.querySelector(ConfirmRestoreSelector).dataset.id =
                data.public_id;
        });

        const confirmBtn = document.querySelector(ConfirmRestoreSelector);
        if (!confirmBtn) return;

        confirmBtn.addEventListener("click", async () => {
            const id = confirmBtn.dataset.id;
            if (!id) return;

            try {
                const response = await fetch(
                    DestroyBloodDataURL + `/${id}/restore`,
                    {
                        method: "PATCH",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": this.#getCsrfToken(),
                        },
                    },
                );

                const result = await response.json();

                if (!response.ok) {
                    notyf.error({
                        message: result.message || "Failed to restore data!",
                    });
                    return;
                }
                notyf.success({
                    message: result.message || "Data restored successfully!",
                });
                this.#getModalInstance(ModalRestoreSelector)?.hide();
                confirmBtn.dataset.id = "";
                this.reloadTable();
            } catch (error) {
                console.error(error);
                notyf.error({ message: "Failed to restore data!" });
            }
        });
    }

    UndestroyDataDestroyBloodActionModal() {
        new GlobalDataConfirmation({
            ButtonSelector: ActionUndestroySelector,
            DataAttributeID: AttributeUndestroy,
            UrlFetchData: (id) => DestroyBloodDataURL + `/${id}`,
            ModalConfirmID: ModalUndestroySelector,
        });

        document.addEventListener("confirmation:open", (e) => {
            const { data } = e.detail;
            if (!data) return;

            document.querySelector("#confirm_data").textContent =
                `${data.blood_stocks.bag_number} with ID ${data.public_id}`;
            document.querySelector(ConfirmUndestroySelector).dataset.id =
                data.public_id;
        });

        const confirmBtn = document.querySelector(ConfirmUndestroySelector);
        if (!confirmBtn) return;

        confirmBtn.addEventListener("click", async () => {
            const id = confirmBtn.dataset.id;
            if (!id) return;

            try {
                const response = await fetch(
                    DestroyBloodDataURL + `/${id}/undestroy`,
                    {
                        method: "PATCH",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": this.#getCsrfToken(),
                        },
                    },
                );

                const result = await response.json();

                if (!response.ok) {
                    notyf.error({
                        message: result.message || "Failed to undestroy data!",
                    });
                    return;
                }
                notyf.success({
                    message: result.message || "Data undestroyed successfully!",
                });
                this.#getModalInstance(ModalUndestroySelector)?.hide();
                confirmBtn.dataset.id = "";
                this.reloadTable();
            } catch (error) {
                console.error(error);
                notyf.error({ message: "Failed to undestroy data!" });
            }
        });
    }

    init() {
        this.DeleteDataDestroyBloodActionModal();
        this.PermanentDeleteDataDestroyBloodActionModal();
        this.RestoreDataDestroyBloodActionModal();
        this.UndestroyDataDestroyBloodActionModal();
    }
}
