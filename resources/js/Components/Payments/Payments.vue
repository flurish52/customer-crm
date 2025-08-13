<template>
    <div class="max-w-4xl mx-auto px-4 py-6">
        <!-- Summary Card with Animation -->
        <div
            class="bg-gradient-to-r from-primary to-primary-dark rounded-xl shadow-lg overflow-hidden mb-8 transition-all duration-300"
            :class="{ 'h-48': expandedSummary, 'h-32': !expandedSummary }"
        >
            <div class="p-6 text-white relative h-full flex flex-col">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-medium opacity-90">Total Received</p>
                        <h2 class="text-3xl font-bold mt-1">{{ formatCurrency(totalReceived) }}</h2>
                        <p class="text-xs opacity-80 mt-1">{{ payments.length }} payment{{ payments.length !== 1 ? 's' : '' }}</p>
                    </div>
                    <button
                        @click="expandedSummary = !expandedSummary"
                        class="p-2 rounded-full bg-white/10 hover:bg-white/20 transition-colors"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5 transform transition-transform duration-300"
                            :class="{ 'rotate-180': expandedSummary }"
                            viewBox="0 0 20 20"
                            fill="currentColor"
                        >
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>

                <transition
                    enter-active-class="transition-opacity duration-300"
                    enter-from-class="opacity-0"
                    enter-to-class="opacity-100"
                    leave-active-class="transition-opacity duration-200"
                    leave-from-class="opacity-100"
                    leave-to-class="opacity-0"
                >
                    <div v-if="expandedSummary" class="mt-auto pt-4 border-t border-white/20">
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <p class="opacity-80">Average Payment</p>
                                <p class="font-medium">{{ formatCurrency(avgPayment) }}</p>
                            </div>
                            <div>
                                <p class="opacity-80">Last Payment</p>
                                <p class="font-medium">{{ lastPaymentDate }}</p>
                            </div>
                        </div>
                    </div>
                </transition>
            </div>
        </div>

        <!-- Payment List with Staggered Animation -->
        <transition-group
            name="list"
            tag="ul"
            class="space-y-4"
            appear
            @before-enter="beforeEnter"
            @enter="enter"
        >
            <li
                v-for="(payment, index) in payments"
                :key="payment.id"
                :data-index="index"
                class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100 hover:shadow-md transition-all duration-200"
            >
                <div class="p-5">
                    <!-- Header Row -->
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <h3 class="font-semibold text-gray-900">{{ payment.customer.name }}</h3>
                            <p class="text-xs text-gray-500 mt-1">{{ payment.customer.company || 'Individual' }}</p>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-primary">{{ formatCurrency(JSON.parse(payment.changes).amount) }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ formatDate(payment.created_at) }}</p>
                        </div>
                    </div>

                    <!-- Job Details -->
                    <div v-if="payment.subject" class="mt-3 pt-3 border-t border-gray-100">
                        <div class="flex justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-700">{{ payment.subject.job_title }}</p>
                                <div class="flex items-center mt-1">
                  <span
                      class="inline-block w-2 h-2 rounded-full mr-2"
                      :class="{
                      'bg-green-500': payment.subject.status === 'completed',
                      'bg-blue-500': payment.subject.status === 'in_progress',
                      'bg-amber-500': payment.subject.status === 'pending'
                    }"
                  ></span>
                                    <span class="text-xs text-gray-500 capitalize">{{ payment.subject.status }}</span>
                                </div>
                            </div>
                            <p class="text-sm text-gray-700">{{ formatCurrency(payment.subject.amount) }}</p>
                        </div>
                    </div>
                    <div v-else class="mt-3 pt-3 border-t border-gray-100">
                        <p class="text-sm text-gray-500 italic">No associated job</p>
                    </div>

                    <!-- Payment Meta -->
                    <div class="mt-3 pt-3 border-t border-gray-100">
                        <div class="flex justify-between items-center">
              <span class="text-xs px-2 py-1 bg-gray-100 rounded-md text-gray-700 capitalize">
                {{ formatMethod(JSON.parse(payment.changes).method) }}
              </span>
                            <button
                                @click="viewDetails(payment)"
                                class="text-xs font-medium text-primary hover:text-primary-dark transition-colors"
                            >
                                View Details
                            </button>
                        </div>
                        <p v-if="JSON.parse(payment.changes).notes" class="text-xs text-gray-500 mt-2 italic truncate">
                            "{{ JSON.parse(payment.changes).notes }}"
                        </p>
                    </div>
                </div>
            </li>
        </transition-group>
    </div>
</template>

<script setup>
import { computed, ref } from 'vue';
import gsap from 'gsap';

const props = defineProps({
    payments: Array
});

const expandedSummary = ref(false);

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

// Computed properties
const totalReceived = computed(() => {
    return props.payments.reduce((sum, payment) => {
        return sum + parseFloat(JSON.parse(payment.changes).amount || 0);
    }, 0);
});

const avgPayment = computed(() => {
    return props.payments.length ? totalReceived.value / props.payments.length : 0;
});

const lastPaymentDate = computed(() => {
    if (props.payments.length === 0) return 'N/A';
    const dates = props.payments.map(p => new Date(p.created_at));
    const lastDate = new Date(Math.max(...dates));
    return formatDate(lastDate);
});

// Helper functions
const formatCurrency = (amount) => {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'NGN',
        minimumFractionDigits: 2
    }).format(amount);
};

const formatDate = (dateString) => {
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', {
        month: 'short',
        day: 'numeric',
        year: 'numeric'
    });
};

const formatMethod = (method) => {
    return method.split('_').map(word =>
        word.charAt(0).toUpperCase() + word.slice(1)
    ).join(' ');
};

const viewDetails = (payment) => {
    // Implement your detail view logic
    console.log('View details for payment:', payment.id);
};
</script>

<style scoped>
.list-move {
    transition: transform 0.5s ease;
}

.list-enter-active,
.list-leave-active {
    transition: all 0.5s ease;
}
.list-enter-from,
.list-leave-to {
    opacity: 0;
    transform: translateY(30px);
}
</style>
