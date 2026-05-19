import { GlobalAdvanceTomselect } from "../../../app";

// ---------- Selectors ----------
const SelectPatientSelector = "#select-patient";
const SelectBloodGroupSelector = "#select-blood-group";
const SelectBloodRhesusSelector = "#select-blood-rhesus";
const SelectRelationTypeSelector = "#select-relation-type";
const SelectInsuranceSelector = "#select-insurance";
const SelectRoomSelector = "#select-room";
const SelectDoctorSelector = "#select-doctor";
const SelectBloodPackSelector = "#select-blood-pack";

// ---------- Helper: Untuk init tomselect ----------
function initializeTomSelect(selector, endpoint, extraOptions = {}) {
    if (!document.querySelector(selector)) {
        return null;
    }

    return new GlobalAdvanceTomselect(selector, {
        valueField: "id",
        preload: true,
        loadOnClick: true,
        load: function (query, callback) {
            fetch(`${endpoint}?q=${encodeURIComponent(query)}`)
                .then((res) => res.json())
                .then((json) => callback(json.results))
                .catch(() => callback());
        },
        ...extraOptions,
    });
}

// ---------- Select Patient ----------
export function initSelectPatient() {
    if (!document.querySelector(SelectPatientSelector)) {
        return null;
    }

    const wrapper = new GlobalAdvanceTomselect(SelectPatientSelector, {
        valueField: "id",
        plugins: ["clear_button"],
        preload: true,
        load: function (query, callback) {
            fetch(`/utility/select/patient?q=${encodeURIComponent(query)}`)
                .then((res) => res.json())
                .then((json) => callback(json.results))
                .catch(() => callback());
        },
    });
    const instance = wrapper.getInstances()[0];

    return { wrapper, instance };
}

// ---------- Select Blood Group ----------
export function initSelectBloodGroup() {
    const wrapper = initializeTomSelect(
        SelectBloodGroupSelector,
        "/utility/select/blood-group",
    );
    if (!wrapper) return null;

    return { wrapper, instance: wrapper.getInstances()[0] };
}

// ---------- Select Blood Rhesus ----------
export function initSelectBloodRhesus() {
    const wrapper = initializeTomSelect(
        SelectBloodRhesusSelector,
        "/utility/select/blood-rhesus",
    );
    if (!wrapper) return null;

    return { wrapper, instance: wrapper.getInstances()[0] };
}

// ---------- Select Relation Type ----------
export function initSelectRelationType() {
    const wrapper = initializeTomSelect(
        SelectRelationTypeSelector,
        "/utility/select/relation-type",
    );
    if (!wrapper) return null;

    return { wrapper, instance: wrapper.getInstances()[0] };
}

// ---------- Select Insurance ----------
export function initSelectInsurance() {
    const wrapper = initializeTomSelect(
        SelectInsuranceSelector,
        "/utility/select/insurance",
    );
    if (!wrapper) return null;

    return { wrapper, instance: wrapper.getInstances()[0] };
}

// ---------- Select Room ----------
export function initSelectRoom() {
    const wrapper = initializeTomSelect(
        SelectRoomSelector,
        "/utility/select/room",
    );
    if (!wrapper) return null;

    return { wrapper, instance: wrapper.getInstances()[0] };
}

// ---------- Select Doctor ----------
export function initSelectDoctor() {
    const wrapper = initializeTomSelect(
        SelectDoctorSelector,
        "/utility/select/doctor",
    );
    if (!wrapper) return null;

    return { wrapper, instance: wrapper.getInstances()[0] };
}

// ---------- Select Blood Pack ----------
export function initSelectBloodPack() {
    const wrapper = initializeTomSelect(
        SelectBloodPackSelector,
        "/utility/select/blood-pack",
    );
    if (!wrapper) return null;

    return { wrapper, instance: wrapper.getInstances()[0] };
}
