<template>
    <div>
        <!-- Mobile Toggle Button -->
        <button
            @click="toggleSidebar"
            class="fixed z-50 md:hidden top-4 left-4 p-2 rounded-full shadow-lg  bg-primary backdrop-blur-sm transition-all duration-300 hover:bg-primary-dark"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24"
                 stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      :d="isSidebarOpen ? 'M6 18L18 6M6 6l12 12' : 'M4 6h16M4 12h16M4 18h16'"/>
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
            class="fixed z-40 h-screen flex flex-col transition-all duration-300 ease-in-out bg-primary-dark shadow-xl"
            :class="{
        'w-64': isSidebarOpen,
        'w-16': !isSidebarOpen,
        '-translate-x-full md:translate-x-0': !isSidebarOpen,
        'translate-x-0': isSidebarOpen
      }"
        >
            <!-- Sidebar Content -->
            <div class="flex flex-col h-full">
                <!-- Logo Section -->
                <div class="flex items-center justify-between p-6 pb-4">
                    <transition name="fade" mode="out-in">
                        <div v-if="isSidebarOpen" key="expanded" class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-lg bg-white/10 flex items-center justify-center">
                                <span class="text-white font-bold text-xl">
                                       <svg class="h-8 w-auto text-tertiary-50" viewBox="0 0 120 120"
                                            fill="currentColor">
        <path d="M60 0L120 30V90L60 120L0 90V30L60 0Z"/>
        <path d="M60 30L90 45V75L60 90L30 75V45L60 30Z" fill="teal"/>
    </svg>

                                </span>
                            </div>
                            <Link href="/">
                            <h1 class="text-xl font-bold text-white">Entroly</h1>
                            </Link>
                        </div>
                        <div v-else key="collapsed"
                             class="w-10 h-10 rounded-lg bg-white/10 flex items-center justify-center mx-auto">
                            <span class="text-white font-bold text-xl">
                                   <svg class="h-8 w-auto text-tertiary-50" viewBox="0 0 120 120" fill="currentColor">
        <path d="M60 0L120 30V90L60 120L0 90V30L60 0Z" />
        <path d="M60 30L90 45V75L60 90L30 75V45L60 30Z" fill="teal"/>
    </svg>
                            </span>
                        </div>
                    </transition>

                    <button
                        @click="toggleSidebar"
                        class="hidden md:block p-1.5 rounded-md hover:bg-primary-dark transition-all duration-200 text-tertiary-dark hover:text-tertiary-light"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  :d="isSidebarOpen ? 'M15 19l-7-7 7-7' : 'M9 5l7 7-7 7'"/>
                        </svg>
                    </button>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 overflow-y-auto px-3 py-4">
                    <ul class="space-y-1">
                        <li v-for="item in navItems" :key="item.name">
                            <Link
                                :href="item.path"
                                class="flex items-center p-3 rounded-xl transition-all duration-200 group relative overflow-hidden"
                                :class="{
                  'bg-white/10 text-white': $page.url.startsWith(item.path),
                  'text-white/80 hover:bg-white/5': !$page.url.startsWith(item.path)
                }"
                            >
                <span class="flex-shrink-0 relative z-10">
                  <component
                      :is="item.icon"
                      class="h-6 w-6 transition-all duration-200"
                      :class="{
                      'text-white': $page.url.startsWith(item.path),
                      'text-white/60 group-hover:text-white': !$page.url.startsWith(item.path)
                    }"
                  />
                </span>
                                <transition name="slide-fade">
                  <span v-if="isSidebarOpen" class="ml-4 whitespace-nowrap text-sm font-medium relative z-10">
                    {{ item.name }}
                  </span>
                                </transition>
                                <span
                                    v-if="$page.url.startsWith(item.path)"
                                    class="absolute inset-0 bg-white/5 rounded-xl"
                                ></span>
                            </Link>
                        </li>
                    </ul>
                </nav>

                <!-- User Profile -->
                <div class="px-3 pb-6 pt-2">
                    <div class="flex items-center p-3 rounded-xl bg-white/5"
                         :class="{'justify-center': !isSidebarOpen}">
                        <div class="relative">
                            <img
                                :src="$page.props.auth.user.avatar"
                                alt="User profile"
                                class="w-10 h-10 rounded-full object-cover border-2 border-white/20 hover:border-white/40 transition-all duration-300"
                            >
                            <span
                                class="absolute bottom-0 right-0 w-3 h-3 bg-green-400 rounded-full border-2 border-primary-900"></span>
                        </div>

                        <transition name="slide-fade">
                            <div v-if="isSidebarOpen" class="ml-3 flex-1 min-w-0">
                                <p class="text-sm font-medium text-white truncate">{{ $page.props.auth.user.name }}</p>
                                <p class="text-xs text-white/60 truncate">{{ $page.props.auth.user.email }}</p>
                            </div>
                        </transition>
                    </div>
                </div>
            </div>
        </aside>
    </div>
</template>

<script setup>
import {Link} from "@inertiajs/vue3";
import {
    HomeIcon,
    UsersIcon,
    ClipboardIcon,
    DocumentTextIcon,
    CogIcon,
    ChartBarIcon,
    CreditCardIcon,
} from '@heroicons/vue/24/outline'
import ApplicationLogo from "@/Components/ApplicationLogo.vue";

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
    {name: 'Dashboard', path: '/dashboard', icon: HomeIcon},
    {name: 'Customers', path: '/dashboard/customers', icon: UsersIcon},
    {name: 'Jobs', path: '/dashboard/jobs', icon: ClipboardIcon},
    {name: 'Invoices', path: '/dashboard/invoices', icon: DocumentTextIcon},
    {name: 'Payments', path: '/dashboard/payments', icon: CreditCardIcon},
    {name: 'Settings', path: '/dashboard/settings', icon: CogIcon}
]
</script>

<style scoped>
/* Fade transition */
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.2s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}

/* Slide-fade transition */
.slide-fade-enter-active {
    transition: all 0.3s ease-out;
}

.slide-fade-leave-active {
    transition: all 0.2s cubic-bezier(1, 0.5, 0.8, 1);
}

.slide-fade-enter-from,
.slide-fade-leave-to {
    transform: translateX(10px);
    opacity: 0;
}

/* Custom scrollbar */
nav::-webkit-scrollbar {
    width: 4px;
}

nav::-webkit-scrollbar-track {
    background: transparent;
}

nav::-webkit-scrollbar-thumb {
    background: rgba(255, 255, 255, 0.2);
    border-radius: 4px;
}

nav::-webkit-scrollbar-thumb:hover {
    background: rgba(255, 255, 255, 0.3);
}
</style>
