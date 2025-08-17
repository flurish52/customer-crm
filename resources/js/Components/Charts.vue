<script setup>
import VueApexCharts from "vue3-apexcharts"
import { ref } from "vue"

const series = ref([44, 25, 31]) // your data
const chartOptions = ref({
    chart: {
        type: "donut",
    },
    labels: ["Paid", "Owing", "Cleared"],
    dataLabels: {
        formatter: (val) => `${val.toFixed(1)}%`, // show percentages
    },
    legend: {
        position: "bottom",
    },
})
</script>

<template>
    <div class="bg-primary-dark text-tertiary-light p-6 w-64 h-64  rounded-2xl shadow-lg">
        <h2 class="text-xl font-semibold mb-4">Payments Overview</h2>
        <apexchart
            type="donut"
            width="100%"
            :options="chartOptions"
            :series="series"
        />
    </div>
</template>

<script>
export default {
    components: {
        apexchart: VueApexCharts,
    },
}
</script>
