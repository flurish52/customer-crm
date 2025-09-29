<template>
    <AuthenticatedLayout>
        <div>
            <SendQuoteViaInvoice
                v-if="showSendQuoteModal"
                :item="quote"
                :subject="`Quote ${quote.quote_number} from ${JSON.parse(quote.business_snapshot)?.name}`"
                :message="`Hello ${quote.client.name}, please find your quote attached.`"
                @cancel="showSendQuoteModal = false"
            />
        </div>

        <div v-if="showCreateInvoiceModal" class="fixed inset-0 z-50 flex items-center justify-center">

                <!-- Overlay -->
                <div class="absolute inset-0 bg-black bg-opacity-50 backdrop-blur-sm"
                     @click="showCreateInvoiceModal = false"></div>

                <!-- Modal Content -->
                <div
                    class="relative bg-white rounded-lg shadow-lg max-w-4xl w-full max-h-[90vh] overflow-y-auto z-10 p-6">
                    <button @click="showCreateInvoiceModal = false"
                            class="absolute top-3 right-3 text-gray-500 hover:text-gray-700 font-bold text-lg">×
                    </button>
                    <CreateInvoiceModal :selectedJob="selectedJob.id"/>
                </div>
            </div>

            <div class="max-w-4xl mx-auto p-6 space-y-6">
                <!-- Header -->
                <div class="flex justify-between items-center border-b border-primary-light pb-4">
                    <div>
                        <h1 class="text-2xl font-bold text-primary-dark">
                            Quote {{ quote.quote_number }}
                        </h1>
                        <div class="flex items-center gap-2">
                            <p class="text-sm text-primary">Status:</p>
                            <select
                                v-model="status"
                                @change="updateStatus"
                                class="border border-primary-light rounded px-2 py-1 text-sm text-primary-dark focus:outline-none"
                            >
                                <option value="draft">Draft</option>
                                <option value="sent">Sent</option>
                                <option value="accepted">Accepted</option>
                                <option value="rejected">Rejected</option>
                            </select>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-primary">Expires on</p>
                        <p class="font-semibold text-primary-dark">{{ quote.exp_date }}</p>
                    </div>
                </div>
                <!-- Business and Client -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="space-y-1">
                        <h2 class="font-semibold text-primary-dark">Business</h2>
                        <p class="text-primary">{{ JSON.parse(quote.business_snapshot)?.name }}</p>
                        <p class="text-primary">{{ JSON.parse(quote.business_snapshot)?.email }}</p>
                        <p class="text-primary">{{ JSON.parse(quote.business_snapshot)?.phone }}</p>
                    </div>
                    <div class="space-y-1">
                        <h2 class="font-semibold text-primary-dark">Client</h2>
                        <p class="text-primary">{{ JSON.parse(quote.client_snapshot)?.name }}</p>
                        <p class="text-primary">{{ JSON.parse(quote.client_snapshot)?.email }}</p>
                        <p class="text-primary">{{ JSON.parse(quote.client_snapshot)?.phone }}</p>
                    </div>

                    <!-- Meta -->
                    <div>
                        <h2 class="text-lg font-semibold text-primary-dark mb-2">Meta Data</h2>
                        <p class="text-primary">
                            <span class="font-semibold">Title:</span> {{ quote.title }}
                        </p>
                        <p class="text-primary">
                            <span class="font-semibold">Description:</span> {{ quote.description }}
                        </p>
                        <p class="text-primary">
                            <span class="font-semibold">Currency:</span> {{ quote.currency }}
                        </p>
                    </div>
                </div>

                <!-- Items -->
                <div>
                    <h2 class="text-lg font-semibold text-primary-dark mb-2">Items</h2>
                    <table class="w-full border-collapse border border-primary-light text-sm">
                        <thead class="bg-primary-light text-primary-dark">
                        <tr>
                            <th class="border border-primary-light px-3 py-2 text-left">Name</th>
                            <th class="border border-primary-light px-3 py-2 text-left">Description</th>
                            <th class="border border-primary-light px-3 py-2 text-right">Qty</th>
                            <th class="border border-primary-light px-3 py-2 text-right">Unit Price</th>
                            <th class="border border-primary-light px-3 py-2 text-right">Total</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="item in quote.items" :key="item.id">
                            <td class="border border-primary-light px-3 py-2">{{ item.name }}</td>
                            <td class="border border-primary-light px-3 py-2">{{ item.description }}</td>
                            <td class="border border-primary-light px-3 py-2 text-right">
                                {{ item.quantity }}
                            </td>
                            <td class="border border-primary-light px-3 py-2 text-right">
                                {{ formatCurrency(item.unit_price) }}
                            </td>
                            <td class="border border-primary-light px-3 py-2 text-right">
                                {{ formatCurrency(item.total) }}
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Totals -->
                <div class="space-y-1 text-right">
                    <p class="text-primary">
                        Subtotal:
                        <span class="font-semibold text-primary-dark">{{ formatCurrency(quote.subtotal) }}</span>
                    </p>
                    <p class="text-primary">
                        Tax: <span class="font-semibold text-primary-dark">{{ quote.tax }}%</span>
                    </p>
                    <p class="text-primary">
                        Discount:
                        <span class="font-semibold text-primary-dark">{{ quote.discount }}%</span>
                    </p>
                    <p class="text-lg font-bold text-primary-dark">
                        Total: {{ formatCurrency(quote.total) }}
                    </p>
                </div>

                <!-- Terms -->
                <div>
                    <h2 class="text-lg font-semibold text-primary-dark mb-2">Terms</h2>
                    <p class="text-primary">{{ quote.terms }}</p>
                </div>
                <!-- Status Section -->
                <div class="flex space-x-3">
                    <div
                        v-for="status in ['draft','sent','accepted','rejected','expired']"
                        :key="status"
                        class="px-3 py-1 rounded-md text-sm cursor-default"
                        :class="status === quote.status
      ? 'bg-primary-dark text-white font-semibold'
      : 'bg-gray-200 text-gray-300'"
                    >
                        {{ status.toUpperCase() }}
                    </div>
                    <div
                        v-if="quote.status !== 'draft' && selectedJob && (selectedJob.invoices.length <= 0 || selectedJob.invoices.some(inv => inv.status !== 'cancelled'))"
                        class="">
                        <button
                            @click="showCreateInvoiceFunc"
                            class="px-4 py-2 bg-primary text-white rounded hover:bg-primary-dark transition-colors">
                            Generate Invoice
                        </button>
                    </div>
                </div>
                <!-- Action Buttons -->
                <div class="flex gap-4 pt-4 border-t border-primary-light">
                    <button
                        @click="showSendQuoteModal = true"
                        class="px-5 py-2 rounded-lg bg-primary text-white font-medium hover:bg-primary-dark"
                    >
                        Send via Email
                    </button>
                    <a :href="`/quote/download/${quote.quote_number}`"
                       download
                       class="px-5 py-2 rounded-lg bg-primary-light text-primary-dark font-medium hover:bg-primary">
                        Download
                    </a>
                    <button
                        class="px-5 py-2 rounded-lg bg-red-600 text-white hover:bg-red-500"
                        @click="deleteQuote"
                    >
                        Delete
                    </button>
                </div>
            </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue"
import {onMounted, ref} from "vue"
import {Link, router} from '@inertiajs/vue3'
import CreateInvoiceModal from "@/Components/Invoice/CreateInvoiceModal.vue";
import SendQuoteViaInvoice from "@/Components/AlertsAndPrompts/SendQuoteViaInvoice.vue";

const props = defineProps({
    quote: Object
})
let selectedJob = ref(null)
let showCreateInvoiceModal = ref(false)
let showSendQuoteModal = ref(false)
const status = ref(props.quote.status)
const formatCurrency = (amount) => {
    if (!amount) return "0.00"
    return new Intl.NumberFormat("en-US", {
        style: "currency",
        currency: props.quote.currency || "USD"
    }).format(amount)
}
const updateStatus = () => {
    axios.patch(`/quote/update/status/${props.quote.id}`, {status: status.value})
        .then(res => {
            if (res.status === 200) {
                alert('status updated successfully!')
                if (res.data.job) {
                    showCreateInvoiceModal.value = true
                    selectedJob.value = res.data.job.id
                } else {
                    router.visit(window.location.href, {preserveScroll: true})
                }
            }
        })
}
const deleteQuote = () => {
    if (confirm("Are you sure you want to delete this quote?")) {
        axios.patch(`/quote/delete/${props.quote.id}`, {status: status.value})
            .then(res => {
                if (res.status === 200) {
                    alert("Status deleted successfully!")
                    window.history.back()
                }
            })
    }
}
const sendEmail = () => {
    showSendQuoteModal.value = true
}
const showCreateInvoiceFunc = () => {
    if (selectedJob.value) {
        showCreateInvoiceModal.value = true
    }
}
onMounted(() => {
    selectedJob.value = props.quote?.job || null
})
</script>
