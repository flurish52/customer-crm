<template>

    <!-- Modal Overlay -->
    <div
        v-if="showModal"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4 transition-opacity duration-300"
        @click.self="closeModal"
    >
        <!-- Modal Container -->
        <div
            class="bg-white rounded-xl shadow-lg w-full max-w-md max-h-[90vh] overflow-y-auto transform transition-all duration-300">
            <!-- Modal Header -->
            <div class="sticky top-0 bg-white p-6 border-b border-tertiary-light flex justify-between items-center">
                <h2 class="text-xl font-bold text-primary-dark">Create New Job</h2>
                <button @click="closeModal" class="text-gray-400 hover:text-gray-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <!-- Modal Content -->
            <div class="p-6">
                <form @submit.prevent="submitForm">
                    <!-- Customer Selection -->
                    <div
                        v-if="customer && Object.keys(customer).length"
                        class="mb-6">
                        <label class="block text-sm font-medium text-primary-dark mb-2">Customer *</label>
                        <input
                            v-model="customer.name"
                            type="text"
                            disabled
                            class="w-full border border-tertiary-dark rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-light focus:border-primary-light"
                            placeholder="Website Redesign"
                        >
                    </div>
                    <!-- Customer Selection -->
                    <div
                        v-else
                        class="mb-6">
                        <label class="block text-sm font-medium text-primary-dark mb-2">Customer *</label>
                        <select
                            v-model="form.customer_id"
                            class="w-full border border-tertiary-dark rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-light focus:border-primary-light"
                        >
                            <option disabled value="">Select a customer</option>
                            <option
                                v-for="cust in allCustomers"
                                :key="cust.id"
                                :value="cust.id"
                            >
                                {{ cust.name }}
                            </option>
                        </select>
                    </div>
                    <!-- Job Title -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-primary-dark mb-2">Job Title *</label>
                        <input
                            v-model="form.job_title"
                            type="text"
                            required
                            class="w-full border border-tertiary-dark rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-light focus:border-primary-light"
                            placeholder="Website Redesign"
                        >
                    </div>
                    <!-- Description -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-primary-dark mb-2">Description</label>
                        <textarea
                            v-model="form.description"
                            rows="3"
                            class="w-full border border-tertiary-dark rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-light focus:border-primary-light"
                            placeholder="Brief description of the job..."
                        ></textarea>
                    </div>
                    <!-- Status & Amount -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Status -->
                        <div>
                            <label class="block text-sm font-medium text-primary-dark mb-2">Status</label>
                            <select
                                v-model="form.status"
                                class="w-full border border-tertiary-dark rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-light focus:border-primary-light"
                            >
                                <option value="pending">Pending</option>
                                <option value="completed">Completed</option>
                                <option value="overdue">Overdue</option>
                            </select>
                        </div>

                        <!-- Amount -->
                        <div>
                            <label class="block text-sm font-medium text-primary-dark mb-2">Amount ($)</label>
                            <input
                                v-model="form.amount"
                                type="number"
                                min="0"
                                step="0.01"
                                class="w-full border border-tertiary-dark rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-light focus:border-primary-light"
                                placeholder="0.00"
                            >
                        </div>
                    </div>
                    <!-- Due Date -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-primary-dark mb-2">Due Date</label>
                        <input
                            v-model="form.due_date"
                            type="date"
                            class="w-full border border-tertiary-dark rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-light focus:border-primary-light"
                        >
                    </div>
                    <!-- Form Actions -->
                    <div class="flex justify-end space-x-3 pt-4 border-t border-tertiary-light">
                        <button
                            type="button"
                            @click="closeModal"
                            class="px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-light"
                        >
                            Cancel
                        </button>
                        <button
                            type="submit"
                            class="px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-primary-light"
                        >
                            Create Job
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
import {onMounted, ref} from 'vue'
import {watch} from "vue";
import axios, {toFormData} from "axios";
const props = defineProps({
    showModal: Boolean,
    customer: Object,
    isEditingJob: Boolean,
    jobToEdit: Object,
    allCustomers: Array,
})
const emit = defineEmits(['close', 'submit'])
const form = ref({})
const closeModal = () => {
    emit('close')
    form.value = {
        customer_id: props.customer.id,
        job_title: '',
        description: '',
        status: 'pending',
        amount: 0,
        due_date: '',
    }

}
watch(
    () => props.isEditingJob,
    (newVal) => {
        if (newVal) {
            form.value = {...props.jobToEdit}
        } else {
            form.value = {
                customer_id: props.customer.id,
                job_title: '',
                description: '',
                status: 'pending',
                amount: 0,
                due_date: '',
            }
        }
    },
    { immediate: true }
)
const submitForm = () => {
    let formData =  form.value
    if (props.isEditingJob){
        axios.patch(`/update_job/${props.jobToEdit.id}`, formData)
            .then(res=>{
                emit('submit', {payload: res.data.jobs})
                emit('close')
                form.value = {
                    customer_id: props.customer.id,
                    job_title: '',
                    description: '',
                    status: 'pending',
                    amount: 0,
                    due_date: '',
                }
                alert(res.data.message)
                console.log(res.data)
            })

    }else {
    axios.post(`/store_job`, formData)
        .then(res => {
            form.value = {
                customer_id: props.customer.id,
                job_title: '',
                description: '',
                status: 'pending',
                amount: 0,
                due_date: '',
            }
            emit('submit', {payload: res.data.jobs})
            emit('close')
            alert(res.data.message)
        })
        .catch(error => {
            console.error('API error:', error)
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
