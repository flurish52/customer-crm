<template>
    <div v-if="showSendViaEmail">
    <EmailDetails
        :item="emailItem"
        @cancel="closeSendViaEmail"
    subject="Your payment receipt"
    message="Find attached file for your necessary action"
    />
    </div>
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-40">
        <div
            class="w-full max-w-md bg-white rounded-xl shadow-2xl flex flex-col overflow-hidden border border-teal-100"
            :style="{ maxHeight: '100vh' }"
        >
            <!-- Header -->
            <!-- Receipt Header -->
            <div v-if="viewingReceipt" class="bg-teal-50 p-6 rounded-t-lg border-b border-teal-200 flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-semibold text-teal-800">
                        Payment Receipt
                    </h2>
                </div>
            </div>

            <div v-else class="bg-teal-600 px-6 py-4 text-white text-center">
                <h2 class="text-xl font-bold">Payment Successful</h2>
                <p class="text-teal-100 mt-1 text-sm">
                    {{ receipt?.business }} received your payment of
                </p>
                <p class="text-2xl font-semibold mt-2">
                    {{ receipt?.currency }} {{ receipt?.payment_amount }}
                </p>
                <div class="mt-3 flex justify-center">
          <span class="status-badge bg-white/20 text-white">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24"
                 stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Completed
          </span>
                </div>
            </div>

            <!-- Scrollable Content -->
            <div class="flex-1 overflow-y-auto px-6 py-4">
                <!-- Summary -->
                <div class="grid grid-cols-3 gap-3 mb-4">
                    <div class="bg-teal-50 rounded-lg p-3 text-center border border-teal-100">
                        <p class="text-xs text-teal-600 font-medium">Total Invoice</p>
                        <p class="text-lg font-bold text-teal-800">{{ receipt?.currency }} {{ receipt?.total_amount }}</p>
                    </div>
                    <div class="bg-teal-50 rounded-lg p-3 text-center border border-teal-100">
                        <p class="text-xs text-teal-600 font-medium">Total paid to date</p>
                        <p class="text-lg font-bold text-teal-800">{{ receipt?.currency }} {{
                                receipt?.total_paid
                            }}</p>
                    </div>
                    <div class="bg-teal-50 rounded-lg p-3 text-center border border-teal-100">
                        <p class="text-xs text-teal-600 font-medium">Remaining Balance</p>
                        <p class="text-lg font-bold text-teal-800">{{ receipt?.currency }} {{
                                receipt?.remaining_balance
                            }}</p>
                    </div>
                </div>

                <!-- Details -->
                <div class="mb-4">
                    <h3 class="font-semibold text-gray-800 mb-3 text-base flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-teal-600" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Payment Details
                    </h3>

                    <div class="rounded-lg overflow-hidden border border-gray-200 receipt-grid">
                        <div class="grid grid-cols-2 p-3">
                            <span class="text-gray-600 text-sm font-medium">Receipt Number</span>
                            <span class="text-right text-sm font-semibold text-gray-800">{{
                                    receipt?.receipt_number
                                }}</span>
                        </div>
                        <div class="grid grid-cols-2 p-3">
                            <span class="text-gray-600 text-sm font-medium">Invoice Number</span>
                            <span class="text-right text-sm font-semibold text-gray-800">{{
                                    receipt?.invoice_number
                                }}</span>
                        </div>
                        <div class="grid grid-cols-2 p-3">
                            <span class="text-gray-600 text-sm font-medium">Customer</span>
                            <span class="text-right text-sm font-semibold text-gray-800">{{ receipt?.customer }}</span>
                        </div>
                        <div class="grid grid-cols-2 p-3">
                            <span class="text-gray-600 text-sm font-medium">Job/Service</span>
                            <span class="text-right text-sm font-semibold text-gray-800">{{ receipt?.job }}</span>
                        </div>
                        <div class="grid grid-cols-2 p-3">
                            <span class="text-gray-600 text-sm font-medium">Payment Method</span>
                            <span class="text-right text-sm font-semibold text-gray-800">{{ receipt?.method }}</span>
                        </div>
                        <div class="grid grid-cols-2 p-3">
                            <span class="text-gray-600 text-sm font-medium">Payment Date</span>
                            <span class="text-right text-sm font-semibold text-gray-800">{{ receipt?.date }}</span>
                        </div>
                    </div>
                </div>

                <!-- Breakdown -->
                <div class="bg-gray-50 rounded-lg p-4 mb-4">
                    <h4 class="font-medium text-gray-700 mb-2 text-sm">Payment Breakdown</h4>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Amount Paid</span>
                            <span class="font-medium">{{ receipt?.currency }} {{ receipt?.payment_amount }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-800 font-semibold">Total Paid to Date</span>
                            <span class="text-gray-800 font-semibold">{{ receipt?.currency }} {{ receipt?.total_paid }}</span>
                        </div>
                    </div>
                </div>

                <!-- Thank you -->
                <div class="text-center py-3 border-t border-gray-100">
                    <p class="text-gray-600 italic text-sm">Thank you for your business!</p>
                    <p class="text-xs text-gray-500 mt-1">{{ receipt?.business }}</p>
                </div>
            </div>

            <!-- Footer -->
            <div class="px-6 py-1 bg-gray-50 flex flex-col sm:flex-row gap-4 border-t border-gray-200">

                <button
                    @click="closeModal"
                    class="flex-1 px-2 py-1 text-xs bg-white text-red-600 border-2 border-red-600 rounded-lg hover:bg-teal-100 transition-colors text-sm font-medium flex items-center justify-center"
                >
                    Close
                </button>
                <button
                    @click="handleDownload(receipt?.receipt_number)"
                    class="flex-1 px-2 py-1 text-xs bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors text-sm font-medium flex items-center justify-center"
                    :disabled="downloading"
                >
                    <svg v-if="!downloading" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    <svg v-else class="animate-spin h-4 w-4 mr-2 text-white" xmlns="http://www.w3.org/2000/svg"
                         fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor"
                              d="M4 12a8 8 0 018-8v4l3-3-3-3v4a8 8 0 11-8 8z"></path>
                    </svg>
                    {{ downloading ? 'Downloading...' : 'Download Receipt' }}
                </button>
                <button
                    @click="handleSendEmail(receipt?.receipt_number)"
                    class="flex-1 px-2 py-2 bg-white border border-teal-600 text-teal-600 rounded-lg hover:bg-teal-50 transition-colors text-sm font-medium flex items-center justify-center"
                    :disabled="sendingEmail"
                >
                    <svg v-if="!sendingEmail" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    <svg v-else class="animate-spin h-4 w-4 mr-2 text-teal-600" xmlns="http://www.w3.org/2000/svg"
                         fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor"
                              d="M4 12a8 8 0 018-8v4l3-3-3-3v4a8 8 0 11-8 8z"></path>
                    </svg>
                    {{ sendingEmail ? 'Sending...' : 'Email Receipt' }}
                </button>
            </div>
        </div>
    </div>
</template>
<script setup>
import {onMounted, ref} from 'vue'
import EmailDetails from "@/Components/AlertsAndPrompts/EmailDetails.vue";
const props = defineProps({
    receipt: {
        type: Object,
    },
    viewingReceipt: Boolean,
})
let emailItem = ref(null)
let showSendViaEmail = ref(false)
onMounted(()=>{
   emailItem.value = {
       ref_number: props.receipt?.receipt_number,
       customer: {
           email: props.receipt?.customer_email
       },
       business: {
           email: props.receipt?.business_email,
           name: props.receipt?.business
       }
   }
})
const emit = defineEmits(['cancel'])
const downloading = ref(false)
const sendingEmail = ref(false)
const handleDownload = (ref_number)=> {
    if (!ref_number) return alert('Invalid receipt, please try again')
    downloading.value = true;
    const url = `/dashboard/serve/${ref_number}`;
    const win = window.open(url, );
    if (win) {
        downloading.value = false;
    } else {
        downloading.value = false;
        alert('Popup blocked. Please allow popups for this site.');
    }
}
const handleSendEmail = (ref_number)=> {
    if (!ref_number) return  alert('Invalid receipt')
    showSendViaEmail.value = true
}
const closeModal = ()=> {
    emit('cancel')
}
function closeSendViaEmail() {
    showSendViaEmail.value = false
}
</script>
<style scoped>
.receipt-grid div:nth-child(even) {
    background-color: #f8fafc;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    padding: 0.35rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 500;
}

.receipt-grid div:nth-child(even) {
    background-color: #f8fafc;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    padding: 0.25rem 0.5rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 500;
}
</style>
