<template>
    <div class="w-full">
        <!-- Table -->
        <div class="md:block overflow-x-auto">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold text-gray-800">Payments Made</h2>
                <span class="text-sm text-gray-500">{{ payments.length }} Records</span>
            </div>
            <table class="min-w-full border border-gray-200 text-sm rounded-lg">
                <thead>
                <tr class="bg-gray-100 text-left text-gray-700">
                    <th class="py-2 px-4 border">#</th>
                    <th class="py-2 px-4 border">Amount</th>
                    <th class="py-2 px-4 border">Method</th>
                    <th class="py-2 px-4 border">Paid At</th>
                    <th class="hidden md:block py-2 px-4 border">Notes</th>
                </tr>
                </thead>
                <tbody>
                <tr
                    v-for="(payment, index) in payments"
                    :key="payment.id"
                    class="hover:bg-gray-50"
                >
                    <td class="py-2 px-4 border">{{ index + 1 }}</td>
                    <td class="py-2 px-4 border font-semibold">{{ payment.amount }}</td>
                    <td class="py-2 px-4 border capitalize">
                        {{ payment.method.replace('_', ' ') }}
                    </td>
                    <td class="py-2 px-4 border">{{ payment.paid_at }}</td>
                    <td class="hidden md:block py-2 px-4 border">
                        {{ payment.notes ? payment.notes : '-' }}
                    </td>
                </tr>
                </tbody>
                <!-- Total Row -->
                <tfoot>
                <tr class="bg-gray-50 font-bold text-gray-800">
                    <td colspan="1" class="py-2 px-2 border">Total paid:</td>
                    <td colspan="4" class="py-2 px-2 border text-right">₦{{ totalAmount.toFixed(2) }}</td>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
    payments: Array,
    totalAmount: Number
})
</script>
