<template>
    <AuthenticatedLayout>
        <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col justify-between gap-4 md:flex-row md:items-center">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-gray-900">Customers</h1>
                <p class="mt-1 text-sm text-gray-500">Manage your customer relationships</p>
            </div>
            <button
                class="btn-primary"
                @click="openCustomerModal"
            >
                <PlusIcon class="w-5 h-5 mr-2" />
                Add Customer
            </button>
        </div>

        <!-- Mount existing Customers component -->
        <CustomerList
            class="mt-6 animate-fade-in"
            @update="refreshPage"
        />
    </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref } from 'vue'
import { PlusIcon } from '@heroicons/vue/24/outline'
import CustomerList from "@/Components/Customer/CustomerList.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {router} from "@inertiajs/vue3";

const componentKey = ref(0)

const openCustomerModal = () => {
    // Your modal logic here
    componentKey.value++ // Force re-render if needed
}
const refreshPage = () => {
    router.reload()
}
</script>
