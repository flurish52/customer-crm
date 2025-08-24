<template>
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
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                <span>{{ job.business?.business_email }}</span>
                            </div>
                            <div class="flex items-center text-sm text-gray-500">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                                <span>{{ job.business?.business_phone }}</span>
                            </div>
                            <div class="flex items-start text-sm text-gray-500">
                                <svg class="w-4 h-4 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
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
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                                <span>{{ job.customer?.company }}</span>
                            </div>
                            <div class="flex items-center text-sm text-gray-500">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                <span>{{ job.customer?.email }}</span>
                            </div>
                            <div class="flex items-center text-sm text-gray-500">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
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
          <span v-if="job.start_date" class="inline-flex items-center bg-blue-50 px-2.5 py-0.5 rounded-full text-xs font-medium text-blue-800">
            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            Start: {{ job.start_date }}
          </span>
                                <span v-if="job.due_date" class="inline-flex items-center bg-purple-50 px-2.5 py-0.5 rounded-full text-xs font-medium text-purple-800">
            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
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
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6 mb-4">
                <!-- Issue Date -->
                <div>
                    <label class="block text-sm mb-1">Issue Date</label>
                    <input v-model="form.issue_date" type="date" class="border rounded px-2 py-1 w-full" />
                    <p class="text-xs text-red-500 mt-1">{{ errors.issue_date }}</p>
                </div>
                <!-- Due Date -->
                <div>
                    <label class="block text-sm mb-1">Due Date</label>
                    <input v-model="form.due_date" type="date" class="border rounded px-2 py-1 w-full" />
                    <p class="text-xs text-red-500 mt-1">{{ errors.due_date }}</p>
                </div>
            <!-- Status -->
            <div class="mb-4">
                <label class="block text-sm mb-1">Status</label>
                <select v-model="form.status" class="border rounded px-2 py-1 w-full">
                    <option value="pending">Pending</option>
                    <option value="paid">Paid</option>
                    <option value="overdue">Overdue</option>
                    <option value="cancelled">Cancelled</option>
                </select>
                <p class="text-xs text-red-500 mt-1">{{ errors.status }}</p>
            </div>
            </div>

            <!-- Invoice Items -->
            <div class="mb-4">
                <label class="block font-medium mb-2">Invoice Items</label>
                <div v-for="(item, index) in invoiceItems" :key="index"
                     class="flex flex-col md:flex-row md:space-x-2 mb-2 space-y-2 md:space-y-0">

                    <!-- Name -->
                    <div class="flex-1">
                        <label class="block text-sm mb-1">Name</label>
                        <input v-model="item.name" placeholder="Design a logo" class="border rounded px-2 py-1 w-full"/>
                        <p class="text-xs text-red-500 mt-1">{{ errors.name }}</p>
                    </div>

                    <!-- Description -->
                    <div class="flex-2">
                        <label class="block text-sm mb-1">Description</label>
                        <input v-model="item.description" placeholder="Job description" class="border rounded px-2 py-1 w-full"/>
                        <p class="text-xs text-red-500 mt-1">{{ errors.description }}</p>

                    </div>

                    <!-- Quantity -->
                    <div class="w-full md:w-20">
                        <label class="block text-sm mb-1">Qty</label>
                        <input v-model.number="item.quantity" type="number" class="border rounded px-2 py-1 w-full"/>
                        <p class="text-xs text-red-500 mt-1">{{ errors.quantity }}</p>
                    </div>

                    <!-- Unit Price -->
                    <div class="w-full md:w-24">
                        <label class="block text-sm mb-1">Unit Price</label>
                        <input v-model.number="item.unit_price" type="number" class="border rounded px-2 py-1 w-full"/>
                        <p class="text-xs text-red-500 mt-1">{{ errors.unit_price }}</p>

                    </div>

                    <!-- Total -->
                    <div class="flex items-center px-2 mt-6">
                        <span>Total: {{ item.quantity * item.unit_price }}</span>
                    </div>

                    <!-- Remove Button -->
                    <div
                        v-if="invoiceItems.length > 1"
                        class="flex items-center mt-6">
                        <button type="button" @click="removeItem(index)" class="text-red-500 font-bold">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-5 h-5" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"> <path d="M3 6h18"/> <path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/> <path d="M6 6l1 14a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2l1-14"/> <path d="M10 11v6"/> <path d="M14 11v6"/> </svg>

                        </button>
                    </div>
                </div>

                <button type="button" @click="addItem"
                        class="mt-2 px-3 py-1 bg-primary text-white rounded hover:bg-primary-dark">
                    Add Item
                </button>
            </div>
            <!-- Tax & Discount -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">

                <div>
                    <label class="block text-sm mb-1">Discount</label>
                    <input v-model.number="form.discount" type="number" class="border rounded px-2 py-1 w-full" placeholder="0.00" />
                    <p class="text-xs text-red-500 mt-1">{{ errors.discount }}</p>
                </div>
                <div>
                    <label class="block text-sm mb-1">Vat: 7.5%</label>
                    <p class="text-xs text-red-500 mt-1">{{ invoiceTotal.vat}}</p>
                </div>
            </div>

            <!-- Notes -->
            <div class="mb-4">
                <label class="block text-sm mb-1">Notes</label>
                <textarea v-model="form.notes" class="border rounded px-2 py-1 w-full" rows="3" placeholder="Additional notes..."></textarea>
                <p class="text-xs text-red-500 mt-1">{{ errors.notes }}</p>
            </div>

            <!-- Totals -->
            <div class="mb-4 text-right">
                <p class="font-medium text-lg">Invoice Total: {{ invoiceTotal.total }}</p>
            </div>
            <!-- Submit -->
            <div class="text-right">
                <button @click="submitInvoice" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Generate</button>
            </div>
        </div>

</template>

<script setup>
import {ref, reactive, computed, onMounted} from "vue";
import axios from "axios";
import {router} from "@inertiajs/vue3";
const props = defineProps({
    selectedJob: Number,
})
const invoiceItems = reactive([
    { name: "", description: "", quantity: 1, unit_price: 0 }
]);
const form = ref(
    { issue_date: "", due_date: "", status: '', tax: 0, notes: '' }
);
const job =  ref({})
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
    const subtotal = invoiceItems.reduce((sum, i) => sum + (i.quantity * i.unit_price), 0);
    const discount = form.value.discount ? parseFloat(form.value.discount) : 0;
    const vatRate = 0.075;
    const vat = parseFloat(((subtotal - discount) * vatRate).toFixed(2));
    const total = parseFloat((subtotal - discount + vat).toFixed(2));

    return {
        subtotal: subtotal,
        discount: discount,
        vat: vat,
        total: total
    };
});

let errors = ref([])
function addItem() {
    invoiceItems.push({ name: "", description: "", quantity: 1, unit_price: 0 });
}
function removeItem(index) {
    invoiceItems.splice(index, 1);
}
function submitInvoice() {
    invoiceItems.forEach(item=>{
        if (!item.name){
            errors.value.name = 'Name field is required'
        }
        if (!item.description){
            errors.value.description = 'Description field is required'
        }
        if (!item.quantity){
            errors.value.quantity = 'Quantity field is required'
        }
        if (!item.unit_price){
            errors.value.unit_price = 'Unit price field is required'
        }
        if (!form.value.issue_date){
            errors.value.issue_date = 'Issue_date is required'
        }
        if (!form.value.due_date){
            errors.value.due_date = 'Due_date field is required'
        }
        if (!form.value.status){
            errors.value.status = 'Status price field is required'
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
        notes: form.value.notes
    };

        axios.post(`/store/invoice/generate`, invoiceData)
        .then((res) => {
            router.visit(`/dashboard/invoice/${res.data.invoice.id}`)
            invoiceItems.value = reactive([
                { name: "", description: "", quantity: 1, unit_price: 0 }
            ]);
            form.value = ref(
                { issue_date: "", due_date: "", status: '', tax: 0, notes: '' }
            );
        })
        .catch((err) => {
            console.error("Error submitting invoice:", err.response || err);
        });
}
onMounted(()=>{
    axios.get(`/get_user/job_details/${props.selectedJob}`)
        .then(res =>{
            if(res.status === 200){
                job.value =  res.data
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
