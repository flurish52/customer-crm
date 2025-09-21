<script setup>
import { Link } from '@inertiajs/vue3';
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import dayjs from "dayjs";
</script>

<template>
    <!-- Header -->
    <header class="px-6 py-4 sm:px-12 flex justify-between items-center">
        <Link href="/" class="flex items-center space-x-2">
            <div class="w-8 h-8 rounded-lg text-red-600 flex items-center justify-center font-bold">
                <ApplicationLogo />
            </div>
            <h1 class="text-2xl font-bold text-primary-dark">
                Entroly
            </h1>
        </Link>
        <button class="md:hidden text-gray-300 hover:text-primary">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
            </svg>
        </button>
    </header>
    <div class="min-h-screen flex flex-col items-center justify-center bg-gray-50 p-6">
        <!-- Subtle grid pattern background -->
        <div class="relative z-10 w-full">

            <!-- Content card with clean shadow -->
            <div class="bg-gray-100 rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="md:px-10 py-">
                    <slot />
                </div>

                <!-- Subtle footer -->
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-100 text-center">
                    <p class="text-xs text-gray-500">
                        ©{{dayjs(Date()).format('YYYY')}} Entroly. All rights reserved.
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
/* Smooth transitions for interactive elements */
a, button {
    transition: all 0.2s ease;
}
</style>
