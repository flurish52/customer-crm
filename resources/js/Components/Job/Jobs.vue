<template>
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
                    <h3 class="text-2xl font-bold">{{ stat.value }}</h3>
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
    <div class="space-y-4">
        <!-- List View with Staggered Animations -->
        <transition-group
            v-if="!selectedJob"
            name="staggered-fade"
            tag="div"
            class="space-y-3"
            appear
            @before-enter="beforeEnter"
            @enter="enter"
        >
            <div
                v-for="(job, index) in jobs"
                :key="job.id"
                :data-index="index"
                class="bg-gradient-to-br from-white to-primary/20 border border-primary shadow-md p-4 rounded-xl cursor-pointer transition-all duration-300 hover:shadow-xl hover:-translate-y-1.5"
                v-motion-slide-visible-once-bottom
            >
                <!-- Job Header -->
                <div class="flex justify-between items-start mb-3">
                    <div class="max-w-[80%]">
                        <h3 class="font-semibold text-lg truncate text-primary-dark">
                            {{ job.job_title }}
                        </h3>
                        <div class="flex items-center mt-1">
                            <p class="text-sm opacity-80 truncate text-secondary-gray-400">
                                {{ job.customer.company }} • {{ job.customer.name }}
                            </p>
                            <span v-if="job.customer.is_owing"
                                  class="ml-2 text-xs px-2 py-0.5 bg-red-100 text-red-700 rounded-full">
                                Owing
                            </span>
                        </div>
                    </div>
                    <span
                        :class="{
                            'bg-gray-100 text-gray-700': !job.status,
                            'bg-green-100 text-green-700': job.status === 'completed',
                            'bg-blue-100 text-blue-700': job.status === 'in_progress',
                            'bg-yellow-100 text-yellow-700': job.status === 'pending',
                            'bg-red-100 text-red-700': job.status === 'overdue'
                        }"
                        class="text-xs font-medium px-3 py-1 rounded-full capitalize shadow-sm transform transition-all hover:scale-105"
                    >
                        {{ job.status || 'not started' }}
                    </span>
                </div>

                <!-- Job Details -->
                <div class="mt-4 pt-4" :style="{ borderTop: '1px solid rgba(var(--primary-rgb), 0.1)' }">
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div class="space-y-2">
                            <div class="flex items-center">
                                <div class="p-2 rounded-lg mr-2"
                                     :style="{ background: 'rgba(var(--primary-rgb), 0.1)' }">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                         viewBox="0 0 24 24" stroke="currentColor" :style="{ color: 'var(--primary)' }">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Amount</p>
                                    <p class="text-sm font-medium">{{ formatCurrency(job.amount) }}</p>
                                </div>
                            </div>
                            <div v-if="job.due_date" class="flex items-center">
                                <div class="p-2 rounded-lg mr-2"
                                     :style="{ background: 'rgba(var(--primary-rgb), 0.1)' }">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                         viewBox="0 0 24 24" stroke="currentColor" :style="{ color: 'var(--primary)' }">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Due Date</p>
                                    <p class="text-sm font-medium">{{ formatDate(job.due_date) }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <div class="flex items-center">
                                <div class="p-2 rounded-lg mr-2"
                                     :style="{ background: 'rgba(var(--primary-rgb), 0.1)' }">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                         viewBox="0 0 24 24" stroke="currentColor" :style="{ color: 'var(--primary)' }">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Paid</p>
                                    <p class="text-sm font-medium text-green-600">
                                        {{ formatCurrency(totalPaidPerJob[job.id]) }}</p>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <div class="p-2 rounded-lg mr-2"
                                     :style="{ background: 'rgba(var(--primary-rgb), 0.1)' }">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                         viewBox="0 0 24 24" stroke="currentColor" :style="{ color: 'var(--primary)' }">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Balance</p>
                                    <p class="text-sm font-medium" :class="getBalanceClass(job)">
                                        {{ formatCurrency(getJobBalance(job)) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <div v-if="job.satisfaction_score"
                             class="flex items-center rounded-full px-3 py-1.5 backdrop-blur-sm"
                             :style="{ background: 'rgba(var(--primary-rgb), 0.15)' }">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor" :style="{ color: 'var(--primary)' }">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"/>
                            </svg>
                            <span class="text-xs font-medium">{{ job.satisfaction_score }}/5</span>
                        </div>
                        <div v-else></div>

                        <!-- Action Buttons -->
                        <div class="flex justify-end space-x-2">
                            <button
                                @click.stop="selectJob(job)"
                                class="group flex items-center justify-center p-2 rounded-lg transition-all duration-200 hover:bg-white hover:shadow-md"
                                :style="{ border: '1px solid rgba(var(--primary-rgb), 0.3)' }"
                                v-tooltip="'View details'"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                     stroke="currentColor" :style="{ color: 'var(--primary)' }">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </button>

                            <button
                                @click.stop="editJob(job)"
                                class="group flex items-center justify-center p-2 rounded-lg transition-all duration-200 hover:bg-white hover:shadow-md"
                                :style="{ border: '1px solid rgba(59, 130, 246, 0.3)' }"
                                v-tooltip="'Edit job'"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500" fill="none"
                                     viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </button>
                            <button
                                @click.stop="deleteJob(job)"
                                class="group flex items-center justify-center p-2 rounded-lg transition-all duration-200 hover:bg-white hover:shadow-md"
                                :style="{ border: '1px solid rgba(239, 68, 68, 0.3)' }"
                                v-tooltip="'Delete job'"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-500" fill="none"
                                     viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </transition-group>

        <!-- Detailed View with Animation -->
        <transition
            v-else
            name="fade-slide"
            mode="out-in"
        >
            <div class="border rounded-lg p-5"
                 :style="{
                     backgroundColor: 'var(--secondary)',
                     borderColor: 'var(--tertiary)'
                 }">
                <ViewJob
                    @closeModal="closeModal"
                    :selectedJob="selectedJob"
                />
            </div>
        </transition>
    </div>
</template>

<script setup>
import {ref, computed} from 'vue';
import {gsap} from 'gsap';
import dayjs from 'dayjs';
import ViewJob from "@/Components/Job/ViewJob.vue";
import CreateJob from "@/Components/Job/CreateJob.vue";
import axios from "axios";
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
    jobs: {
        type: Array,
        required: true
    },
    showModal: Boolean,
    allCustomers: Array,
});

const emits = defineEmits(['closeModal', 'showModal', 'isEditing', 'update'])

const selectedJob = ref(null);
const customer = ref({});
const isEditingJob = ref(false);
const jobToEdit = ref({});


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
        currency: 'USD',
    }).format(amount);
};

const formatDate = (dateString) => {
    if (!dateString) return 'Not set';
    return dayjs(dateString).format('MMM D, YYYY');
};

// Job actions
const editJob = (job) => {
    emits('showModal');
    customer.value = job.customer;
    emits('isEditing', {payload: job})
};

const update = () => {
    closeCreateJobModal();
};

const selectJob = (job) => {
    selectedJob.value = job;
};

const closeModal = () => {
    selectedJob.value = null;
};

const closeCreateJobModal = () => {
    emits('closeModal');
    jobToEdit.value = '';
    isEditingJob.value = false;
    customer.value = {};
};

const deleteJob = (job) => {
    if (!confirm(`Are you sure you want to delete "${job.job_title}"?`)) {
        return;
    }
    axios.delete(`/job_delete/${job.id}`)
        .then(res => {
            if (res.status === 200) {
                emits('update');
                alert(res.data);
            }
        })
        .catch(error => {
            console.error('Error deleting job:', error);
            alert('Failed to delete job. Please try again.');
        });
};

// Animation functions
const beforeEnter = (el) => {
    el.style.opacity = 0;
    el.style.transform = 'translateY(20px)';
};

const enter = (el, done) => {
    gsap.to(el, {
        opacity: 1,
        y: 0,
        duration: 0.5,
        delay: el.dataset.index * 0.1,
        onComplete: done
    });
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

const totalPaidPerJob = computed(() => {
    return props.jobs.reduce((acc, job) => {
        acc[job.id] = getTotalPaid(job.activities)
        return acc
    }, {})
})
</script>

<style scoped>
/* Staggered Fade Animation */
.staggered-fade-move {
    transition: transform 0.5s ease;
}

.staggered-fade-enter-active,
.staggered-fade-leave-active {
    transition: all 0.5s ease;
}

.staggered-fade-enter-from,
.staggered-fade-leave-to {
    opacity: 0;
    transform: translateY(30px);
}

/* Fade Slide Animation */
.fade-slide-enter-active,
.fade-slide-leave-active {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.fade-slide-enter-from {
    opacity: 0;
    transform: translateY(20px);
}

.fade-slide-leave-to {
    opacity: 0;
    transform: translateY(-20px);
}

/* Button hover effects */
button {
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
}

button:hover {
    transform: translateY(-1px);
}
</style>
