// ---------- Global variable untuk memudahkan penyesuaian :begin ----------
// ORDER STATUS
const ORDER_STATUS = window.AppEnum.orderBloodStatus;
// ---------- Global variable untuk memudahkan penyesuaian :end ----------

// ---------- Helper: ORDER STATUS CHECKER ----------
export const OrderStatus = {
    DONE: ORDER_STATUS.DONE,
    DRAFT: ORDER_STATUS.DRAFT,
    ORDER_CREATED: ORDER_STATUS.ORDER_CREATED,

    isDone(status) {
        return status?.toLowerCase() === this.DONE;
    },

    isDraft(status) {
        return status?.toLowerCase() === this.DRAFT;
    },

    isOrderCreated(status) {
        return status?.toLowerCase() === this.ORDER_CREATED;
    },
};
