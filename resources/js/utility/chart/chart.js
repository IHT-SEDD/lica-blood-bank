import { Chart } from "chart.js/auto";
import ChartDataLabels from "chartjs-plugin-datalabels";
Chart.register(ChartDataLabels);

export const ins = (v, a = 1) => {
    const val = getComputedStyle(document.documentElement)
        .getPropertyValue(`--ins-${v}`)
        .trim();
    return v.includes("-rgb") ? `rgba(${val}, ${a})` : val;
};

export function debounce(func, wait) {
    let timeout;
    return function () {
        clearTimeout(timeout);
        timeout = setTimeout(func, wait);
    };
}

export class CustomChartJs {
    static instances = [];

    constructor({ selector, options = () => ({}) }) {
        if (!selector) {
            console.warn("CustomChartJs: 'selector' is required.");
            return;
        }

        this.selector = selector;
        this.getOptions =
            typeof options === "function" ? options : () => options;
        this.element = null;
        this.chart = null;

        try {
            this.render();
            CustomChartJs.instances.push(this);
        } catch (err) {
            console.error("CustomChartJs: Initialization error", err);
        }
    }

    static getDefaultOptions() {
        const bodyFont = getComputedStyle(document.body).fontFamily.trim();

        return {
            responsive: true,
            maintainAspectRatio: false,
            layout: {
                padding: {
                    top: -10,
                },
            },
            scales: {
                x: {
                    ticks: {
                        font: { family: bodyFont },
                        color: ins("secondary-color"),
                        display: true,
                        drawTicks: true,
                    },
                    grid: {
                        display: false,
                        drawBorder: false,
                    },
                    border: {
                        display: false, // Hides bottom X axis line
                    },
                },
                y: {
                    ticks: {
                        font: { family: bodyFont },
                        color: ins("secondary-color"),
                    },
                    grid: {
                        display: true, // Keeps horizontal lines
                        drawBorder: false, // Hides Y axis border line
                        color: ins("chart-border-color"),
                        lineWidth: 1,
                    },
                    border: {
                        display: false, // Hides Y axis line (left)
                        dash: [5, 5],
                    },
                },
            },
            plugins: {
                legend: {
                    display: true,
                    position: "top",
                    labels: {
                        font: { family: bodyFont },
                        color: ins("secondary-color"),
                        usePointStyle: true, // Show circles instead of default box
                        pointStyle: "circle", // Circle shape
                        boxWidth: 8, // Circle size
                        boxHeight: 8, // (optional) same as width by default
                        padding: 15, // Space between legend items
                    },
                },
                tooltip: {
                    enabled: true,
                    titleFont: { family: bodyFont },
                    bodyFont: { family: bodyFont },
                },
            },
        };
    }

    render() {
        try {
            this.element =
                this.selector instanceof HTMLElement
                    ? this.selector
                    : document.querySelector(this.selector);

            if (!this.element) {
                console.warn(
                    `CustomChartJs: No element found for selector '${this.selector}'`,
                );
                return;
            }

            // Destroy existing chart instance if present
            if (this.chart) {
                this.chart.destroy();
            }

            const { type, data, options, plugins } = this.getOptions();

            // Merge dynamic default options with instance-specific options
            this.chart = new Chart(this.element, {
                type: type || "bar",
                data,
                options: {
                    ...structuredClone(CustomChartJs.getDefaultOptions()),
                    ...(options || {}),
                },
                plugins: plugins || [],
            });

            // Resize listener
            window.addEventListener(
                "resize",
                debounce(() => {
                    if (this.chart) {
                        this.chart.resize();
                    }
                }, 200),
            );
        } catch (err) {
            console.error(
                `CustomChartJs: Render error for '${this.selector}'`,
                err,
            );
        }
    }

    static rerenderAll() {
        for (const instance of CustomChartJs.instances) {
            instance.render();
        }
    }

    static reSizeAll() {
        for (const instance of CustomChartJs.instances) {
            if (instance.chart) {
                instance.chart.resize();
            }
        }
    }

    static destroyAll() {
        for (const instance of CustomChartJs.instances) {
            if (instance.chart) {
                instance.chart.destroy();
            }
        }
        CustomChartJs.instances = [];
    }
}

CustomChartJs.instances = [];

const themeObserver = new MutationObserver(() => CustomChartJs.rerenderAll());
themeObserver.observe(document.documentElement, {
    attributes: true,
    attributeFilter: ["data-skin", "data-bs-theme"],
});

const menuObserver = new MutationObserver(() => {
    requestAnimationFrame(() => CustomChartJs.reSizeAll());
});
menuObserver.observe(document.documentElement, {
    attributes: true,
    attributeFilter: ["data-sidenav-size"],
});
