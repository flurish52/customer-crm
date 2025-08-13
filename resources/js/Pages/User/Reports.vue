<template>
    <AuthenticatedLayout>

    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col justify-between gap-4 md:flex-row md:items-center">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-gray-900">Reports</h1>
                <p class="mt-1 text-sm text-gray-500">Business analytics and insights</p>
            </div>
            <div class="flex gap-3">
                <select class="select-input">
                    <option>Monthly Summary</option>
                    <option>Annual Report</option>
                    <option>Customer Analysis</option>
                </select>
                <button class="btn-primary">
                    <ChartBarIcon class="w-5 h-5 mr-2" />
                    Generate
                </button>
            </div>
        </div>

        <!-- Mount existing Reports component -->
<!--        <Reports-->
<!--            class="mt-6 animate-fade-in"-->
<!--        />-->
    </div>
    </AuthenticatedLayout>

</template>

<script setup>
import { ChartBarIcon } from '@heroicons/vue/24/outline'
// import Reports from '@/components/Reports.vue'
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
</script>
