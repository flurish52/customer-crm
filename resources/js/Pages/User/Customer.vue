<template>
    <AuthenticatedLayout>
        <CreateJob
        :customer="jobCustomer"
        :jobToEdit="jobToEdit"
        @close="closeModal"
        :showModal="showModal"
        :amountPaid="previousPayment"
        @submit="refreshPage"
        :isEditingJob="isEditingJob"
        />

        <JobRating
            :jobId="selectedJobId"
            :showModal="showRatingModal"
            @submit="completeJob"
            @close="closeRatingModal"
        />
        <PaymentModal
        :jobs="jobs"
        @cancel="cancelPaymentForm"
        :showPaymentModal="showPaymentModal"
        />
        <div v-if="showAddCustomerModal"
             @click.self="closeAddCustomerModal"
             class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
            <AddCustomerModal
                :customerToEdit="customer"
                :isEditing="isEditing"

                @closeAddCustomerModal="closeAddCustomerModal"
            />
        </div>

        <div class="">
            <!-- Back Button -->
            <Link
                href="/dashboard"
                class="flex items-center text-primary hover:text-primary-dark transition-colors"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back
            </Link>
            <!-- Customer Profile Header -->
            <div class="bg-white rounded-xl shadow-sm border border-tertiary-light overflow-hidden mb-8">
                <!-- Avatar Section (Centered) -->
                <div class="flex flex-col items-center pt-8">
                    <div class="relative">
                        <div v-if="!customer.avatar"
                             class="h-24 w-24 rounded-full bg-primary-light/10 flex items-center justify-center text-3xl font-bold text-primary-DEFAULT">
                            {{ customer.initials }}
                        </div>
                        <div v-else
                             class="h-24 w-24 rounded-full overflow-hidden border-2 border-primary-light/20">
                            <img :src="`/storage/${customer.avatar}`" alt="Customer avatar"
                                 class="w-full h-full object-cover">
                        </div>
                        <span class="absolute bottom-2 right-2 h-3 w-3 rounded-full bg-green-500 border-2 border-white"></span>
                    </div>

                    <h1 class="text-2xl font-bold text-primary-dark mt-4">{{ customer.name }}</h1>
                    <p v-if="customer.company" class="text-primary-dark/80">{{ customer.company }}</p>
                </div>

                <!-- Contact Info Section -->
                <div class="p-6 border-b border-tertiary-light">
                    <div class="flex flex-col md:flex-row gap-6 justify-center">
                        <!-- Email -->
                        <div v-if="customer.email" class="flex items-center">
                            <div class="bg-primary-light/10 p-2 rounded-lg mr-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary-DEFAULT" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Email</p>
                                <p class="text-primary-dark font-medium">{{ customer.email }}</p>
                            </div>
                        </div>

                        <!-- Phone -->
                        <div v-if="customer.phone" class="flex items-center">
                            <div class="bg-primary-light/10 p-2 rounded-lg mr-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary-DEFAULT" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Phone</p>
                                <p class="text-primary-dark font-medium">{{ customer.phone }}</p>
                            </div>
                        </div>

                        <!-- Address -->
                        <div v-if="customer.address" class="flex items-center">
                            <div class="bg-primary-light/10 p-2 rounded-lg mr-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary-DEFAULT" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Address</p>
                                <p class="text-primary-dark font-medium">{{ customer.address }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="hidden md:block h-0.5 w-full bg-tertiary-dark/30 my-2"></div>
                <!-- Stats Section (Always in row) -->
                <div class="grid grid-cols-4 divide-x divide-tertiary-light">
                    <div class="p-4 text-center">
                        <p class="text-sm text-gray-500">Total Jobs</p>
                        <p class="text-xl font-bold text-primary-DEFAULT">{{jobs?.length}}</p>
                    </div>
                    <div class="p-4 text-center">
                        <p class="text-sm text-gray-500">Active Jobs</p>
                        <p class="text-xl font-bold text-secondary">{{ activeJobs }}</p>
                    </div>
                    <div class="p-4 text-center">
                        <p class="text-sm text-gray-500">Total Spent</p>
                        <p class="text-xl font-bold text-primary-dark">₦ {{ totalSpent }}</p>
                    </div>
                    <div class="p-4 text-center">
                        <p class="text-sm text-gray-500">Since</p>
                        <p class="text-xl font-bold text-primary-dark">{{ dayjs(customer.created_at).format('MMM D, YYYY') }}</p>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="p-6 border-t border-tertiary-light flex flex-col sm:flex-row gap-3 justify-center">
                    <button
                        @click="showModalComponent"
                        class="px-6 py-3 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors flex items-center justify-center"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Create Job
                    </button>

                    <button
                        @click="openAddCustomerModal"
                        class="px-6 py-3 border border-primary-DEFAULT text-primary-DEFAULT rounded-lg hover:bg-primary-light/10 transition-colors flex items-center justify-center"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit Profile
                    </button>
                    <button
                        @click="openPaymentForm"
                        class="px-6 py-3 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors flex items-center justify-center"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Made payment
                    </button>
                </div>
            </div>
            <!-- Jobs Section -->
            <div class="bg-white rounded-xl shadow-sm border border-tertiary-light overflow-hidden">
                <!-- Section Header -->
                <div class="px-6 py-4 border-b border-tertiary-light flex justify-between items-center">
                    <h2 class="text-xl font-bold text-primary-dark">Job History</h2>
                    <div class="flex items-center space-x-2">
                        <button
                            @click="filterJobs('all')"
                            class="px-3 py-1 text-sm rounded-md"
                            :class="{
              'bg-primary-DEFAULT text-white': activeFilter === 'all',
              'text-primary-dark hover:bg-tertiary-light': activeFilter !== 'all'
            }"
                        >
                            All
                        </button>
                        <button
                            @click="filterJobs('active')"
                            class="px-3 py-1 text-sm rounded-md"
                            :class="{
              'bg-secondary-light text-secondary-dark': activeFilter === 'active',
              'text-primary-dark hover:bg-tertiary-light': activeFilter !== 'active'
            }"
                        >
                            Active
                        </button>
                        <button
                            @click="filterJobs('completed')"
                            class="px-3 py-1 text-sm rounded-md"
                            :class="{
              'bg-green-100 text-green-800': activeFilter === 'completed',
              'text-primary-dark hover:bg-tertiary-light': activeFilter !== 'completed'
            }"
                        >
                            Completed
                        </button>
                    </div>
                </div>
                <!-- Jobs List -->
                <div v-if="jobs.length > 0" class="divide-y divide-tertiary-light">
                    <div
                        class=" hover:bg-tertiary-light/20 transition-colors"
                    >
                        <Jobs
                            :jobs="customer.jobs"
                            @isEditing="isEditingJobFunc"
                            @showModal="showModalComponent"
                            @completeJob="openRatingModal"
                            class=""
                        />

                    </div>
                </div>

                <!-- Empty State -->
                <div v-else class="p-12 text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    <h3 class="mt-2 text-lg font-medium text-primary-dark">No jobs found</h3>
                    <p class="mt-1 text-gray-500">This customer doesn't have any
                        {{ activeFilter === 'all' ? '' : activeFilter }} jobs yet</p>
                    <button
                        @click="startNewJob"
                        class="mt-4 px-4 py-2 bg-secondary-DEFAULT text-white rounded-lg hover:bg-secondary-dark transition-colors flex items-center mx-auto"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Create New Job
                    </button>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import {ref, onMounted} from 'vue'
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {Link, router} from "@inertiajs/vue3";
import dayjs from "dayjs";
import CreateJob from "@/Components/Job/CreateJob.vue";
import axios from "axios";
import JobRating from "@/Components/Job/JobRating.vue";
import AddCustomerModal from "@/Components/Customer/AddCustomerModal.vue";
import PaymentModal from "@/Components/Job/PaymentModal.vue";
import Jobs from "@/Components/Job/Jobs.vue";
import AOS from "aos";
let props = defineProps({
    customer: Object,
    totalSpent: Number,
})
let showModal = ref(false)
let showRatingModal = ref(false)
const jobs = ref([])
const isEditing  = ref(false)
const activeFilter = ref('all')
const isEditingJob = ref(false)
const previousPayment = ref(0)

const showAddCustomerModal = ref(false)
const showPaymentModal = ref(false)
const jobToEdit = ref({})
const jobCustomer = ref({})
const isEditingJobFunc = ({payload, amountPaid}) => {
    previousPayment.value =  amountPaid
    isEditingJob.value = true
    jobToEdit.value = payload
    jobCustomer.value = jobToEdit.value.customer
    showModal.value = true;
};
const openPaymentForm = () => {
    showPaymentModal.value = true
};
const cancelPaymentForm = () => {
    showPaymentModal.value = false
};
const openAddCustomerModal = () => {
    isEditing.value = true
    showAddCustomerModal.value = true
}
const closeAddCustomerModal = () => {
    showAddCustomerModal.value = false
    isEditing.value = false
}

const refreshPage = () =>{
    router.reload({
        preserveScroll: true,
        preserveState: true
    })
}
let closeModal = () =>{
    isEditingJob.value = false
    showModal.value =  !showModal.value
}

let satisfaction_score = ref(0)
let selectedJobId = ref()
const openRatingModal = ({payload}) => {
    selectedJobId.value =  payload
    showRatingModal.value = true
}
const closeRatingModal = () => {
    showRatingModal.value = false
}
let completeJob = ({payload, jobId}) =>{
    axios.patch(`/job_update/status/${jobId}`, {type: 'complete_job', satisfaction_score: payload})
        .then(res=>{
            jobs.value =  res.data.job
            closeRatingModal()
            alert(res.data.message)
        })
}
let activeJobs = ref([])
const getCustomerJobs =(customerId)=>{
    axios.get(`/get_jobs/${customerId}`)
        .then(res => {
            jobs.value =  res.data
            activeJobs.value = jobs.value.filter(job => job.status !== 'completed').length
        })
}

onMounted(()=>{
    getCustomerJobs(props.customer.id)
})

const filterJobs = (filter) => {
    activeFilter.value = filter
}
</script>

<style scoped>
/* Custom styling if needed */
</style>
