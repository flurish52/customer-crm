<template>
    <AuthenticatedLayout>

    <div class="min-h-screen flex flex-col items-center justify-center bg-gray-50 ">
        <div class="bg-white rounded-lg shadow-lg p-8 w-full text-center">
            <h1 class="text-2xl font-bold text-gray-800 mb-4">Set Up Your Business</h1>
            <p class="text-gray-600 mb-6">
                We need some details about your business to continue. You can update these details anytime
                under the Business tab in your settings.
            </p>

            <BusinessInfo
            @saved="businessDetailsSaved"
            :user="$page.props.auth.user"
            :onboarding="true"
            :business="business || {}"
            />
        </div>
    </div>
    </AuthenticatedLayout>
</template>

<script setup>
import BusinessInfo from "@/Components/User/BusinessInfo.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {router} from "@inertiajs/vue3";

const props = defineProps({
    business: Object,
})
const businessDetailsSaved =()=>{
    router.visit('/dashboard')
}

</script>

<style scoped>
</style>
