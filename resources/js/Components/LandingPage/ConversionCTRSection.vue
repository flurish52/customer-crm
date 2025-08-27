<template>
    <section class="bg-primary-800 py-16 px-4 sm:px-6 lg:px-8 relative overflow-hidden">
        <!-- Decorative elements -->
        <div class="absolute inset-0 opacity-5">
            <div class="absolute inset-0 bg-gradient-to-r from-tertiary-400 to-primary-600 mix-blend-overlay"></div>
        </div>

        <div class="max-w-7xl mx-auto relative z-10">
            <div class="text-center mb-12">
                <div class="inline-flex items-center bg-primary-dark border border-primary-600 rounded-full px-4 py-1.5 mb-4">
                    <SignalIcon class="h-4 w-4 text-tertiary-light mr-2 animate-pulse" />
                    <span class="text-sm font-medium text-tertiary-light">
            <span class="font-bold">{{ liveCounter }}</span> businesses onboarded this week
          </span>
                </div>
                <h2 class="text-3xl md:text-4xl font-bold text-primary-dark mb-4">
                    Ready to Transform Your Client Management?
                </h2>
                <p class="text-xl text-primary-100 max-w-3xl mx-auto">
                    Join thousands who upgraded from spreadsheets to stress-free control
                </p>
            </div>

            <div class="grid md:grid-cols-2 gap-12 items-center">
                <!-- Benefit Recap -->
                <div class="space-y-5">
                    <div v-for="(benefit, index) in benefits" :key="index" class="flex items-start">
                        <div class="flex-shrink-0 bg-primary-dark p-2 rounded-lg mr-4">
                            <component :is="benefit.icon" class="h-6 w-6 text-tertiary-light" />
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-primary-dark mb-1">{{ benefit.title }}</h3>
                            <p class="text-primary-100">{{ benefit.description }}</p>
                        </div>
                    </div>
                </div>

                <!-- CTA Card -->
                <div class="bg-tertiary-50 rounded-xl shadow-2xl p-8 border border-tertiary-200">
                    <h3 class="text-2xl font-bold text-primary-dark mb-3">Get Started in 30 Seconds</h3>
                    <p class="text-primary-700 mb-6">
                        Free • No credit card needed • Upgrade anytime
                    </p>

                    <div class="space-y-4">
                        <button @click="navigate('register')" class="w-full bg-gradient-to-br from-primary-light/50 to-primary-dark hover:from-primary-700 hover:to-primary-800 text-tertiary-50 py-4 px-6 rounded-lg font-bold text-lg shadow-lg hover:shadow-primary-700/30 transition-all duration-300 transform hover:-translate-y-1">
                            Start Free Now →
                        </button>

                        <div class="flex items-center justify-center">
                            <div class="flex -space-x-2 mr-3">
                                <img v-for="(avatar, i) in avatars" :key="i" :src="avatar" class="w-8 h-8 rounded-full border-2 border-tertiary-50" />
                            </div>
                            <span class="text-sm text-primary-600">
        Join <span class="font-semibold">2,500+</span> professionals using Entroly
      </span>
                        </div>

                        <!-- Trust Badge -->
                        <div class="mt-4 flex items-center justify-center bg-tertiary-light rounded-full py-2 px-4 border border-tertiary">
                            <ShieldCheckIcon class="h-5 w-5 text-primary mr-2" />
                            <span class="text-sm font-medium text-primary-dark">No hidden costs - ever</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
<script setup>
import {
    SignalIcon,
    ShieldCheckIcon,
    ClockIcon,
    ChartBarIcon,
    UserIcon,
    ServerIcon
} from '@heroicons/vue/24/outline'
import {router} from "@inertiajs/vue3";
import { ref, onMounted } from 'vue'
// Live counter animation
const liveCounter = ref(142)
onMounted(() => {
    const interval = setInterval(() => {
        liveCounter.value += Math.floor(Math.random() * 3)
        if (liveCounter.value > 210) clearInterval(interval)
    }, 7200000)
})
const benefits = [
    {
        icon: ClockIcon,
        title: "Save 10+ Hours Weekly",
        description: "Automate client follow-ups and job tracking"
    },
    {
        icon: ChartBarIcon,
        title: "Never Miss a Payment",
        description: "Real-time revenue tracking per client"
    },
    {
        icon: UserIcon,
        title: "360° Client Profiles",
        description: "All interactions and files in one searchable place"
    },
    {
        icon: ServerIcon,
        title: "Military-Grade Security",
        description: "Your data stays private and encrypted"
    }
]
const avatars = [
    'https://randomuser.me/api/portraits/women/44.jpg',
    'https://randomuser.me/api/portraits/men/32.jpg',
    'https://randomuser.me/api/portraits/women/68.jpg'
]

const navigate=(route)=>{
    router.visit(route)
}
</script>
