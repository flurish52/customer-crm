<template>
    <div>
        <div
            v-if="showModal"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
            @click.self="cancel"
        >
            <!-- Modal Content -->
            <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">
                <h2 class="text-lg font-semibold mb-4">Update Invoice Status</h2>
            {{message}}werwe
                <h1 class="text-xl font-semibold text-gray-800 mb-2">
                    <span class="text-primary">Invoice #{{ invoice.invoice_number }}</span>
                    <div>
                        <span>Status:</span>
                    <span class="ml-4 px-2 py-1 text-sm font-medium text-primary-dark rounded-full">
                        {{ invoice.status.toUpperCase() }}
  </span>
                    </div>
                </h1>

                <select v-model="selectedStatus" class="w-full border rounded px-3 py-2 mb-4">
                    <option value="" disabled>Select status</option>
                    <option value="paid">Paid</option>
                    <option value="pending">Pending</option>
                    <option value="cancelled">Cancelled</option>
                    <option value="overdue">Overdue</option>
                </select>

                <div class="flex justify-end gap-3">
                    <button @click="cancel" class="px-4 py-2 rounded bg-gray-200 hover:bg-gray-300">
                        Cancel
                    </button>
                    <button @click="submit" class="px-4 py-2 rounded bg-primary text-white hover:bg-primary-dark">
                        Submit
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import {ref} from 'vue'
import {router} from "@inertiajs/vue3";
const props = defineProps({
    showModal: Boolean,
    invoice: Object,
})
const selectedStatus = ref('')
const message = ref('')
const emits =  defineEmits(['cancel'])
const cancel = () => {
    selectedStatus.value = ''
    emits('cancel')
}
const submit = () => {
    if (!selectedStatus.value) return
        axios.patch(`/update_invoice/status/${props.invoice.id}`, {selectedStatus: selectedStatus.value})
            .then(res =>{
                if (res.status === 200){
                    message.value = res.data.message
                    router.visit('/dashboard/invoices')
                }
            })
}
</script>

<style scoped>
.bg-primary {
    background-color: #4361ee;
}

.bg-primary-dark {
    background-color: #3a56d4;
}
</style>
