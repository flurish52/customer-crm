<script setup>
const props = defineProps({
    showModal: Boolean,
})
const emits = defineEmits(['closeModal'])
const closeModal = () => {
    emits('closeModal')
}
const navigateToSettings = ()=>{
    window.open('/dashboard/settings', '_blank');
}
</script>

<template>
    <div v-if="showModal" class="fixed inset-0 flex items-center justify-center bg-black/30 p-4 z-50">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 w-full max-w-md">
            <p class="text-gray-800 dark:text-gray-200 mb-4">
                To create or add jobs, please complete your business details first.
                This ensures your account is fully set up and ready to manage clients and invoices.
                <span class="font-semibold text-gray-900 dark:text-white">Go to Settings → Business Info to update your information.</span>
                <br>
                <span class="text-red-600 text-xs italic">Note: Settings would open in a new tab.</span>
            </p>

            <div class="flex justify-end space-x-3">
                <button @click="navigateToSettings"
                        class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors">
                    Add Business
                </button>
                <button @click="closeModal"
                        class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                    Already added
                </button>
            </div>
        </div>
    </div>
</template>
