<template>
    <AuthenticatedLayout>
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col justify-between gap-4 md:flex-row md:items-center">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-gray-900">Receipts</h1>
                <p class="mt-1 text-sm text-gray-500">Financial records and invoices</p>
            </div>
            <div class="flex gap-3">
                <select class="select-input">
                    <option>This Month</option>
                    <option>Last Month</option>
                    <option>This Year</option>
                </select>
                <button class="btn-primary">
                    <DocumentArrowDownIcon class="w-5 h-5 mr-2" />
                    Export
                </button>
            </div>
        </div>

        <!-- Mount existing Receipts component -->
<!--        <Receipts-->
<!--            class="mt-6 animate-fade-in"-->
<!--        />-->
    </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { DocumentArrowDownIcon } from '@heroicons/vue/24/outline'
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
// import Receipts from '@/components/Receipts.vue'
</script>
