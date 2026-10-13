// ---------- Konfigurasi tampilan ----------
const STATUS_MAP = {
    connected: {
        text: "Connected",
        cls: ["bg-success-subtle", "text-success"],
    },
    degraded: { text: "Degraded", cls: ["bg-warning-subtle", "text-warning"] },
    disconnected: {
        text: "Disconnected",
        cls: ["bg-danger-subtle", "text-danger"],
    },
};

const LATENCY_CLASS = {
    fast: "text-success",
    normal: "text-warning",
    slow: "text-danger",
};

const ALL_BADGE_CLASSES = [
    "bg-secondary-subtle",
    "text-secondary",
    "bg-success-subtle",
    "text-success",
    "bg-warning-subtle",
    "text-warning",
    "bg-danger-subtle",
    "text-danger",
];

// ---------- Helper ----------
const $ = (id) => document.getElementById(id);

function setText(id, value) {
    const el = $(id);
    if (el) el.textContent = value ?? "-";
}

function setStatus(status) {
    const el = $("connection_status");
    if (!el) return;

    const conf = STATUS_MAP[status] ?? STATUS_MAP.disconnected;
    el.classList.remove(...ALL_BADGE_CLASSES);
    el.classList.add(...conf.cls);
    el.textContent = conf.text;
}

function renderLatency(latency) {
    const el = $("emsifa_latency");
    if (!el) return;

    el.classList.remove(...Object.values(LATENCY_CLASS));

    if (latency?.average_ms == null) {
        el.textContent = "-";
        el.removeAttribute("title");
        return;
    }

    el.textContent = `${latency.average_ms} ms`;
    el.classList.add(LATENCY_CLASS[latency.rating] ?? "");
    el.title = `Fastest: ${latency.fastest_ms} ms | Slowest: ${latency.slowest_ms} ms`;
}

function renderDetail(data) {
    setStatus(data.status);
    setText("emsifa_total_endpoints", data.total_endpoints);
    renderLatency(data.latency);
    setText("emsifa_success_rate", `${data.success_rate}%`);
    setText("emsifa_total_provinces", data.total_provinces);

    // Tooltip detail per endpoint di badge status
    const badge = $("connection_status");
    if (badge) {
        badge.title = data.endpoints
            .map(
                (e) =>
                    `${e.ok ? "✔" : "✘"} ${e.label}: ${e.latency_ms ?? "-"} ms`,
            )
            .join("\n");
    }
}

function renderFailed() {
    setStatus("disconnected");
    setText("emsifa_total_endpoints", "-");
    setText("emsifa_latency", "-");
    setText("emsifa_success_rate", "-");
    setText("emsifa_total_provinces", "-");
}

// ---------- Check connection (sekali per page load) ----------
async function checkConnection() {
    const card = $("emsifa-card");
    if (!card) return;

    try {
        const response = await fetch(card.dataset.url, {
            headers: { Accept: "application/json" },
        });

        if (!response.ok) throw new Error(`HTTP ${response.status}`);

        const json = await response.json();
        renderDetail(json.data);
    } catch (error) {
        console.error("[EMSIFA] Check connection failed:", error);
        renderFailed();
    }
}

document.addEventListener("DOMContentLoaded", checkConnection);
