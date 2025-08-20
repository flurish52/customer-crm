<template>
    <div class="mb-6 space-y-6">
        <!-- Amount Paid -->
        <div>
            <div class="grid grid-cols-2 gap-4 mb-6">
                <!-- Amount Paid -->
                <div>
                    <label class="block text-sm font-medium text-primary-dark mb-2">Amount Paid</label>
                    <input
                        v-model="localForm.amount_paid"
                        type="number"
                        min="0"
                        step="0.01"
                        class="w-full border border-tertiary-dark rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-light focus:border-primary-light"
                        :class="isDisabled ? 'bg-gray-300' : ''"
                        placeholder="0.00"
                        :disabled="isDisabled"
                    >
                </div>

                <!-- Payment Method -->
                <div >
                    <label  class="block text-sm font-medium text-primary-dark mb-2">Payment Method</label>
                    <select
                        v-model="localForm.payment_method"
                        class="w-full border border-tertiary-dark rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-light focus:border-primary-light"
                        :disabled="isDisabled"
                    >
                        <option disabled value="">Select method</option>
                        <option value="cash">Cash</option>
                        <option value="bank_transfer">Bank Transfer</option>
                        <option value="card">Card</option>
                        <option value="other">Other</option>
                    </select>
                </div>
            </div>

            <div class="flex justify-end w-full mt-2">
                <button
                    type="button"
                    @click="localForm.amount_paid = 0; isDisabled= !isDisabled "
                    class="text-red-600  text-sm font-medium hover:underline"
                >
                   {{isDisabled ? 'Paid' : 'Not yet'}}
                </button>
            </div>
        </div>


        <!-- Satisfaction Score -->
        <div>
            <label class="block text-sm font-medium text-primary-dark mb-2">Satisfaction Score</label>
            <div class="flex space-x-4">
                <button
                    v-for="item in emojis"
                    :key="item.value"
                    type="button"
                    @click="localForm.satisfaction = item.value"
                    :class="[
        'flex flex-col items-center p-1 rounded-lg border cursor-pointer',
        localForm.satisfaction === item.value
          ? 'border-primary-light bg-primary-light/10'
          : 'border-tertiary-dark'
      ]"
                >
                    <span class="text-2xl">{{ item.emoji }}</span>
                    <span class="text-xs">{{ item.label }}</span>
                </button>
            </div>
        </div>

    </div>
</template>

<script setup>
import {watch, reactive, ref} from 'vue'

const props = defineProps({
    modelValue: {
        type: Object,
        default: () => ({
            amount_paid: 0,
            satisfaction: ''
        })
    }

})
const emit = defineEmits(['update:modelValue'])

const localForm = reactive({ ...props.modelValue })

const emojis = [
    { value: 1, emoji: '😞', label: 'Poor' },
    { value: 2, emoji: '🙁', label: 'Fair' },
    { value: 3, emoji: '😐', label: 'Good' },
    { value: 4, emoji: '🙂', label: 'Very Good' },
    { value: 5, emoji: '😍', label: 'Excellent' }
]
let isDisabled = ref(false)


watch(
    localForm,
    (val) => {
        emit('update:modelValue', val)
    },
    { deep: true }
)

</script>
