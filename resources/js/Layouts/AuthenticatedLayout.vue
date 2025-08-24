<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue'
import AuthSidebar from "@/Components/AuthSideBar.vue"
import AuthNavBar from "@/Components/AuthNavBar.vue";

const showingNavigationDropdown = ref(false)
const isSidebarOpen = ref(window.innerWidth >= 1024)

const handleResize = () => {
    isSidebarOpen.value = window.innerWidth >= 1024
}

onMounted(() => {
    window.addEventListener('resize', handleResize)
})

onBeforeUnmount(() => {
    window.removeEventListener('resize', handleResize)
})

const toggleSidebar = () => {
    isSidebarOpen.value = !isSidebarOpen.value
}
</script>

<template>
    <div class="min-h-screen bg-primary-light/5 flex">
        <!-- Sidebar - fixed on large screens, overlay on mobile -->
        <AuthSidebar
            :isSidebarOpen="isSidebarOpen"
            @toggleSidebar="toggleSidebar"
            class="fixed lg:static z-40"
        />

        <!-- Main content area -->
        <div
            class="flex-1 flex flex-col transition-all duration-300 ease-in-out"
            :class="{
        'lg:ml-64': isSidebarOpen,
        'lg:ml-20': !isSidebarOpen
      }"
        >
            <!-- Header -->
            <header
                class="bg-white shadow sticky top-0 z-30"
                v-if="$slots.header"
            >
                <div class="mx-auto px-4 py-4 ">
                    <slot name="header" />
                </div>
            </header>
<AuthNavBar
@toggleSidebar="toggleSidebar"
/>

            <!-- Main content -->
            <main class="flex-1 p-4 sm:p-6 lg:p-8">
                <slot />
            </main>
        </div>
    </div>
</template>
