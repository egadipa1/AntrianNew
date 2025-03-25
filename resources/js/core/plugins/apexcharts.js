import VueApexCharts from "vue3-apexcharts";
/**
 * Initialize VueApexChart component
 * @param app vue instance
 */
export function initApexCharts(app) {
    app.use(VueApexCharts);
}
