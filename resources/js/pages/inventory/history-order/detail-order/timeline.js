import { TimelineStatusConfig } from "./utility";
import { TimestampFormatter } from "../../../../utility/ui";

// ---------- Helper: render satu timeline item ----------
export function renderTimelineItem(log) {
    const config =
        TimelineStatusConfig[log.status] ?? TimelineStatusConfig.fallback;
    console.log(config);

    const timestamp = TimestampFormatter(
        log.timestamp ?? log.created_at,
        "en-GB",
    );
    const description = log.description ?? "-";
    const createdByRole = log.created_by_role ?? "";
    const createdByUser = log.created_by_user_name ?? "-";

    return `
        <div class="timeline-item d-flex align-items-stretch">
            <div class="timeline-time pe-3 text-muted">
                ${timestamp}
            </div>

            <div class="timeline-dot">
                <i class="ti ti-${config.icon} fs-4 ${config.colorClass} align-middle" data-bs-title="${config.tooltipTitle}" 
                data-bs-toggle="tooltip"data-bs-trigger="hover"></i>
            </div>

            <div class="timeline-content ps-3 pb-4">
                <h5 class="mb-1">${config.title}</h5>
                <p class="mb-1 text-muted">${description}</p>
                <div class="d-flex align-items-center justify-content-start gap-1">
                    <span class="text-primary fw-medium">${createdByUser}</span>
                </div>
            </div>
        </div>
    `;
}
