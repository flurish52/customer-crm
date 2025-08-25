<template>
    <AuthenticatedLayout>
        <Head title="Job" />
        <div v-if="showCreateInvoiceModal" class="fixed inset-0 z-50 flex items-center justify-center">

            <!-- Overlay -->
            <div class="absolute inset-0 bg-black bg-opacity-50 backdrop-blur-sm"
                 @click="showCreateInvoiceModal = false"></div>

            <!-- Modal Content -->
            <div class="relative bg-white rounded-lg shadow-lg max-w-4xl w-full max-h-[90vh] overflow-y-auto z-10 p-6">
                <button @click="showCreateInvoiceModal = false"
                        class="absolute top-3 right-3 text-gray-500 hover:text-gray-700 font-bold text-lg">×
                </button>

                <CreateInvoiceModal :selectedJob="selectedJob.id"/>
            </div>
        </div>
        <div
            class="bg-white rounded-xl shadow-xl overflow-hidden   w-full border border-gray-100 transform transition-all duration-300 hover:shadow-2xl">
            <!-- Header with subtle gradient -->
            <div
                class="flex justify-between items-start p-6 border-b border-gray-200 bg-gradient-to-r from-primary/95 to-primary-dark/95"
                data-aos="fade-down"
            >
                <div>
                    <h2 class="text-2xl font-bold text-white">{{ selectedJob.job_title }}</h2>
                    <div class="mt-2">
          <span
              :class="statusBadgeClasses"
              class="text-xs font-medium px-3 py-1 rounded-full shadow-sm transform transition-all hover:scale-105"
          >
            {{ selectedJob.status }}
          </span>
                    </div>
                </div>
                <button
                    @click="closeModal"
                    class="p-2 rounded-full hover:bg-white/20 transition-all duration-200 text-white hover:rotate-90"
                    aria-label="Close"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                              d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                              clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>

            <!-- Main Content with staggered animations -->
            <div
                class="p-6 grid grid-cols-1 md:grid-cols-2 gap-8"
                data-aos="fade-up"
                data-aos-delay="100"
            >
                <!-- Job Details -->
                <div class="space-y-6">
                    <h3 class="text-sm font-semibold uppercase tracking-wider text-primary-dark flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        Job Details
                    </h3>
                    <div class="space-y-4">
                        <div
                            v-for="(item, index) in jobDetails"
                            :key="index"
                            class="animate-detail"
                            :style="{'--delay': `${index * 50}ms`}"
                        >
                            <div
                                class="flex justify-between items-center py-2 px-3 rounded-lg hover:bg-gray-50 transition-colors">
                                <span class="text-sm text-gray-600">{{ item.label }}</span>
                                <span class="font-medium text-gray-900">{{ item.value }}</span>
                            </div>
                            <hr class="border-gray-100">
                        </div>
                    </div>
                </div>

                <!-- Customer Info -->
                <div class="space-y-6">
                    <h3 class="text-sm font-semibold uppercase tracking-wider text-primary-dark flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        Customer
                    </h3>
                    <div class="space-y-4">
                        <div class="py-2 px-3 rounded-lg hover:bg-gray-50 transition-colors">
                            <p class="font-medium text-gray-900">{{ selectedJob.customer.name }}</p>
                            <p class="text-sm text-gray-600 mt-1">{{ selectedJob.customer.company }}</p>
                        </div>

                        <div
                            v-for="(item, index) in selectedJob.customerDetails"
                            v-if="item"
                            :key="'customer-'+index"
                            class="animate-detail"
                            :style="{'--delay': `${index * 50 + 100}ms`}"
                        >
                            <div
                                class="flex items-center space-x-3 py-2 px-3 rounded-lg hover:bg-gray-50 transition-colors">
                                <div class="text-gray-400">
                                    <component :is="item.icon" class="h-4 w-4"/>
                                </div>
                                <span class="text-sm text-gray-600 flex-1">{{ item.label }}</span>
                                <span class="font-medium text-gray-900">{{ item.value }}</span>
                            </div>
                            <hr class="border-gray-100">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Description with smooth expand -->
            <div
                class="px-6 pb-6"
                data-aos="fade-up"
                data-aos-delay="200"
            >
                <h3 class="text-sm font-semibold uppercase tracking-wider text-primary-dark mb-3 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Description
                </h3>
                <div
                    class="bg-gray-50 p-4 rounded-lg border border-gray-100 transition-all duration-300 hover:border-primary/30">
                    <p class="whitespace-pre-line text-gray-700">{{
                            selectedJob.description || 'No description provided'
                        }}</p>
                </div>
            </div>

            <!-- Activity Log with timeline animation -->
            <div
                v-if="selectedJob.activities?.length"
                class="border-t border-gray-200 pt-6 px-6 pb-6"
                data-aos="fade-up"
                data-aos-delay="300"
            >
                <h3 class="text-sm font-semibold uppercase tracking-wider text-primary-dark mb-4 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Activity Log
                </h3>
                <div class="space-y-6">
                    <div
                        v-for="(activity, index) in selectedJob.activities"
                        :key="activity.id"
                        class="relative pl-8 group"
                        data-aos="fade-up"
                        :data-aos-delay="300 + (index * 50)"
                    >
                        <!-- Animated timeline dot -->
                        <div
                            class="absolute left-0 top-1 h-full w-0.5 bg-gray-200 group-hover:bg-primary transition-colors"
                            aria-hidden="true"></div>
                        <div
                            class="absolute left-0 top-1 flex h-4 w-4 items-center justify-center rounded-full bg-gray-200 group-hover:bg-primary transition-all transform group-hover:scale-125">
                            <div class="h-2 w-2 rounded-full bg-white transition-all"></div>
                        </div>
                        <!-- Activity content -->
                        <div
                            class="bg-white p-4 rounded-lg border border-gray-100 shadow-sm hover:shadow-md transition-all">
                            <div class="flex flex-col sm:flex-row sm:items-baseline sm:justify-between gap-2">
                                <p class="text-sm font-medium text-gray-900 capitalize">{{
                                        formatActivityType(activity.type)
                                    }}</p>
                                <p class="text-xs text-gray-500">{{ formatDateTime(activity.created_at) }}</p>
                            </div>

                            <!-- Animated activity details -->
                            <div
                                v-if="activity.changes"
                                class="mt-3 bg-gray-50 p-3 rounded-lg overflow-hidden transition-all duration-300 max-h-0 group-hover:max-h-96"
                            >
                                <div v-for="(value, key) in JSON.parse(activity.changes)" :key="key"
                                     class="text-sm grid grid-cols-3 gap-2 py-1">
                                    <span class="text-gray-500 col-span-1">{{ key }}:</span>
                                    <span class="font-medium text-gray-800 col-span-2">{{ value }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Submit -->
            <div class="text-right p-3">
                <button @click="showCreateInvoiceFunc"
                        class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                    Generate Invoice
                </button>
            </div>

        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import {computed, ref} from "vue";
import {onMounted} from "vue";
import {
    PhoneIcon,
    MapPinIcon,
    CurrencyDollarIcon,
    CalendarDaysIcon,
    CheckCircleIcon,
    StarIcon
} from '@heroicons/vue/24/outline'
import AOS from 'aos';
import 'aos/dist/aos.css';
import CreateInvoiceModal from "@/Components/Invoice/CreateInvoiceModal.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {Head, router} from "@inertiajs/vue3";
// Define props and emits
const props = defineProps({
    selectedJob: Object,
    fullPage: Boolean
})
const emit = defineEmits(['closeModal'])
// Initialize animations
onMounted(() => {
    AOS.init({
        once: true,
        duration: 600,
        easing: 'ease-out-cubic'
    });
})
// Status badge classes
const statusBadgeClasses = computed(() => {
    return {
        'bg-green-100 text-green-800': props.selectedJob.status === 'completed',
        'bg-blue-100 text-blue-800': props.selectedJob.status === 'in_progress',
        'bg-yellow-100 text-yellow-800': props.selectedJob.status === 'pending',
        'bg-gray-100 text-gray-800': !props.selectedJob.status,
        'shadow-green-200': props.selectedJob.status === 'completed',
        'shadow-blue-200': props.selectedJob.status === 'in_progress',
        'shadow-yellow-200': props.selectedJob.status === 'pending',
        'shadow-gray-200': !props.selectedJob.status
    }
})
// Job details array for consistent rendering
const jobDetails = computed(() => [
    {label: 'Amount', value: formatCurrency(props.selectedJob.amount), icon: CurrencyDollarIcon},
    {
        label: 'Due Date',
        value: props.selectedJob.due_date ? formatDate(props.selectedJob.due_date) : 'Not set',
        icon: CalendarDaysIcon
    },
    {
        label: 'Completed',
        value: props.selectedJob.completed_at ? formatDateTime(props.selectedJob.completed_at) : 'Not completed',
        icon: CheckCircleIcon
    },
    {
        label: 'Satisfaction',
        value: props.selectedJob.satisfaction_score ? `${props.selectedJob.satisfaction_score}/5` : 'Not rated',
        icon: StarIcon
    }
].filter(item => item.value !== undefined))

// Customer details array
const customerDetails = computed(() => [
    {label: 'Phone', value: props.selectedJob.customer.phone, icon: PhoneIcon},
    {label: 'Address', value: props.selectedJob.customer.address, icon: MapPinIcon}
].filter(item => item.value))

// Formatting functions
const formatCurrency = (amount) => {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'NGN',
    }).format(amount)
}

const formatDateTime = (dateString) => {
    return new Date(dateString).toLocaleString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    })
}

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    })
}

const formatActivityType = (type) => {
    return type.split('_').map(word =>
        word.charAt(0).toUpperCase() + word.slice(1)
    ).join(' ')
}

let showCreateInvoiceModal = ref(false);

const showCreateInvoiceFunc = () => {
    showCreateInvoiceModal.value = true
}
const closeCreateInvoiceFunc = () => {
    emit('')
}
const closeModal = () => {
 router.back()
}
</script>

<style scoped>
/* Custom animations */
.animate-detail {
    animation: slideIn var(--delay, 0ms) ease-out forwards;
    opacity: 0;
    transform: translateX(-10px);
}

@keyframes slideIn {
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

/* Smooth transitions */
.hover-rotate {
    transition: transform 0.3s ease;
}

.hover-rotate:hover {
    transform: rotate(90deg);
}

/* Glow effect for status badge */
.status-glow {
    box-shadow: 0 0 8px currentColor;
}
</style>
