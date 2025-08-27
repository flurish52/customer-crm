<template>
    <section class="bg-primary text-primary-dark px-6 rounded-xl shadow-md text-sm mb-6 pb-3">
        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
            <div>
                <h1 class="text-xl font-bold">Invoices</h1>
                <p class="text-sm text-secondary mt-1">Manage all your invoices, track payments, and monitor statuses</p>
            </div>
            <div class="flex flex-wrap gap-3 text-primary-dark">
<!--                <button @click="createInvoice" class="bg-primary hover:bg-primary-dark text-white flex items-center gap-2  px-4 py-2 rounded-lg font-semibold transition">-->
<!--                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">-->
<!--                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>-->
<!--                    </svg>-->
<!--                    New Invoice-->
<!--                </button>-->

                <button @click="filterInvoices" class="flex items-center gap-2 border-2 hover:bg-secondary  border-primary  px-4 py-2 rounded-lg font-semibold transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L15 12v6a1 1 0 01-1.447.894l-4-2A1 1 0 019 16v-4L3.293 6.707A1 1 0 013 6V4z"/>
                    </svg>
                    Filter
                </button>

                <button @click="exportInvoices" class="border-2  hover:bg-secondary border-primary flex items-center gap-2 text-primary-dark px-4 py-2 rounded-lg font-semibold transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M12 12v8m0-8l-4-4m4 4l4-4"/>
                    </svg>
                    Export
                </button>
            </div>

        </div>

<!--        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 border-b-2 border-t-2 mb-3 border-primary">-->
<!--            <div class="bg-white/20 backdrop-blur-sm rounded-lg p-4 text-center">-->
<!--                <div class="text-xl font-bold">{{ totalInvoices }}</div>-->
<!--                <div class="text-sm opacity-90">Total Invoices</div>-->
<!--            </div>-->
<!--            <div class="bg-white/20 backdrop-blur-sm rounded-lg p-4 text-center">-->
<!--                <div class="text-xl font-bold">{{ outstandingBalance }}</div>-->
<!--                <div class="text-sm opacity-90">Outstanding Balance</div>-->
<!--            </div>-->
<!--            <div class="bg-white/20 backdrop-blur-sm rounded-lg p-4 text-center">-->
<!--                <div class="text-xl font-bold">{{ overdueInvoices }}</div>-->
<!--                <div class="text-sm opacity-90">Overdue Invoices</div>-->
<!--            </div>-->
<!--            <div class="bg-white/20 backdrop-blur-sm rounded-lg p-4 text-center">-->
<!--                <div class="text-xl font-bold">{{ paidPercentage }}%</div>-->
<!--                <div class="text-sm opacity-90">Paid Invoices</div>-->
<!--            </div>-->
<!--        </div>-->
    </section>
</template>

<script setup>
import { ref } from "vue"
import dayjs from "dayjs";
const props = defineProps({
    invoices: Array,
})

const totalInvoices = props.invoices.length
const outstandingBalance = props.invoices.reduce((sum, invoice) => {
    return sum + Number(invoice.total || 0)
}, 0)

const overdueInvoices = props.invoices.filter(invoice =>
    invoice.due_date && dayjs(invoice.due_date).isBefore(dayjs(), 'day')
).length
const paidPercentage = ref(82)
function createInvoice() { alert('Creating new invoice') }
function filterInvoices() { alert('Filter invoices') }
function exportInvoices() { alert('Export invoices') }
</script>

<style scoped>

</style>
