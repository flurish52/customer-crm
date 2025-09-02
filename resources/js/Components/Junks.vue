<template>

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







    <button
        @click="toggleQuickActions"
        class="p-2 rounded-full text-primary hover:text-primary hover:bg-tertiary-light focus:outline-none transition-colors duration-200"
    >
        <span class="sr-only">Quick actions</span>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
             stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
        </svg>
    </button>
    </div>

    <!-- Quick actions dropdown menu -->
    <transition
        enter-active-class="transition ease-out duration-100"
        enter-from-class="transform opacity-0 scale-95"
        enter-to-class="transform opacity-100 scale-100"
        leave-active-class="transition ease-in duration-75"
        leave-from-class="transform opacity-100 scale-100"
        leave-to-class="transform opacity-0 scale-95"
    >
        <div
            v-show="showQuickActions"
            class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50"
        >
            <div class="py-1">
                <button
                    @click="openAddCustomerModal"
                    class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-tertiary-light hover:text-primary"
                >
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="mr-3 h-5 w-5 text-gray-400 group-hover:text-primary"
                         fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    New Customer
                </button>
                <a
                    href="#"
                    class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-tertiary-light hover:text-primary-DEFAULT"
                >
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="mr-3 h-5 w-5 text-gray-400 group-hover:text-primary"
                         fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Generate Receipt
                </a>
                <a
                    href="#"
                    class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-tertiary-light hover:text-primary-DEFAULT"
                >
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="mr-3 h-5 w-5 text-gray-400 group-hover:text-primary-DEFAULT"
                         fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/>
                    </svg>
                    Export Data
                </a>
            </div>
        </div>
    </transition>

</template>

<script setup>
import AddCustomerModal from "@/Components/Customer/AddCustomerModal.vue";
import CustomerList from "@/Components/Customer/CustomerList.vue";
import SearchComponent from "@/Components/SearchComponent.vue";

const editCustomer = ({payload}) => {
    customerToEdit.value = payload
    isEditing.value = true
    openAddCustomerModal()
}

</script>

<style scoped>

</style>
