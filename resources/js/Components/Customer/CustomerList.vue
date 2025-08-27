<template>
    <div class="bg-white rounded-xl shadow-sm border border-tertiary-light overflow-hidden ">
        <!-- Table Header -->
            <!-- Desktop Table Headers -->
            <div class="hidden md:grid grid-cols-12 gap-4 px-6 py-4 bg-tertiary-light/50 border-b border-tertiary-light">
                <div class="col-span-3 font-medium text-primary-dark">Client</div>
                <div class="col-span-2 font-medium text-primary-dark">Contact</div>
                <div class="col-span-3 font-medium text-primary-dark">Recent Activity</div>
                <div class="col-span-2 font-medium text-primary-dark">Jobs</div>
                <div class="col-span-2 font-medium text-primary-dark text-right">Actions</div>
            </div>

            <!-- Customer Rows -->
            <div
                v-for="customer in filteredCustomers"
                :key="customer?.id"
                class="bg-white rounded-lg shadow-sm mb-3 md:shadow-none md:mb-0 md:px-4 md:py-4 md:border-b md:border-tertiary-light md:hover:bg-tertiary-light/20 md:transition-colors md:grid md:grid-cols-12 md:gap-4 md:items-center"
            >
                <!-- Mobile Card Layout -->
                <div class="md:hidden p-4">
                    <!-- Customer Header with Avatar and Actions -->
                    <div class="flex justify-between items-start mb-3">
                        <div class="flex  items-center space-x-3">
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

                        <!-- Mobile Actions -->
                        <div class="flex space-x-2">
                            <Link
                                :href="`/customer/${customer.id}`"
                                class="p-2 rounded-lg hover:bg-tertiary-light text-blue-500"
                                title="View"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                     stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                View
                            </Link>
                        </div>
                    </div>

                    <!-- Contact Info -->
                    <div class="mb-3">
                        <div class="flex items-center text-sm text-primary-dark mb-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                            {{ customer?.phone || 'No phone' }}
                        </div>
                        <div class="flex items-center text-sm text-gray-500 truncate">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            {{ customer?.email || 'No email' }}
                        </div>
                    </div>

                    <!-- Jobs Info -->
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center">
          <span class="text-sm font-medium text-primary-dark mr-2">
            {{ customer.jobs?.length || 0 }} jobs
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

                        <!-- Additional Actions -->
                        <div class="flex space-x-2">
                            <button
                                @click="editCustomer(customer)"
                                class="p-2 rounded-lg hover:bg-tertiary-light text-blue-500"
                                title="Edit"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                     stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Edit
                            </button>
                            <button
                                @click="deleteCustomer(customer)"
                                class="p-2 rounded-lg hover:bg-tertiary-light text-red-500"
                                title="Delete"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Recent Activity Preview -->
                    <div v-if="customer.activities && customer.activities.length > 0" class="pt-3 border-t border-gray-100">
                        <p class="text-xs text-gray-500 uppercase font-medium mb-2">Recent Activity</p>
                        <div class="flex items-center">
                            <div
                                class="h-2 w-2 rounded-full mr-2"
                                :class="{
              'bg-green-500': customer.activities[0].type === 'completed',
              'bg-secondary': customer.activities[0].type === 'payment',
              'bg-primary': customer.activities[0].type === 'job'
            }"
                            ></div>
                            <p class="text-sm text-gray-600 truncate">
                                {{ customer.activities[0]?.type || 'No activity' }}
                                <span class="text-xs text-gray-400 ml-1">
              {{ dayjs(customer.activities[0]?.created_at).format('DD-MMM-YYYY') || '' }}
            </span>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Desktop Layout (unchanged) -->
                <div class="hidden md:flex md:col-span-3 md:flex-row md:items-center md:space-x-3">
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

                <!-- Desktop Contact Info -->
                <div class="hidden md:block md:col-span-2">
                    <p class="text-primary-dark">{{ customer?.phone }}</p>
                    <p class="text-sm text-gray-500 truncate">{{ customer?.email || 'No email' }}</p>
                </div>

                <!-- Desktop Activities -->
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
              'bg-secondary': activity.type === 'payment',
              'bg-primary': activity.type === 'job'
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

                <!-- Desktop Job Count & Status -->
                <div class="hidden md:block md:col-span-2">
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

                <!-- Desktop Actions -->
                <div class="hidden md:flex md:col-span-2 md:justify-end md:items-center md:space-x-2">
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
                        title="Delete"
                    >
                        Delete
                    </button>
                </div>
            </div>

        <!-- Empty State -->
        <div v-if="filteredCustomers.length === 0" class="p-12 text-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
            <h3 class="mt-2 text-lg font-medium text-primary-dark">No Clients found</h3>
            <p class="mt-1 text-gray-500">Try adjusting your search or add a new client</p>
            <button
                @click="emits('openAddCustomerModal')"
                class="mt-4 px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors"
            >
                Add client
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
