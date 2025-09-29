<template>
    <div
        @click.self="$emit('close')"
        v-if="show"
        class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50 p-4"
    >
        <div class="bg-white w-full max-w-4xl rounded-2xl shadow-2xl overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-primary-700 to-primary-800 px-6 py-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-bold text-primary">
                            {{ isEditing ? "Edit Quote" : "Create New Quote" }}
                        </h2>
                        <p class="text-primary-100 text-sm">Fill in the details below to create a professional quote</p>
                    </div>
                    <button
                        @click="$emit('close')"
                        class="text-primary-100 hover:text-white transition-colors duration-200"
                    >
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Messages -->
            <div class="px-6 pt-4">
                <div
                    v-if="successMessage"
                    class="mb-4 p-3 rounded-lg bg-green-50 border border-green-200 flex items-center"
                >
                    <svg class="w-5 h-5 text-green-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-green-700 font-medium">{{ successMessage }}</span>
                </div>

                <div
                    v-if="errorMessage"
                    class="mb-4 p-3 rounded-lg bg-red-50 border border-red-200 flex items-center"
                >
                    <svg class="w-5 h-5 text-red-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-red-700 font-medium">{{ errorMessage }}</span>
                </div>
            </div>

            <!-- Form Content -->
            <div class="max-h-[70vh] overflow-y-auto px-6 py-4">
                <form @submit.prevent="submitForm" class="space-y-6">
                    <!-- Business & Client Section -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Business Info Card -->

                            <div class="flex flex-col">
                        <div class="flex items-start gap-4">
                            <!-- Logo -->
                            <div class="w-12 h-12 rounded-full overflow-hidden bg-gray-100 flex items-center justify-center">
                                <img
                                    v-if="business?.logo_path"
                                    :src="`/storage/${business.logo_path}`"
                                    alt="Business Logo"
                                    class="w-full h-full object-cover"
                                />
                                <span v-else class="text-sm text-gray-400">Logo</span>
                            </div>

                            <!-- Business Info -->

                            <div class="space-y-2 text-sm text-primary-700">
                                <p class="font-semibold text-primary-900">{{ business?.business_name }}</p>
                                <p class="flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                    {{ business?.business_email }}
                                </p>
                                <p class="flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                    </svg>
                                    {{ business?.business_phone }}
                                </p>
                            </div>
                        </div>

<!--meta data-->
                            <div class="grid grid-cols-1 md:grid-cols-1 gap-4">
                                <h2 class="mt-10 text-xl font-semibold text-primary-900">Meta data</h2>
                                <div>
                                    <label class="block text-sm font-semibold text-primary-900 mb-2">Valid Until</label>
                                    <input
                                        v-model="form.exp_date"
                                        type="date"
                                        class="w-full border border-primary-200 rounded-xl p-3 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all duration-200"
                                    />
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-primary-900 mb-2">Currency</label>
                                    <select
                                        v-model="form.currency"
                                        class="w-full border border-primary-200 rounded-xl p-3 bg-white focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all duration-200"
                                    >
                                        <option value="" selected disabled >Select currency</option>
                                        <option value="USD">USD ($)</option>
                                        <option value="EUR">EUR (€)</option>
                                        <option value="NGN">NGN (₦)</option>
                                    </select>
                                </div>
                            </div>

                        </div>

                        <!-- Client Selection -->
                        <div>
                            <label class="block text-sm font-semibold text-primary-900 mb-2">Select Client</label>
                            <select
                                v-model="form.client_id"
                                class="w-full border border-primary-200 rounded-xl p-3 bg-white focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all duration-200"
                            >
                                <option disabled value="">Choose a client...</option>
                                <option v-for="client in clients" :key="client.id" :value="client.id">
                                    {{ client.name }}
                                </option>
                            </select>

                            <!-- Client Details Card -->
                            <div v-if="selectedClient" class="mt-3 bg-gray-50 rounded-xl p-4 border border-gray-200">
                                <h4 class="font-semibold text-gray-900 mb-2">Client Details</h4>
                                <div class="grid grid-cols-2 gap-2 text-sm text-gray-700">
                                    <div>
                                        <span class="font-medium">Name:</span>
                                        <p>{{ selectedClient.name }}</p>
                                    </div>
                                    <div>
                                        <span class="font-medium">Email:</span>
                                        <p>{{ selectedClient.email }}</p>
                                    </div>
                                    <div>
                                        <span class="font-medium">Phone:</span>
                                        <p>{{ selectedClient.phone }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Quote Details -->

                        <div>
                            <label class="block text-sm font-semibold text-primary-900 mb-2">Quote Title</label>
                            <input
                                v-model="form.title"
                                type="text"
                                placeholder="e.g., Website Development"
                                class="w-full border border-primary-200 rounded-xl p-3 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all duration-200"
                                required
                            />
                        </div>

                    <!-- Description -->
                    <div>
                        <label class="block text-sm font-semibold text-primary-900 mb-2">Job Description</label>
                        <textarea
                            v-model="form.description"
                            rows="3"
                            placeholder="Describe the work to be done..."
                            class="w-full border border-primary-200 rounded-xl p-3 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all duration-200"
                        ></textarea>
                    </div>

                    <!-- Items Section -->
                    <div class="border border-primary-100 rounded-xl overflow-hidden">
                        <div class="bg-primary-50 px-4 py-3 border-b border-primary-100">
                            <h3 class="font-semibold text-primary-900 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                Items & Services
                            </h3>
                        </div>
                        <div class="p-4">
                            <div class="space-y-3">
                                <div
                                    v-for="(item, index) in form.items"
                                    :key="index"
                                    class="block md:grid grid-cols-12 gap-3 items-start p-3 bg-white rounded-lg border border-primary-50 hover:border-primary-200 transition-colors duration-200"
                                >
                                    <input
                                        v-model="item.name"
                                        placeholder="Item name"
                                        class="my-1 col-span-3 border border-primary-200 rounded-lg p-2 text-sm focus:border-primary-500 focus:ring-1 focus:ring-primary-200"
                                    />
                                    <input
                                        v-model="item.description"
                                        placeholder="Description"
                                        class="my-1 col-span-3 border border-primary-200 rounded-lg p-2 text-sm focus:border-primary-500 focus:ring-1 focus:ring-primary-200"
                                    />
                                    <input
                                        v-model.number="item.quantity"
                                        type="number"
                                        min="1"
                                        placeholder="Qty"
                                        class="my-1 col-span-1 border border-primary-200 rounded-lg p-2 text-sm focus:border-primary-500 focus:ring-1 focus:ring-primary-200"
                                    />
                                    <input
                                        v-model.number="item.unit_price"
                                        type="number"
                                        step="0.01"
                                        placeholder="Unit Price"
                                        class="my-1 col-span-2 border border-primary-200 rounded-lg p-2 text-sm focus:border-primary-500 focus:ring-1 focus:ring-primary-200"
                                    />
                                    <div class="my-1 col-span-2 bg-primary-50 rounded-lg p-2 text-sm font-medium text-primary-900">
                                        {{ formatCurrency(item.quantity * item.unit_price) }}
                                    </div>

                                    <button
                                        type="button"
                                        @click="removeItem(item, index)"
                                        aria-label="Remove item"
                                        class="p-2 rounded-lg bg-red-50 hover:bg-red-100 text-red-600 transition-colors duration-200 flex items-center justify-center group"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 group-hover:scale-110 transition-transform" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M3 6h18"/>
                                            <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/>
                                            <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/>
                                            <line x1="10" y1="11" x2="10" y2="17"/>
                                            <line x1="14" y1="11" x2="14" y2="17"/>
                                        </svg>
                                    </button>

                                </div>
                            </div>
                            <button
                                type="button"
                                @click="addItem"
                                class="mt-3 px-4 py-2 bg-primary text-primary-700 rounded-lg hover:bg-primary-200 transition-colors duration-200 flex items-center text-sm font-medium"
                            >
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                                Add Another Item
                            </button>
                        </div>
                    </div>

                    <!-- Terms & Conditions -->
                    <div>
                        <label class="block text-sm font-semibold text-primary-900 mb-2">Terms & Conditions</label>
                        <textarea
                            v-model="form.terms"
                            rows="3"
                            placeholder="Specify payment terms, delivery conditions, etc..."
                            class="w-full border border-primary-200 rounded-xl p-3 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all duration-200"
                        ></textarea>
                    </div>

                    <!-- Totals Section -->
                    <div class="bg-primary-50 rounded-xl p-6 border border-primary-100">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="space-y-3">
                                <div class="flex justify-between items-center">
                                    <span class="text-primary-700">Subtotal:</span>
                                    <span class="font-semibold text-primary-900">{{ formatCurrency(subtotal) }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-primary-700">Tax:</span>
                                    <div class="flex items-center space-x-2">
                                        <input
                                            v-model.number="form.tax"
                                            type="number"
                                            class="w-20 border border-primary-200 rounded-lg p-1 text-sm text-center"
                                        />
                                        <span>%</span>
                                    </div>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-primary-700">Discount:</span>
                                    <div class="flex items-center space-x-2">
                                        <input
                                            v-model.number="form.discount"
                                            type="number"
                                            class="w-20 border border-primary-200 rounded-lg p-1 text-sm text-center"
                                        />
                                        <span>%</span>
                                    </div>
                                </div>
                            </div>
                            <div class="md:col-span-2 flex items-center justify-center">
                                <div class="text-center">
                                    <p class="text-sm text-primary-600 mb-1">Total Amount</p>
                                    <p class="text-3xl font-bold text-primary-900">{{ formatCurrency(total) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex justify-end gap-3 pt-4 border-t border-primary-100">
                        <button
                            type="button"
                            @click="close"
                            class="px-6 py-3 rounded-xl bg-gray-100 text-gray-700 hover:bg-gray-200 transition-colors duration-200 font-medium"
                        >
                            Cancel
                        </button>
                        <button
                            type="submit"
                            :disabled="loading"
                            class="px-6 py-3 rounded-xl bg-primary text-white hover:bg-primary-dark transition-colors duration-200 font-medium flex items-center disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            <span v-if="loading" class="loader mr-2"></span>
                            {{ loading ? (isEditing ? "Updating..." : "Creating...") : (isEditing ? "Update Quote" : "Create Quote") }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from "vue"
import {watch} from "vue";
import { usePage } from "@inertiajs/vue3"
import axios from "axios"

const props = defineProps({
    show: { type: Boolean, default: false },
    jobId: { type: Number, default: null },
    quote: { type: Object, default: null },
})

const emit = defineEmits(["close", "saved"])
const page = usePage()
const business = page.props.business

const clients = ref([])
const form = ref({
    client_id: "",
    business_id: business?.id || null,
    job_id: props?.jobId || null,
    title: "",
    description: "",
    items: [
        {
            name: "",
            description: "",
            quantity: 1,
            unit_price: 0,
        },
    ],
    terms: "",
    exp_date: "",
    currency: "USD",
    tax: 0,
    discount: 0,
})

const loading = ref(false)
const successMessage = ref("")
const errorMessage = ref("")

// Computed
const isEditing = computed(() => !!props.quote)

const selectedClient = computed(() =>
    clients.value.find((c) => c.id === form.value.client_id)
)

const subtotal = computed(() =>
    form.value.items.reduce(
        (sum, item) => sum + item.quantity * item.unit_price,
        0
    )
)

const total = computed(() => {
    let result = subtotal.value
    if (form.value.tax) result += (form.value.tax / 100) * subtotal.value
    if (form.value.discount)
        result -= (form.value.discount / 100) * subtotal.value
    return result
})

// Methods
const formatCurrency = (amount) => {
    const currency = form.value.currency || "USD"
    return new Intl.NumberFormat("en-US", {
        style: "currency",
        currency: currency,
    }).format(amount || 0)
}

const addItem = () => {
    form.value.items.push({
        name: "",
        description: "",
        quantity: 1,
        unit_price: 0,
    })
}

const validateForm = () => {
    const errors = []
    if (!form.value.client_id) errors.push("Client is required.")
    if (!form.value.title.trim()) errors.push("Title is required.")
    if (!form.value.exp_date) errors.push("Expiration date is required.")
    return errors
}

const submitForm = async () => {
    loading.value = true
    successMessage.value = ""
    errorMessage.value = ""

    const errors = validateForm()
    if (errors.length) {
        errorMessage.value = errors.join(" ")
        loading.value = false
        return
    }
console.log(form.value.job_id)
    try {
        let response
        if (isEditing.value) {
            response = await axios.patch(`/quote/update/${props.quote.id}`, form.value)
            successMessage.value = "Quote updated successfully!"
        } else {
            response = await axios.post("/quote/store", form.value)
            successMessage.value = "Quote created successfully!"
        }

        setTimeout(() => {
            resetForm()
            emit("saved")
        }, 1500)
    } catch (error) {
        errorMessage.value =
            error.response?.data?.message ||
            "Something went wrong. Please try again."
    } finally {
        loading.value = false
    }
}

const close = () => {
    resetForm()
    emit("close")
}
const resetForm = () => {
    form.value = {
        client_id: "",
        business_id: business?.id || null,
        job_id: props?.jobId || null,
        title: "",
        description: "",
        items: [
            {
                name: "",
                description: "",
                quantity: 1,
                unit_price: 0,
            },
        ],
        terms: "",
        exp_date: "",
        currency: "USD",
        tax: 0,
        discount: 0,
    }
    successMessage.value = ""
    errorMessage.value = ""
}

watch(
    () => props.quote,
    (newQuote) => {
        if (newQuote) {
            form.value = {
                client_id: newQuote.client_id,
                business_id: newQuote.business_id,
                job_id: props.jobId,
                title: newQuote.title,
                description: newQuote.description,
                items: newQuote.items || [],
                terms: newQuote.terms,
                exp_date: newQuote.exp_date,
                currency: newQuote.currency,
                tax: newQuote.tax,
                discount: newQuote.discount,
            }
        } else {
            resetForm()
        }
    },
    { immediate: true }
)

onMounted(() => {
    axios.get("/get_customer").then((res) => {
        if (res.status === 200) {
            clients.value = res.data
        }
    })
})

const removeItem = (item, index)=>{
    form.value.items.splice(index, 1)
}
</script>


<style scoped>
.loader {
    border: 2px solid #f3f3f3;
    border-top: 2px solid white;
    border-radius: 50%;
    width: 16px;
    height: 16px;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Custom scrollbar */
::-webkit-scrollbar {
    width: 6px;
}

::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 3px;
}

::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 3px;
}

::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}
</style>
