<template>
    <div class="bg-white rounded-xl shadow-sm border border-tertiary-light overflow-hidden ">
        <!-- Table Header -->
        <div class="grid grid-cols-12 gap-4 px-6 py-4 bg-tertiary-light/50 border-b border-tertiary-light">
            <div class="col-span-4 md:col-span-3 font-medium text-primary-dark">Customer</div>
            <div class="col-span-4 md:col-span-2 font-medium text-primary-dark">Contact</div>
            <div class="hidden md:block md:col-span-3 font-medium text-primary-dark">Recent Activity</div>
            <div class="col-span-4 md:col-span-2 font-medium text-primary-dark">Jobs</div>
            <div class="col-span-4 md:col-span-2 font-medium text-primary-dark text-right">Actions</div>
        </div>

        <!-- Customer Rows -->
        <div
            v-for="customer in filteredCustomers"
            :key="customer?.id"
            class="px-4 py-4 border-b border-tertiary-light hover:bg-tertiary-light/20 transition-colors md:grid md:grid-cols-12 md:gap-4 md:items-center"
        >
            <!-- Mobile Stack -->
            <div class="flex flex-col space-y-2 md:col-span-3 md:flex-row md:items-center md:space-y-0 md:space-x-3">
                <!-- Avatar -->
                <div
                    class="flex-shrink-0 h-10 w-10 rounded-full overflow-hidden bg-primary-light/10 flex items-center justify-center text-primary-DEFAULT font-medium">
                    <template v-if="customer.avatar">
                        <img :src="`/storage/${customer.avatar}`" alt="Avatar" class="object-cover w-full h-full"/>
                    </template>
                    <template v-else>
                        {{ customer?.initials }}
                    </template>
                </div>

                <!-- Name & Company -->
                <div>
                    <p class="font-medium text-primary-dark">{{ customer?.name }}</p>
                    <p class="text-sm text-gray-500">{{ customer?.company }}</p>
                </div>
            </div>

            <!-- Contact Info -->
            <div class="mt-2 md:mt-0 md:col-span-2">
                <p class="text-primary-dark">{{ customer?.phone }}</p>
                <p class="text-sm text-gray-500 truncate">{{ customer?.email || 'No email' }}</p>
            </div>

            <!-- Activities (Desktop Only) -->
            <div class="hidden md:block md:col-span-3">
                <div class="flex flex-col space-y-2">
                    <div
                        v-for="(activity, index) in customer.activities"
                        :key="index"
                        class="flex items-center space-x-2"
                    >
                        <div
                            class="h-2 w-2 rounded-full"
                            :class="{
            'bg-green-500': activity.type === 'completed',
            'bg-secondary': customer?.type === 'payment',
            'bg-primary': customer?.type === 'job'
          }"
                        ></div>

                        <p class="text-sm text-gray-600 whitespace-nowrap">
                            {{ activity?.type || 'No activity' }}
                            <span class="text-xs text-gray-400 ml-1">
            {{ dayjs(activity?.created_at).format('DD-MMM-YYYY') || '' }}
          </span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Job Count & Status -->
            <div class="mt-2 md:mt-0 md:col-span-2">
                <div class="flex items-center">
      <span class="text-sm font-medium text-primary-dark mr-2">
        {{ customer.jobs?.length || 0 }}
      </span>
                    <span
                        v-if="(customer?.pendingJobs || 0) > 0 || (customer?.overdueJobs || 0) > 0"
                        class="px-2 py-0.5 rounded-full text-xs font-medium"
                        :class="{
          'bg-secondary-light/20 text-secondary-dark': (customer?.pendingJobs || 0) > 0,
          'bg-red-100 text-red-800': (customer?.overdueJobs || 0) > 0
        }"
                    >
        {{ customer?.overdueJobs > 0 ? `${customer.overdueJobs} overdue` : `${customer.pendingJobs} pending` }}
      </span>
                </div>
            </div>

            <!-- Actions -->
            <div class="mt-3 md:mt-0 md:col-span-2 flex items-center space-x-2">
                <Link
                    :href="`/customer/${customer.id}`"
                    class="p-2 rounded-lg hover:bg-tertiary-light text-primary-DEFAULT"
                    title="View"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                </Link>

                <button
                    @click="editCustomer(customer)"
                    class="p-2 rounded-lg hover:bg-tertiary-light text-primary-DEFAULT"
                    title="Edit"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </button>
                <button
                    @click="deleteCustomer(customer)"
                    class="p-2 rounded-lg hover:bg-tertiary-light text-red-500"
                    title="Edit"
                >
                    Delete
                </button>
            </div>

            <!-- Divider (for full width) -->
            <div class="block md:hidden mt-3">
                <hr class="w-full border-t border-tertiary-dark"/>
            </div>
        </div>

        <!-- Empty State -->
        <div v-if="filteredCustomers.length === 0" class="p-12 text-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
            <h3 class="mt-2 text-lg font-medium text-primary-dark">No customers found</h3>
            <p class="mt-1 text-gray-500">Try adjusting your search or add a new customer</p>
            <button
                @click="openAddCustomerModal"
                class="mt-4 px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors"
            >
                Add Customer
            </button>
        </div>
    </div>
</template>

<script setup>
import {computed, onMounted, ref, watch} from "vue";
import axios from "axios";
import {Link, router} from "@inertiajs/vue3";
import dayjs from "dayjs";

const props = defineProps({
    searchQuery: String,
    newUpdate: String,
})

let customers = ref([])
let emits = defineEmits(['openAddCustomerModal', 'isEditing', 'update'])
// Filter customers based on search query
const filteredCustomers = computed(() => {
    if (!props.searchQuery) return customers.value
    const query = props.searchQuery.toLowerCase()
    return customers.value.filter(customer =>
        customer.name.toLowerCase().includes(query) ||
        customer.company?.toLowerCase().includes(query) ||
        customer.email?.toLowerCase().includes(query) ||
        customer.phone?.toLowerCase().includes(query)
    )
})
const openAddCustomerModal = (id) => {
    emits(['openAddCustomerModal'])
}
const editCustomer = (customer) => {
    emits('isEditing', {payload: customer})
}
const getUserCustomers = ()=>{
    axios.get(`/get_customer`)
        .then(res => {
            customers.value = res.data.map(customer => {
                return {
                    ...customer,
                    initials: customer.name
                        .split(' ')
                        .map(word => word[0])
                        .join('')
                        .toUpperCase()
                }
            })
        })
}
onMounted(() => {
getUserCustomers()
})
const deleteCustomer = (customer) => {
    if (!confirm(`Are you sure you want to delete "${customer.name}"?`)) {
        return;
    }
    axios.delete(`/customer_delete/${customer.id}`)
        .then(res => {
            if (res.status === 200) {
                emits('update')
                alert(res.data.message)
                getUserCustomers()
            }
        })
        .catch(error => {
            console.error('Error deleting job:', error);
            alert('Failed to delete job. Please try again.');
        });
}
</script>

<style scoped>

</style>
