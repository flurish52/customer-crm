<template>
    <AuthenticatedLayout>

        <Head title="Settings" />
        <div class="">
        <!-- Page Header -->
        <div class="flex flex-col justify-between gap-4 md:flex-row md:items-center">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-gray-900">Settings</h1>
                <p class="m text-sm text-gray-500">Manage your account and preferences</p>
            </div>
        </div>
        <!-- Settings Navigation -->
        <nav class="flex overflow-x-auto border-b border-gray-200">
            <button
                v-for="tab in tabs"
                :key="tab.name"
                class="px-4 py-2 text-sm font-medium whitespace-nowrap"
                :class="activeTab === tab.name ? 'text-indigo-600 border-b-2 border-indigo-600' : 'text-gray-500 hover:text-gray-700'"
                @click="activeTab = tab.name"
            >
                {{ tab.name }}
            </button>
        </nav>

        <!-- Mount existing Settings component -->
        <Settings
            class="mt-2 animate-fade-in"
            :active-tab="activeTab"
            :user="user"
        />
    </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref } from 'vue'
import Settings from '@/components/User/Settings.vue'
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {Head} from "@inertiajs/vue3";
const props = defineProps({
    user: Object,
})
const tabs = [
    { name: 'General' },
    { name: 'Business info' },
    // { name: 'Team' },
    { name: 'Billing' },
    // { name: 'Notifications' },
    { name: 'Security' }
]
const activeTab = ref('General')
</script>
