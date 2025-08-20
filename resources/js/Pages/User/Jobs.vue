<template>
    <AuthenticatedLayout>
        <CreateJob
            :showModal="showModal"
            :customer="customer"
            :isEditingJob="isEditingJob"
            :jobToEdit="jobToEdit"
            :amountPaid="previousPayment"
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
        <!-- Analytics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div
                v-for="(stat, index) in stats"
                :key="stat.label"
                class="bg-gradient-to-br from-white to-primary/20 border border-primary/30 p-4 rounded-xl shadow-sm transition-all hover:shadow-md"
                v-motion-pop
                :style="getCardStyle(index)"
            >
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">{{ stat.label }}</p>
                        <h3 class="text-xl font-bold">{{ stat.value }}</h3>
                    </div>
                    <div class="p-3 rounded-lg" :style="{ background: stat.bgColor }">
                        <component :is="stat.icon" class="h-6 w-6" :style="{ color: stat.color }"/>
                    </div>
                </div>
                <div v-if="stat.subValue" class="mt-2 pt-2 border-t border-primary/10">
                    <p class="text-xs text-gray-500 flex items-center">
                        <span class="w-2 h-2 rounded-full mr-1" :style="{ background: stat.dotColor }"></span>
                        {{ stat.subValue }}
                    </p>
                </div>
            </div>
        </div>
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
                    class=""
                />
            </div>
        </div>
    </AuthenticatedLayout>
</template>
<script setup>
import { ref, onMounted } from 'vue';
import AOS from 'aos';
import 'aos/dist/aos.css';
import Jobs from "@/Components/Job/Jobs.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { router } from '@inertiajs/vue3'
import CreateJob from "@/Components/Job/CreateJob.vue";
import dayjs from "dayjs";
import JobRating from "@/Components/Job/JobRating.vue";
import axios from "axios";
import {computed} from "vue";
import {
    BriefcaseIcon,
    CurrencyDollarIcon,
    CheckCircleIcon,
    ClockIcon,
    ScaleIcon,
    StarIcon,
    UserGroupIcon
} from '@heroicons/vue/24/outline';
const props = defineProps({
    jobs: Array,
});
const customer = ref({});
const isEditingJob = ref(false);
const jobToEdit = ref({});
const showModal = ref(false);
const allCustomers = ref(null);
const isRefreshing = ref(false);
const selectedJobId = ref(null);
let showRatingModal = ref(false)
let previousPayment = ref(0)
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
const isEditingJobFunc = ({payload, amountPaid}) => {
    previousPayment.value =  amountPaid
    isEditingJob.value = true
    jobToEdit.value = payload
    customer.value = jobToEdit.value.customer
    showModal.value = true;
};

// Stats computation
const stats = computed(() => {
    const totalJobs = props.jobs.length;
    const completedJobs = props.jobs.filter(j => j.status === 'completed').length;
    const totalAmount = props.jobs.reduce((sum, j) => sum + Number(j.amount || 0), 0);
    const totalPaid = props.jobs.reduce((sum, j) => sum + getTotalPaid(j.activities), 0);
    const totalBalance = totalAmount - totalPaid;
    const overdueJobs = props.jobs.filter(
        j => j.due_date && dayjs().isAfter(dayjs(j.due_date)) && j.status !== "completed"
    ).length;

    const jobsWithRating = props.jobs.filter(j => j.satisfaction_score);
    const avgRating = jobsWithRating.length > 0
        ? (jobsWithRating.reduce((sum, j) => sum + Number(j.satisfaction_score), 0) / jobsWithRating.length)
        : 0;

    return [
        {
            label: "Total Jobs",
            value: totalJobs,
            subValue: `${completedJobs} completed`,
            icon: BriefcaseIcon,
            color: 'var(--primary)',
            bgColor: 'rgba(var(--primary-rgb), 0.1)',
            dotColor: '#10B981'
        },
        {
            label: "Total Amount",
            value: formatCurrency(totalAmount),
            subValue: formatCurrency(totalBalance) + ' outstanding',
            icon: CurrencyDollarIcon,
            color: '#3B82F6',
            bgColor: 'rgba(59, 130, 246, 0.1)',
            dotColor: '#F59E0B'
        },
        {
            label: "Total Paid",
            value: formatCurrency(totalPaid),
            subValue: `${overdueJobs} overdue jobs`,
            icon: CheckCircleIcon,
            color: '#10B981',
            bgColor: 'rgba(16, 185, 129, 0.1)',
            dotColor: '#EF4444'
        },
        {
            label: "Avg. Rating",
            value: avgRating.toFixed(1),
            subValue: `${jobsWithRating.length} ratings`,
            icon: StarIcon,
            color: '#F59E0B',
            bgColor: 'rgba(245, 158, 11, 0.1)',
            dotColor: '#3B82F6'
        }
    ];
});
const getCardStyle = (index) => {
    const colors = [
        {border: 'primary', bgTo: 'primary/20'},
        {border: 'blue-500', bgTo: 'blue-500/20'},
        {border: 'green-500', bgTo: 'green-500/20'},
        {border: 'yellow-500', bgTo: 'yellow-500/20'}
    ];
    return {
        borderColor: `var(--${colors[index].border})`,
        background: `linear-gradient(to bottom right, white, var(--${colors[index].bgTo}))`
    };
};
const getJobBalance = (job) => {
    const paid = totalPaidPerJob.value[job.id];
    return Number(job.amount || 0) - Number(paid);
};
const getBalanceClass = (job) => {
    const balance = getJobBalance(job);
    if (balance <= 0) return 'text-green-600';
    if (dayjs().isAfter(dayjs(job.due_date)) && job.status !== 'completed') return 'text-yellow-600';
    return 'text-red-600';
};

const getTotalPaid = (activities) => {
    if (!activities) return 0;
    return activities
        .filter(a => a.type === "payment")
        .reduce((sum, a) => {
            const changes = JSON.parse(a.changes);
            return sum + Number(changes.amount);
        }, 0);
};

const formatCurrency = (amount) => {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'NGN',
    }).format(amount)
};

const formatDate = (dateString) => {
    if (!dateString) return 'Not set';
    return dayjs(dateString).format('MMM D, YYYY');
};


let completeJob = ({payload, jobId}) =>{
    axios.patch(`/job_update/status/${jobId}`, {type: 'complete_job', satisfaction_score: payload})
        .then(res=>{
            closeRatingModal()
            alert(res.data.message)
        })
}
// Initialize animations
onMounted(() => {
    AOS.init({
        once: true,
        easing: 'ease-out-cubic',
        offset: 50
    });
});
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
