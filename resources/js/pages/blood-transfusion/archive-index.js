import {
    DatatableArchiveBagRequest,
    DatatableArchiveRequestBlood,
    DatatableArchiveTest,
    listArchiveTableInstance,
    OnSelectBagTransaction,
    OnSelectTransaction,
    currentTransfusionPublicId,
} from "./datatable/archive-datatables";
import { GlobalAdvanceFlatpickr } from "../../app";
import { BloodTransfusionLogConfigTL } from "../../utility/config/timeline-config";
import { GlobalRenderTimelineItem } from "../../utility/ui";

// ---------- Global variable untuk memudahkan penyesuaian ----------
const ArchiveDateFilterSelector = ".archive-blood-transfusion-date-filter";
const ArchiveBTLogContainerSelector =
    ".archive-blood-transfusion-log-data-container";
const TimelineArchiveContainerSelector =
    ".timeline-archive-blood-transfusion-log";

// ---------- Filter tanggal ----------
function archiveDateRangeFilter() {
    new GlobalAdvanceFlatpickr(ArchiveDateFilterSelector, {
        maxDate: "today",
        defaultDate: "today",
    });

    $(document)
        .off("change", ArchiveDateFilterSelector)
        .on("change", ArchiveDateFilterSelector, function () {
            if (listArchiveTableInstance) {
                listArchiveTableInstance.reload();
            }
        });
}

// ---------- Fungsi mengambil data log ----------
export async function getArchiveBloodTransfusionLog(transfusionPublicID) {
    if (!transfusionPublicID) return;

    try {
        const res = await fetch(
            `/blood-transfusion/${transfusionPublicID}/log`,
            {
                method: "GET",
                cache: "no-store",
                headers: {
                    "Cache-Control": "no-cache",
                    Pragma: "no-cache",
                },
            },
        );
        if (!res.ok) throw new Error(`HTTP error! status: ${res.status}`);

        const data = await res.json();
        GenerateTimelineArchive(data);
    } catch (err) {
        notyf.error({ message: "Failed to fetch blood transfusion log data!" });
        console.error(err);
        GenerateTimelineArchive([]);
    }
}
// ---------- Fungsi generate timeline ----------
function GenerateTimelineArchive(logs = []) {
    const archiveBloodTransfusionTimeline = GlobalRenderTimelineItem.create({
        container: ArchiveBTLogContainerSelector,
        wrapper: TimelineArchiveContainerSelector,
        locale: "en-GB",
        statusConfig: BloodTransfusionLogConfigTL,
        iconLibrary: "tabler",
    });

    archiveBloodTransfusionTimeline.render(logs);
}

document.addEventListener("DOMContentLoaded", function () {
    archiveDateRangeFilter();

    DatatableArchiveRequestBlood();
    DatatableArchiveBagRequest();
    DatatableArchiveTest();

    OnSelectTransaction();
    OnSelectBagTransaction();
});
