<template>
    <div>
        <!-- Desktop Table -->
        <div class="hidden md:block overflow-x-auto rounded-lg shadow">
            <table class="w-full border-collapse bg-white text-left text-sm border-b-2 border-gray-900">
                <thead class="bg-primary-dark text-white border-b-2 border-gray-200">
                <tr>
                    <th class="px-2 py-4 font-semibold text-text">S/N</th>
                    <th class="px-2 py-4 font-semibold text-text">Job Title</th>
                    <th class="px-2 py-4 font-semibold text-text">Client</th>
                    <th class="px-2 py-4 font-semibold text-text">Expected Amount</th>
                    <th class="px-2 py-4 font-semibold text-text">Billed Amount</th>
                    <th class="px-2 py-4 font-semibold text-text">Paid</th>
                    <th class="px-2 py-4 font-semibold text-text">Balance</th>
                    <th class="px-2 py-4 font-semibold text-text">Due Date</th>
                    <th class="px-2 py-4 font-semibold text-text">Overdue</th>
                    <th class="px-2 py-4 font-semibold text-text text-center">Actions</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                <tr v-for="(job, index) in overdueJobs" :key="job.id" class="bg-gray-50 text-black">
                    <td class="px-2 py-4 font-medium">{{ index + 1 }}</td>
                    <td class="px-2 py-4">{{ job.job_title }}</td>
                    <td class="px-2 py-4">{{ job.customer?.name || '—' }}</td>
                    <td class="px-2 py-4">{{ formatCurrency(job.amount, jobCurrency(job)) }}</td>
                    <td class="px-2 py-4">{{ formatCurrency(billedAmount(job), jobCurrency(job)) }}</td>
                    <td class="px-2 py-4">{{ formatCurrency(paidAmount(job), jobCurrency(job)) }}</td>
                    <td class="px-2 py-4">{{ formatCurrency(balanceAmount(job), jobCurrency(job)) }}</td>
                    <td class="px-2 py-4">{{ formatDate(job.due_date) }}</td>
                    <td class="px-2 py-4">
              <span class="px-2 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-full">
                {{ getOverdueStatus(job.due_date) }}
              </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex justify-center space-x-2">
                            <Link :href="`/dashboard/job/${job.id}/view`"
                                  class="text-secondary hover:text-secondary-dark p-1 rounded transition-colors"
                                  title="View">
                                View
                            </Link>
                            <button
                                v-if="job.status === 'pending'"
                                @click="markInProgress(job)"
                                class="px-3 py-1 bg-teal-600 text-white rounded-md hover:bg-teal-700 text-sm font-medium">
                                Mark In Progress
                            </button>
                            <button
                                v-else-if="job.status === 'in_progress'"
                                @click="markCompleted(job)"
                                class="px-3 py-1 bg-green-600 text-white rounded-md hover:bg-green-700 text-sm font-medium">
                                Mark Completed
                            </button>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

        <!-- Mobile Cards -->
        <div class="block md:hidden space-y-4 mt-4">
            <div
                v-for="job in overdueJobs"
                :key="job.id"
                class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden"
            >
                <!-- Header -->
                <div class="p-2 bg-gray-50 flex justify-between items-center border-b">
                    <h3 class="font-semibold text-base text-gray-800">
                        {{ job.job_title }}
                    </h3>
                    <span
                        class="px-2 py-0.5 text-xs font-medium bg-red-100 text-red-800 rounded-full"
                    >
        {{ getOverdueStatus(job.due_date) }}
      </span>
                </div>

                <!-- Body -->
                <div class="p-2 space-y-3 text-sm">
                    <div class="grid grid-cols-2 gap-x-4 gap-y-2">
                        <div>
                            <p class="text-gray-500 text-xs">Client</p>
                            <p class="font-medium text-gray-800">{{ job.customer?.name || '—' }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500 text-xs">Expected</p>
                            <p class="font-medium text-gray-800">{{ formatCurrency(job.amount, jobCurrency(job)) }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500 text-xs">Billed</p>
                            <p class="font-medium text-gray-800">{{ formatCurrency(billedAmount(job), jobCurrency(job)) }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500 text-xs">Paid</p>
                            <p class="font-medium text-gray-800">{{ formatCurrency(paidAmount(job), jobCurrency(job)) }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500 text-xs">Balance</p>
                            <p class="font-medium text-gray-800">{{ formatCurrency(balanceAmount(job), jobCurrency(job)) }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500 text-xs">Due Date</p>
                            <p class="font-medium text-gray-800">{{ formatDate(job.due_date) }}</p>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex flex-col space-y-2 pt-2">
                        <Link
                            :href="`/dashboard/job/${job.id}/view`"
                            class="text-center px-1 py-2 bg-secondary text-white rounded-md hover:bg-secondary-dark text-sm font-medium"
                        >
                            View
                        </Link>

                        <button
                            v-if="job.status === 'pending'"
                            @click="markInProgress(job)"
                            class="px-3 py-2 bg-teal-600 text-white rounded-md hover:bg-teal-700 text-sm font-medium"
                        >
                            Mark In Progress
                        </button>

                        <button
                            v-else-if="job.status === 'in_progress'"
                            @click="markCompleted(job)"
                            class="px-3 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 text-sm font-medium"
                        >
                            Mark Completed
                        </button>
                    </div>
                </div>
            </div>

            <div
                v-if="overdueJobs.length === 0"
                class="bg-white p-6 rounded-lg text-center text-gray-500 italic border border-dashed border-gray-300"
            >
                No overdue jobs
            </div>
        </div>
    </div>
</template>

<script setup>
import {onMounted, ref} from 'vue'
import dayjs from 'dayjs'
import {Link, router} from '@inertiajs/vue3'
import axios from 'axios'

const props = defineProps({
    overdueJobs: Array
})
const formatDate = (date) => date ? dayjs(date).format('MMM D, YYYY') : '—'
const getOverdueStatus = (dueDate) => {
    if (!dueDate) return 'No due date'
    const days = dayjs().diff(dayjs(dueDate, 'YYYY-MM-DD'), 'day')
    if (days > 0) return `Overdue ${days} day${days > 1 ? 's' : ''}`
    if (days === 0) return 'Due today'
    return `Due in ${Math.abs(days)} day${days < -1 ? 's' : ''}`
}
const billedAmount = (job) =>
    job.invoices.filter(inv => inv.status !== 'cancelled')
        .reduce((sum, inv) => sum + parseFloat(inv.total || 0), 0)
const paidAmount = (job) =>
    job.invoices.reduce((sum, inv) => {
        return sum + inv.payments
            .filter(pay => !pay.is_invalid)
            .reduce((pSum, pay) => pSum + parseFloat(pay.amount_in_invoice_currency || 0), 0)
    }, 0)
const balanceAmount = (job) => billedAmount(job) - paidAmount(job)
const jobCurrency = (job) => {
    const invoice = job.invoices.find(inv => inv.status !== 'cancelled')
    return invoice ? invoice.currency : 'NGN'
}
const formatCurrency = (amount, currency) =>
    `${currency} ${parseFloat(amount).toLocaleString()}`
const markInProgress = (job) => {
    axios.patch(`/job_update/status/${job.id}`, {type: 'in_progress'})
        .then(res => {
            if (res.status === 200) {
                router.visit(window.location.pathname, {replace: true, preserveScroll: true})
            }
        })
        .catch(err => alert(err.response.data.message))
}
const markCompleted = (job) => {
    axios.patch(`/job_update/status/${job.id}`, {type: 'completed'})
        .then(res => {
            if (res.status === 200) {
                router.visit(window.location.pathname, {replace: true, preserveScroll: true})
            }
        })
        .catch(err => alert(err.response.data.message))
}

onMounted(()=>{
    console.log(props.overdueJobs)
})
</script>
