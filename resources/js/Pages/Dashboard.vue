<template>
    <AuthenticatedLayout>

        <div class="p-6 lg:p-8">
            <!-- Page Header -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-primary-dark">Customer Management</h1>
                    <p class="text-secondary-dark">View and manage all your customers</p>
                </div>
                <div class="flex gap-3 flex-col-reverse md:flex-row">
                    <button
                        @click="openAddCustomerModal"
                        class="flex flex-col md:flex-row items-center gap-2 px-4 py-2 bg-primary text-white rounded-lg hover:bg-secondary-dark transition-colors"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Add Customer
                    </button>
                    <div>
                        <SearchComponent
                            @sendSearchQuery="sendSearchQuery"
                        />
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            <StatCards/>
            <!-- Customer List -->
            <CustomerList
                @openAddCustomerModal="openAddCustomerModal"
                :searchQuery="searchQuery"
                @isEditing="editCustomer"
                :newUpdate="newUpdate"
            />


            <!-- Add Customer Modal -->
            <div v-if="showAddCustomerModal"
                 @click.self="closeAddCustomerModal"
                 class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
                <AddCustomerModal
                    :customerToEdit="customerToEdit"
                    :isEditing="isEditing"
                    @customerCreated_Updated="customerCreated_Updated"
                    @closeAddCustomerModal="closeAddCustomerModal"
                />
            </div>
        </div>
    </AuthenticatedLayout>

</template>

<script setup>
import {onMounted, ref} from 'vue'
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import AddCustomerModal from "@/Components/Customer/AddCustomerModal.vue";
import CustomerList from "@/Components/Customer/CustomerList.vue";
import SearchComponent from "@/Components/SearchComponent.vue";
import StatCards from "@/Components/StatCards.vue"

const searchQuery = ref('')
let isEditing = ref(false)
let customerToEdit = ref({})
let newUpdate = ref('')
const sendSearchQuery = ({payload}) => {
    searchQuery.value = payload
}
const showAddCustomerModal = ref(false)
const openAddCustomerModal = () => {
    showAddCustomerModal.value = true
}
const closeAddCustomerModal = () => {
    showAddCustomerModal.value = false
}
const editCustomer = ({payload}) => {
    customerToEdit.value = payload
    isEditing.value = true
    openAddCustomerModal()
}

const customerCreated_Updated = ()=>{
    newUpdate.value = Date.now()
}
</script>

<style scoped>
/* Custom scrollbar for modal */
.modal-content::-webkit-scrollbar {
    width: 8px;
}

.modal-content::-webkit-scrollbar-track {
    background: #f5f0e6;
}

.modal-content::-webkit-scrollbar-thumb {
    background: #0E9C9A;
    border-radius: 4px;
}
</style>
