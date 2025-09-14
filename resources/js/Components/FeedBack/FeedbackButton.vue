<template>
    <div>
        <!-- Close Button -->
        <button
            v-if="showButton"
            @click="showButton = false"
            class="fixed bottom-20 right-6 bg-gray-200 text-gray-700 w-6 h-6 rounded-full flex items-center justify-center shadow-lg hover:bg-gray-300 z-50"
            title="Hide Feedback Button"
        >
            ×
        </button>

        <button
            v-if="showButton"
            @click="openFeedback"
            class="fixed bottom-6 right-6 bg-teal-900 text-white px-4 py-2 rounded-lg shadow-lg hover:bg-teal-700 transition-colors z-50 flex items-center gap-2"
        >
            Feedback
        </button>
    </div>
</template>

<script setup>
import { ref } from 'vue'

const showButton = ref(true)
const emits = defineEmits(['toggleFeedback'])

function openFeedback() {
    emits('toggleFeedback')
}
</script>
