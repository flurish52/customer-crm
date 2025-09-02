<script setup>
import { ref, onMounted } from 'vue'
import {computed} from "vue";
import axios from 'axios'
import {router} from "@inertiajs/vue3";

const props = defineProps({
    invoice: {
        type: Object
    },
    showPaymentModal: Boolean
})

let errorMessage = ref('')
const emit = defineEmits(['submit', 'cancel'])

const paymentForm = ref({
    invoiceId: props.invoice.id,
    amount: '',
    date: new Date().toISOString().split('T')[0],
    payment_method: '',
    notes: ''
})

const paymentMethods = ref([
    { value: 'cash', label: 'Cash' },
    { value: 'bank_transfer', label: 'Bank Transfer' },
    { value: 'card', label: 'Card' },
    { value: 'other', label: 'Other' }
])

const maxHeight = ref('90vh')
onMounted(() => {
    maxHeight.value = window.innerHeight > 800 ? '80vh' : '90vh'
})


const successMessage = ref('')
const totalPaid = computed(() => {
    if (!props.invoice || !props.invoice.payments) return 0
    return props.invoice.payments.reduce((sum, payment) => {
        return sum + Number(payment.amount || 0)
    }, 0)
})

const invoiceBalance = computed(() => {
    if (!props.invoice) return 0
    const total = Number(props.invoice.total || 0)
    const paid = props.invoice.payments
        ? props.invoice.payments.reduce((sum, payment) => {
            return sum + Number(payment.amount || 0)
        }, 0)
        : 0
    return total - paid
})
const payInFull = () => {
    paymentForm.value.amount = invoiceBalance.value
}

const validateForm = () => {
    if (!paymentForm.value.amount || paymentForm.value.amount <= 0) {
        errorMessage.value = 'Amount is required'
        return false
    }
    if (paymentForm.value.amount > invoiceBalance.value ) {
        errorMessage.value = 'Amount filled cannot be greater than invoice balance!'
        return false
    }
    if (!paymentForm.value.date) {
        errorMessage.value = 'Date is required'
        return false
    }
    if (paymentForm.value.date < new Date() || paymentForm.value.date < props.invoice.issue_date) {
        errorMessage.value = 'Payment date cannot be before invoice date or after today'
        return false
    }
    if (!paymentForm.value.payment_method) {
        errorMessage.value = 'Payment method is required'
        return false
    }
    if (!paymentForm.value.invoiceId) {
        errorMessage.value = 'Invalid invoice, please refresh and try again.'
        return false
    }
    errorMessage.value = ''
    return true
}
const submitForm = async () => {
    paymentForm.value.invoiceId = props.invoice.id;
    if (!validateForm()) return
    try {
        const { data } = await axios.post('/user/payment/on_invoice', paymentForm.value)
        if (data.status === 'success') {
            successMessage.value = data.message || 'Payment recorded successfully'
            errorMessage.value = ''
            paymentForm.value = {
                invoiceId: null,
                amount: '',
                date: new Date().toISOString().split('T')[0],
                payment_method: '',
                notes: ''
            }
            errorMessage.value = ''
            setTimeout(() => {
                successMessage.value = ''
            emit('submit')
                emit('cancel')
            }, 2000)
        }
        router.reload()
    } catch (error) {
        console.error(error)
       errorMessage.value = error
    }
}

const cancelForm = () => {
    paymentForm.value = {
        invoiceId: props.invoice.id,
        amount: '',
        date: new Date().toISOString().split('T')[0],
        payment_method: '',
        notes: ''
    }
    emit('cancel')
}
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
                    <p v-if="errorMessage" class="text-red-500 text-sm mt-2">{{ errorMessage }}</p>
                    <p v-if="successMessage" class="text-green-500 text-sm mt-2">{{ successMessage }}</p>

                </div>

                <!-- Scrollable Form Content -->
                <div class="flex-1 overflow-y-auto px-6 py-5">
                    <div class="space-y-5">
                        <!-- Job Select -->
                        <div v-if="invoice && Object.keys(invoice).length > 0">
                            <label for="invoice"
                                   class="block font-medium text-primary mb-1">
                                Invoice Number
                            </label>
                            <input type="text"
                                   id="invoice"
                                   v-model="invoice.invoice_number"
                                   readonly
                                   disabled
                                   class="w-full bg-primary text-white font-semibold rounded-lg px-3 py-2 border border-tertiary focus:outline-none">
                        </div>
                        <!-- Amount Field -->
                        <div>
                            <label for="amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Amount
                            </label>
                            <div class="relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 dark:text-gray-400 sm:text-sm">{{ invoice?.currency || '₦' }}</span>
                                </div>
                                <input
                                    type="number"
                                    id="amount"
                                    v-model="paymentForm.amount"
                                    class="block w-full pl-12  py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                                    placeholder="0.00"
                                    step="0.01"
                                    min="0"
                                />
                            </div>
                            <div class="mt-2 space-y-1">
                                <button
                                    @click="payInFull()"
                                    class="px-3 flex justify-between items-center py-1 text-xs font-medium text-primary-600 hover:text-primary-800  transition-colors rounded bg-primary-50"
                                >
                                        Pay Remaining Balance ({{ invoice?.currency || '₦' }} {{ invoiceBalance }})

                                </button>

                                <div class="flex px-3 justify-between text-xs text-gray-700 dark:text-gray-300">
                                    <span>Amount paid:</span>
                                    <span>{{ invoice?.currency || '₦' }} {{ totalPaid }}</span>
                                </div>
                                <hr class="bg-primary">
                                <div class="flex px-3 justify-between text-xs text-gray-700 dark:text-gray-300">
                                    <span>Total amount: </span>
                                    <span>({{ invoice?.currency || '₦' }} {{ invoice.total }})</span>
                                </div>
                                <hr class="bg-primary">
                            </div>

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
                                v-model="paymentForm.payment_method"
                                class="block w-full py-3 px-4 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                            >
                                <option value="" selected disabled>
                                    Select payment method
                                </option>   <option v-for="method in paymentMethods" :key="method.value" :value="method.value">
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
                <div
                    class="sticky bottom-0 px-6 py-4 bg-gray-50 dark:bg-gray-700/50 border-t border-gray-100 dark:border-gray-700 flex justify-end space-x-3">
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
