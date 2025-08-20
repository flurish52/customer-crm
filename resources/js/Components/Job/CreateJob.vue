<template>
    <div
        v-if="showModal"
        class="fixed inset-0 bg-black/70 backdrop-blur-sm flex items-center justify-center z-50 p-4 transition-all duration-300"
        @click.self="closeModal"
    >
        <!-- Modal Container -->
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl max-h-[95vh] overflow-hidden flex flex-col transform transition-all duration-300 scale-100 opacity-100">

            <!-- Header -->
            <div class="sticky top-0 bg-white px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-bold text-gray-900">{{ isEditingJob ? 'Edit Job' : 'Create New Job' }}</h2>
                    <p class="text-sm text-gray-500 mt-1">Fill in the job details below</p>
                </div>
                <button
                    @click="closeModal"
                    class="text-gray-400 hover:text-gray-600 transition-colors duration-200 rounded-full p-1 hover:bg-gray-100"
                    aria-label="Close modal"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Body Content -->
            <div class="overflow-y-auto flex-1 px-6 py-5">
                <form @submit.prevent="submitForm" class="space-y-6">
                    <!-- Customer Information Section -->
                    <section class="space-y-4">
                        <h3 class="text-lg font-semibold text-gray-800 border-b pb-2">Customer Information</h3>

                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Customer <span class="text-red-500">*</span></label>
                                <template v-if="customer && Object.keys(customer).length">
                                    <div class="relative">
                                        <input
                                            v-model="customer.name"
                                            type="text"
                                            disabled
                                            class="w-full border border-gray-200 rounded-lg px-4 py-3 bg-gray-50 text-gray-600 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-200"
                                        >
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                        </div>
                                    </div>
                                </template>
                                <template v-else>
                                    <div class="relative">
                                        <select
                                            v-model="form.customer_id"
                                            class="w-full border border-gray-200 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 appearance-none"
                                        >
                                            <option disabled value="">Select a customer</option>
                                            <option v-for="cust in allCustomers" :key="cust.id" :value="cust.id">
                                                {{ cust.name }}
                                            </option>
                                        </select>
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4" />
                                            </svg>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </section>

                    <!-- Job Details Section -->
                    <section class="space-y-4">
                        <h3 class="text-lg font-semibold text-gray-800 border-b pb-2">Job Details</h3>

                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Job Title <span class="text-red-500">*</span></label>
                                <input
                                    v-model="form.job_title"
                                    type="text"
                                    required
                                    class="w-full border border-gray-200 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                    placeholder="e.g., Website Redesign, Logo Creation"
                                >
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                                <textarea
                                    v-model="form.description"
                                    rows="3"
                                    class="w-full border border-gray-200 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                    placeholder="Describe the job requirements, deliverables, and any special instructions..."
                                ></textarea>
                            </div>
                        </div>
                    </section>

                    <!-- Status & Financials Section -->
                    <section class="space-y-4">
                        <h3 class="text-lg font-semibold text-gray-800 border-b pb-2">Status & Financials</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <!-- Status -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                                <div class="relative">
                                    <select
                                        v-model="form.status"
                                        class="w-full border border-gray-200 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 appearance-none"
                                    >
                                        <option value="pending">Pending</option>
                                        <option value="in_progress">In Progress</option>
                                        <option value="completed">Completed</option>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4" />
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <!-- Amount -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Amount (₦)</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-3 text-gray-500">₦</span>
                                    <input
                                        v-model="form.amount"
                                        type="number"
                                        min="0"
                                        step="0.01"
                                        class="w-full border border-gray-200 rounded-lg pl-8 pr-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                        placeholder="0.00"
                                    >
                                </div>
                            </div>
                        </div>

                        <!-- Payment Summary (only when editing) -->
                        <div v-if="isEditingJob" class="bg-blue-50 p-4 rounded-lg border border-blue-100 text-sm text-gray-700 grid grid-cols-2 gap-3">
                            <div class="space-y-1">
                                <p class="text-gray-500">Previous Payment:</p>
                                <p class="font-semibold text-lg text-gray-800">{{ amountPaid }}</p>
                            </div>
                            <div class="space-y-1">
                                <p class="text-gray-500">Balance:</p>
                                <p class="font-semibold text-lg text-blue-600">{{ jobBalance }}</p>
                            </div>
                        </div>
                    </section>

                    <!-- Completed Extras Section -->
                    <section v-if="form.status === 'completed'" class="space-y-4">
                        <h3 class="text-lg font-semibold text-gray-800 border-b pb-2">Completed Extras</h3>
                        <CompletedExtras
                            :isEditingJob="isEditingJob"
                            v-model="form.completedExtras"
                        />
                    </section>

                    <!-- Timeline Section -->
                    <section class="space-y-4">
                        <h3 class="text-lg font-semibold text-gray-800 border-b pb-2">Timeline</h3>

                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Delivery Date</label>
                                <div class="relative">
                                    <input
                                        v-model="form.due_date"
                                        type="date"
                                        class="w-full border border-gray-200 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                    >
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- Error Messages -->
                    <div v-if="errors.length" class="bg-red-50 border border-red-200 rounded-lg p-4 mt-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">Please correct the following errors:</h3>
                                <div class="mt-2 text-sm text-red-700">
                                    <ul class="list-disc pl-5 space-y-1">
                                        <li v-for="(error, index) in errors" :key="index">{{ error }}</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Footer -->
            <div class="sticky bottom-0 bg-white px-6 py-4 border-t border-gray-200 flex justify-end space-x-3">
                <button
                    type="button"
                    @click="closeModal"
                    class="px-5 py-2.5 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors duration-200"
                >
                    Cancel
                </button>
                <button
                    type="submit"
                    @click="submitForm"
                    class="px-5 py-2.5 rounded-lg text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 shadow-sm transition-all duration-200 transform hover:-translate-y-0.5"
                >
                    {{ isEditingJob ? 'Update Job' : 'Create Job' }}
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import {onMounted, ref} from 'vue'
import {watch} from "vue";
import axios, {toFormData} from "axios";
import CompletedExtras from "@/Components/Job/CompletedExtras.vue";

const props = defineProps({
    showModal: Boolean,
    customer: Object,
    isEditingJob: Boolean,
    jobToEdit: Object,
    allCustomers: Array,
    amountPaid: Number
})
const emit = defineEmits(['close', 'submit'])
const form = ref({})
let errors = ref([])
let jobBalance = ref(0)
const closeModal = () => {
    emit('close')
    form.value = {
        customer_id: props.customer.id,
        job_title: '',
        description: '',
        status: 'pending',
        amount: null,
        due_date: '',
        completedExtras: {
            amount_paid: null,
            satisfaction: '',
            payment_method: 'banK_transfer'
        }
    }

}
watch(
    () => props.isEditingJob,
    (newVal) => {
        if (newVal) {
            form.value = {...props.jobToEdit}
            jobBalance.value = form.value.amount - props.amountPaid
        } else {
            form.value = {
                customer_id: props.customer.id,
                job_title: '',
                description: '',
                status: 'pending',
                amount: null,
                due_date: '',
                completedExtras: {
                    amount_paid: null,
                    satisfaction: '',
                    payment_method: 'banK_transfer'
                }
            }
        }
    },
    {immediate: true}
)
const submitForm = () => {
    let formData = form.value

    // form validations
    console.log(form.value.completedExtras)


    errors.value = []
    if (!form.value.customer_id) errors.value.push('Customer is required')
    if (!form.value.job_title) errors.value.push('Job title is required')
    if (!form.value.status) errors.value.push('Status is required')
    if (!form.value.amount && form.value.amount !== 0) errors.value.push('Amount is required')
    if (!form.value.due_date) errors.value.push('Due date is required')

    if (form.value.status === 'completed') {
        if (
            form.value.completedExtras.amount_paid === null ||
            form.value.completedExtras.amount_paid === undefined
        ) {
            errors.value.push('Amount paid is required')
        }
        if (!form.value.completedExtras.satisfaction) {
            errors.value.push('Satisfaction score is required')
        }
        if (!form.value.completedExtras.payment_method) {
            errors.value.push('Payment method is required')
        }
        if (form.value.completedExtras.amount_paid > form.value.amount) {
            errors.value.push('Amount paid cannot be greater than job amount')
        }
        if (props.isEditingJob && form.value.completedExtras.amount_paid > jobBalance.value) {
            errors.value.push('Amount paid cannot be greater than remaining Balance')
        }
    }

    if (errors.value.length) {
        return
    }


    if (props.isEditingJob) {
        axios.patch(`/update_job/${props.jobToEdit.id}`, formData)
            .then(res => {
                emit('submit', {payload: res.data.jobs})
                emit('close')
                form.value = {
                    customer_id: props.customer.id,
                    job_title: '',
                    description: '',
                    status: 'pending',
                    amount: null,
                    due_date: '',
                    completedExtras: {
                        amount_paid: null,
                        satisfaction: '',
                        payment_method: 'banK_transfer'
                    }
                }
            })

    } else {
        axios.post(`/store_job`, formData)
            .then(res => {
                emit('submit', {payload: res.data.jobs})
                emit('close')
                form.value = {
                    customer_id: props.customer.id,
                    job_title: '',
                    description: '',
                    status: 'pending',
                    amount: null,
                    due_date: '',
                    completedExtras: {
                        amount_paid: null,
                        satisfaction: '',
                        payment_method: 'banK_transfer'
                    }
                }
            })
            .catch(error => {
                alert(error.message)
            })
    }

}
</script>

<style scoped>
/* Custom scrollbar for modal */
.modal-content::-webkit-scrollbar {
    width: 6px;
}

.modal-content::-webkit-scrollbar-track {
    background: #f5f0e6;
}

.modal-content::-webkit-scrollbar-thumb {
    background: #0E9C9A;
    border-radius: 3px;
}
</style>
