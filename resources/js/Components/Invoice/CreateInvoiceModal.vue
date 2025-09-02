<template>
    <div class="p-6 bg-white rounded-lg shadow max-w-4xl mx-auto">
        <h2 class="text-xl font-bold mb-4">Create Invoice</h2>

        <!-- Business, Customer & Job Info Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 p-4 bg-gray-50 rounded-xl">
            <!-- Business Info -->
            <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
                <div class="flex items-center mb-4">
                    <div class="w-3 h-6 rounded-md bg-blue-500 mr-3"></div>
                    <h3 class="text-lg font-semibold text-gray-800">Business details</h3>
                </div>
                <div v-if="job.business" class="space-y-3">
                    <p class="text-gray-800 font-medium">{{ job.business?.business_name }}</p>
                    <div class="flex items-center text-sm text-gray-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                             xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        <span>{{ job.business?.business_email }}</span>
                    </div>
                    <div class="flex items-center text-sm text-gray-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                             xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                        <span>{{ job.business?.business_phone }}</span>
                    </div>
                    <div class="flex items-start text-sm text-gray-500">
                        <svg class="w-4 h-4 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                             xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span>{{ job.business?.business_address }}</span>
                    </div>
                </div>
            </div>

            <!-- Customer Info -->
            <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
                <div class="flex items-center mb-4">
                    <div class="w-3 h-6 rounded-md bg-purple-500 mr-3"></div>
                    <h3 class="text-lg font-semibold text-gray-800">Customer details</h3>
                </div>
                <div class="space-y-3">
                    <p class="text-gray-800 font-medium">{{ job.customer?.name }}</p>
                    <div class="flex items-center text-sm text-gray-500" v-if="job.customer?.company">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                             xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        <span>{{ job.customer?.company }}</span>
                    </div>
                    <div class="flex items-center text-sm text-gray-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                             xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        <span>{{ job.customer?.email }}</span>
                    </div>
                    <div class="flex items-center text-sm text-gray-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                             xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                        <span>{{ job.customer?.phone }}</span>
                    </div>
                </div>
            </div>

            <!-- Job Details -->
            <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
                <div class="flex items-center mb-4">
                    <div class="w-3 h-6 rounded-md bg-green-500 mr-3"></div>
                    <h3 class="text-lg font-semibold text-gray-800">Job details</h3>
                </div>
                <div class="space-y-3">
                    <p class="text-gray-800 font-medium">{{ job.job_title }}</p>
                    <p v-if="job.description" class="text-sm text-gray-500">{{ job.description }}</p>
                    <div class="flex flex-wrap gap-2 text-sm text-gray-500" v-if="job.start_date || job.due_date">
          <span v-if="job.start_date"
                class="inline-flex items-center bg-blue-50 px-2.5 py-0.5 rounded-full text-xs font-medium text-blue-800">
            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                 xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            Start: {{ job.start_date }}
          </span>
                        <span v-if="job.due_date"
                              class="inline-flex items-center bg-purple-50 px-2.5 py-0.5 rounded-full text-xs font-medium text-purple-800">
            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                 xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            Due: {{ job.due_date }}
          </span>
                    </div>
                    <div v-if="job.status" class="mt-2">
          <span :class="['inline-flex items-center px-3 py-1 rounded-full text-xs font-medium', statusColor]">
            {{ job.status }}
          </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Invoice Meta -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-6 mb-4">
            <!-- Issue Date -->
            <div>
                <label class="block text-sm mb-1 font-medium text-gray-700">Issue Date</label>
                <input v-model="form.issue_date" type="date"
                       class="border border-gray-300 rounded-md px-3 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"/>
                <p class="text-xs text-red-500 mt-1">{{ errors.issue_date }}</p>
            </div>

            <!-- Due Date -->
            <div>
                <label class="block text-sm mb-1 font-medium text-gray-700">Due Date</label>
                <input v-model="form.due_date" type="date"
                       class="border border-gray-300 rounded-md px-3 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"/>
                <p class="text-xs text-red-500 mt-1">{{ errors.due_date }}</p>
            </div>

            <!-- Status -->
            <div>
                <label class="block text-sm mb-1 font-medium text-gray-700">Status</label>
                <select v-model="form.status"
                        class="border border-gray-300 rounded-md px-3 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="" selected disabled>Select invoice status</option>
                    <option value="pending">Pending</option>
                    <option value="paid">Paid</option>
                    <option value="overdue">Overdue</option>
                    <option value="cancelled">Cancelled</option>
                </select>
                <p class="text-xs text-red-500 mt-1">{{ errors.status }}</p>
            </div>

            <div>
                <label class="block text-sm mb-1 font-medium text-gray-700">Currency</label>
                <select v-model="form.currency"
                        class="border border-gray-300 rounded-md px-3 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="" selected disabled>Select currency</option>
                    <option value="USD">USD</option>
                    <option value="NGN">NGN</option>
                    <option value="EUR">EURO</option>
                </select>
                <p class="text-xs text-red-500 mt-1">{{ errors.currency }}</p>
            </div>
        </div>

        <!-- Invoice Items -->
        <div class="mb-2">
            <label class="block font-bold mb-1 text-gray-700">Invoice Items</label>
            <div v-for="(item, index) in invoiceItems" :key="index"
                 class="grid grid-cols-1 md:grid-cols-8 gap-4 mb-2 px-3 bg-gray-50 rounded-lg items-center">

                <!-- Name -->
                <div class="md:col-span-1">
                    <label class="block text-sm mb-1 font-medium text-gray-700">Name</label>
                    <input v-model="item.name" placeholder="Design a logo"
                           class="border border-gray-300 rounded-md px-3 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"/>
                    <p class="text-xs text-red-500 mt-1">{{ errors.name }}</p>
                </div>
                <!-- Description -->
                <div class="md:col-span-2">
                    <label class="block text-sm mb-1 font-medium text-gray-700">Description</label>
                    <input v-model="item.description" placeholder="Job description"
                           class="border border-gray-300 rounded-md px-3 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"/>
                    <p class="text-xs text-red-500 mt-1">{{ errors.description }}</p>
                </div>

                <!-- Quantity -->
                <div class="md:col-span-1">
                    <label class="block text-sm mb-1 font-medium text-gray-700">Qty</label>
                    <input v-model.number="item.quantity" type="number"
                           class="border border-gray-300 rounded-md px-3 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"/>
                    <p class="text-xs text-red-500 mt-1">{{ errors.quantity }}</p>
                </div>

                <!-- Unit Price -->
                <div class="md:col-span-2">
                    <label class="block text-sm mb-1 font-medium text-gray-700">Unit Price</label>
                    <input v-model.number="item.unit_price" type="number"
                           class="border border-gray-300 rounded-md px-3 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"/>
                    <p class="text-xs text-red-500 mt-1">{{ errors.unit_price }}</p>
                </div>

                <!-- Total -->
                <div class="md:col-span-1 flex flex-col justify-center">
                    <span class="font-medium text-gray-700">Total: {{
                            (item.quantity * item.unit_price).toFixed(2)
                        }}</span>
                </div>

                <!-- Remove Button -->
                <div v-if="invoiceItems.length > 1" class="md:col-span-1 flex justify-center">
                    <button type="button" @click="removeItem(index)"
                            class="text-red-500 hover:text-red-700 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             class="w-5 h-5" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 6h18"/>
                            <path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                            <path d="M6 6l1 14a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2l1-14"/>
                            <path d="M10 11v6"/>
                            <path d="M14 11v6"/>
                        </svg>
                    </button>
                </div>
            </div>


            <button type="button" @click="addItem"
                    class="mt-2 px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Add Item
            </button>
        </div>

        <!-- Tax & Discount -->
        <div class="flex flex-col ">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 pb-2 border-b border-gray-200 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-blue-500" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                </svg>
                Add-ons
            </h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-6">
                <div class="relative w-fit flex  flex-col justify-center items-left">
                    <label class="block text-sm mb-1 font-medium text-gray-700">Discount</label>
                    <input
                        v-model.number="form.discount"
                        type="number"
                        class="border border-gray-300 rounded-md px-3 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 pr-10"
                        placeholder="10%"
                    />
                    <span
                        class="absolute inset-y-0 right-3 font-bold flex items-center justify-start text-gray-500">%</span>
                    <p class="text-xs text-red-500 mt-1">{{ errors.discount }}</p>
                </div>
                <div class="relative w-fit flex  flex-col justify-center items-left">
                    <label class="block text-sm mb-1 font-medium text-gray-700">Tax</label>
                    <input
                        v-model.number="form.tax"
                        type="number"
                        class="border border-gray-300 rounded-md px-3 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 pr-10"
                        placeholder="7.5%"
                    />
                    <span
                        class="absolute inset-y-0 right-3 font-bold flex items-center justify-start text-gray-500">%</span>
                    <p class="text-xs text-red-500 mt-1">{{ errors.tax }}</p>
                </div>

            </div>

        </div>

        <!-- Notes -->
        <div class="mb-6">
            <label class="block text-sm mb-1 font-medium text-gray-700">Notes</label>
            <textarea v-model="form.notes"
                      class="border border-gray-300 rounded-md px-3 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                      rows="3" placeholder="Additional notes..."></textarea>
            <p class="text-xs text-red-500 mt-1">{{ errors.notes }}</p>
        </div>
        <!-- Totals -->
        <div class="mb-6 p-4 bg-gray-50 rounded-lg text-right">
            <p class="font-bold text-xl text-gray-800">Invoice Total: {{ invoiceTotal.total }}</p>
        </div>
        <!-- Submit -->
        <div class="text-right">
            <button @click="submitInvoice"
                    class="px-6 py-3 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors font-medium">
                Generate Invoice
            </button>
        </div>
    </div>

</template>

<script setup>
import {ref, reactive, computed, onMounted, watch} from "vue";
import axios from "axios";
import {router} from "@inertiajs/vue3";

const props = defineProps({
    selectedJob: Number,
})
const invoiceItems = reactive([
    {name: "", description: "", quantity: 1, unit_price: 0}
]);
const form = ref(
    {
        issue_date: "",
        discount: 0,
        due_date: "",
        status: '',
        notes: '',
        tax: 0,
        currency: "",
    }
);



const job = ref({})
let businessSettings = ref({})

const statusColor = computed(() => {
    const jobStatus = job.status ? job.status.toLowerCase() : "";

    switch (jobStatus) {
        case "completed":
            return "bg-green-100 text-green-800";
        case "in progress":
            return "bg-blue-100 text-blue-800";
        case "pending":
            return "bg-yellow-100 text-yellow-800";
        case "cancelled":
            return "bg-red-100 text-red-800";
        default:
            return "bg-gray-100 text-gray-800";
    }
});
const invoiceTotal = computed(() => {
    const subtotal = invoiceItems.reduce((sum, i) => sum + i.quantity * i.unit_price, 0);

    const discount = subtotal * ((form.value.discount || 0) / 100);
    const vat = (subtotal - discount) * ((form.value.tax || 0) / 100);
    return {
        subtotal: +subtotal.toFixed(2),
        discount: +discount.toFixed(2),
        vat: +vat.toFixed(2),
        total: +(subtotal - discount + vat).toFixed(2)
    };
});

let errors = ref([])

function addItem() {
    invoiceItems.push({name: "", description: "", quantity: 1, unit_price: 0});
}

function removeItem(index) {
    invoiceItems.splice(index, 1);
}

function submitInvoice() {
    invoiceItems.forEach(item => {
        if (!item.name) {
            errors.value.name = 'Name field is required'
        }
        if (!item.description) {
            errors.value.description = 'Description field is required'
        }
        if (!item.quantity) {
            errors.value.quantity = 'Quantity field is required'
        }
        if (!item.unit_price) {
            errors.value.unit_price = 'Unit price field is required'
        }
        if (!form.value.issue_date) {
            errors.value.issue_date = 'Issue_date is required'
        }
        if (!form.value.due_date) {
            errors.value.due_date = 'Due_date field is required'
        }
        if (form.value.due_date < form.value.issue_date) {
            errors.value.due_date = 'Due date cannot be ahead of issue date'
        }
        if (!form.value.status) {
            errors.value.status = 'Status field is required'
        }
        if (!form.value.currency) {
            errors.value.currency = 'Currency field is required'
        }
        if (form.value.tax < 0 || form.value.tax > 100) {
            errors.tax = "Tax must be between 0 and 100"
        }
        if (form.value.discount < 0 || form.value.discount > 100) {
            errors.discount = "Discount must be between 0 and 100"
        }
    })
    const invoiceData = {
        business_id: job.value.business.id,
        customer_id: job.value.customer.id,
        job_id: job.value.id,
        job_description: job.value.description,
        items: invoiceItems,
        total: invoiceTotal.value,
        issue_date: form.value.issue_date,
        due_date: form.value.due_date,
        status: form.value.status,
        tax: form.value.tax,
        currency: form.value.currency,
        notes: form.value.notes
    };

    axios.post(`/store/invoice/generate`, invoiceData)
        .then((res) => {
            router.visit(`/dashboard/invoice/${res.data.invoice.id}`)
            invoiceItems.value = reactive([
                {name: "", description: "", quantity: 1, unit_price: 0}
            ]);
            form.value = ref(
                {issue_date: "", due_date: "", status: '', tax: 0, notes: ''}
            );
        })
        .catch((err) => {
            console.error("Error submitting invoice:", err.response || err);
        });
}

onMounted(() => {
    axios.get(`/get_user/job_details/${props.selectedJob}`)
        .then(res => {
            if (res.status === 200) {
                job.value = res.data
            }
        })
})

watch(
    () => job.value.business?.settings,
    (val) => {
        if (!val) {
            businessSettings.value = {}
            return
        }
        try {
            businessSettings.value = typeof val === 'string' ? JSON.parse(val) : val
            form.value.currency =  businessSettings.value.currency
            form.value.tax = businessSettings.value.tax_percent
        } catch (e) {
            console.error('Failed to parse business settings:', e)
            businessSettings.value = {}
        }
    },
    { immediate: true }
)
</script>

<style scoped>
.bg-primary {
    background-color: #1D4ED8;
}

/* replace with your primary color */
.bg-secondary {
    background-color: #10B981;
}

/* replace with your secondary color */
</style>
