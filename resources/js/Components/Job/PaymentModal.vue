<script setup>
import { ref, computed, onMounted } from 'vue';

const props = defineProps({
    jobs: {
        type: Array,
    },
    showPaymentModal: Boolean
});

let selectedJob = ref('')
const emit = defineEmits(['submit', 'cancel']);
const paymentForm = ref({
    amount: '',
    jobId: null,
    date: new Date().toISOString().split('T')[0],
    method: 'bank_transfer',
    notes: ''
});
const paymentMethods = ref([
    { value: 'cash', label: 'Cash' },
    { value: 'bank_transfer', label: 'Bank Transfer' },
    { value: 'card', label: 'Card' },
    { value: 'other', label: 'Other' }
]);
// Calculate max height based on viewport
const maxHeight = ref('90vh');
onMounted(() => {
    maxHeight.value = window.innerHeight > 800 ? '80vh' : '90vh';
});
let showModal = ref(false)
const payInFull = () => {
    if (!selectedJob.value === '') {
        alert('Please select a job')
        return
    }
    paymentForm.value.amount = selectedJob.value.amount
}
const onJobSelected = () => {
    paymentForm.value.jobId = selectedJob.value.id
};

const submitForm = async () => {
    try {
        const response = await axios.post('/user/payments/received', paymentForm.value)
        emit('activityCreated', response.data.activity)
        paymentForm.value = {
            amount: '',
            jobId: null,
            date: new Date().toISOString().split('T')[0],
            method: 'bank_transfer',
            notes: ''
        }
        emit('cancel')
    } catch (error) {
        console.error(error)
        alert('Failed to record payment')
    }
}
const cancelForm = () => {
    paymentForm.value = {
        amount: '',
        jobId: null,
        date: new Date().toISOString().split('T')[0],
        method: 'bank_transfer',
        notes: ''
    }
    emit('cancel')
};
</script>

<template>
    <div
        v-show="showPaymentModal"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/30 backdrop-blur-sm"
    @click.self="cancelForm"
    >
        <Transition
            enter-active-class="transition duration-200 ease-out"
            enter-from-class="transform scale-95 opacity-0"
            enter-to-class="transform scale-100 opacity-100"
            leave-active-class="transition duration-150 ease-in"
            leave-from-class="transform scale-100 opacity-100"
            leave-to-class="transform scale-95 opacity-0"
        >
            <div
                class="w-full max-w-md bg-white rounded-xl shadow-2xl overflow-hidden dark:bg-gray-800 flex flex-col"
                :style="`max-height: ${maxHeight}`"
            >
                <!-- Header -->
                <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-700">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Record Payment</h2>
                </div>

                <!-- Scrollable Form Content -->
                <div class="flex-1 overflow-y-auto px-6 py-5">
                    <div class="space-y-5">
                        <!-- Job Select -->
                        <div>
                            <label for="job" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Select Job
                            </label>
                            <select
                                id="job"
                                v-model="selectedJob"
                                @change="onJobSelected"
                                class="block w-full py-3 px-4 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                            >
                                <option :value="null" disabled selected>Select a job</option>
                                <option v-for="job in jobs" :key="job.id" :value="job">
                                    {{ job.job_title }}
                                </option>
                            </select>
                        </div>
                        <!-- Amount Field -->
                        <div>
                            <label for="amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Amount
                            </label>
                            <div class="relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 dark:text-gray-400 sm:text-sm">$</span>
                                </div>
                                <input
                                    type="number"
                                    id="amount"
                                    v-model="paymentForm.amount"
                                    class="block w-full pl-7 pr-12 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                                    placeholder="0.00"
                                    step="0.01"
                                    min="0"
                                />
                            </div>
                            <button
                                @click="payInFull()"
                                class="mt-2 text-xs font-medium text-primary-600 hover:text-primary-800 dark:text-primary-400 dark:hover:text-primary-300 transition-colors"
                            >
                                Paid in full (${{ selectedJob.amount }})
                            </button>
                        </div>

                        <!-- Date Picker -->
                        <div>
                            <label for="date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Date
                            </label>
                            <input
                                type="date"
                                id="date"
                                v-model="paymentForm.date"
                                class="block w-full py-3 px-4 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                            />
                        </div>

                        <!-- Payment Method -->
                        <div>
                            <label for="method" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Payment Method
                            </label>
                            <select
                                id="method"
                                v-model="paymentForm.method"
                                class="block w-full py-3 px-4 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                            >
                                <option v-for="method in paymentMethods" :key="method.value" :value="method.value">
                                    {{ method.label }}
                                </option>
                            </select>
                        </div>

                        <!-- Notes -->
                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Notes
                            </label>
                            <textarea
                                id="notes"
                                v-model="paymentForm.notes"
                                rows="3"
                                class="block w-full py-3 px-4 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                                placeholder="Additional details about this payment"
                            ></textarea>
                        </div>
                    </div>
                </div>

                <!-- Sticky Footer with Buttons -->
                <div class="sticky bottom-0 px-6 py-4 bg-gray-50 dark:bg-gray-700/50 border-t border-gray-100 dark:border-gray-700 flex justify-end space-x-3">
                    <button
                        @click="cancelForm"
                        class="px-4 py-2 text-sm border-2 border-red-500 hover:bg-red-200 font-medium text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white rounded-lg transition-colors"
                    >
                        Cancel
                    </button>
                    <button
                        @click="submitForm"
                        class="px-4 py-2 bg-primary hover:bg-primary-dark text-white text-sm font-medium rounded-lg shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500"
                    >
                        Save Payment
                    </button>
                </div>
            </div>
        </Transition>
    </div>
</template>
