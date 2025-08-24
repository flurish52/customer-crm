<template>
    <AuthenticatedLayout>
            <div v-if="showAddCustomerModal ===  true"
                 @click.self="closeAddCustomerModal"
                 class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
                <AddCustomerModal
                :customerToEdit="customerToEdit"
                :isEditing="isEditing"
                @closeAddCustomerModal="closeAddCustomerModal"
                @customerCreated_Updated="refreshPage"
                />
            </div>
        <div class="space-y-6">
            <!-- Page Header -->
            <div class="flex flex-col justify-between gap-4 md:flex-row md:items-center bg-primary-light/10 p-6 rounded-xl shadow-inner border border-primary/20">
                <div>
                    <h1 class="text-2xl font-bold text-primary-dark">Customers</h1>
                    <p class="mt-1 text-sm text-secondary-dark/80">Manage your customer relationships</p>
                </div>

                <div class="flex flex-col md:flex-row xs:flex-row gap-3 w-full md:w-auto items-stretch">

                    <div class="flex items-center gap-3 w-full">
                        <!-- Search Component (always shows on desktop, conditional on mobile) -->
                        <SearchComponent
                            v-if="showSearchBar || !isMobile"
                            @sendSearchQuery="sendSearchQuery"
                            class="flex-1"
                            @close="showSearchBar = false"
                        />

                        <!-- Add Customer Button -->
                        <button
                            class="inline-flex items-center justify-center px-4 py-2.5 bg-primary hover:bg-primary-dark text-white rounded-lg transition-all duration-200 hover:shadow-md hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-primary/50 flex-shrink-0"
                            @click="openAddCustomerModal"
                        >
                            <PlusIcon class="w-5 h-5 md:mr-2" />
                            <span class="hidden md:inline">Add Customer</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mount existing Customers component -->
            <CustomerList
                class="mt-6 animate-fade-in"
                @update="refreshPage"
                :searchQuery="searchQuery"
                @isEditing="isEditingCustomer"
                @openAddCustomerModal="openAddCustomerModal"
            />
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import {ref} from 'vue'
import {PlusIcon, MagnifyingGlassIcon} from '@heroicons/vue/24/outline'
import CustomerList from "@/Components/Customer/CustomerList.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {router} from "@inertiajs/vue3";
import AddCustomerModal from "@/Components/Customer/AddCustomerModal.vue";
import SearchComponent from "@/Components/SearchComponent.vue";


const componentKey = ref(0)
const searchQuery = ref('')
let isEditing = ref(false)
let customerToEdit = ref({})
let newUpdate = ref('')
let showSearchBar = ref(false)

const sendSearchQuery = ({payload}) => {
    searchQuery.value = payload
}

const isEditingCustomer = ({payload})=>{
    isEditing.value = true
    customerToEdit.value = payload
    openAddCustomerModal()
}
const refreshPage = () => {
    router.reload()
}

const showAddCustomerModal = ref(false)
const openAddCustomerModal = () => {
    showAddCustomerModal.value = true
}
const closeAddCustomerModal = () => {
    showAddCustomerModal.value = false
    isEditing.value = false
    customerToEdit.value = {}
}
const customerCreated_Updated = () => {
    newUpdate.value = Date.now()
}
</script>

