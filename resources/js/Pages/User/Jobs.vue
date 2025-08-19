<template>
    <AuthenticatedLayout>
        <CreateJob
            :showModal="showModal"
            :customer="customer"
            :isEditingJob="isEditingJob"
            :jobToEdit="jobToEdit"
            :allCustomers="allCustomers"
            @close="closeCreateJobModal"
            @submit="refreshPage"
        />
        <JobRating
            :jobId="selectedJobId"
            :showModal="showRatingModal"
            @submit="completeJob"
            @close="closeRatingModal"
        />
        <header class="mb-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <!-- Title with decorative element -->
                <div class="flex items-center gap-3">
                    <div class="hidden md:block w-1.5 h-8 rounded-full bg-primary"></div>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">All Jobs</h1>
                        <p class="text-gray-500 dark:text-gray-400 mt-1">
                            Overview of all your active and completed jobs
                        </p>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-3">
                    <button
                        @click=showCreateJobModal
                        class="flex items-center gap-2 px-4 py-2.5 bg-primary hover:bg-primary-dark text-white rounded-lg transition-all shadow-sm hover:shadow-md"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        <span>New Job</span>
                    </button>

                    <button
                        @click="refreshPage"
                        class="flex items-center gap-2 px-4 py-2.5 border border-gray-300 hover:border-primary/30 bg-white hover:bg-primary/5 text-gray-700 rounded-lg transition-all shadow-sm hover:shadow-md"
                    >
<!--                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"-->
<!--                             stroke="currentColor">-->
<!--                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"-->
<!--                                  d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>-->
<!--                        </svg>-->
                        <span>Refresh</span>
                    </button>
                </div>
            </div>
        </header>
        <div class="space-y-6">
            <!-- Jobs Component with Staggered Animation -->
            <div
                data-aos="fade-up"
                data-aos-duration="400"
                data-aos-delay="100"
            >
                <Jobs
                    :jobs="jobs"
                    :allCustomers="allCustomers"
                    @isEditing="isEditingJobFunc"
                    @jobCompleted="openRatingModal"
                    class="mt-6"
                />
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { PlusIcon, ArrowPathIcon } from '@heroicons/vue/24/outline';
import AOS from 'aos';
import 'aos/dist/aos.css';
import Jobs from "@/Components/Job/Jobs.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { router } from '@inertiajs/vue3'
import CreateJob from "@/Components/Job/CreateJob.vue";
import JobRating from "@/Components/Job/JobRating.vue";
import axios from "axios";
defineProps({
    jobs: Array,
});
const customer = ref({});
const isEditingJob = ref(false);
const jobToEdit = ref({});
const showModal = ref(false);
const allCustomers = ref(null);
const isRefreshing = ref(false);
const selectedJobId = ref(null);
const showRatingModal = ref(false);
const openRatingModal = ({payload})=>{
    selectedJobId.value = payload
    showRatingModal.value = true
}
const closeRatingModal = ()=>{
    selectedJobId.value = null
    showRatingModal.value = false
}
const getUserCustomers = () => {
    axios.get(`/get_customer`)
        .then(res => {
            allCustomers.value = res.data;
        })
        .catch(error => {
            console.error('API error:', error);
        });
};
const refreshPage = () => {
    isRefreshing.value = true;
    router.reload({
        preserveScroll: true,
        onFinish: () => {
            isRefreshing.value = false;
        }
    });
};
const showCreateJobModal = () => {
    getUserCustomers();
    showModal.value = true;
};

const closeCreateJobModal = () => {
    showModal.value = false;
    allCustomers.value = null;
    jobToEdit.value = {}
    customer.value = {}
    isEditingJob.value = false
};
const isEditingJobFunc = ({payload}) => {
    isEditingJob.value = true
    jobToEdit.value = payload
    customer.value = jobToEdit.value.customer
    showModal.value = true;
};
// Initialize animations
onMounted(() => {
    AOS.init({
        once: true,
        easing: 'ease-out-cubic',
        offset: 50
    });
});

let completeJob = ({payload, jobId}) =>{
    axios.patch(`/job_update/status/${jobId}`, {type: 'complete_job', satisfaction_score: payload})
        .then(res=>{
            refreshPage()
            closeRatingModal()
            alert(res.data.message)
        })
}
</script>

<style scoped>
/* Button styles with transitions */
.btn-primary {
    @apply bg-primary hover:bg-primary-dark text-white px-4 py-2 rounded-lg flex items-center shadow-sm transition-all duration-300;
}

.btn-secondary {
    @apply bg-white hover:bg-gray-50 text-gray-700 px-4 py-2 rounded-lg flex items-center border border-gray-300 shadow-sm transition-all duration-300;
}

/* Custom animations */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in {
    animation: fadeIn 0.5s ease-out forwards;
}

/* Refresh animation */
.rotate-animation {
    animation: rotate 1s linear infinite;
}

@keyframes rotate {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
}
</style>
