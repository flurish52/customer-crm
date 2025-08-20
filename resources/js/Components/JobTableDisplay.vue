<template>
    <div class="overflow-x-auto">
        <table class="min-w-full border-separate border-spacing-y-3">
            <thead>
            <tr>
                <th class="text-left px-4 py-2">Job</th>
                <th class="text-left px-4 py-2">Customer</th>
                <th class="text-left px-4 py-2">Delivery date</th>
                <th class="text-left px-4 py-2">Amount</th>
                <th class="text-left px-4 py-2">Paid</th>
                <th class="text-left px-4 py-2">Balance</th>
                <th class="text-left px-4 py-2">Status</th>
                <th class="text-left px-4 py-2">Satisfaction</th>
                <th class="text-left px-4 py-2">Actions</th>
            </tr>
            </thead>
            <tbody>
            <tr
                v-for="job in jobs"
                :key="job.id"
                class="bg-white border-t-2  border-primary text-sm shadow-md rounded-xl border border-primary transition-all hover:shadow-lg"
            >
                <td class="px-2 py-3 font-medium text-primary-dark">{{ job.job_title }}</td>
                <td class="px-2 py-3">
                    <p class="text-sm font-medium text-primary-dark">{{ job.customer?.name }}</p>
                    <span class="text-xs font-light text-gray-400">{{ job.customer?.company }}</span>
                    <span v-if="job.customer.is_owing"
                          class="text-xs text-red-600 bg-red-100 px-2 py-0.5 rounded-full ml-1">Owing</span>
                </td>

                <td class="px-2 py-3">
                    {{dayjs(job.due_date).format('DD.MMM.YYYY')}}
                </td>
                <td class="px-2 py-3">{{ formatCurrency(job.amount) }}</td>
                <td class="px-2 py-3 text-green-600">{{ formatCurrency(totalPaidPerJob[job.id]) }}</td>
                <td class="px-2 py-3" :class="getBalanceClass(job)">{{ formatCurrency(getJobBalance(job)) }}</td>
                <td class="px-2 py-3">
            <span
                :class="{
                'bg-gray-100 text-gray-700': !job.status,
                'bg-green-100 text-green-700': job.status === 'completed',
                'bg-blue-100 text-blue-700': job.status === 'in_progress',
                'bg-yellow-100 text-yellow-700': job.status === 'pending',
                'bg-red-100 text-red-700': job.status === 'overdue'
              }"
                class="px-3 py-1 rounded-full text-xs font-medium capitalize"
            >
              {{ job.status || 'not started' }}
            </span>
                </td>
                <td class="px-2 py-3">
                    <span v-if="job.satisfaction_score" class="text-xs font-medium">{{ job.satisfaction_score }}/5</span>
                </td>
                <td class="px-2 py-3 flex space-x-2 justify-end">
                    <button
                        v-if="job.status !== 'completed'"
                        @click="$emit('completeJob', { payload: job.id })"
                        class="p-2 rounded-full border border-emerald-600 text-emerald-700 hover:bg-emerald-50"
                        title="Mark complete"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor">
                            <path d="M9 12l2 2 4-4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <circle cx="12" cy="12" r="9" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                    <button @click.stop="editJob(job)" class="p-2 rounded-full border border-blue-400 text-blue-500" title="Edit">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </button>
                    <button @click.stop="deleteJob(job)" class="p-2 rounded-full border border-red-400 text-red-500" title="Delete">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script setup>
import dayjs from "dayjs";

</script>

<style scoped>

</style>
