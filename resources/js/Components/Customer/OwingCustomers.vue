<template>
    <div class="p-6 bg-white rounded-2xl shadow-lg">
        <div class="mb-4">
            <h2 class="text-xl font-semibold">Account Receivables </h2>
            <p class="text-secondary-dark text-sm ">Clients who are yet to pay.</p>
        </div>

        <!-- Desktop Table (hidden on mobile) -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full border-collapse">
                <thead>
                <tr class="bg-gray-50 text-left text-sm text-gray-600">
                    <th class="p-3">Client</th>
                    <th class="p-3">Job</th>
                    <th class="p-3">Amount</th>
                    <th class="p-3">Paid</th>
                    <th class="p-3">Balance</th>
                    <th class="p-3">Due Date</th>
                    <th class="p-3">Status</th>
                </tr>
                </thead>
                <tbody>
                <tr
                    v-for="job in owingJobs"
                    :key="job.id"
                    class="border-t text-sm hover:bg-gray-50 transition-colors"
                >
                    <td class="p-3">
                        <div class="font-medium whitespace-nowrap">{{ job.customer.name }}</div>
                        <div class="text-gray-500 whitespace-nowrap">{{
                                job.customer.phone || job.customer.email
                            }}
                        </div>
                    </td>
                    <td class="p-3 whitespace-nowrap">
                        <div>{{ job.job_title }}</div>
                    </td>
                    <td class="p-3 font-medium whitespace-nowrap">₦{{ formatMoney(job.amount) }}</td>
                    <td class="p-3 text-green-600 whitespace-nowrap">₦{{ formatMoney(job.totalPaid) }}</td>
                    <td class="p-3 text-red-600 whitespace-nowrap">₦{{ formatMoney(job.balance) }}</td>
                    <td class="p-3 whitespace-nowrap">
                        {{ formatDate(job.due_date) }}
                    </td>
                    <td class="p-3 whitespace-nowrap">
              <span
                  :class="[
                  'px-2 py-1 rounded-full text-xs font-medium inline-flex items-center',
                  job.balance > 0 ? 'bg-red-100 text-red-600' : 'bg-green-100 text-green-600'
                ]"
              >
                <span class="h-2 w-2 rounded-full mr-1" :class="job.balance > 0 ? 'bg-red-600' : 'bg-green-600'"></span>
                {{ job.balance > 0 ? `Owing (${job.overdueLabel})` : 'Cleared' }}
              </span>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

        <!-- Mobile Cards (shown on mobile) -->
        <div class="md:hidden space-y-4">
            <div
                v-for="job in owingJobs"
                :key="job.id"
                class="p-4 border rounded-lg hover:shadow-md transition-shadow"
            >
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <h3 class="font-semibold text-primary-dark">{{ job.customer.name }}</h3>
                        <p class="text-sm text-gray-500">{{ job.customer.phone || job.customer.email }}</p>
                    </div>
                    <span
                        :class="[
              'px-2 py-1 rounded-full text-xs font-medium inline-flex items-center',
              job.balance > 0 ? 'bg-red-100 text-red-600' : 'bg-green-100 text-green-600'
            ]"
                    >
            <span class="h-2 w-2 rounded-full mr-1" :class="job.balance > 0 ? 'bg-red-600' : 'bg-green-600'"></span>
            {{ job.balance > 0 ? `Owing (${job.overdueLabel})` : 'Cleared' }}
          </span>
                </div>

                <div class="grid grid-cols-2 gap-4 mt-3">
                    <div>
                        <p class="text-xs text-gray-500">Job Title</p>
                        <p class="font-medium">{{ job.job_title }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Due Date</p>
                        <p class="font-medium">{{ formatDate(job.due_date) }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Amount</p>
                        <p class="font-medium">₦{{ formatMoney(job.amount) }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Paid</p>
                        <p class="text-green-600 font-medium">₦{{ formatMoney(job.totalPaid) }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Balance</p>
                        <p class="text-red-600 font-medium">₦{{ formatMoney(job.balance) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="owingJobs.length === 0" class="p-6 text-center text-gray-500">
            No account receivables
        </div>
    </div>
</template>

<script setup>
import {computed} from "vue"
import dayjs from "dayjs"

const props = defineProps({
    owingCustomers: {
        type: Array,
        required: true,
    }
})

const formatMoney = (val) => Number(val).toLocaleString()
const formatDate = (date) => dayjs(date).format("MMM D, YYYY")

const getTotalPaid = (activities) => {
    return activities
        ?.filter(a => a.type === "payment")
        ?.reduce((sum, a) => {
            const changes = JSON.parse(a.changes)
            return sum + Number(changes.amount)
        }, 0) || 0
}

const owingJobs = computed(() => {
    const jobs = []
    props.owingCustomers.value?.forEach(c => {
        c.jobs?.forEach(j => {
            const totalPaid = getTotalPaid(j.activities)
            const balance = Number(j.amount) - totalPaid
            if (balance > 0) {
                jobs.push({
                    ...j,
                    customer: c,
                    totalPaid,
                    balance,
                    overdueLabel: getOverdueStatus(j.due_date)
                })
            }
        })
    })
    return jobs
})

const getOverdueStatus = (dueDate) => {
    if (!dueDate) return ""
    const days = dayjs().diff(dayjs(dueDate, "YYYY-MM-DD"), "day")
    return days > 0 ? `${days} days overdue` : "Not yet due"
}
</script>

<style scoped>
/* Smooth hover transitions */
.hover\:shadow-md {
    transition: box-shadow 0.2s ease;
}

/* Status dot animation */
.h-2.w-2 {
    transition: background-color 0.2s ease;
}
</style>
