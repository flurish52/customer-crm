<template>
    <section class="bg-gradient-to-b from-white to-tertiary-dark py-6 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-6">
                <h2 class="text-4xl font-extrabold text-primary-dark mb-4">
                    Transform Your Workflow
                </h2>
                <p class="text-xl text-secondary-dark max-w-3xl mx-auto">
                    Professional tools trusted by industry leaders to drive efficiency and growth
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
                <div
                    v-for="(feature, index) in features"
                    :key="index"
                    class="group relative bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-300 hover:border-primary-200 transition-all duration-500"
                    @mouseenter="activeCard = index"
                    @mouseleave="activeCard = null"
                >
                    <!-- Animated background element -->
                    <div
                        class="absolute inset-0 bg-gradient-to-br from-tertiary-light to-primary-light opacity-0 group-hover:opacity-100 transition-opacity duration-500"
                        :class="{ 'opacity-100': activeCard === index }"
                    ></div>

                    <div class="relative p-8 h-full flex flex-col">
                        <!-- Icon with animated border -->
                        <div class="relative w-14 h-14 mb-6">
                            <div
                                class="absolute inset-0 rounded-xl border-2 border-primary-light transform group-hover:scale-110 transition-transform duration-300"
                                :class="{ 'scale-110': activeCard === index }"
                            ></div>
                            <div class="absolute inset-0 flex items-center justify-center">
                                <component
                                    :is="feature.icon"
                                    class="w-7 h-7 text-primary-dark transform group-hover:scale-125 transition-transform duration-300"
                                    :class="{ 'scale-125': activeCard === index }"
                                />
                            </div>
                        </div>

                        <!-- Content with staggered animation -->
                        <div class="flex-1">
                            <h3 class="text-xl font-bold text-primary-dark mb-3 leading-tight transition-all duration-300 group-hover:translate-x-1">
                                {{ feature.title }}
                            </h3>
                            <p class="text-gray-900  opacity-90">
                                {{ feature.description }}
                            </p>
                        </div>

                        <!-- Animated CTA -->
<!--                        <div class="mt-auto pt-4">-->
<!--              <span class="inline-flex items-center text-primary-dark font-medium group-hover:text-primary-700 transition-colors duration-300">-->
<!--                Learn more-->
<!--                <ArrowRightIcon class="ml-2 h-4 w-4 transform group-hover:translate-x-1 transition-transform duration-300" />-->
<!--              </span>-->
<!--                        </div>-->
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script setup>

import { ref } from 'vue'

const activeCard = ref(null)

import {
    ShieldCheckIcon,
    ClockIcon,
    ScaleIcon,
    CurrencyDollarIcon,
    ChartPieIcon,
    ArrowRightIcon,

} from '@heroicons/vue/24/outline'

const features = [
    {
        icon: ShieldCheckIcon,
        title: "No More Client Data Leaks",
        description: "Your address book isn’t secure. Zelvra encrypts every client detail and access log—so sensitive data never slips through the cracks."
    },
    {
        icon: ClockIcon,
        title: "Recover 12+ Hours Weekly",
        description: "Stop juggling spreadsheets. Automate follow-ups, payment tracking, and job status updates—all in one place."
    },
    {
        icon: ScaleIcon,
        title: "Double Your Client Retention",
        description: "Missed follow-ups lose clients. Zelvra’s timeline alerts ensure you’re always proactive, not reactive."
    },
    {
        icon: CurrencyDollarIcon,
        title: "See Every Dollar’s Journey",
        description: "Spreadsheets lie. Real-time profit/loss per client exposes which relationships actually grow your revenue."
    },
    {
        icon: ChartPieIcon,
        title: "One-Click Client Reports",
        description: "Impress clients with polished summaries of their project history—generated automatically during meetings."
    }
]
</script>

<style scoped>
/* Smooth entry animation for cards */
.card-enter-active {
    transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
}
.card-enter-from {
    opacity: 0;
    transform: translateY(20px);
}
</style>
