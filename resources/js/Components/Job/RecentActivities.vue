<template>
    <div class="bg-primary-light/10 shadow rounded-lg p-4 mb-8 w-full">
        <h2 class="text-lg font-semibold mb-4">Recent Activities</h2>
        <!-- Scrollable container -->
        <div class="overflow-x-auto">
            <ul
                v-if="paginatedActivities.length > 0"
                class="space-y-3  "
            >
                <li
                    v-for="activity in paginatedActivities"
                    :key="activity.id"
                    class="px-4 py-3 rounded-xl bg-white shadow-sm hover:shadow-md transition-all duration-200 border border-gray-100 hover:border-primary-100"
                >
                    <div class="flex gap-3 items-center">
                        <!-- Activity icon -->
                        <div
                            :class="[
              'flex-shrink-0 flex items-center justify-center w-10 h-10 rounded-full',
              getActivityBgClass(activity.type)
            ]"
                        >
                            <component
                                :is="getActivityIcon(activity.type)"
                                class="w-10 h-10 text-primary-light"
                            />
                        </div>

                        <!-- Activity content -->
                        <div
                            class="flex flex-col sm:flex-row sm:items-center min-w-0 flex-1 gap-1 sm:gap-2"
                        >
                            <p class="text-gray-500 font-medium ">
                                {{ formatActivity(activity) }},
                            </p>
                            <p class="text-gray-500 text-sm whitespace-nowrap">
                                {{ formatDate(activity.created_at) }}
                            </p>
                        </div>
                    </div>
                </li>
            </ul>

            <div
                v-else
                class="p-6 text-center text-gray-500 border border-dashed border-gray-300 rounded-lg min-w-max"
            >
                <p>No activities yet</p>
            </div>
        </div>

        <!-- Pagination controls -->
        <div
            class="mt-4 flex sm:flex-row justify-between items-center gap-3 sm:gap-0"
        >
            <button
                @click="prevPage"
                :disabled="currentPage === 1"
                class="px-4 py-2 text-sm rounded-md border border-gray-300 disabled:opacity-50 hover:bg-gray-50 transition-colors w-full sm:w-auto"
            >
                Previous
            </button>
            <p class="text-sm text-gray-600">
                Page {{ currentPage }} of {{ totalPages }}
            </p>
            <button
                @click="nextPage"
                :disabled="currentPage === totalPages"
                class="px-4 py-2 text-sm rounded-md border border-gray-300 disabled:opacity-50 hover:bg-gray-50 transition-colors w-full sm:w-auto"
            >
                Next
            </button>
        </div>
    </div>


</template>

<script setup>
import { ref, computed } from 'vue'
import dayjs from 'dayjs'
import relativeTime from 'dayjs/plugin/relativeTime'
import { CurrencyDollarIcon, CheckCircleIcon, BriefcaseIcon, UserPlusIcon, BellIcon } from '@heroicons/vue/24/solid'

dayjs.extend(relativeTime)
const props = defineProps({
    recentActivities: Object // should contain .data array
})
// Pagination state
const currentPage = ref(1)
const perPage = 5
const totalPages = computed(() => {
    return Math.ceil(props.recentActivities.data.length / perPage)
})

const paginatedActivities = computed(() => {
    const start = (currentPage.value - 1) * perPage
    return props.recentActivities.data.slice(start, start + perPage)
})

const nextPage = () => {
    if (currentPage.value < totalPages.value) {
        currentPage.value++
    }
}

const prevPage = () => {
    if (currentPage.value > 1) {
        currentPage.value--
    }
}

// Helpers
const formatDate = (date) => dayjs(date).fromNow()

const formatActivity = (activity) => {
    const changes = activity.changes ? JSON.parse(activity.changes) : null
    const customerName = activity?.customer?.name || 'a customer'

    switch (activity.type) {
        case 'payment':
            return `You received a payment of $${changes.amount} from ${customerName} via ${changes.method}`
        case 'complete_job':
            return `You completed the job "${activity.subject?.job_title}" for ${customerName}`
        case 'job_created':
            return `You got a job "${activity.subject?.job_title}" from ${customerName}, Congrats!`
        case 'customer_created':
            return `New Client ${customerName} added, congrats!`
        default:
            return 'New activity logged'
    }
}

const getActivityBgClass = (type) => {
    const typeClasses = {
        payment: 'bg-secondary-500',
        complete_job: 'bg-success-500',
        job_created: 'bg-primary-500',
        customer_created: 'bg-tertiary-500'
    }
    return typeClasses[type] || 'bg-gray-500'
}

const getActivityIcon = (type) => {
    const icons = {
        payment: CurrencyDollarIcon,
        complete_job: CheckCircleIcon,
        job_created: BriefcaseIcon,
        customer_created: UserPlusIcon
    }
    return icons[type] || BellIcon
}
</script>
