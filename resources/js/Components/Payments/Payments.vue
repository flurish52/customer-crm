<template>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <!-- Desktop Table -->
            <div class="hidden md:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                    <tr>
                        <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">S/N</th>
                        <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reference</th>
                        <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Invoice</th>
                        <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-normal break-words">
                            Amount <br> (Invoice Currency)
                        </th>
                        <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Method</th>
                        <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Paid At</th>
                        <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Job</th>
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="(payment, index) in payments" :key="payment.id" class="hover:bg-gray-50 transition-colors duration-150">
                        <td class="px-2 py-4 whitespace-nowrap text-sm text-gray-900">  {{ index +1}}</td>
                        <td class="px-2 py-4 whitespace-nowrap text-sm text-gray-900">  {{ payment.reference_number }}</td>
                        <td class="px-2 py-4 whitespace-nowrap text-sm text-gray-900">{{ payment.invoice.invoice_number }}</td>
                        <td class="px-2 py-4 whitespace-nowrap text-sm text-gray-900">{{ payment.amount_in_invoice_currency }} {{ payment.invoice.currency }}</td>
                        <td class="px-2 py-4 whitespace-nowrap text-sm text-gray-900">{{ payment.method.replace('-', ' ') }}</td>
                        <td class="px-2 py-4 whitespace-nowrap text-sm text-gray-900">{{ payment.paid_at }}</td>
                        <td class="px-2 py-4 whitespace-nowrap text-sm text-gray-900 flex items-center justify-between">
                            <button @click="viewReceipt(payment)" class="px-3 py-1 bg-blue-100 text-blue-700 text-xs rounded-md hover:bg-blue-200 transition-colors duration-150 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                                View
                            </button>
                            <div>
                                <button v-if="!payment.is_invalid" @click="markInvalid(payment)" class="px-2 mx-1 py-1 bg-red-100 text-red-700 text-xs rounded-md hover:bg-red-200 transition-colors duration-150 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                                    Mark invalid
                                </button>
                                <button v-else class="cursor-not-allowed px-2 mx-1 py-1 bg-gray-100 text-gray-700 text-xs rounded-md hover:bg-gray-200 transition-colors duration-150 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                                    Cancelled
                                </button>
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <!-- Mobile Cards -->
            <div class="md:hidden space-y-4 p-2">
                <div v-for="payment in payments" :key="payment.id" class="border border-gray-200 rounded-lg p-3 shadow-sm">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-medium text-gray-700">Ref</span>
                        <span class="text-[10px] mx-1 text-gray-900">{{ payment.reference_number }}</span>
                    </div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-medium text-gray-700">Invoice</span>
                        <span class="text-sm text-gray-900">{{ payment.invoice.invoice_number }}</span>
                    </div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-medium text-gray-700">Amount</span>
                        <span class="text-sm text-gray-900">{{ payment.amount_in_invoice_currency }} {{ payment.invoice.currency }}</span>
                    </div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-medium text-gray-700">Method</span>
                        <span class="text-sm text-gray-900">{{ payment.method.replace('-', ' ') }}</span>
                    </div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-medium text-gray-700">Paid At</span>
                        <span class="text-sm text-gray-900">{{ payment.paid_at }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <button @click="viewReceipt(payment)" class="px-3 py-1 bg-blue-100 text-blue-700 text-xs rounded-md hover:bg-blue-200 transition-colors duration-150 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                            View
                        </button>
                        <div>
                        <button v-if="!payment.is_invalid" @click="markInvalid(payment)" class="px-2 mx-1 py-1 bg-red-100 text-red-700 text-xs rounded-md hover:bg-red-200 transition-colors duration-150 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                            Mark as invalid
                        </button>
                            <button v-else class="px-2 mx-1 py-1 bg-gray-100 text-gray-700 text-xs rounded-md hover:bg-gray-200 transition-colors duration-150 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                            Cancelled
                        </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</template>
<script setup>
import { defineProps } from 'vue'
import MarkPaymentInvalid from "@/Components/MarkPaymentInvalid.vue";
const props = defineProps({
    payments: {
        type: Array,
        required: true
    }
})
const  emit = defineEmits(['cancel', 'view', 'mark_invalid'])
const viewReceipt = (payment)=>{
    emit('view', payment)
}
const markInvalid = (payment)=>{
    emit('mark_invalid', payment)
}
</script>

