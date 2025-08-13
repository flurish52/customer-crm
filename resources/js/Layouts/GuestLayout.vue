<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div
        class="flex min-h-screen flex-col items-center justify-center bg-gradient-to-br from-indigo-900 via-purple-900 to-gray-900 p-6"
    >
        <!-- Animated background elements -->
        <div class="fixed inset-0 overflow-hidden pointer-events-none">
            <div v-for="i in 8" :key="i"
                 class="absolute rounded-full bg-purple-500 opacity-10"
                 :style="{
                    width: `${Math.random() * 200 + 50}px`,
                    height: `${Math.random() * 200 + 50}px`,
                    left: `${Math.random() * 100}%`,
                    top: `${Math.random() * 100}%`,
                    animation: `float ${Math.random() * 15 + 10}s ease-in-out ${Math.random() * 5}s infinite alternate`
                }">
            </div>
        </div>

        <div class="relative z-10">
            <Link href="/">
                <ApplicationLogo class="h-24 w-24 fill-current text-purple-300 hover:text-purple-200 transition-colors duration-300" />
            </Link>
        </div>

        <div
            class="relative z-10 mt-8 w-full overflow-hidden bg-gray-900/30 backdrop-blur-sm border border-purple-500/20 px-8 py-8 shadow-lg shadow-purple-900/20 sm:max-w-md sm:rounded-2xl"
        >
            <slot />
        </div>
    </div>
</template>

<style scoped>
@keyframes float {
    0% {
        transform: translateY(0) translateX(0) rotate(0deg);
    }
    50% {
        transform: translateY(-20px) translateX(10px) rotate(5deg);
    }
    100% {
        transform: translateY(0) translateX(0) rotate(0deg);
    }
}

@media (prefers-reduced-motion: reduce) {
    [class*='rounded-full'] {
        animation: none !important;
    }
}
</style>
