import { CustomChartJs, ins } from "../app";

new CustomChartJs({
    selector: "#promptsChart",
    options: () => {
        return {
            type: "bar",
            data: {
                labels: ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"],
                datasets: [
                    {
                        data: [120, 150, 180, 220, 200, 245, 145],
                        backgroundColor: ins("chart-primary"),
                        borderRadius: 4,
                        borderSkipped: false,
                    },
                ],
            },
            options: {
                plugins: {
                    legend: { display: false },
                    tooltip: { enabled: false },
                },
                scales: {
                    x: {
                        display: false,
                        grid: { display: false },
                    },
                    y: {
                        display: false,
                        grid: { display: false },
                    },
                },
            },
        };
    },
});

// Token Consumption Chart (Line Chart)
new CustomChartJs({
    selector: "#tokenChart",
    options: () => {
        return {
            type: "line",
            data: {
                labels: ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"],
                datasets: [
                    {
                        data: [
                            82000, 95000, 103000, 112000, 121500, 135200,
                            148000,
                        ],
                        backgroundColor: ins("chart-primary-rgb", 0.1),
                        borderColor: ins("chart-primary"),
                        tension: 0.4,
                        fill: true,
                        pointRadius: 0,
                        borderWidth: 2,
                    },
                ],
            },
            options: {
                plugins: {
                    legend: { display: false },
                    tooltip: { enabled: false },
                },
                scales: {
                    x: {
                        display: false,
                        grid: { display: false },
                    },
                    y: {
                        display: false,
                        grid: { display: false },
                    },
                },
            },
        };
    },
});

function generateSmoothData(count, start = 40, variation = 5) {
    const data = [start];
    for (let i = 1; i < count; i++) {
        const prev = data[i - 1];
        const next = prev + (Math.random() * variation * 2 - variation);
        data.push(Math.round(next));
    }
    return data;
}

function generateHigherData(baseData, diffRange = [3, 6]) {
    return baseData.map(
        (val) =>
            val +
            Math.floor(Math.random() * (diffRange[1] - diffRange[0] + 1)) +
            diffRange[0],
    );
}

// Labels changed to time slots or AI usage checkpoints
const labels = ["0h", "3h", "6h", "9h", "12h", "15h", "18h", "21h"];

const currentAiUsers = generateSmoothData(8, 45, 4);
const previousAiUsers = generateHigherData(currentAiUsers);

new CustomChartJs({
    selector: "#activeUsersChart",
    options: () => ({
        type: "line",
        data: {
            labels,
            datasets: [
                {
                    label: "AI Users (Today)",
                    data: currentAiUsers,
                    fill: true,
                    borderColor: ins("chart-primary"),
                    backgroundColor: ins("chart-primary-rgb", 0.2),
                    tension: 0.4,
                    pointRadius: 0,
                    borderWidth: 1,
                },
                {
                    label: "AI Users (Yesterday)",
                    data: previousAiUsers,
                    fill: true,
                    borderColor: ins("chart-gray"),
                    backgroundColor: ins("chart-gray-rgb", 0.2),
                    tension: 0.4,
                    pointRadius: 0,
                    borderWidth: 1,
                },
            ],
        },
    }),
});
