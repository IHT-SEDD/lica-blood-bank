import { CustomChartJs } from "../../../app";
import { setBloodFilter, ListStockTable } from "./datatables";

// ---------- Global variable untuk memudahkan penyesuaian :begin ----------
const UrlBloodStat = "/inventory/dashboard/data/blood-stat";
const BloodStatChartSelector = "#bloodPackStatsChart";
// ---------- Global variable untuk memudahkan penyesuaian :begin ----------

// ---------- Global state :begin ----------
const BLOOD_STAT_MAP = {
    a_positive: { group: "a", rhesus: "+" },
    b_positive: { group: "b", rhesus: "+" },
    ab_positive: { group: "ab", rhesus: "+" },
    o_positive: { group: "o", rhesus: "+" },
    a_negative: { group: "a", rhesus: "-" },
    b_negative: { group: "b", rhesus: "-" },
    ab_negative: { group: "ab", rhesus: "-" },
    o_negative: { group: "o", rhesus: "-" },
};
const BLOOD_GROUP_COLORS = ["#FFD700", "#FF0000", "#0033CC", "#D9D9D9"];
let bloodStatChartInstance = null;
// ---------- Global state :end ----------

// ---------- Styling card terpilih ----------
function setActiveCard(selectedBtn) {
    document.querySelectorAll(".blood_stat_card_btn").forEach((btn) => {
        btn.classList.remove("blood-card-active");
        const card = btn.querySelector(".card");
        if (card) {
            card.classList.remove(
                "border-primary",
                "shadow",
                "blood-card-selected",
            );
        }
    });

    selectedBtn.classList.add("blood-card-active");
    const card = selectedBtn.querySelector(".card");
    if (card) {
        card.classList.add("border-primary", "shadow", "blood-card-selected");
    }
}

// ---------- Inisialisasi event klik pada setiap card ----------
function InitCardSelection() {
    const cardButtons = document.querySelectorAll(".blood_stat_card_btn");

    cardButtons.forEach((btn) => {
        btn.addEventListener("click", function () {
            const bloodGroup = this.dataset.bloodGroup;
            const bloodRhesus = this.dataset.bloodRhesus;

            // Terapkan styling aktif
            setActiveCard(this);

            // Trigger reload datatable dengan filter baru
            setBloodFilter(bloodGroup, bloodRhesus);
        });
    });

    // ---------- Set default: card A+ aktif saat halaman pertama dimuat :begin ----------
    const defaultCard = document.querySelector(
        '.blood_stat_card_btn[data-blood-group="a"][data-blood-rhesus="+"]',
    );
    if (defaultCard) {
        setActiveCard(defaultCard);
        // Filter datatable sudah default A+ di datatables.js, tidak perlu reload ulang
    }
    // ---------- Set default: card A+ aktif :end ----------
}

// ---------- Populate blood stat dari response API :begin ----------
async function PopulateBloodStat() {
    try {
        const res = await fetch(UrlBloodStat);
        if (!res.ok) throw new Error(`HTTP error! status: ${res.status}`);

        const data = await res.json();

        Object.entries(BLOOD_STAT_MAP).forEach(([key, { group, rhesus }]) => {
            const totalEl = document.querySelector(
                `.total-blood[data-blood-group="${group}"][data-blood-rhesus="${rhesus}"]`,
            );
            const spinnerEl = document.querySelector(
                `.loading_spinner[data-blood-group="${group}"][data-blood-rhesus="${rhesus}"]`,
            );

            if (!totalEl) return;

            const count = data[key] ?? 0;
            totalEl.innerHTML = `${count} <span class="fw-bold my-0 text-muted fs-4">Bags</span>`;

            if (spinnerEl) spinnerEl.classList.add("d-none");
            totalEl.classList.remove("d-none");
        });

        BloodStatChart(data);
    } catch (err) {
        console.error("Failed to fetch blood stat data:", err);
    }
}
// ---------- Populate blood stat dari response API :end ----------

// ---------- Fungsi render / update Blood Stat Pie Chart :begin ----------
function BloodStatChart(data) {
    const labels = ["Type A", "Type B", "Type O", "Type AB"];
    const values = [
        data.blood_a_count ?? 0,
        data.blood_b_count ?? 0,
        data.blood_o_count ?? 0,
        data.blood_ab_count ?? 0,
    ];

    // Jika instance sudah ada, update data tanpa re-render
    if (bloodStatChartInstance) {
        bloodStatChartInstance.data.datasets[0].data = values;
        bloodStatChartInstance.update();
        return;
    }

    bloodStatChartInstance = new CustomChartJs({
        selector: BloodStatChartSelector,
        options: () => {
            return {
                type: "pie",
                data: {
                    labels: labels,
                    datasets: [
                        {
                            data: values,
                            backgroundColor: BLOOD_GROUP_COLORS,
                            borderWidth: 1,
                        },
                    ],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    layout: {
                        padding: {
                            top: 10,
                            bottom: 40,
                            left: 10,
                            right: 10,
                        },
                    },
                    plugins: {
                        legend: {
                            display: true,
                            position: "right",
                            labels: {
                                usePointStyle: true,
                                pointStyle: "circle",
                                boxWidth: 10,
                                boxHeight: 10,
                            },
                        },
                        tooltip: {
                            enabled: true,
                            callbacks: {
                                label: function (ctx) {
                                    return `${ctx.label}: ${ctx.parsed} Bags`;
                                },
                            },
                        },
                        datalabels: {
                            formatter: (value, ctx) => {
                                const total =
                                    ctx.chart.data.datasets[0].data.reduce(
                                        (sum, v) => sum + v,
                                        0,
                                    );
                                const pct =
                                    total > 0
                                        ? ((value / total) * 100).toFixed(1)
                                        : 0;
                                return `${pct}%`;
                            },
                            font: {
                                weight: "bold",
                                size: 11,
                            },
                            anchor: "end",
                            align: "end",
                            offset: 8,
                            clamp: true,
                            clip: false,
                            color: "#000",
                        },
                    },
                    scales: {
                        x: { display: false },
                        y: { display: false },
                    },
                },
            };
        },
    });
}
// ---------- Fungsi render / update Blood Stat Pie Chart :end ----------

// ---------- Entry point ----------
document.addEventListener("DOMContentLoaded", function () {
    ListStockTable();
    InitCardSelection();
    PopulateBloodStat();
});
