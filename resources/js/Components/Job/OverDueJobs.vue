<template>
    <div class="bg-primary-light/10 shadow rounded-lg p-4 mb-8">
        <h2 class="text-lg font-semibold mb-4">Overdue Jobs</h2>

        <!-- Desktop Table (hidden on mobile) -->
        <div class="hidden md:block">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Job Title</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Client</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Amount Due</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Due Date</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Status</th>
                </tr>
                </thead>
                <tbody v-if="overdueJobs.value.length > 0" class="divide-y divide-gray-200 bg-white">
                <tr
                    v-for="job in overdueJobs.value"
                    :key="job.id"
                    class="hover:bg-gray-50 transition-colors"
                >
                    <td class="px-4 py-3 text-sm text-gray-700">{{ job.job_title }}</td>
                    <td class="px-4 py-3 text-sm text-gray-700">{{ job.customer?.name || '—' }}</td>
                    <td class="px-4 py-3 text-sm text-gray-700">{{ job.amount }}</td>
                    <td class="px-4 py-3 text-sm text-gray-700">{{ formatDate(job.due_date) }}</td>
                    <td class="px-4 py-3 text-sm">
              <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                {{ getOverdueStatus(job.due_date) }}
              </span>
                    </td>
                </tr>
                </tbody>
                <tbody v-else>
                <tr>
                    <td colspan="5" class="py-4 text-center text-gray-500 italic">No overdue jobs</td>
                </tr>
                </tbody>
            </table>
        </div>

        <!-- Mobile Cards (shown on mobile) -->
        <div class="md:hidden space-y-3">
            <div
                v-for="job in overdueJobs.value"
                :key="job.id"
                class="bg-white p-4 rounded-lg shadow-sm border border-gray-100 hover:shadow-md transition-shadow"
            >
                <div class="flex justify-between items-start mb-2">
                    <h3 class="font-medium text-primary-dark">{{ job.job_title }}</h3>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
            {{ getOverdueStatus(job.due_date) }}
          </span>
                </div>

                <div class="grid grid-cols-2 gap-2 text-sm mt-2">
                    <div>
                        <p class="text-gray-500 text-xs">Client</p>
                        <p>{{ job.customer?.name || '—' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 text-xs">Amount Due</p>
                        <p>{{ job.amount }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 text-xs">Due Date</p>
                        <p>{{ formatDate(job.due_date) }}</p>
                    </div>
                </div>
            </div>

            <div
                v-if="overdueJobs.value.length === 0"
                class="bg-white p-4 rounded-lg text-center text-gray-500 italic border border-dashed border-gray-300"
            >
                No overdue jobs
            </div>
        </div>
    </div>
</template>

<script setup>
import dayjs from 'dayjs'

const props = defineProps({
    overdueJobs: {
        type: Array,
        default: () => []
    }
})

const formatDate = (date) => {
    return date ? dayjs(date).format('MMM D, YYYY') : '—'
}

const getOverdueStatus = (dueDate) => {
    if (!dueDate) return 'No due date'
    const days = dayjs().diff(dayjs(dueDate, 'YYYY-MM-DD'), 'day')

    if (days > 0) {
        return `Overdue ${days} day${days > 1 ? 's' : ''}`
    } else if (days === 0) {
        return 'Due today'
    } else {
        return `Due in ${Math.abs(days)} day${days < -1 ? 's' : ''}`
    }
}
</script>

<style scoped>
/* Smooth hover transitions */
.hover\:shadow-md {
    transition: box-shadow 0.2s ease;
}
/* Status badge animation */
.bg-red-100 {
    transition: all 0.2s ease;
}
</style>
