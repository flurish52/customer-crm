<template>
    <div class="fixed inset-0 flex items-center justify-center z-50">
        <!-- Overlay -->
        <div
            @click.self="$emit('cancel')"
            class="absolute inset-0 bg-black bg-opacity-50"></div>

        <!-- Modal -->
        <div class="bg-white rounded-lg shadow-lg z-10 w-full max-w-md p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-3">Confirm Action</h2>
            <p class="text-sm text-gray-600 mb-5">
                {{ message }}
            </p>

            <div class="flex justify-end space-x-3">
                <button
                    @click="$emit('cancel')"
                    class="px-4 py-2 rounded-md bg-gray-200 text-gray-800 hover:bg-gray-300 transition"
                >
                    Cancel
                </button>
                <button
                    @click="$emit('confirmAction', item)"
                    class="px-4 py-2 rounded-md bg-red-600 text-white hover:bg-red-700 transition"
                >
                    Confirm
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { defineProps, defineEmits } from 'vue'
const props = defineProps({
    message: {
        type: String,
        required: true
    },
    item: {
        type: Object,
        required: true
    }
})
const emit = defineEmits(['confirmAction'])
</script>

<style scoped>
</style>
