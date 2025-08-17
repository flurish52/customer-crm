<template>
    <AuthenticatedLayout>
        <div class="">
            <!-- Stats Cards -->
            <StatCards/>
            <div class="flex items-center justify-between my-3">
                    <div>
                        <h2 class="text-xl font-semibold text-primary-dark flex items-center justify-start">
                            <svg class="w-5 h-5 text-primary-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <span class="text-2xl font-semibold text-gray-900 relative inline-block ml-2">
        Client Management
        <span class="absolute bottom-0 left-0 h-0.5 w-12 bg-primary-dark rounded-full"></span>
      </span>
                            <span class="ml-3 text-xs font-normal text-gray-400">OVERVIEW</span>
                        </h2>
                        <p class="text-sm text-gray-500 ml-8 mt-1">
                            Monitor your business health at a glance
                        </p>
                    </div>
            </div>
            <div>
                <!-- Tab buttons -->
                <div class="flex items-end relative z-10 pl-2 pt-2 bg-gray-100 dark:bg-gray-800 rounded-t-lg">
                    <button
                        v-for="tab in tabs"
                        :key="tab.id"
                        @click="activeTab = tab.id"
                        class="relative px-4 py-2 rounded-t-lg mr-1 transition-all duration-200"
                        :class="{
        'bg-white  text-primary shadow-[0_1px_0_0_rgba(255,255,255,0.7),1px_0_3px_-1px_rgba(0,0,0,0.1),-1px_0_3px_-1px_rgba(0,0,0,0.1)]': activeTab === tab.id,
        'bg-primary text-tertiary-light hover:bg-primary-dark': activeTab !== tab.id
      }"
                    >
                        <p class="font-medium text-sm flex items-center gap-2">
                            {{ tab.label }}
                        </p>
                        <span
                            v-if="activeTab === tab.id"
                            class="absolute bottom-0 left-0 right-0 h-0.5 bg-primary"
                        ></span>
                    </button>
                </div>

                <!-- Tab content -->
                <div class="bg-white rounded-b-lg rounded-tr-lg shadow-lg relative -mt-1 z-0">
                    <div v-for="tab in tabs" :key="tab.id">
                        <section
                            v-if="activeTab === tab.id"
                            class="rounded-lg min-w-0"
                        >
                        <div class="relative overflow-hidden"> <!-- New constraining wrapper -->
                            <component
                                :is="tab.component"
                                v-bind="tab.props || []"
                                class="w-full"
                            />
                        </div>
                        </section>
                    </div>

                </div>
            </div>
        </div>
    </AuthenticatedLayout>

</template>

<script setup>
import {onMounted, ref} from 'vue'
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import StatCards from "@/Components/StatCards.vue"
import OverDueJobs from "@/Components/Job/OverDueJobs.vue";
import RecentActivities from "@/components/Job/RecentActivities.vue"
import OwingCustomers from "@/Components/Customer/OwingCustomers.vue";
let props = defineProps({
    jobs: Array,
    recentActivities: Array,
    customers: Array
})
let overdueJobs = ref([])
let owingCustomers = ref()
const tabs = [
    {id: 1, label: 'Overdue Jobs', component: OverDueJobs, props: {overdueJobs}},
    {id: 3, label: 'Owing customers', component: OwingCustomers, props: {owingCustomers}},
    {id: 2, label: ' Recent Activity', component: RecentActivities, props: {recentActivities: props.recentActivities}},
]
const activeTab = ref(tabs[0].id)
function getOwingCustomers(customers) {
    const today = new Date();

    return customers.filter(customer => {
        return customer.jobs.some(job => {
            const jobAmount = parseFloat(job.amount);
            const totalPayments = job.activities
                .filter(a => a.type === "payment")
                .reduce((sum, a) => {
                    const change = JSON.parse(a.changes);
                    return sum + parseFloat(change.amount);
                }, 0);

            const balance = jobAmount - totalPayments;
            const jobDueDate = new Date(job.due_date);

            const isOverdue = balance > 0 && (
                job.status === "completed" &&
                jobDueDate < today
            );

            return isOverdue;
        });
    });
}
onMounted(()=>{
    overdueJobs.value = props.jobs.filter(job => job.status === 'overdue')
    owingCustomers.value = getOwingCustomers(props.customers.data)
})
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


.tab-container {
    --tab-border-radius: 8px;
    --tab-active-shadow: 0 1px 0 0 rgba(255, 255, 255, 0.7);
}

.dark .tab-container {
    --tab-active-shadow: 0 1px 0 0 rgba(0, 0, 0, 0.5);
}

.shadow-tab-active {
    box-shadow:
        var(--tab-active-shadow),
        1px 0 3px -1px rgba(0, 0, 0, 0.1),
        -1px 0 3px -1px rgba(0, 0, 0, 0.1);
}

.tab-content {
    min-height: 200px; /* Adjust as needed */
}

/* Transition effects */
.fade-slide-enter-active,
.fade-slide-leave-active {
    transition: all 0.2s ease;
}

.fade-slide-enter-from {
    opacity: 0;
    transform: translateY(5px);
}

.fade-slide-leave-to {
    opacity: 0;
    transform: translateY(-5px);
}
</style>
<style>
.min-w-0 {
    min-width: 0;
}
</style>
