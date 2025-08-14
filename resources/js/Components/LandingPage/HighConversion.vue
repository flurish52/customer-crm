<template>
    <!-- Sticky Mobile CTA (Always visible) -->
    <div class="fixed lg:hidden bottom-0 inset-x-0 bg-primary-700 shadow-lg z-50 text-white">
        <div class="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between">
            <div class="flex-1">
                <h3 class="text-sm font-bold text-tertiary-50 truncate">Get your free client management guide</h3>
            </div>
            <button
                @click="showModal = true"
                class="ml-4 flex-shrink-0 bg-tertiary-50 hover:bg-tertiary-100 text-primary-700 px-4 py-2 rounded-md text-sm font-medium transition-colors"
            >
                Download Now
            </button>
        </div>
    </div>

    <!-- Desktop Exit-Intent Footer CTA -->
    <div
        v-if="showModal"
        class=" bg-gray-800 z-50 flex items-end justify-center text-white"
        @click.self="showModal = false"
    >
        <div class="bg-tertiary-50 rounded-t-2xl shadow-xl w-full max-w-4xl animate-slide-up">
            <div class="grid md:grid-cols-2 gap-8">
                <!-- Lead Magnet Offer -->
                <div class="p-8 md:border-r border-tertiary-200">
                    <h3 class="text-2xl font-bold text-primary-900 mb-2">Free: Ultimate Client Management Guide</h3>
                    <p class="text-primary-700 mb-6">
                        Get our proven framework to manage clients efficiently- <span class="text-yellow-500 font-bold">for free!</span>  (valued at $100)
                    </p>

                    <form @submit.prevent="handleSubmit" class="space-y-4">
                        <div>
                            <input
                                type="email"
                                v-model="email"
                                placeholder="Your work email"
                                required
                                class="w-full px-4 py-3 border border-tertiary-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                            />
                        </div>
                        <button
                            type="submit"
                            class="w-full bg-primary-600 hover:bg-primary-700 text-tertiary-50 py-3 px-6 rounded-lg font-bold shadow-md transition-colors"
                        >
                            Get Free Guide →
                        </button>
                    </form>

                    <p class="mt-4 text-sm text-primary-500">
                        We'll also send you Zelvra tips. Unsubscribe anytime.
                    </p>
                </div>

                <!-- Value Prop + Chat -->
                <div class="p-8 flex flex-col">
                    <div class="flex-1">
                        <ul class="space-y-4 mb-6">
                            <li class="flex items-start">
                                <CheckCircleIcon class="h-5 w-5 text-green-500 mt-0.5 mr-2 flex-shrink-0" />
                                <span class="text-primary-800">7 templates for client onboarding</span>
                            </li>
                            <li class="flex items-start">
                                <CheckCircleIcon class="h-5 w-5 text-green-500 mt-0.5 mr-2 flex-shrink-0" />
                                <span class="text-primary-800">Email scripts to reduce follow-ups</span>
                            </li>
                            <li class="flex items-start">
                                <CheckCircleIcon class="h-5 w-5 text-green-500 mt-0.5 mr-2 flex-shrink-0" />
                                <span class="text-primary-800">Zelvra setup checklist</span>
                            </li>
                        </ul>
                    </div>

                    <div class="pt-4 border-t border-tertiary-200">
                        <button
                            @click="openChat"
                            class="flex items-center text-primary-600 hover:text-primary-800 transition-colors"
                        >
                            <ChatBubbleLeftRightIcon class="h-5 w-5 mr-2" />
<!--                            <span class="font-medium">Quick question? Chat with us</span>-->
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { CheckCircleIcon, ChatBubbleLeftRightIcon } from '@heroicons/vue/24/outline'
import { ref } from 'vue'
import {onMounted} from "vue";

const showModal = ref(false)
const email = ref('')

// Trigger modal when user scrolls up (exit intent)
onMounted(() => {
    let lastScrollTop = 0
    window.addEventListener('scroll', () => {
        const st = window.pageYOffset || document.documentElement.scrollTop
        if (st < lastScrollTop && st > 100) { // Scroll-up detection
            showModal.value = true
        }
        lastScrollTop = st <= 0 ? 0 : st
    }, { passive: true })
})

const handleSubmit = () => {
    if (!email.value) return
alert('Success!')
    email.value = ''
}

const openChat = () => {
}
</script>

<style>
.animate-slide-up {
    animation: slideUp 0.3s ease-out forwards;
}

@keyframes slideUp {
    from { transform: translateY(100%); }
    to { transform: translateY(0); }
}
</style>
