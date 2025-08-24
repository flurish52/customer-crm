<div class="p-6 bg-white rounded-lg shadow max-w-4xl mx-auto">
<h2 class="text-xl font-bold mb-4">Create Invoice</h2>





<!-- Invoice Items -->
... (your existing invoice items block, keep it as is)



<!-- Totals -->
<div class="mb-4 text-right">
    <p class="font-medium text-lg">Invoice Total: {{ invoiceTotal }}</p>
</div>

<!-- Submit -->
<div class="text-right">
    <button @click="submitInvoice" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Generate</button>
</div>
</div>

























<template>
    <AuthenticatedLayout>
        <div class="p-6 bg-white rounded-lg shadow max-w-4xl mx-auto">
            <h2 class="text-xl font-bold mb-4">Create Invoice</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 p-4 bg-gray-50 rounded-xl">
                <!-- Business Info -->
                <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
                    <div class="flex items-center mb-4">
                        <div class="w-3 h-6 rounded-md bg-blue-500 mr-3"></div>
                        <h3 class="text-lg font-semibold text-gray-800">Business details</h3>
                    </div>
                    <div class="space-y-3">
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

            <!-- Invoice Items -->
            <div class="mb-4">
                <label class="block font-medium mb-2">Invoice Items</label>
                <div v-for="(item, index) in invoiceItems" :key="index" class="flex flex-col md:flex-row md:space-x-2 mb-2 space-y-2 md:space-y-0">

                    <div class="flex flex-col flex-1">
                        <input v-model="item.name" placeholder="Design a logo" class="border rounded px-2 py-1"/>
                        <span v-if="errors[index]?.name" class="text-red-500 text-sm">{{ errors[index].name }}</span>
                    </div>

                    <div class="flex flex-col flex-2">
                        <input v-model="item.description" placeholder="Job description" class="border rounded px-2 py-1"/>
                        <span v-if="errors[index]?.description" class="text-red-500 text-sm">{{ errors[index].description }}</span>
                    </div>

                    <div class="flex flex-col w-full md:w-20">
                        <input v-model.number="item.quantity" type="number" placeholder="Qty" class="border rounded px-2 py-1"/>
                        <span v-if="errors[index]?.quantity" class="text-red-500 text-sm">{{ errors[index].quantity }}</span>
                    </div>

                    <div class="flex flex-col w-full md:w-24">
                        <input v-model.number="item.unit_price" type="number" placeholder="Unit Price" class="border rounded px-2 py-1"/>
                        <span v-if="errors[index]?.unit_price" class="text-red-500 text-sm">{{ errors[index].unit_price }}</span>
                    </div>

                    <span class="flex items-center px-2">Total: {{ item.quantity * item.unit_price }}</span>
                    <button type="button" @click="removeItem(index)" class="text-red-500 font-bold">x</button>
                </div>
                <button type="button" @click="addItem" class="mt-2 px-3 py-1 bg-primary text-white rounded hover:bg-primary-dark">Add Item</button>
            </div>

            <!-- Totals -->
            <div class="mb-4 text-right">
                <p class="font-medium text-lg">Invoice Total: {{ invoiceTotal }}</p>
                {{errors}}
            </div>
            <!-- Submit -->
            <div class="text-right">
                <button @click="submitInvoice" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                    Generate Invoice
                </button>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import {ref, reactive, computed, onMounted} from "vue";
import axios from "axios";
import {router} from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

const props = defineProps({
    selectedJob: Number,
})

const job = ref({})
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
const invoiceItems = reactive([
    {name: "", description: "", quantity: 1, unit_price: 0}
]);
const invoiceTotal = computed(() => {
    return invoiceItems.reduce((sum, i) => sum + i.quantity * i.unit_price, 0);
});
let errors = ref([])

function addItem() {
    invoiceItems.push({name: "", description: "", quantity: 1, unit_price: 0});
}


function removeItem(index) {
    invoiceItems.splice(index, 1);
}
    function validateInvoiceItems() {
        errors.value = []

        invoiceItems.forEach((item, index) => {
            if (!item.name.trim()) {
                errors.value.push(`Item ${index + 1}: Name is required`)
            }
            if (!item.description.trim()) {
                errors.value.push(`Item ${index + 1}: Description is required`)
            }
            if (item.quantity <= 0) {
                errors.value.push(`Item ${index + 1}: Quantity must be greater than 0`)
            }
            if (item.unit_price <= 0) {
                errors.value.push(`Item ${index + 1}: Unit price must be greater than 0`)
            }
        })

        return errors.value
    }
function submitInvoice() {
    validateInvoiceItems()
    const invoiceData = {
        business_id: job.value.business.id,
        customer_id: job.value.customer.id,
        job_id: job.value.id,
        job_description: job.value.description || "",
        items: invoiceItems,
        total: invoiceTotal.value,
    };

    axios.post(`/store/invoice/generate`, invoiceData)
        .then((res) => {
            router.visit('/dashboard/invoice')
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
