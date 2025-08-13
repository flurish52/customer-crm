<template>
    <div class=" mb-8">
        <div class="flex items-center justify-between">

            <div class="flex justify-between flex-col mb-6">
                <!-- Main Header with Accent -->
                <div class="flex mb-3">
                    <h1 class="text-2xl font-bold text-primary-dark">Statistics</h1>
                </div>

                <!-- Optional Time Filter -->
                <div
                    v-show="!toggledCard"
                    class="flex items-center space-x-2">
<!--                    <button-->
<!--                        v-for="period in timePeriods"-->
<!--                        :key="period"-->
<!--                        @click="activePeriod = period"-->
<!--                        class="px-3 py-1 text-sm rounded-md transition-colors"-->
<!--                        :class="{-->
<!--          'bg-primary-DEFAULT text-white bg-primary-dark': activePeriod === period,-->
<!--          'text-primary-dark hover:bg-tertiary-light': activePeriod !== period-->
<!--        }"-->
<!--                    >-->
<!--                        {{ period }}-->
<!--                    </button>-->
                </div>
            </div>

            <button @click="togleSection">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
        </div>
        <hr>
        <transition
            name="fold-transition"
            enter-active-class="transition transform duration-500"
            leave-active-class="transition transform duration-500"
            enter-from-class="-translate-y-full opacity-0"
            enter-to-class="translate-y-0 opacity-100"
            leave-from-class="translate-y-0 opacity-100"
            leave-to-class="-translate-y-full opacity-0"
        >
            <div
                v-show="!toggledCard"
                class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6"
            >
                <div class="bg-white p-6 rounded-xl shadow-sm border border-tertiary-light">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Total Customers</p>
                            <p class="text-2xl font-bold text-primary-dark mt-1">{{ customers.length }}</p>
                        </div>
                        <div class="p-3 rounded-full bg-primary-light/10 text-primary-DEFAULT">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mt-3">+{{ newCustomersThisMonth }} this month</p>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-sm border border-tertiary-light">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Active Jobs</p>
                            <p class="text-2xl font-bold text-primary-dark mt-1">{{ activeJobs }}</p>
                        </div>
                        <div class="p-3 rounded-full bg-secondary-light/10 text-secondary-DEFAULT">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-xs text-red-500 mt-3">{{ overdueJobs }} overdue jobs</p>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-sm border border-tertiary-light">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Receivables</p>
                            <p class="text-2xl font-bold text-primary-dark mt-1">&#8358;{{ totalReceivables }}</p>
                        </div>
                        <div class="p-3 rounded-full bg-primary-light/10 text-primary-DEFAULT">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
<!--                    <p class="text-xs text-gray-500 mt-3">{{ // overduePaymentsCount }} overdue payments</p>-->
                </div>

                <div class="bg-white p-6 rounded-xl shadow-sm border border-tertiary-light">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Customer Satisfaction</p>
                            <p class="text-2xl font-bold text-primary-dark mt-1">{{ satisfactionRate }}%</p>
                        </div>
                        <div class="p-3 rounded-full bg-secondary-light/10 text-secondary-DEFAULT">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
<!--                    <p class="text-xs text-gray-500 mt-3">{{ positiveReviewsCount }} positive reviews</p>-->
                </div>
            </div>
        </transition>
    </div>
</template>

<script setup>
import {computed, onMounted} from "vue";
import {ref} from "vue";
import axios from "axios";
import dayjs from 'dayjs'


let customers = ref([])
let jobs = ref([])
let activeJobs = ref()

const overdueJobs = ref(0)
const totalReceivables = ref(0)
// const overduePaymentsCount = computed(() => Math.floor(Math.random() * 3))
const newCustomersThisMonth = computed(() => {
    const now = dayjs()
    return customers.value.filter(customer => {
        return dayjs(customer.created_at).isSame(now, 'month')
    }).length
})

// const timePeriods = ['Today', 'Week', 'Month', 'Year']
// const activePeriod = ref('Month')

let toggledCard = ref(false)
const togleSection = () => {
    toggledCard.value = !toggledCard.value
}

const getCustomers = () => {
    axios.get(`/get_customer`)
        .then(res => {
            customers.value = res.data
        })
}
const getJobs = () => {
    axios.get(`/get_user/jobs`)
        .then(res => {
            jobs.value = res.data
            activeJobs.value = jobs.value.filter(job => job.status !== 'completed').length
            overdueJobs.value = jobs.value.filter(job => job.status === 'overdue').length
        })
}

const getReceivedAmount = () => {
    axios.get(`/get_user/total_receivables`)
        .then(res => {
            totalReceivables.value = res.data
        })
}

onMounted(() => {
    getCustomers()
    getJobs()
    getReceivedAmount()

})

const satisfactionRate = computed(() => {
    const filtered = jobs.value.filter(job =>
        job.status === 'completed' && job.satisfaction_score != null
    )
    if (filtered.length === 0) return 0

    const sum = filtered.reduce((total, job) => total + Number(job.satisfaction_score), 0)
    return sum / filtered.length
})

</script>

<style scoped>

</style>
