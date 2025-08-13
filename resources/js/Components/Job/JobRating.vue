<template>
    <!-- Overlay & Modal -->
    <div
        v-if="showModal"
        class="fixed inset-0 bg-black bg-opacity-20 flex items-center justify-center z-50 p-4 transition-opacity duration-300"
        @click.self="close"
    >
        <!-- Modal Container -->
        <div class="bg-white rounded-xl shadow-lg w-full max-w-md transform transition-all duration-300">
            <!-- Modal Content -->
            <div class="p-6">
                <div class="text-center">
                    <h3 class="text-xl font-bold text-primary-dark mb-2">Rate his satisfaction</h3>
                    <p class="text-secondary-dark mb-6">How would you rate your customer's satisfaction?</p>
        {{jobId}}

                    <!-- Rating Options with Emoji and Label -->
                    <div class="flex flex-col items-center mb-8">
                        <div class="flex justify-center space-x-2 mb-2">
                            <button
                                v-for="rating in 5"
                                :key="rating"
                                @click="selectRating(rating)"
                                class="w-12 h-12 rounded-full flex items-center justify-center transition-all duration-200"
                                :class="{
                  'bg-primary-light/20 text-primary-DEFAULT border-2 border-primary-DEFAULT': selectedRating === rating,
                  'bg-tertiary-light text-tertiary-dark hover:bg-primary-light/10': selectedRating !== rating
                }"
                            >
                                <span class="text-xl">{{ getEmoji(rating).emoji }}</span>
                            </button>
                        </div>

                        <!-- Labels aligned under each emoji -->
                        <div class="flex justify-between w-full max-w-xs text-sm text-primary-dark/80">
              <span v-for="rating in 5" :key="'label-'+rating" class="w-12 text-center">
                {{ getEmoji(rating).label }}
              </span>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex items-center justify-center">

                    <button
                        @click="close"
                        class="w-full mx-3 py-3 bg-white text-primary rounded-lg hover:bg-tertiary-dark border-2 border-primary transition-colors"
                    >
                        Cancel
                    </button>
                    <button
                        @click="submitRating"
                        :disabled="!selectedRating"
                        class="w-full mx-3 py-3 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors disabled:bg-primary-light disabled:cursor-not-allowed"
                    >
                        Submit Rating
                    </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue'
let props = defineProps({
    showModal: Boolean,
    jobId: Number
})

// const showModal = ref(false)
const selectedRating = ref(null)
const emits = defineEmits(['submit', 'close'])


const selectRating = (rating) => {
    selectedRating.value = rating
}

const getEmoji = (rating) => {
    const emojis = [
        { emoji: '😞', label: 'Poor' },
        { emoji: '🙁', label: 'Fair' },
        { emoji: '😐', label: 'Good' },
        { emoji: '🙂', label: 'Very Good' },
        { emoji: '😍', label: 'Excellent' }
    ]
    return emojis[rating - 1]
}

const submitRating = async () => {
    if (!selectedRating.value || !props.jobId) return

    emits('submit', {payload: selectedRating.value, jobId: props.jobId})
}
const close = ()=>{
    emits('close')
}
</script>

<style scoped>
/* Custom transitions */
.modal-enter-active, .modal-leave-active {
    transition: opacity 0.3s ease;
}
.modal-enter-from, .modal-leave-to {
    opacity: 0;
}
</style>
