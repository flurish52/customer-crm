<template>
    <div class=" bg-gradient-to-br from-gray-900 via-gray-700 to-gray-900 overflow-hidden">
        <!-- Animated background elements -->
        <div class="absolute top-0 left-0 w-full h-full overflow-hidden pointer-events-none z-0">
            <div v-for="i in 8" :key="i"
                 class="absolute rounded-full bg-primary/20 opacity-30 animate-float"
                 :class="`bubble-${i}`"
                 :style="bubbleStyle(i)"></div>
        </div>

        <!-- Main content -->
        <div class="relative z-10 max-h-fit flex flex-col">
            <!-- Header -->
            <header class="px-6 py-4 sm:px-12 flex justify-between items-center">
                <div class="flex items-center space-x-2">
                    <div class="w-8 h-8 rounded-lg bg-gray-900 text-red-600 flex items-center justify-center font-bold">
                        <svg class="h-8 w-auto text-white" viewBox="0 0 120 120" fill="currentColor">
                            <path d="M60 0L120 30V90L60 120L0 90V30L60 0Z" />
                            <path d="M60 30L90 45V75L60 90L30 75V45L60 30Z" fill="#111827"/>
                        </svg>
                    </div>
                    <h1 class="text-2xl font-bold text-white">
                        Entroly
                    </h1>
                </div>
                <div v-if="$page.props.auth.user" class="hidden md:flex justify-center items-center space-x-4">
                    <button @click="navigateUser('dashboard')"
                            class="px-4 py-2 text-sm font-medium text-white bg-primary hover:bg-primary-dark rounded-full transition-all duration-300 hover:scale-105 shadow-md hover:shadow-primary/40">
                        Dashboard
                    </button>
                </div>
                <div v-else class="hidden md:flex justify-center items-center space-x-4">
                    <button @click="navigateUser('login')"
                            class="px-4 py-2 text-sm font-medium text-gray-300 hover:text-primary transition-colors duration-300">
                        Login
                    </button>
                    <button @click="navigateUser('register')"
                            class="px-4 py-2 text-sm font-medium text-white bg-primary hover:bg-primary-dark rounded-full transition-all duration-300 hover:scale-105 shadow-md hover:shadow-primary/40">
                        Start free
                    </button>
                </div>
                <button class="md:hidden text-gray-300 hover:text-primary">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
                    </svg>
                </button>
            </header>

            <!-- Hero section -->
            <main class="flex-grow flex items-center justify-center px-6 sm:px-12 py-12 sm:py-6">
                <div class="max-w-4xl mx-auto text-center">
                    <h1 class="text-4xl sm:text-5xl md:text-6xl font-bold mb-6 leading-tight text-white">
                        <span class="bg-clip-text text-transparent bg-gradient-to-r from-primary to-primary-light">Organize</span>
                        your Clients, jobs, and Invoices
                    </h1>
                    <p class="text-lg sm:text-xl text-gray-300 max-w-2xl mx-auto mb-8">
                        Entroly helps you manage client relationships, track jobs, and generate professional Invoices - all in one beautiful interface.
                    </p>
                    <div
                        v-if="$page.props.auth.user"
                        class="flex flex-col sm:flex-row justify-center gap-4 mb-12">
                        <Link @click="navigateUser('dashboard')"
                              class="px-8 py-3 text-lg font-medium text-white bg-transparent border-2 border-primary hover:bg-gray-800/50 rounded-full transition-all duration-300 transform hover:scale-105">
                            Return to Dashboard
                        </Link>
                    </div>
                    <div v-else class="flex flex-col sm:flex-row justify-center gap-4 mb-12">
                        <Link @click="navigateUser('login')"
                              class="px-8 py-3 text-lg font-medium text-white bg-transparent border-2 border-primary hover:bg-gray-800/50 rounded-full transition-all duration-300 transform hover:scale-105">
                            Login
                        </Link>
                        <Link @click="navigateUser('register')"
                              class="px-8 py-3 text-lg font-medium text-white bg-gradient-to-r from-primary to-primary-dark hover:from-primary-light hover:to-primary rounded-full transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-primary/30">
                            Start for free
                        </Link>
                    </div>

                </div>
            </main>

            <!-- Feature preview cards -->
            <div class="hidden lg:flex bottom-0 left-0 right-0 justify-center gap-8 px-12 pb-12">
                <!-- Customer Management Card -->
                <div class="w-56 h-56 bg-gray-800/50 border border-gray-700 rounded-xl backdrop-blur-sm transform transition-all duration-500 hover:scale-105 hover:shadow-lg hover:shadow-primary/20 overflow-hidden">
                    <div class="p-4 h-full flex flex-col">
                        <div class="flex items-center mb-4">
                            <div class="w-10 h-10 rounded-full bg-primary/20 flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-gray-200">Client Management</span>
                        </div>
                        <div class="flex-grow bg-gray-700/30 rounded-lg p-3">
                            <div class="space-y-2">
                                <div class="h-2 bg-primary/30 rounded-full"></div>
                                <div class="h-2 bg-primary/30 rounded-full w-3/4"></div>
                                <div class="h-2 bg-primary/30 rounded-full w-1/2"></div>
                                <div class="h-2 bg-primary/30 rounded-full w-5/6 mt-4"></div>
                                <div class="h-2 bg-primary/30 rounded-full w-2/3"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Job Tracking Card -->
                <div class="w-56 h-56 bg-gray-800/50 border border-gray-700 rounded-xl backdrop-blur-sm transform transition-all duration-500 hover:scale-105 hover:shadow-lg hover:shadow-primary/20 overflow-hidden">
                    <div class="p-4 h-full flex flex-col">
                        <div class="flex items-center mb-4">
                            <div class="w-10 h-10 rounded-full bg-secondary/20 flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-gray-200">Job Tracking</span>
                        </div>
                        <div class="flex-grow bg-gray-700/30 rounded-lg p-3 flex items-center justify-center">
                            <div class="w-full space-y-3">
                                <div class="flex items-center space-x-2">
                                    <div class="w-3 h-3 rounded-full bg-secondary/50"></div>
                                    <div class="h-2 bg-secondary/30 rounded-full flex-1"></div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <div class="w-3 h-3 rounded-full bg-secondary/50"></div>
                                    <div class="h-2 bg-secondary/30 rounded-full flex-1"></div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <div class="w-3 h-3 rounded-full bg-secondary/50"></div>
                                    <div class="h-2 bg-secondary/30 rounded-full flex-1"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Receipt Generation Card -->
                <div class="w-56 h-56 bg-gray-800/50 border border-gray-700 rounded-xl backdrop-blur-sm transform transition-all duration-500 hover:scale-105 hover:shadow-lg hover:shadow-primary/20 overflow-hidden">
                    <div class="p-4 h-full flex flex-col">
                        <div class="flex items-center mb-4">
                            <div class="w-10 h-10 rounded-full bg-tertiary/20 flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-tertiary" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"></path>
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-gray-200">Invoices/Receipts</span>
                        </div>
                        <div class="flex-grow bg-gray-700/30 rounded-lg p-3 flex items-center justify-center">
                            <div class="w-4/5 h-10 bg-tertiary/20 rounded flex items-center justify-center border border-tertiary/30">
                                <span class="text-xs text-tertiary">Generate PDF</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import {router} from "@inertiajs/vue3";

const bubbleStyle = (i) => {
    const size = Math.random() * 100 + 50
    return {
        width: `${size}px`,
        height: `${size}px`,
        left: `${Math.random() * 100}%`,
        top: `${Math.random() * 100}%`,
        animationDuration: `${Math.random() * 20 + 10}s`,
        animationDelay: `${Math.random() * 5}s`
    }
}

const navigateUser = (route) => {
    router.visit(route);
}
</script>

<style>
@keyframes float {
    0% {
        transform: translateY(0) translateX(0);
    }
    50% {
        transform: translateY(-50px) translateX(20px);
    }
    100% {
        transform: translateY(0) translateX(0);
    }
}

.animate-float {
    animation: float linear infinite;
}

/* Set your color variables */
:root {
    --primary: #0E9C9A; /* Teal */
    --primary-light: #1BB3B1;
    --primary-dark: #0B7A78;
    --secondary: #6B46C1; /* Purple */
    --tertiary: #4299E1; /* Blue */
}
</style>
