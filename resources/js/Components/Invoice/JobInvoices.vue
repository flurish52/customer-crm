<template>
    <UpdateInvoiceStatus
        :invoice="selectedInvoice"
        :showModal="showInvoiceUpdateModal"
        @cancel="showInvoiceUpdateModal = false"
    />
    <InvoicePagePaymentModal
        :invoice="invoiceToAddPayment"
        :showPaymentModal="showPaymentModal"
        @cancel="cancelPaymentForm"
    />

    <div class="w-full flex items-center justify-start px-6">

        <h2 class="text-2xl font-bold text-gray-800 border-b border-gray-300 pb-2 mb-4">
            Invoices
        </h2>

    </div>
    <!-- Desktop Table View -->
    <div class="hidden md:block overflow-x-auto rounded-lg shadow">
        <table class="w-full border-collapse bg-white text-left text-sm border-b-2 border-gray-900">
            <thead class="bg-primary border-b-2 border-gray-600">
            <tr>
                <th class="px-6 py-4 font-semibold text-primary-dark">S/N</th>
                <th class="px-6 py-4 font-semibold text-primary-dark">Invoice #</th>
                <th v-if="!customerPage" class="px-6 py-4 font-semibold text-primary-dark">Client</th>
                <th v-else class="px-6 py-4 font-semibold text-primary-dark">Job</th>
                <th class="px-4 py-4 font-semibold text-primary-dark">Total</th>
                <th class="px-4 py-4 font-semibold text-primary-dark">Total Paid</th>
                <th class="px-4 py-4 font-semibold text-primary-dark">Balance</th>
                <th class="px-4 py-4 font-semibold text-primary-dark">Status</th>
                <th class="px-4 py-4 font-semibold text-primary-dark text-left">Actions</th>
            </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
            <tr
                v-if="sortedInvoices.length > 0"
                v-for="(invoice, index) in sortedInvoices"
                :class="invoice.status === 'cancelled' ? 'bg-gray-200 hover:bg-gray-200 text-black' : ' text-black bg-gray-50'"
                class="transition-colors">
                <td class="px-4 py-4 font-medium text-gray-900">{{ index + 1 }}</td>
                <td class="px-4 py-4 font-medium text-gray-900">{{ invoice.invoice_number }}</td>
                <td v-if="!customerPage" class="px-6 py-4">{{ JSON.parse(invoice.customer_snapshot).name }}</td>
                <td v-else class="px-4 py-4">{{ JSON.parse(invoice.job_snapshot).title }}</td>
                <td class="px-4 py-4 font-medium">{{ invoice.total }} {{ invoice.currency }}</td>
                <td class="px-4 py-4 font-medium text-primary-dark">
                    {{ calculateTotalPaid(invoice) }} {{ invoice.currency }}
                </td>
                <td class="px-4 py-4 font-medium text-red-600">
                    {{ calculateInvoiceBalance(invoice) }} {{ invoice.currency }}
                </td>
                <td class="px-4 py-4">
                <span :class="statusBadgeClass(invoice.status)"
                      class="px-3 py-1 text-xs font-medium rounded-full">
                    {{ invoice.status }}
                </span>
                </td>
                <td class="px-4 py-4">
                    <div class="flex justify-center space-x-2">
                        <button @click="viewInvoice(invoice)"
                                class="text-secondary hover:text-secondary-dark p-1 rounded transition-colors"
                                title="View details">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                        <button @click="downloadPDF(invoice)"
                                class="text-tertiary hover:text-tertiary-dark p-1 rounded transition-colors"
                                title="Download PDF">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </button>
                        <button
                            v-if="invoice.status !== 'cancelled'"
                            @click="updateStatus(invoice)"
                            class="text-gray-600 hover:text-gray-800 p-1 rounded transition-colors"
                            title="Update Status">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </button>
                        <button
                            v-show="invoice.status !== 'cancelled' && invoice.status !== 'paid'"
                            @click="addPayment(invoice)"
                            class="px-3 py-1 bg-teal-600 text-white rounded-md hover:bg-teal-700 transition-colors duration-200 font-medium text-sm flex items-center shadow-sm hover:shadow-md"
                            title="Add payment">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-full w-10" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Record payment
                        </button>
                    </div>
                </td>
            </tr>
            <tr
            v-else>
                <td colspan="9" class="px-4 py-6 text-center text-gray-500">
                    No invoices yet
                </td>
            </tr>
            </tbody>
        </table>
    </div>
    <!-- Mobile Card View -->
    <div class="block md:hidden space-y-4">
        <div
            v-if="sortedInvoices.length > 0"
            v-for="invoice in sortedInvoices"
            :key="invoice.id"
            :class="invoice.status === 'cancelled' ? 'bg-gray-200 text-black' : ' text-black'"
            class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100">
            <div class="p-4 border-b border-gray-100 bg-gray-50">
                <div class="flex justify-between items-start">
                    <h3 class="font-semibold text-lg text-primary">{{ invoice.invoice_number }}</h3>
                    <span :class="statusBadgeClass(invoice.status)" class="px-3 py-1 text-xs font-medium rounded-full">
                    {{ invoice.status }}
                </span>
                </div>
            </div>

            <div class="p-4">
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div v-if="!customerPage">
                        <p class="text-xs text-gray-500 uppercase tracking-wide">Customer</p>
                        <p class="font-medium">{{ JSON.parse(invoice.customer_snapshot).name }}</p>
                    </div>
                    <div v-else>
                        <p class="text-xs text-gray-500 uppercase tracking-wide">Job</p>
                        <p class="font-medium">{{ JSON.parse(invoice.job_snapshot).title }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wide">Total</p>
                        <p class="font-medium">{{ invoice.total }} {{ invoice.currency }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wide">Paid</p>
                        <p class="font-medium">{{ calculateTotalPaid(invoice) }} {{ invoice.currency }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wide">Balance</p>
                        <p class="font-medium">{{ calculateInvoiceBalance(invoice) }} {{ invoice.currency }}</p>
                    </div>
                </div>

                <div class="flex flex-col">
                    <div class="flex justify-between py-2 border-t border-gray-100">
                        <button @click="viewInvoice(invoice)"
                                class="flex items-center text-secondary font-medium text-sm py-2 px-3 rounded-lg hover:bg-secondary hover:text-white transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            View
                        </button>
                        <button @click="downloadPDF(invoice)"
                                class="flex items-center text-tertiary font-medium text-sm py-2 px-3 rounded-lg hover:bg-tertiary hover:text-white transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            PDF
                        </button>
                        <button @click="updateStatus(invoice)"
                                class="flex items-center text-gray-600 font-medium text-sm py-2 px-3 rounded-lg hover:bg-gray-200 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Status
                        </button>
                    </div>
                    <button
                        v-show="invoice.status !== 'cancelled' && invoice.status !== 'paid'"
                        @click="addPayment(invoice)"
                        class="flex px-3 py-1 bg-teal-600 text-white rounded-md hover:bg-teal-700 transition-colors duration-200 font-medium text-xm flex items-center shadow-sm hover:shadow-md"
                        title="Add payment">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div>Record payment</div>
                    </button>
                </div>
            </div>
        </div>
        <div v-else>
        <td colspan="9" class="px-4 py-6 text-center text-gray-500">
            No invoices yet
        </td>
        </div>
    </div>
</template>

<script setup>
import UpdateInvoiceStatus from "@/Components/Invoice/UpdateInvoiceStatus.vue";
import {ref} from "vue";
import {router} from "@inertiajs/vue3";
import {computed} from "vue";
import InvoicePagePaymentModal from "@/Components/Invoice/InvoicePagePaymentModal.vue";

const props = defineProps({
    invoices: Array,
    customerPage: Boolean
});
const sortedInvoices = computed(() => {
    const order = ['overdue', 'pending', 'partially_paid', 'paid', 'cancelled']
    return [...props.invoices].sort((a, b) => {
        return order.indexOf(a.status) - order.indexOf(b.status)
    })
})

let showInvoiceUpdateModal = ref(false)
let showPaymentModal = ref(false)
let invoiceToAddPayment = ref({});
const calculateTotalPaid = (invoice) => {
    if (!invoice || !invoice.payments) return 0
    return invoice.payments
        .filter(payment => !payment.is_invalid)
        .reduce((sum, payment) => sum + Number(payment.amount_in_invoice_currency || 0), 0)
}

const calculateInvoiceBalance = (invoice) => {
    const total = Number(invoice.total || 0)
    return total - calculateTotalPaid(invoice)
}
const cancelPaymentForm = () => {
    showPaymentModal.value = false
};
const statusBadgeClass = (status) => {
    const statusClasses = {
        'paid': 'bg-green-100 text-green-800',
        'pending': 'bg-yellow-100 text-yellow-800',
        'overdue': 'bg-red-100 text-red-800',
        'draft': 'bg-gray-100 text-gray-800'
    };

    return statusClasses[status.toLowerCase()] || 'bg-gray-100 text-gray-800';
};
const formatDate = (dateString) => {
    if (!dateString) return '-';
    const options = {year: 'numeric', month: 'short', day: 'numeric'};
    return new Date(dateString).toLocaleDateString(undefined, options);
};
const viewInvoice = (invoice) => {
    router.visit(`/dashboard/invoice/${invoice.id}`)
};
const addPayment = (invoice) => {
    invoiceToAddPayment.value = invoice
    showPaymentModal.value = true
};
let selectedInvoice = ref();
const downloadPDF = (invoice) => {
    window.open(`/invoice/${invoice.id}/download`)
};
const updateStatus = (invoice) => {
    selectedInvoice.value = invoice
    showInvoiceUpdateModal.value = true
};
</script>

<style scoped>
:root {
    --color-primary: #4361ee;
    --color-secondary: #3a0ca3;
    --color-tertiary: #7209b7;
}

.bg-primary {
    background-color: var(--color-primary);
}

.text-secondary {
    color: var(--color-secondary);
}

.text-secondary-dark {
    color: #2f0885;
}

.hover\:bg-secondary:hover {
    background-color: var(--color-secondary);
}

.text-tertiary {
    color: var(--color-tertiary);
}

.text-tertiary-dark {
    color: #5d078f;
}

.hover\:bg-tertiary:hover {
    background-color: var(--color-tertiary);
}
</style>
