<template>
    <div v-if="showMarkInvalidModal">
        <MarkPaymentInvalid
            @cancel="cancelMarkInvalid"
            :payment="paymentToBeMarked"
        />
    </div>
    <div v-if="showPaymentReceiptModal">
        <PaymentReceipt
            :viewingReceipt="viewingReceipt"
            :receipt="receiptDetails"
            @cancel="closePaymentReceipt"
        />
    </div>
    <div class="w-full">
        <div class="md:block overflow-x-auto">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold text-gray-800">Payments Made</h2>
                <span class="text-sm text-gray-500">{{ payments.length }} Records</span>
            </div>
            <table class="min-w-full border border-gray-200 text-sm rounded-lg">
                <thead>
                <tr class="bg-gray-100 text-left text-gray-700">
                    <th class="py-2 px-2 border">#</th>
                    <th class="py-2 px-2 border">Invoice amount</th>
                    <th class="py-2 px-2 border">Business amount</th>
                    <th class="hidden md:table-cell py-2 px-4 border">Method</th>
                    <th class="hidden md:table-cell py-2 px-4 border">Paid At</th>
                    <th class="py-2 px-2 border">Action</th>
                </tr>
                </thead>
                <tbody>
                <tr
                    v-for="(payment, index) in payments"
                    :key="payment.id"
                    class="hover:bg-gray-50"
                >
                    <td class="py-2 px border">{{ index + 1 }}</td>
                    <td class="py-2 px border font-semibold">{{ payment.amount_in_invoice_currency }}</td>
                    <td class="py-2 px border font-semibold">{{ payment.amount_in_business_currency }}</td>
                    <td class="hidden md:table-cell py-2 px-4 border capitalize">
                        {{ payment.method.replace('_', ' ') }}
                    </td>
                    <td class="hidden md:table-cell py-2 px-4 border">{{ payment.paid_at }}</td>
                    <td class="py-2 px-1 border">
                        <div class="flex flex-col md:flex-row justify-center items-center ">
                            <div>
                                <button @click="openPaymentReceipt(payment)" class="text-primary-dark font-bold">View</button>

                            </div>
                            <div>
                                <button
                                    v-if="!payment.is_invalid"
                                    @click="markInvalid(payment)"
                                    class="px-4 py-2 font-extrabold text-red-600 rounded-md hover:text-red-700 transition"
                                >
                                    Mark invalid
                                </button>
                                <button
                                    v-else
                                    class="px-4 py-2 font-extrabold text-red-600 rounded-md  transition cursor-not-allowed"
                                >
                                    Cancelled
                                </button>
                            </div>
                        </div>

                    </td>
                </tr>
                </tbody>
                <!-- Total Row -->
                <tfoot>
                <tr class="bg-gray-50 font-bold text-gray-800">
                    <td colspan="2" class="py-2 px-2 border">Total paid:</td>
                    <td colspan="4" class="py-2 px-2 border text-right">{{ currency }} {{ totalAmount.toFixed(2) }}</td>
                </tr>
                <tr class="bg-gray-50 font-bold text-gray-800">
                    <td colspan="2" class="py-2 px-2 border">Total in your business currency:</td>
                    <td colspan="4" class="py-2 px-2 border text-right">{{
                            totalAmountInBusinessCurrency.toFixed(2)
                        }}
                    </td>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
</template>

<script setup>
import MarkPaymentInvalid from "@/Components/MarkPaymentInvalid.vue";
import {ref} from "vue";
import PaymentReceipt from "@/Components/Invoice/PaymentReceipt.vue";

const props = defineProps({
    payments: Array,
    invoice:Object,
    totalAmount: Number,
    currency: String,
    totalAmountInBusinessCurrency: Number
})
let showMarkInvalidModal = ref(false)
let paymentToBeMarked = ref({})
let showPaymentReceiptModal = ref(false)
const  closePaymentReceipt = ()=>{
    showPaymentReceiptModal.value = false
}
let  viewingReceipt = ref(false)
const  openPaymentReceipt = (payment)=>{
    viewingReceipt.value = true
    receiptDetails.value = {
        receipt_number: payment.reference_number,
        invoice_number: props.invoice.invoice_number,
        customer: JSON.parse(props.invoice.customer_snapshot).name,
        customer_email: JSON.parse(props.invoice.customer_snapshot).email,
        business: JSON.parse(props.invoice.business_snapshot).name,
        business_email: JSON.parse(props.invoice.business_snapshot).email,
        job: JSON.parse(props.invoice.job_snapshot).title,
        payment_amount: payment.amount_in_invoice_currency,
        total_amount: props.invoice.total,
        total_paid: props.totalAmount,
        remaining_balance: props.invoice.total - props.totalAmount,
        method: payment.payment_method,
        date: payment.paid_at,
        currency: payment.currency,
        note: payment.note
    }
    showPaymentReceiptModal.value = true
}
const cancelMarkInvalid = () => {
    showMarkInvalidModal.value = false
}
const markInvalid = (payment) => {
    paymentToBeMarked.value = payment
    showMarkInvalidModal.value = true
}
const  receiptDetails = ref({})
</script>
