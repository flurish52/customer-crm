<template>
    <div>
        <!-- Mobile Toggle Button -->
        <button
            @click="toggleSidebar"
            class="fixed z-50 md:hidden top-4 left-4 p-2 rounded-full shadow-lg bg-primary backdrop-blur-sm transition-all duration-300 hover:bg-primary-dark"
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
            <div class="flex flex-col h-full">
                <!-- Logo Section -->
                <div class="flex items-center justify-between p-6 pb-4">
                    <transition name="fade" mode="out-in">
                        <div v-if="isSidebarOpen" key="expanded" class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-lg bg-white/10 flex items-center justify-center">
                                <span class="text-white font-bold text-xl">
                              <ApplicationLogo  />
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
                            <div v-if="item.children">
                                <button
                                    @click="toggleSection(item.section)"
                                    class="flex items-center w-full p-3 rounded-xl text-white hover:bg-white/10 transition-all duration-200"
                                >
                                    <component :is="item.icon" class="h-6 w-6"/>
                                    <span v-if="isSidebarOpen" class="ml-4">{{ item.name }}</span>
                                    <svg v-if="isSidebarOpen" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-auto"
                                         :class="{ 'rotate-90': openSections[item.section], 'transition-transform': true }"
                                         fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </button>
                                <ul v-show="openSections[item.section]" class="ml-6 mt-1 space-y-1">
                                    <li v-for="child in item.children" :key="child.name">
                                        <Link :href="child.path" class="block p-2 rounded-lg text-white/80 hover:bg-white/5">
                                            {{ child.name }}
                                        </Link>
                                    </li>
                                </ul>
                            </div>
                            <div v-else>
                                <Link
                                    :href="item.path"
                                    class="flex items-center p-3 rounded-xl text-white hover:bg-white/10 transition-all duration-200"
                                >
                                    <component :is="item.icon" class="h-6 w-6"/>
                                    <span v-if="isSidebarOpen" class="ml-4">{{ item.name }}</span>
                                </Link>
                            </div>
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
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import { ref } from 'vue'
import { Link } from "@inertiajs/vue3"
import {
    HomeIcon,
    UsersIcon,
    ClipboardIcon,
    DocumentTextIcon,
    CogIcon,
    CreditCardIcon,
} from '@heroicons/vue/24/outline'

const props = defineProps({
    isSidebarOpen: { type: Boolean, default: true }
})
const emit = defineEmits(['toggle-sidebar'])
const toggleSidebar = () => { emit('toggle-sidebar') }
const openSections = ref({
    contacts: false,
    projects: false,
    billing: false
})
const toggleSection = (section) => { openSections.value[section] = !openSections.value[section] }

const navItems = [
    { name: 'Dashboard', path: '/dashboard', icon: HomeIcon },
    {
        name: 'Contacts', icon: UsersIcon, section: 'contacts',
        children: [
            { name: 'Prospects', path: '/dashboard/prospects' },
            { name: 'Clients', path: '/dashboard/customers' }
        ]
    },
    {
        name: 'Projects', icon: ClipboardIcon, section: 'projects',
        children: [
            { name: 'Quotes/Proposal', path: '/dashboard/quote_proposals' },
            { name: 'Jobs', path: '/dashboard/jobs' },
        ]
    },
    {
        name: 'Billing', icon: CreditCardIcon, section: 'billing',
        children: [
            { name: 'Invoices', path: '/dashboard/invoices' },
            { name: 'Payments', path: '/dashboard/payments' }
        ]
    },
    { name: 'Settings', path: '/dashboard/settings', icon: CogIcon }
]
</script>
<style scoped>
/* Sidebar background and transitions */
aside {
    background-color: #0e3a3a;
    color: #fff;
    transition: width 0.3s ease, transform 0.3s ease;
}

/* Links */
nav a {
    display: flex;
    align-items: center;
    padding: 0.75rem 1rem;
    border-radius: 0.75rem;
    transition: all 0.2s ease;
    position: relative;
}

/* Active link highlight */
nav a.active,
nav a:hover {
    background-color: rgba(255, 255, 255, 0.1);
    color: #ffffff;
}

/* Sidebar collapsed text hidden */
.sidebar-collapsed span {
    display: none;
}

/* Nested items */
nav ul ul {
    margin-left: 1.5rem;
    margin-top: 0.25rem;
    transition: max-height 0.3s ease, opacity 0.3s ease;
    overflow: hidden;
}

/* Rotate arrow icon when section open */
button svg.rotate-90 {
    transform: rotate(90deg);
    transition: transform 0.3s ease;
}

/* Scrollbar for sidebar */
nav::-webkit-scrollbar {
    width: 6px;
}
nav::-webkit-scrollbar-track {
    background: transparent;
}
nav::-webkit-scrollbar-thumb {
    background: rgba(255, 255, 255, 0.2);
    border-radius: 3px;
}
nav::-webkit-scrollbar-thumb:hover {
    background: rgba(255, 255, 255, 0.4);
}

/* Profile section */
aside .user-profile {
    background-color: rgba(255, 255, 255, 0.05);
    padding: 0.75rem;
    border-radius: 0.75rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

/* User avatar */
aside .user-profile img {
    width: 2.5rem;
    height: 2.5rem;
    border-radius: 9999px;
    border: 2px solid rgba(255, 255, 255, 0.2);
}

/* User name and email */
aside .user-profile .user-info p:first-child {
    font-size: 0.875rem;
    font-weight: 500;
}
aside .user-profile .user-info p:last-child {
    font-size: 0.75rem;
    color: rgba(255, 255, 255, 0.6);
}

/* Collapse toggle button */
aside button.toggle-collapse {
    background: transparent;
    border: none;
    cursor: pointer;
}

/* Mobile overlay */
.fixed.inset-0.bg-black\/50 {
    background-color: rgba(0, 0, 0, 0.5);
}

/* Fade and slide-fade transitions */
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.2s ease;
}
.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
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

</style>
