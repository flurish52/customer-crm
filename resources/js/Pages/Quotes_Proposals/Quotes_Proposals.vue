<template>
    <AuthenticatedLayout>
        <!-- Header Section -->
        <div class="bg-gradient-to-r from-white rounded-md px-6 py-6 shadow-md">
            <div class="max-w-7xl mx-auto">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div>
                        <h1 class="text-3xl font-bold text-primary mb-2">Quotes</h1>
                        <p class="text-secondary">Manage and track your quotes in one place</p>
                    </div>
                    <button
                        @click="showQuoteModal = true"
                        class="px-6 py-3 bg-primary-dark text-tertiary rounded-lg font-semibold hover:bg-primary-50 transition-all duration-200 shadow-lg hover:shadow-xl flex items-center gap-2 group"
                    >
                        <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        New Quote
                    </button>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-xl p-6 shadow-sm border border-primary-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-primary-600">Total Quotes</p>
                            <p class="text-2xl font-bold text-primary-900">{{ quotes.length }}</p>
                        </div>
                        <div class="p-3 bg-primary-50 rounded-lg">
                            <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl p-6 shadow-sm border border-primary-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-primary-600">Sent</p>
                            <p class="text-2xl font-bold text-primary-900">{{ quotes.filter(q => q.status === 'sent').length }}</p>
                        </div>
                        <div class="p-3 bg-blue-50 rounded-lg">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl p-6 shadow-sm border border-primary-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-primary-600">Accepted</p>
                            <p class="text-2xl font-bold text-primary-900">{{ quotes.filter(q => q.status === 'accepted').length }}</p>
                        </div>
                        <div class="p-3 bg-green-50 rounded-lg">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl p-6 shadow-sm border border-primary-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-primary-600">Draft</p>
                            <p class="text-2xl font-bold text-primary-900">{{ quotes.filter(q => q.status === 'draft').length }}</p>
                        </div>
                        <div class="p-3 bg-yellow-50 rounded-lg">
                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Desktop Table -->
            <div class="hidden lg:block bg-white rounded-xl shadow-sm border border-primary-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-primary/10 text-primary-dark">
                        <tr>
                            <th class="px-2 py-4 text-left text-xs font-semibold  uppercase tracking-wider">S/N</th>
                            <th class="px-2 py-4 text-left text-xs font-semibold  uppercase tracking-wider">Quote #</th>
                            <th class="px-2 py-4 text-left text-xs font-semibold  uppercase tracking-wider">Client</th>
                            <th class="px-2 py-4 text-left text-xs font-semibold  uppercase tracking-wider">Title</th>
                            <th class="px-2 py-4 text-left text-xs font-semibold  uppercase tracking-wider">Total</th>
                            <th class="px-2 py-4 text-left text-xs font-semibold  uppercase tracking-wider">Status</th>
                            <th class="px-2 py-4 text-left text-xs font-semibold  uppercase tracking-wider">Valid Until</th>
                            <th class="px-2 py-4 text-left text-xs font-semibold  uppercase tracking-wider">Actions</th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-primary-100">
                        <tr
                            v-for="(quote, index) in quotes"
                            :key="quote.id"
                            class="hover:bg-primary-50 transition-colors duration-150"
                        >
                            <td class="px-2 py-4 text-left whitespace-nowrap">
                                <span class="font-semibold text-primary-900">{{ index+1 }}</span>
                            </td>
                            <td class="px-2 py-4 whitespace-nowrap">
                                <span class="font-semibold text-primary-900">#{{ quote.quote_number }}</span>
                            </td>
                            <td class="px-6 py-4 text-left">
                                <div class="flex items-center">
                                    <span class="text-primary-900">{{ quote.client.name }}</span>
                                </div>
                            </td>
                            <td class="px-2 py-4 text-left">
                                <span class="text-primary-800">{{ quote.title }}</span>
                            </td>
                            <td class="px-2 py-4 text-left">
                                <span class="font-semibold text-primary-900">{{ formatCurrency(quote.total) }}</span>
                            </td>
                            <td class="px-2 py-4 text-left">
        <span
            :class="getStatusClasses(quote.status)"
            class="px-3 py-1 rounded-full text-xs font-medium"
        >
            {{ getStatusText(quote.status) }}
        </span>
                            </td>
                            <td class="px-2 py-4 text-left">
                                <span class="text-primary-700">{{ formatDate(quote.exp_date) }}</span>
                            </td>
                            <td class="px-2 py-4 text-left space-x-2 flex">
                                <!-- View -->
                                <Link
                                    :href="`/quote/view/${quote.id}`"
                                    class="px-3 py-1 bg-blue-100 text-blue-700 rounded hover:bg-blue-200 text-sm"
                                >
                                    View
                                </Link>

                                <!-- Edit (only if draft) -->
                                <button
                                    v-if="quote.status === 'draft'"
                                    @click="editQuote(quote)"
                                    class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded hover:bg-yellow-200 text-sm"
                                >
                                    Edit
                                </button>

                                <!-- Delete -->
                                <button
                                    @click="deleteQuote(quote)"
                                    class="px-3 py-1 bg-red-100 text-red-700 rounded hover:bg-red-200 text-sm"
                                >
                                    Delete
                                </button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Mobile Cards -->
            <div class="lg:hidden space-y-4">
                <div
                    v-for="quote in quotes"
                    :key="quote.id"
                    class="bg-white rounded-xl p-6 shadow-sm border border-primary-100 hover:shadow-md transition-shadow duration-200 cursor-pointer"
                    @click="viewQuote(quote)"
                >
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <h2 class="font-semibold text-primary-900 text-lg">{{ quote.title }}</h2>
                            <span class="text-sm text-primary-600">#{{ quote.quote_number }}</span>
                        </div>
                        <span
                            :class="getStatusClasses(quote.status)"
                            class="px-3 py-1 rounded-full text-xs font-medium"
                        >
                            {{ getStatusText(quote.status) }}
                        </span>
                    </div>

                    <div class="space-y-2">
                        <div class="flex items-center text-primary-700">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            <span>{{ quote.client.name }}</span>
                        </div>

                        <div class="flex items-center text-primary-700">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                            </svg>
                            <span class="font-semibold">{{ formatCurrency(quote.total) }}</span>
                        </div>

                        <div class="flex items-center text-primary-600 text-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span>Valid until {{ formatDate(quote.exp_date) }}</span>
                        </div>

                        <div class="py-4 space-x-2 flex justify-between">
                            <!-- View -->
                            <Link
                                :href="`/quote/view/${quote.id}`"
                                class="px-3 py-1 bg-blue-100 text-blue-700 rounded hover:bg-blue-200 text-sm"
                            >
                                View
                            </Link>

                            <!-- Edit (only if draft) -->
                            <button
                                v-if="quote.status === 'draft'"
                                @click="editQuote(quote)"
                                class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded hover:bg-yellow-200 text-sm"
                            >
                                Edit
                            </button>

                            <!-- Delete -->
                            <button
                                @click="deleteQuote(quote.id)"
                                class="px-3 py-1 bg-red-100 text-red-700 rounded hover:bg-red-200 text-sm"
                            >
                                Delete
                            </button>
                        </div>

                    </div>

                </div>
            </div>

            <!-- Empty State -->
            <div v-if="quotes.length === 0" class="text-center py-12">
                <div class="max-w-md mx-auto">
                    <svg class="w-24 h-24 text-primary-200 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <h3 class="text-lg font-medium text-primary-900 mb-2">No quotes yet</h3>
                    <p class="text-primary-600 mb-6">Get started by creating your first quote</p>
                    <button
                        @click="showQuoteModal = true"
                        class="px-6 py-3 bg-primary-600 text-white rounded-lg font-semibold hover:bg-primary-700 transition-colors duration-200"
                    >
                        Create Your First Quote
                    </button>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <QuoteFormModal
            :show="showQuoteModal"
            @close="showQuoteModal = false"
            @saved="saved"
            :quote="quoteToEdit"
        />
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { ref } from "vue";
import QuoteFormModal from "@/Components/QuoteAndProposal/QuoteFormModal.vue";
import {Link, router} from "@inertiajs/vue3";

const props = defineProps({
    quotes: Array,
});

let showQuoteModal = ref(false);
let quoteToEdit = ref(null);

const saved = () => {
    showQuoteModal.value = false;
    quoteToEdit.value = null;
    router.visit(window.location.href, { preserveScroll: true });
};

const getStatusClasses = (status) => {
    const baseClasses = "px-3 py-1 rounded-full text-xs font-medium";
    const statusClasses = {
        'accepted': 'bg-green-100 text-green-800',
        'rejected': 'bg-red-100 text-red-800',
        'draft': 'bg-yellow-100 text-yellow-800',
        'sent': 'bg-blue-100 text-blue-800'
    };
    return `${baseClasses} ${statusClasses[status] || 'bg-primary-100 text-primary-800'}`;
};

const getStatusText = (status) => {
    const statusMap = {
        'accepted': 'Accepted',
        'rejected': 'Rejected',
        'draft': 'Draft',
        'sent': 'Sent'
    };
    return statusMap[status] || status;
};

const formatCurrency = (amount) => {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD'
    }).format(amount || 0);
};

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
};

const deleteQuote = (quote) => {
    if (confirm("Are you sure you want to delete this quote?")) {
        axios.patch(`/quote/delete/${quote.id}`)
            .then(res => {
                if (res.status === 200) {
                    alert("Status deleted successfully!")
                   router.visit(window.location.href, {preserveScroll: true})
                }else {
                    alert('Something went wrong, please try again')
                }
            })
    }
}
const editQuote = (quote) => {
    if (quote.status === 'draft')
    quoteToEdit.value = quote
    showQuoteModal.value = true
};
</script>

<style scoped>
/* Smooth transitions for better UX */
table th {
    font-weight: 600;
    transition: background-color 0.2s ease;
}

/* Custom scrollbar for table */
.overflow-x-auto::-webkit-scrollbar {
    height: 6px;
}

.overflow-x-auto::-webkit-scrollbar-track {
    background: #f1f5f9;
}

.overflow-x-auto::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 3px;
}

.overflow-x-auto::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}
</style>
