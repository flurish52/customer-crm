<template>
    <div>
        <!-- Mobile Toggle Button -->
        <button
            @click="toggleSidebar"
            class="fixed z-50 md:hidden top-4 right-4 p-2 rounded-full shadow-lg transition-all duration-300 hover:scale-110"
            :class="{
        'bg-primary text-white': !isSidebarOpen,
        'bg-secondary text-primary': isSidebarOpen
      }"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      :d="isSidebarOpen ? 'M6 18L18 6M6 6l12 12' : 'M4 6h16M4 12h16M4 18h16'" />
            </svg>
        </button>

        <!-- Overlay for mobile -->
        <transition
            enter-active-class="transition-opacity ease-out duration-300"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition-opacity ease-in duration-200"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div
                v-if="isSidebarOpen"
                @click="toggleSidebar"
                class="fixed inset-0 bg-black/50 z-40 md:hidden"
            ></div>
        </transition>

        <!-- Sidebar -->
        <aside
            class="fixed z-40 h-screen flex flex-col transition-all duration-300 ease-in-out bg-primary-dark"
            :class="{
        'w-64': isSidebarOpen,
        '-translate-x-full md:translate-x-0 md:w-20': !isSidebarOpen,
        'translate-x-0': isSidebarOpen
      }"
        >
            <!-- Sidebar Content -->
            <div class="flex flex-col h-full border-r border-gray-800 shadow-xl">
                <!-- Logo Section -->
                <div class="flex items-center justify-between p-4 border-b border-gray-800">
                    <transition name="logo" mode="out-in">
                        <div v-if="isSidebarOpen" key="expanded" class="flex items-center space-x-2">
                            <h1 class="text-xl font-bold text-white">Zelvra</h1>
                        </div>
                        <div v-else key="collapsed" class="w-10 h-10 rounded-lg bg-primary flex items-center justify-center text-white font-bold mx-auto">
                            Z
                        </div>
                    </transition>

                    <button
                        @click="toggleSidebar"
                        class="hidden md:block p-1 rounded-md hover:bg-gray-800 transition-all duration-200 text-gray-400 hover:text-white"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  :d="isSidebarOpen ? 'M15 19l-7-7 7-7' : 'M9 5l7 7-7 7'" />
                        </svg>
                    </button>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 overflow-y-auto py-4">
                    <ul class="space-y-1 px-2">
                        <li v-for="item in navItems" :key="item.name">
                            <Link
                                :href="item.path"
                                class="flex items-center p-3 rounded-lg transition-all duration-200 group"
                                :class="{
                  'bg-primary/20 text-primary': $page.url.startsWith(item.path),
                  'text-gray-300 hover:bg-gray-800 hover:text-white': !$page.url.startsWith(item.path)
                }"
                            >
                <span class="flex-shrink-0">
                  <component
                      :is="item.icon"
                      class="h-5 w-5 transition-all duration-200"
                      :class="{
                      'text-primary': $page.url.startsWith(item.path),
                      'text-gray-400 group-hover:text-white': !$page.url.startsWith(item.path)
                    }"
                  />
                </span>
                                <transition name="nav-text">
                  <span v-if="isSidebarOpen" class="ml-3 whitespace-nowrap text-sm font-medium">
                    {{ item.name }}
                  </span>
                                </transition>
                            </Link>
                        </li>
                    </ul>
                </nav>

                <!-- User Profile -->
                <div class="border-t border-gray-800 p-4">
                    <div class="flex items-center" :class="{'justify-center': !isSidebarOpen}">
                        <div class="relative">
                            <img
                                :src="$page.props.auth.user.avatar"
                                alt="User profile"
                                class="w-10 h-10 rounded-full object-cover border-2 border-gray-700 hover:border-primary transition-all duration-300"
                            >
                            <span class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 rounded-full border-2 border-gray-900"></span>
                        </div>

                        <transition name="profile">
                            <div v-if="isSidebarOpen" class="ml-3 flex-1 min-w-0">
                                <p class="text-sm font-medium text-white truncate">{{ $page.props.auth.user.name }}</p>
                                <p class="text-xs text-gray-400 truncate">{{ $page.props.auth.user.email }}</p>
                            </div>
                        </transition>
                    </div>
                </div>
            </div>
        </aside>
    </div>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";
import {
    HomeIcon,
    UsersIcon,
    ClipboardIcon,
    DocumentTextIcon,
    CogIcon,
    ChartBarIcon,
    CreditCardIcon,
} from '@heroicons/vue/24/outline'

const props = defineProps({
    isSidebarOpen: {
        type: Boolean,
        default: true
    }
})

const emit = defineEmits(['toggle-sidebar'])

const toggleSidebar = () => {
    emit('toggle-sidebar')
}

const navItems = [
    { name: 'Dashboard', path: '/dashboard', icon: HomeIcon },
    { name: 'Customers', path: '/dashboard/customers', icon: UsersIcon },
    { name: 'Jobs', path: '/dashboard/jobs', icon: ClipboardIcon },
    { name: 'Payments', path: '/dashboard/payments', icon: CreditCardIcon },
    { name: 'Settings', path: '/dashboard/settings', icon: CogIcon }
]
</script>

<style scoped>
/* Logo transition */
.logo-enter-active,
.logo-leave-active {
    transition: all 0.3s ease;
}
.logo-enter-from,
.logo-leave-to {
    opacity: 0;
    transform: translateX(-10px);
}

/* Navigation text transition */
.nav-text-enter-active {
    transition: all 0.3s ease-out;
}
.nav-text-leave-active {
    transition: all 0.2s cubic-bezier(1, 0.5, 0.8, 1);
}
.nav-text-enter-from,
.nav-text-leave-to {
    transform: translateX(5px);
    opacity: 0;
}

/* Profile transition */
.profile-enter-active {
    transition: all 0.3s ease-out;
}
.profile-leave-active {
    transition: all 0.2s ease-in;
}
.profile-enter-from,
.profile-leave-to {
    transform: translateX(10px);
    opacity: 0;
}

/* Custom scrollbar */
nav::-webkit-scrollbar {
    width: 4px;
}
nav::-webkit-scrollbar-track {
    background: rgba(255,255,255,0.05);
}
nav::-webkit-scrollbar-thumb {
    background: var(--primary);
    border-radius: 4px;
}
nav::-webkit-scrollbar-thumb:hover {
    background: color-mix(in srgb, var(--primary) 80%, white);
}

/* Active link indicator */
.router-link-active {
    position: relative;
}
.router-link-active::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 3px;
    background: var(--primary);
    border-radius: 3px 0 0 3px;
}
</style>
