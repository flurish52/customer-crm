<template>
    <div class="pb-3">
        <div class="welcome-header">
            <div class="flex items-center justify-between">
                <div class="flex flex-col">
                    <h1 class="text-2xl font-bold text-primary-dark">
                        Welcome to Your Dashboard
                    </h1>
                    <p class="text-gray-500 text-sm mt-1">
                        Here's what's happening with your business
                    </p>
                </div>
                <button
                    @click="toggleSection"
                    class="text-gray-500 hover:text-primary transition-all duration-200 p-1 rounded-full hover:bg-gray-100"
                    :class="{ 'rotate-180': toggledCard }"
                    aria-label="Toggle statistics"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M19 9l-7 7-7-7"
                        />
                    </svg>
                </button>
            </div>
        </div>
        <hr class="mb-4 border-gray-200">

        <transition
            name="fade-slide"
            enter-active-class="transition-all duration-300 ease-out"
            leave-active-class="transition-all duration-200 ease-in"
            enter-from-class="opacity-0 -translate-y-4"
            enter-to-class="opacity-100 translate-y-0"
            leave-from-class="opacity-100 translate-y-0"
            leave-to-class="opacity-0 -translate-y-4"
        >
            <div v-show="!toggledCard" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div
                    v-for="(card, index) in statCards"
                    :key="index"
                    class="bg-primary-dark  p-5 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-all duration-300 hover:-translate-y-1"
                    :style="{ 'transition-delay': `${index * 50}ms` }"
                >
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-tertiary-light">{{ card.title }}</p>
                            <p class="text-2xl font-bold text-tertiary-light mt-1">{{ card.value }}</p>
                        </div>
                        <div class="p-3 rounded-full" :class="card.iconBg">
                            <component :is="card.icon" class="h-6 w-6" :class="card.iconColor" />
                        </div>
                    </div>
                    <p v-if="card.footer" class="text-xs mt-3 bg-white rounded-md px-3 w-fit py-1" :class="card.footerColor">{{ card.footer }}</p>
                </div>
            </div>
        </transition>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'
import dayjs from 'dayjs'
import {
    UserGroupIcon,
    BriefcaseIcon,
    CurrencyDollarIcon,
    FaceSmileIcon,
} from "@heroicons/vue/24/outline"


let customers = ref([])
let jobs = ref([])
let activeJobs = ref(0)
let overdueJobs = ref(0)
let totalReceivables = ref(0)
let toggledCard = ref(false)

const toggleSection = () => {
    toggledCard.value = !toggledCard.value
}

const newCustomersThisMonth = computed(() => {
    const now = dayjs()
    return customers.value.filter(customer => {
        return dayjs(customer.created_at).isSame(now, 'month')
    }).length
})

const satisfactionRate = computed(() => {
    const filtered = jobs.value.filter(job =>
        job.status === 'completed' && job.satisfaction_score != null
    )
    if (filtered.length === 0) return 0
    const sum = filtered.reduce((total, job) => total + Number(job.satisfaction_score), 0)
    return Math.round((sum / filtered.length) * 20) // Convert 1-5 scale to percentage
})

const statCards = computed(() => [
    {
        title: 'Total Customers',
        value: customers.value.length,
        icon: UserGroupIcon,
        iconBg: 'bg-blue-50',
        iconColor: 'text-blue-500',
        footer: `+${newCustomersThisMonth.value} this month`,
        footerColor: 'text-gray-500'
    },
    {
        title: 'Active Jobs',
        value: activeJobs.value,
        icon: BriefcaseIcon,
        iconBg: 'bg-purple-50',
        iconColor: 'text-purple-500',
        footer: `${overdueJobs.value} overdue jobs`,
        footerColor: 'text-red-500'
    },
    {
        title: 'Receivables',
        value: `₦${totalReceivables.value.toLocaleString()}`,
        icon: CurrencyDollarIcon,
        iconBg: 'bg-green-50',
        iconColor: 'text-green-500',
        footer: null
    },
    {
        title: 'Customer Satisfaction',
        value: `${satisfactionRate.value}%`,
        icon: FaceSmileIcon,
        iconBg: 'bg-yellow-50',
        iconColor: 'text-yellow-500',
        footer: null
    }
])

const getCustomers = () => {
    axios.get('/get_customer')
        .then(res => {
            customers.value = res.data
        })
}
const getJobs = () => {
    axios.get('/get_user/jobs')
        .then(res => {
            jobs.value = res.data
            activeJobs.value = jobs.value.filter(job => job.status !== 'completed').length
            overdueJobs.value = jobs.value.filter(job =>
                job.status !== 'completed' &&
                dayjs(job.due_date).isBefore(dayjs(), 'day') // due_date is before today
            ).length
        })
}
const getReceivedAmount = () => {
    axios.get('/get_user/total_receivables')
        .then(res => {
            totalReceivables.value = res.data
        })
}

onMounted(() => {
    getCustomers()
    getJobs()
    getReceivedAmount()
})
</script>
