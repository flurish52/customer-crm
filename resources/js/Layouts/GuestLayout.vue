<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="min-h-screen flex flex-col items-center justify-center bg-gray-50 p-6">
        <!-- Subtle grid pattern background -->
        <div class="fixed inset-0 bg-[radial-gradient(#e5e7eb_1px,transparent_1px)] [background-size:16px_16px] opacity-10"></div>

        <div class="relative z-10 w-full max-w-md">
            <!-- Logo with professional spacing -->
            <div class="mb-12 flex flex-col items-center justify-center text-center">
                <Link href="/" class="mb-4">
                    <ApplicationLogo class="h-16 w-auto text-primary-600 hover:text-primary-700 transition-colors" />
                </Link>
                <h1 class="text-2xl font-bold tracking-tight text-gray-900">Zelvra CRM</h1>
            </div>

            <!-- Content card with clean shadow -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-10 py-12">
                    <slot />
                </div>

                <!-- Subtle footer -->
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-100 text-center">
                    <p class="text-xs text-gray-500">
                        © 2024 Zelvra. All rights reserved.
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
