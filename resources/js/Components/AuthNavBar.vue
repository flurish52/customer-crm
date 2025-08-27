<template>
    <header class="bg-white shadow-sm sticky top-0 text-primary z-30">
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Left section (logo and mobile menu button) -->
                <div class="flex items-center">
                    <!-- Mobile menu button (only visible when sidebar is hidden) -->
                    <button
                        @click="$emit('toggleSidebar')"
                        class="md:hidden p-2 rounded-md hover:text-primary-dark focus:outline-none"
                    >
                        <span class="sr-only">Open sidebar</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>

                    <!-- App name - visible on mobile -->
                    <div class="flex-shrink-0 flex items-center md:hidden">
                        <div
                            class="hidden md:block w-8 h-8 rounded-full bg-primary flex items-center justify-center text-white font-bold">
                            <ApplicationLogo />
                        </div>
                        <span class="ml-2 text-lg font-semibold text-white">Entroly</span>
                    </div>
                </div>
                <!-- Desktop Navbar Greeting (original but enhanced) -->
                <div class="w-auto hidden md:flex items-center space-x-2">
                    <span class="text-primary">Hi,</span>
                    <h1 class="text-lg font-semibold text-primary truncate max-w-[160px]">
                        {{ $page.props.auth.user.name }}
                    </h1>
                </div>

                <!-- Search bar (center section) -->
                <div class="flex-1 max-w-md mx-4 hidden md:block">
                    <div class="relative">
<!--                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">-->
<!--                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none"-->
<!--                                 viewBox="0 0 24 24" stroke="currentColor">-->
<!--                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"-->
<!--                                      d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>-->
<!--                            </svg>-->
<!--                        </div>-->
<!--                        <input-->
<!--                            type="text"-->
<!--                            placeholder="Search customers, jobs, receipts..."-->
<!--                            class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-tertiary-light placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary-light focus:border-primary-light sm:text-sm transition-all duration-200"-->
<!--                        >-->
                    </div>
                </div>

                <!-- Right section (user profile and notifications) -->
                <div class="flex items-center">
                    <!-- Notification button -->
                    <button
                        class="p-2 rounded-full text-primary hover:text-primary hover:bg-tertiary-light focus:outline-none relative transition-colors duration-200"
                        @click="toggleNotifications"
                    >
                        <span class="sr-only">View notifications</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        <span
                            v-if="unreadNotifications > 0"
                            class="absolute top-0 right-0 h-3 w-3 rounded-full bg-secondary border-2 border-red-200"
                        ></span>
                    </button>

                    <!-- Messages button -->
<!--                    <button-->
<!--                        class="ml-2 p-2 rounded-full text-gray-500 hover:text-primary-DEFAULT hover:bg-tertiary-light focus:outline-none relative transition-colors duration-200"-->
<!--                    >-->
<!--                        <span class="sr-only">View messages</span>-->
<!--                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"-->
<!--                             stroke="currentColor">-->
<!--                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"-->
<!--                                  d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>-->
<!--                        </svg>-->
<!--                        <span-->
<!--                            v-if="unreadMessages > 0"-->
<!--                            class="absolute top-0 right-0 h-3 w-3 rounded-full bg-secondary-DEFAULT border-2 border-white"-->
<!--                        ></span>-->
<!--                    </button>-->

                    <!-- Quick action dropdown -->
                    <div class="ml-4 relative">
                        <button
                            @click="toggleQuickActions"
                            class="p-2 rounded-full text-primary hover:text-primary hover:bg-tertiary-light focus:outline-none transition-colors duration-200"
                        >
                            <span class="sr-only">Quick actions</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                        </button>

                        <!-- Quick actions dropdown menu -->
                        <transition
                            enter-active-class="transition ease-out duration-100"
                            enter-from-class="transform opacity-0 scale-95"
                            enter-to-class="transform opacity-100 scale-100"
                            leave-active-class="transition ease-in duration-75"
                            leave-from-class="transform opacity-100 scale-100"
                            leave-to-class="transform opacity-0 scale-95"
                        >
                            <div
                                v-show="showQuickActions"
                                class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50"
                            >
                                <div class="py-1">
                                    <button
                                        @click="openAddCustomerModal"
                                        class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-tertiary-light hover:text-primary"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                             class="mr-3 h-5 w-5 text-gray-400 group-hover:text-primary"
                                             fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                        </svg>
                                        New Customer
                                    </button>
                                    <a
                                        href="#"
                                        class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-tertiary-light hover:text-primary-DEFAULT"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                             class="mr-3 h-5 w-5 text-gray-400 group-hover:text-primary"
                                             fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        Generate Receipt
                                    </a>
                                    <a
                                        href="#"
                                        class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-tertiary-light hover:text-primary-DEFAULT"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                             class="mr-3 h-5 w-5 text-gray-400 group-hover:text-primary-DEFAULT"
                                             fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/>
                                        </svg>
                                        Export Data
                                    </a>
                                </div>
                            </div>
                        </transition>
                    </div>

                    <!-- Profile dropdown -->
                    <div class="ml-4 relative">
                        <div>
                            <button
                                @click="toggleProfileDropdown"
                                class="flex items-center max-w-xs rounded-full focus:outline-none focus:ring-2 focus:ring-primary-light"
                                id="user-menu"
                                aria-expanded="false"
                                aria-haspopup="true"
                            >
                                <span class="sr-only">Open user menu</span>
                                <img
                                    v-if="$page.props.auth.user.name !== null"
                                    :src="$page.props.auth.user.avatar"
                                    alt="User profile"
                                    class="w-10 h-10 rounded-full object-cover border-2 border-tertiary hover:border-tertiary-dark transition-all duration-300"
                                >
                                <svg
                                    v-else
                                    width="50"
                                    height="50"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="#0E9C9A"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    xmlns="http://www.w3.org/2000/svg"
                                >
                                    <circle cx="12" cy="8" r="4" />
                                    <path d="M4 20c0-4 8-4 8-4s8 0 8 4" />
                                </svg>
                            </button>
                        </div>

                        <!-- Profile dropdown menu -->
                        <transition
                            enter-active-class="transition ease-out duration-100"
                            enter-from-class="transform opacity-0 scale-95"
                            enter-to-class="transform opacity-100 scale-100"
                            leave-active-class="transition ease-in duration-75"
                            leave-from-class="transform opacity-100 scale-100"
                            leave-to-class="transform opacity-0 scale-95"
                        >
                            <div
                                v-show="showProfileDropdown"
                                class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50"
                                role="menu"
                                aria-orientation="vertical"
                                aria-labelledby="user-menu"
                            >
                                <a
                                    href="/dashboard/profile"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-tertiary-light hover:text-primary"
                                    role="menuitem"
                                >
                                    Your Profile
                                </a>
                                <a
                                    href="/dashboard/settings"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-tertiary-light hover:text-primary"
                                    role="menuitem"
                                >
                                    Settings
                                </a>
                                <a
                                    href="#"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-tertiary-light hover:text-primary"
                                    role="menuitem"
                                >
                                    Help
                                </a>
                                <div class="border-t border-red-100"></div>
                                <button
                                    @click="logout"
                                    class="block px-4 py-2 text-sm text-red-500 hover:bg-tertiary-light hover:text-red-600"
                                    role="menuitem"
                                >
                                    Sign out
                                </button>
                            </div>
                        </transition>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile search (hidden on desktop) -->
        <div class="md:hidden px-4 pb-3">
            <div class="relative">
<div>
    <!-- Greeting Text -->
    <div class="flex flex-col">
        <span class="text-xs text-primary-dark">Welcome back</span>
        <span class="text-xl font-medium text-primary truncate max-w-[120px]">
        {{ $page.props.auth.user.name.split(' ')[0] }}
      </span>
    </div>

</div>
<!--                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">-->
<!--                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none"-->
<!--                         viewBox="0 0 24 24" stroke="currentColor">-->
<!--                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"-->
<!--                              d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>-->
<!--                    </svg>-->
<!--                </div>-->
<!--                <input-->
<!--                    type="text"-->
<!--                    placeholder="Search..."-->
<!--                    class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-tertiary-light placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary-light focus:border-primary-light sm:text-sm"-->
<!--                >-->
            </div>
        </div>

        <!-- Notifications panel (shown when notifications button is clicked) -->
        <transition
            enter-active-class="transform ease-out duration-300 transition"
            enter-from-class="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
            enter-to-class="translate-y-0 opacity-100 sm:translate-x-0"
            leave-active-class="transition ease-in duration-100"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div
                v-show="showNotifications"
                class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg overflow-hidden z-50 border border-tertiary-dark"
            >
                <div class="p-4 border-b border-tertiary-dark">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-medium text-primary-dark">Notifications</h3>
                        <button
                            @click="markAllAsRead"
                            class="text-sm text-secondary-DEFAULT hover:text-secondary-dark"
                        >
                            Mark all as read
                        </button>
                    </div>
                </div>
                <div class="divide-y divide-tertiary-light max-h-96 overflow-y-auto">
                    <a
                        v-for="notification in notifications"
                        :key="notification.id"
                        href="#"
                        class="block px-4 py-3 hover:bg-tertiary-light transition-colors duration-150"
                        :class="{ 'bg-blue-50': notification.unread }"
                    >
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div
                                    class="h-10 w-10 rounded-full bg-primary-light flex items-center justify-center text-white">
                                    <component :is="notification.icon" class="h-5 w-5"/>
                                </div>
                            </div>
                            <div class="ml-3 flex-1">
                                <p class="text-sm font-medium text-gray-900">{{ notification.title }}</p>
                                <p class="text-sm text-gray-500">{{ notification.message }}</p>
                                <p class="text-xs text-gray-400 mt-1">{{ notification.time }}</p>
                            </div>
                            <div v-if="notification.unread" class="ml-4 flex-shrink-0">
                                <span class="h-2 w-2 rounded-full bg-secondary-DEFAULT"></span>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="p-3 border-t border-tertiary-dark text-center">
                    <button class="text-sm font-medium text-primary-DEFAULT hover:text-primary-dark">
                        View all notifications
                    </button>
                </div>
            </div>
        </transition>
    </header>


    <div v-if="showAddCustomerModal"
         @click.self="closeAddCustomerModal"
         class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <AddCustomerModal
            @closeAddCustomerModal="closeAddCustomerModal"
        />
    </div>
</template>

<script setup>
import {ref} from 'vue'

import {
    BellIcon,
    ChatBubbleLeftIcon,
    PlusIcon,
    DocumentChartBarIcon,
    ArrowDownTrayIcon,
    UserIcon,
    Cog6ToothIcon,
    QuestionMarkCircleIcon,
    CheckIcon,
    DocumentTextIcon
} from '@heroicons/vue/24/outline'
import AddCustomerModal from "@/Components/Customer/AddCustomerModal.vue";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";

const emit = defineEmits(['toggleSidebar'])

const showProfileDropdown = ref(false)
const showQuickActions = ref(false)
const showNotifications = ref(false)
const unreadNotifications = ref(3)
const unreadMessages = ref(1)

const notifications = ref([
    // {
    //     id: 1,
    //     title: 'New customer added',
    //     message: 'Sarah Johnson has been added to your customers',
    //     time: '5 minutes ago',
    //     unread: true,
    //     icon: UserIcon
    // },
])

const toggleProfileDropdown = () => {
    showProfileDropdown.value = !showProfileDropdown.value
    showQuickActions.value = false
    showNotifications.value = false
}
let showAddCustomerModal = ref(false)

const logout = () => {
    axios.post('/logout')
        .then(res => {
            window.location.href = '/login'
        })
}

const openAddCustomerModal = () => {
    showAddCustomerModal.value = true
}

const closeAddCustomerModal = () => {
    showAddCustomerModal.value = false
}
const toggleQuickActions = () => {
    showQuickActions.value = !showQuickActions.value
    showProfileDropdown.value = false
    showNotifications.value = false
}

const toggleNotifications = () => {
    showNotifications.value = !showNotifications.value
    showProfileDropdown.value = false
    showQuickActions.value = false
}

const markAllAsRead = () => {
    notifications.value = notifications.value.map(n => ({...n, unread: false}))
    unreadNotifications.value = 0
}
</script>


<style scoped>
/* Custom scrollbar for notifications */
.notifications-panel::-webkit-scrollbar {
    width: 6px;
}

.notifications-panel::-webkit-scrollbar-track {
    background: #f5f0e6;
}

.notifications-panel::-webkit-scrollbar-thumb {
    background: #0E9C9A;
    border-radius: 3px;
}

.notifications-panel::-webkit-scrollbar-thumb:hover {
    background: #0B7A78;
}
</style>
