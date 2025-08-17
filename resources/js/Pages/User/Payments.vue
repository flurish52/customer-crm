<template>
    <AuthenticatedLayout>
        <PaymentModal
            @cancel="closePaymentModal"
            @submit="paymentMade"
            :jobs="jobs"
            :showPaymentModal="showPaymentModal"
        />
        <div class="space-y-6">
            <!-- Page Header with Animation -->
            <div
                class="flex flex-col justify-between gap-4 md:flex-row md:items-center"
                data-aos="fade-down"
                data-aos-duration="300"
            >
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-gray-900">Payments</h1>
                    <p class="mt-1 text-sm text-gray-500">Transaction history and records</p>
                </div>
                <div class="flex gap-3">
                    <button
                        class="btn-secondary hover:scale-[1.02] transition-transform"
                        @mouseenter="hoverButton('filter')"
                        @mouseleave="resetButton"
                    >
                        <FunnelIcon class="w-5 h-5 mr-2 transition-transform" :class="{ 'rotate-12': activeButton === 'filter' }"/>
                        Filters
                    </button>
                    <button
                        class="btn-primary hover:scale-[1.02] transition-transform"
                        @mouseenter="hoverButton('record')"
                        @mouseleave="resetButton"
                        @click="openPaymentModal"
                    >
                        <BanknotesIcon class="w-5 h-5 mr-2 transition-transform" :class="{ 'scale-110': activeButton === 'record' }"/>
                        Record Payment
                    </button>
                </div>
            </div>

            <!-- Payments Component with Staggered Animation -->
            <div
                data-aos="fade-up"
                data-aos-duration="400"
                data-aos-delay="100"
            >
                <Payments
                    :payments="payments"
                    class="mt-6"
                />
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref } from 'vue';
import { BanknotesIcon, FunnelIcon } from '@heroicons/vue/24/outline';
import AOS from 'aos';
import 'aos/dist/aos.css';
import {onMounted} from "vue";
import Payments from '@/components/Payments/Payments.vue';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import PaymentModal from "@/Components/Job/PaymentModal.vue";
import {router} from "@inertiajs/vue3";

let props = defineProps({
    payments: Array,
    jobs: Array
});

const activeButton = ref(null);
const showPaymentModal = ref(false);

const hoverButton = (button) => {
    activeButton.value = button;
};

const openPaymentModal = () =>{
    showPaymentModal.value = true
}
const closePaymentModal = () =>{
    showPaymentModal.value = false
}

const paymentMade = () =>{
    router.reload()
}

const resetButton = () => {
    activeButton.value = null;
};

// Initialize animations on mount
onMounted(() => {
    AOS.init({
        once: true,
        easing: 'ease-out-cubic',
        offset: 50
    });
});
</script>

<style scoped>
/* Button animations */
.btn-primary, .btn-secondary {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    transform-origin: center;
}

.btn-primary {
    @apply bg-primary hover:bg-primary-dark text-white px-4 py-2 rounded-lg flex items-center shadow-sm;
}

.btn-secondary {
    @apply bg-white hover:bg-gray-50 text-gray-700 px-4 py-2 rounded-lg flex items-center border border-gray-300 shadow-sm;
}

/* Custom fade-in animation */
.animate-fade-in {
    animation: fadeIn 0.5s ease-out forwards;
}

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
</style>
