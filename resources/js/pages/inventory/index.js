import { CustomChartJs, ins } from "../../app";

// Blood Pack Stats Chart (Pie Chart)
new CustomChartJs({
    selector: "#bloodPackStatsChart",
    options: () => {
        return {
            type: "pie",
            data: {
                labels: ["Type A", "Type B", "Type O", "Type AB"],
                datasets: [
                    {
                        data: [37, 23, 9, 31],
                        backgroundColor: [
                            "#FFD700",
                            "#FF0000",
                            "#0033CC",
                            "#D9D9D9",
                        ],
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
                                return `${ctx.label}: ${ctx.parsed}%`;
                            },
                        },
                    },

                    // 🔥 datalabel keluar chart
                    datalabels: {
                        formatter: (value, ctx) => {
                            return `${value}%`;
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
