<template>
    <AuthenticatedLayout>
        <Head :title="`Invoice ${invoice.invoice_number}`" />
        <InvoicePagePaymentModal
            @cancel="closePaymentModal"
            @submit="paymentMade"
            :invoice="invoice"
            :showPaymentModal="showPaymentModal"
        />
            <div class="sticky top-16  flex items-center justify-end mb-3">
                <button
                    v-if="invoice.status === 'paid' || invoice.status === 'cancelled' "
                        class=" "
                    disabled
                >
                </button>
                <button
                    v-else
                    @click="showPaymentModal=true"
                        class="bg-primary-dark w-full md:w-fit text-white px-3 font-bold rounded-md h-10"
                >Add payment
                </button>
            </div>
        <div ref="invoiceContainer" id="invoiceContainer" class="max-w-5xl my-2 mx-auto px-4 md:px-8 bg-white text-gray-800 shadow-lg rounded-lg">

            <!-- Header Section -->
            <header class=" flex flex-col-reverse md:flex-row justify-between items-start mb-8 pb-6 border-b-2 border-gray-200">
                <div class="flex items-start mt-6">
                    <div v-if="business.logo" class="mr-4">
                        <img :src="`/storage/${business.logo}`" :alt="`${business.name} logo`" class="w-20 h-20 object-contain"/>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-primary mb-2">{{ business.name }}</h1>
                        <p class="text-gray-500 mb-1">{{ business.address }}</p>
                        <p class="text-gray-500">
                            <span v-if="business.phone">{{ business.phone }}</span>
                            <span v-if="business.phone && business.email"> • </span>
                            <span v-if="business.email">{{ business.email }}</span>
                        </p>
                    </div>
                </div>

                <div class="text-right">
                    <h2 class="text-xl font-semibold">INVOICE</h2>
                    <div class="space-y-1 mt-">
                        <div>
                            <span class="font-bold mr-2">Invoice #:</span>
                            <span>{{ invoice.invoice_number }}</span>
                        </div>
                        <div>
                            <span class="font-bold mr-2">Date:</span>
                            <span>{{ formatDate(invoice.issue_date) }}</span>
                        </div>
                        <div>
                            <span class="font-bold mr-2">Due Date:</span>
                            <span>{{ formatDate(invoice.due_date) }}</span>
                        </div>
                        <div>
                            <span class="font-bold mr-2">Status:</span>
                            <span :class="statusClass(invoice.status)">{{ invoice.status.toUpperCase() }}</span>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Client & Job Section -->
            <section id="billToAndJob" class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                <div>
                    <h3 class="text-blue-600 text-lg mb-4 border-b border-gray-200 pb-2">Bill To</h3>
                    <div class="space-y-1">
                        <p class="font-bold">{{ customer.name }}</p>
                        <p v-if="customer.company">{{ customer.company }}</p>
                        <p v-if="customer.phone">{{ customer.phone }}</p>
                        <p v-if="customer.email">{{ customer.email }}</p>
                    </div>
                </div>
                <div v-if="job">
                    <h3 class="text-blue-600 text-lg mb-4 border-b border-gray-200 pb-2">Job Details</h3>
                    <div class="space-y-2">
                        <p v-if="job.title" class="font-semibold">{{ job.title }}</p>
                        <p v-if="job.description">{{ job.description }}</p>
                        <div v-if="job.start_date || job.due_date">
                            <span v-if="job.start_date">{{ formatDate(job.start_date) }}</span>
                            <span v-if="job.due_date"> - {{ formatDate(job.due_date) }}</span>
                        </div>
                        <p v-if="job.status">
                            Status:
                            <span :class="statusClass(job.status)">{{ job.status.toUpperCase() }}</span>
                        </p>
                    </div>
                </div>
            </section>

            <!-- Invoice Items Table -->
            <section id="invoiceItems" class="mb-8">
                <h3 class="text-blue-600 text-lg mb-4 border-b border-gray-200 pb-2">Invoice Items</h3>
                <div class="border border-gray-200 rounded overflow-hidden">
                    <div class="grid grid-cols-4 bg-primary-dark text-white font-bold p-4">
                        <div>Description</div>
                        <div>Qty</div>
                        <div>Unit Price</div>
                        <div>Amount</div>
                    </div>

                    <div v-for="(item, index) in invoice.items" :key="item.id"
                         id="invoiceItemsDetails"
                         class="grid grid-cols-4 gap-2 md:gap-0 p-4 border-b border-gray-200 items-center last:border-b-0">
                        <div>
                            <div class="font-bold">{{ item.name }}</div>
                            <div v-if="item.description" class="text-gray-500 text-sm mt-1">{{ item.description }}</div>
                        </div>
                        <div>
                            <span class="font-bold md:hidden print:hidden">Quantity: </span>{{ item.quantity }}
                        </div>
                        <div>
                            <span class="font-bold md:hidden print:hidden">Unit Price: </span>{{ formatCurrency(item.unit_price, invoice.currency) }}
                        </div>
                        <div>
                            <span class="font-bold md:hidden print:hidden">Amount: </span>{{ formatCurrency(item.total, invoice.currency) }}
                        </div>
                    </div>
                </div>
            </section>

            <!-- Totals Section -->
            <section class="mb-8 flex justify-end">
                <div class="w-full md:w-72 space-y-2">
                    <div class="flex justify-between font-bold">
                        <span>Subtotal</span>
                        <span>{{ formatCurrency(invoice.subtotal, invoice.currency) }}</span>
                    </div>
                    <div v-if="invoice.tax > 0" class="flex justify-between">
                        <span>Tax</span>
                        <span>{{ formatCurrency(invoice.tax, invoice.currency) }}</span>
                    </div>
                    <div v-if="invoice.discount > 0" class="flex justify-between">
                        <span>Discount</span>
                        <span>{{ formatCurrency(invoice.discount, invoice.currency) }}</span>
                    </div>
                    <div class="flex justify-between border-t-2 border-gray-200 pt-2 font-bold text-lg">
                        <span>Total</span>
                        <span>{{ formatCurrency(invoice.total, invoice.currency) }}</span>
                    </div>

                    <div class="flex justify-between border-t-2 border-gray-200 pt-6 text-lg">
                        <span>Paid</span>
                        <span>{{ formatCurrency(totalAmount, invoice.currency) || 0 }}</span>
                    </div>
                    <div class="flex bg-primary-dark text-white rounded-md font-bold px-2 justify-between border-t-2 border-gray-200 pt-2 text-lg">
                        <span>Balance due</span>
                        <span>{{ formatCurrency(totalBalance, invoice.currency) }}</span>
                    </div>
                </div>
            </section>


            <!-- Footer -->
            <div class="flex flex-col items-center justify-center">
                <footer class="pt-8 border-t-2 border-gray-200 text-center">
                    <div v-if="invoice.notes" class="mb-6 text-center">
                        <h4 class="mb-2 font-semibold text-gray-800">Notes</h4>
                        <p class="text-gray-500 text-sm italic leading-relaxed">{{ invoice.notes }}</p>
                    </div>
                </footer>

            </div>
            <div v-if="invoice.payments && invoice.payments.length > 0" class="pb-6">
                <PaymentsMade
            :payments="invoice.payments"
            :invoice="invoice"
            :totalAmount="totalAmount"
            :currency="invoice?.currency"
            :totalAmountInBusinessCurrency="totalAmountInBusinessCurrency"
            />
        </div>
        </div>
        <div class="w-full flex items-center justify-center gap-4 py-2 m-1">
            <SendInvoiceModal
                :invoice="invoice"
                :personalEmail="user.email"
                :businessEmail="user.business.business_email"
                :business="user.business"
                class="mb-8"
            />
            <InvoiceFooter
                @print="printSection"
                :invoice="invoice"
            />
        </div>


    </AuthenticatedLayout>
</template>

<script setup>
import {computed, onMounted, ref} from "vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import InvoiceFooter from "@/Pages/Invoice/InvoiceFooter.vue";
import SendInvoiceModal from "@/Components/Invoice/SendInvoiceModal.vue";
import {Head, router} from "@inertiajs/vue3";
import InvoicePagePaymentModal from "@/Components/Invoice/InvoicePagePaymentModal.vue";
import PaymentsMade from "@/Components/Invoice/PaymentsMade.vue";

const props = defineProps({
    invoice: Object,
    user: Object
});

// Parse snapshots
const business = computed(() => JSON.parse(props.invoice.business_snapshot));
const customer = computed(() => JSON.parse(props.invoice.customer_snapshot));
const job = computed(() => JSON.parse(props.invoice.job_snapshot));
let showPaymentModal = ref(false);

const  paymentMade = ()=>{
    router.visit(`/dashboard/invoice/${props.invoice.id}`)
}

const closePaymentModal =()=> {
    showPaymentModal.value = false
}

// Helper functions
const formatCurrency = (amount, currencyCode) => {
    if (!amount) return ''
    return new Intl.NumberFormat('en', {
        style: 'currency',
        currency: currencyCode // e.g., 'NGN' or 'USD' from invoice.currency
    }).format(Number(amount))
}

const formatDate = (dateString) => {
    if (!dateString) return "—";
    return new Date(dateString).toLocaleDateString(undefined, {year: "numeric", month: "short", day: "numeric"});
};

const statusClass = (status) => {
    const mapping = {
        PAID: "bg-green-600 text-white px-2 py-0.5 rounded",
        PENDING: "bg-yellow-500 text-white px-2 py-0.5 rounded",
        OVERDUE: "bg-red-600 text-white px-2 py-0.5 rounded",
        CANCELLED: "bg-gray-500 text-white px-2 py-0.5 rounded",
        IN_PROGRESS: "bg-yellow-400 text-white px-2 py-0.5 rounded"
    };
    return mapping[status.toUpperCase()] || "bg-gray-400 text-white px-2 py-0.5 rounded";
};

// Sum of filtered payments
const totalAmount = computed(() => {
    return props.invoice.payments
        .filter(p => !p.is_invalid)
        .reduce((sum, p) => sum + parseFloat(p.amount_in_invoice_currency || 0), 0)
})

const totalAmountInBusinessCurrency = computed(() => {
    return props.invoice.payments
        .filter(p => !p.is_invalid) // only valid payments
        .reduce((sum, p) => sum + parseFloat(p.amount_in_business_currency || 0), 0)
})


const totalBalance = computed(() => {
    return props.invoice.total - totalAmount.value
})

</script>
<style scoped>
@media print {
    /* Hide everything by default */
    body * {
        visibility: hidden;
        margin: 0;
        padding: 0;
    }

    /* Show only the invoice container */
    #invoiceContainer, #invoiceContainer * {
        visibility: visible;
    }

    /* Position invoice at top-left and fill width */
    #invoiceContainer {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
    }

    /* Optional: remove shadows, background colors that affect print */
    #invoiceContainer {
        box-shadow: none !important;
        background: white !important;
    }
    #billToAndJob {
        display: flex !important;
        justify-content: space-between !important;
        align-items: center !important;
    }
    #invoiceItemsDetails {
        justify-content: space-between;
        align-content: center;

        display: grid !important;
        grid-template-columns: repeat(4, 1fr) !important; /* 4 equal columns */
        gap: 0.5rem !important; /* 2 = 0.5rem spacing */
        padding: 1rem !important; /* p-4 = 1rem padding */
        border-bottom: 1px solid #e5e7eb !important; /* border-gray-200 */
        align-items: center !important; /* items-center */
    }

    /* Ensure text colors print correctly */
    #invoiceContainer, #invoiceContainer * {
        color: black !important;
        background: transparent !important;
    }

    /* Optional: adjust font sizes for print */
    #invoiceContainer {
        font-size: 12pt;
    }
}
</style>

