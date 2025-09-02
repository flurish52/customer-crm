<template>
    <!-- Desktop Table View -->
    <div class="hidden md:block overflow-x-auto rounded-lg shadow">
        <table class="w-full border-collapse bg-white text-left text-sm border-b-2 border-gray-900">
            <thead class="bg-primary border-b-2 border-gray-600">
            <tr>
                <th class="px-6 py-4 font-semibold text-tertiary">S/N</th>
                <th class="px-6 py-4 font-semibold text-tertiary">Job Title</th>
                <th class="px-6 py-4 font-semibold text-tertiary">Status</th>
                <th class="px-6 py-4 font-semibold text-tertiary">Expected Amount</th>
                <th class="px-6 py-4 font-semibold text-tertiary">Billed Amount</th>
                <th class="px-6 py-4 font-semibold text-tertiary">Paid</th>
                <th class="px-6 py-4 font-semibold text-tertiary">Balance</th>
                <th class="px-6 py-4 font-semibold text-tertiary text-center">Actions</th>
            </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
            <tr v-for="(job, index) in completedJobs" :key="job.id" class="bg-gray-50 text-black transition-colors">
                <td class="px-6 py-4 font-medium">{{ index + 1 }}</td>
                <td class="px-6 py-4">{{ job.job_title }}</td>
                <td class="px-6 py-4">
            <span :class="statusBadgeClass(job.status)" class="px-3 py-1 text-xs font-medium rounded-full">
              {{ job.status }}
            </span>
                </td>
                <td class="px-6 py-4">{{ formatCurrency(job.amount, jobCurrency(job)) }}</td>
                <td class="px-6 py-4">{{ formatCurrency(billedAmount(job), jobCurrency(job)) }}</td>
                <td class="px-6 py-4">{{ formatCurrency(paidAmount(job), jobCurrency(job)) }}</td>
                <td class="px-6 py-4">{{ formatCurrency(balanceAmount(job), jobCurrency(job)) }}</td>
                <td class="px-6 py-4">
                    <div class="flex justify-center space-x-2">
                        <Link :href="`/dashboard/job/${job.id}/view`"
                              class="text-secondary hover:text-secondary-dark p-1 rounded transition-colors"
                              title="View">
                            View
                        </Link>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
    </div>

    <!-- Mobile Card View -->
    <div class="block md:hidden space-y-4">
        <div v-for="job in completedJobs" :key="job.id"
             class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100">
            <div class="p-4 border-b border-gray-100 bg-gray-50">
                <div class="flex justify-between items-start">
                    <h3 class="font-semibold text-lg text-primary">{{ job.job_title }}</h3>
                    <span :class="statusBadgeClass(job.status)"
                          class="px-3 py-1 text-xs font-medium rounded-full">{{ job.status }}</span>
                </div>
            </div>
            <div class="p-4">
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wide">Expected Amount</p>
                        <p class="font-medium">{{ formatCurrency(job.amount, jobCurrency(job)) }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wide">Billed</p>
                        <p class="font-medium">{{ formatCurrency(billedAmount(job), jobCurrency(job)) }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wide">Paid</p>
                        <p class="font-medium">{{ formatCurrency(paidAmount(job), jobCurrency(job)) }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wide">Balance</p>
                        <p class="font-medium">{{ formatCurrency(balanceAmount(job), jobCurrency(job)) }}</p>
                    </div>
                </div>
                <div class="flex flex-col space-y-2">
                    <Link :href="`/dashboard/job/${job.id}/view`"
                          class="px-3 py-1 bg-secondary text-white rounded-md hover:bg-secondary-dark transition-colors font-medium">
                        View
                    </Link>
                </div>
            </div>
        </div>
    </div>
    <div v-if="completedJobs.length <= 0">
        <p class="text-center py-4 text-gray-500">No pending jobs</p>
    </div>

</template>
<script setup>
import {computed} from 'vue'
import {toRefs} from 'vue'
import {Link} from "@inertiajs/vue3";

const props = defineProps({
    jobs: Array
})
const {jobs} = toRefs(props)
const completedJobs = computed(() => jobs.value.filter(job => job.status === 'completed'))
const billedAmount = (job) => job.invoices.reduce((sum, inv) => sum + parseFloat(inv.total || 0), 0)
const paidAmount = (job) => job.invoices.reduce((sum, inv) => {
    return sum + inv.payments.reduce((pSum, pay) => pSum + parseFloat(pay.amount || 0), 0)
}, 0)
const balanceAmount = (job) => billedAmount(job) - paidAmount(job)
const jobCurrency = (job) => {
    const invoice = job.invoices.find(inv => inv.status !== 'cancelled')
    return invoice ? invoice.currency : 'NGN'
}
const formatCurrency = (amount, currency) => `${currency} ${parseFloat(amount).toLocaleString()}`
const statusBadgeClass = (status) => {
    switch (status) {
        case 'completed':
            return 'bg-green-100 text-green-800'
        default:
            return 'bg-gray-100 text-gray-800'
    }
}
const emit = defineEmits(['view-job'])
</script>

