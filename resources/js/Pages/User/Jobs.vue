<template>
    <AuthenticatedLayout>
        <div class="space-y-6">
            <!-- Animated Page Header -->
            <div
                class="flex flex-col justify-between gap-4 md:flex-row md:items-center"
                data-aos="fade-down"
                data-aos-duration="300"
            >
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-gray-900">Jobs</h1>
                    <p class="mt-1 text-sm text-gray-500">Track current and completed work</p>
                </div>
                <div class="flex gap-3">
                    <button
                        @click="refreshPage"
                        class="btn-secondary hover:scale-[1.02] transition-transform"
                        @mouseenter="hoverButton('refresh')"
                        @mouseleave="resetButton"
                    >
                        <ArrowPathIcon
                            class="w-5 h-5 mr-2 transition-transform duration-300"
                            :class="{ 'rotate-180': isRefreshing || activeButton === 'refresh' }"
                        />
                        {{ isRefreshing ? 'Refreshing...' : 'Refresh' }}
                    </button>
                    <button
                        @click="showCreateJobModal"
                        class="btn-primary hover:scale-[1.02] transition-transform"
                        @mouseenter="hoverButton('newJob')"
                        @mouseleave="resetButton"
                    >
                        <PlusIcon
                            class="w-5 h-5 mr-2 transition-transform"
                            :class="{ 'scale-125': activeButton === 'newJob' }"
                        />
                        New Job
                    </button>
                </div>
            </div>

            <!-- Jobs Component with Staggered Animation -->
            <div
                data-aos="fade-up"
                data-aos-duration="400"
                data-aos-delay="100"
            >
                <Jobs
                    :showModal="showModal"
                    :jobs="jobs"
                    :allCustomers="allCustomers"
                    @closeModal="closeModal"
                    @showModal="showCreateJobModal"
                    @update="refreshPage"
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

defineProps({
    jobs: Array,
});

const showModal = ref(false);
const allCustomers = ref(null);
const isRefreshing = ref(false);
const activeButton = ref(null);

// Animation control
const hoverButton = (button) => {
    activeButton.value = button;
};

const resetButton = () => {
    activeButton.value = null;
};

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

const closeModal = () => {
    showModal.value = false;
    allCustomers.value = null;
};

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
