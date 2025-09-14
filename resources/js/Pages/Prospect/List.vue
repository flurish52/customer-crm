<template>
    <AuthenticatedLayout>
        <AddProspect :isEditing="isEditing"
                     :visible="showAddProspect"
                     @close="closeAddProspectModal"
                     :prospect="prospectToEdit"
        />
        <div class="p-6 space-y-8 bg-gray-50 min-h-screen">
            <!-- Header with title and button -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <h1 class="text-2xl font-bold text-gray-800">Prospect Management</h1>
                <button
                    @click="showAddProspectModal"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-lg shadow-sm transition-colors flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Add Prospect
                </button>
            </div>

            <!-- Analytics Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100 transition-all hover:shadow-md">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Total Prospects</p>
                            <h2 class="text-2xl font-bold text-gray-800 mt-1">{{ prospects.length }}</h2>
                        </div>
                        <div class="bg-blue-100 p-3 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100 transition-all hover:shadow-md">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Converted</p>
                            <h2 class="text-2xl font-bold text-green-700 mt-1">{{ converted }}</h2>
                        </div>
                        <div class="bg-green-100 p-3 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100 transition-all hover:shadow-md">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Lost</p>
                            <h2 class="text-2xl font-bold text-red-700 mt-1">{{ lost }}</h2>
                        </div>
                        <div class="bg-red-100 p-3 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100 transition-all hover:shadow-md">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Active</p>
                            <h2 class="text-2xl font-bold text-blue-700 mt-1">{{ active }}</h2>
                        </div>
                        <div class="bg-blue-100 p-3 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters and Search -->
            <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100">
                <div class="flex flex-col md:flex-row gap-4 items-start md:items-center justify-between">
                    <div class="relative w-full md:w-64">
                        <input
                            type="text"
                            placeholder="Search prospects..."
                            class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"
                        >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 absolute left-3 top-3"
                             fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>

                    <div class="flex flex-wrap gap-3 w-full md:w-auto">
                        <select
                            class="border border-gray-300 rounded-lg px-3 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition text-sm">
                            <option>All Statuses</option>
                            <option>New</option>
                            <option>Contacted</option>
                            <option>Follow Up</option>
                            <option>Converted</option>
                            <option>Lost</option>
                        </select>

                        <select
                            class="border border-gray-300 rounded-lg px-3 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition text-sm">
                            <option>All Sources</option>
                            <option>Website</option>
                            <option>Referral</option>
                            <option>Social Media</option>
                            <option>Event</option>
                        </select>

                        <button
                            class="flex items-center gap-2 text-gray-600 border border-gray-300 rounded-lg px-3 py-2.5 hover:bg-gray-50 transition text-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                            </svg>
                            More Filters
                        </button>
                    </div>
                </div>
            </div>

            <!-- If empty -->
            <div v-if="prospects.length === 0"
                 class="text-center py-16 bg-white rounded-xl shadow-sm border border-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-300 mb-4" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-gray-500 mb-2 text-lg">No prospects yet</p>
                <p class="text-gray-400 text-sm mb-6">Get started by adding your first prospect</p>
                <button
                    @click="showAddProspectModal"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg shadow-sm transition-colors inline-flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Add Prospect
                </button>
            </div>

            <!-- Table (Desktop) -->
            <div v-if="prospects.length > 0"
                 class="hidden md:block bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100">
                <table class="w-full">
                    <thead class="bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    <tr>
                        <th class="p-2">Name</th>
                        <th class="p-2">Company</th>
                        <th class="p-2">Email</th>
                        <th class="p-2">Phone</th>
                        <th class="p-2">Last Activity</th>
                        <th class="p-2">Status</th>
                        <th class="p-2 text-right">Actions</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                    <tr v-for="prospect in prospects" :key="prospect.id" class="hover:bg-gray-50 transition-colors">
                        <td class="p-2 font-medium text-gray-900">{{ prospect.name }}</td>
                        <td class="p-2 text-gray-600">{{ prospect.company }}</td>
                        <td class="p-2 text-gray-600">{{ prospect.email }}</td>
                        <td class="p-2 text-gray-600">{{ prospect.phone }}</td>
                        <td class="p-2 text-sm">
                          <span
                              class="inline-block px-3 py-1 rounded-lg bg-gray-50 text-gray-500 italic text-xs font-medium"
                              v-html="getLastActivity(prospect)"
                          ></span>
                        </td>
                        <td class=" relative">
                            <TagBadge :label="prospect.status" position="top-1/4 right bg-white"
                                      :status="prospect.status"/>
                        </td>
                        <td class="p-2 flex gap-3 justify-end">
                            <Link
                                :href="`/dashboard/prospect/${prospect.id}`"
                                class="text-blue-600 hover:text-blue-800 transition-colors p-1 rounded hover:bg-blue-50">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                     stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                View
                            </Link>
                            <button
                                @click="editProspect(prospect)"
                                class="text-yellow-600 hover:text-yellow-800 transition-colors p-1 rounded hover:bg-yellow-50">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                     stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Edit
                            </button>
                            <button
                                @click="deleteProspect(prospect)"
                                class="text-red-600 hover:text-red-800 transition-colors p-1 rounded hover:bg-red-50">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                     stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                Delete
                            </button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <!-- Cards (Mobile) -->
            <div v-if="prospects.length > 0" class="grid grid-cols-1 gap-4 md:hidden">
                <div v-for="prospect in prospects" :key="prospect.id"
                     class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
                    <div class=" flex justify-start items-start mb-3">
                        <div>
                            <h3 class="font-bold text-gray-900 text-lg">{{ prospect.name }}</h3>
                            <p class="text-sm text-gray-600">{{ prospect.company }}</p>
                        </div>
                        <TagBadge :label="prospect.status" position="top-1/50 right-10 text-white"
                                  :status="prospect.status"/>
                    </div>

                    <div class="space-y-2 text-sm">
                        <div class="flex items-center gap-2 text-gray-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <span>{{ prospect.email }}</span>
                        </div>

                        <div class="flex items-center gap-2 text-gray-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            <span>{{ prospect.phone }}</span>
                        </div>

                        <div class="flex items-center gap-2 text-gray-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>

                            <span
                                class="inline-block px-3 py-1 rounded-lg bg-gray-100 text-gray-500 text-xs font-medium"
                                v-html="getLastActivity(prospect)"
                            ></span>
                        </div>
                    </div>

                    <div class="flex gap-3 mt-4 pt-3 border-t border-gray-100">
                        <Link
                            :href="`/dashboard/prospect/${prospect.id}`"
                            class="flex-1 flex items-center justify-center gap-1 text-blue-600 text-sm py-2 rounded-lg hover:bg-blue-50 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            View
                        </Link>
                        <button
                            @click="editProspect(prospect)"
                            class="flex-1 flex items-center justify-center gap-1 text-yellow-600 text-sm py-2 rounded-lg hover:bg-yellow-50 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Edit
                        </button>
                        <button
                            @click="deleteProspect(prospect)"
                            class="flex-1 flex items-center justify-center gap-1 text-red-600 text-sm py-2 rounded-lg hover:bg-red-50 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import TagBadge from "@/Components/AlertsAndPrompts/TagBadge.vue";
import {computed, ref} from "vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import AddProspect from "@/Components/Prospect/AddProspect.vue";
import dayjs from "dayjs";
import relativeTime from "dayjs/plugin/relativeTime";
import axios from "axios";
import {router} from "@inertiajs/vue3";
import {Link} from "@inertiajs/vue3";

dayjs.extend(relativeTime);

const loading = ref(false);
const error = ref('');
const message = ref('');

const props = defineProps({
    prospects: {type: Array, required: true}
});

const isEditing = ref(false);
const showAddProspect = ref(false);
const prospectToEdit = ref(null);

const closeAddProspectModal = () => {
    prospectToEdit.value = null;
    isEditing.value = false;
    showAddProspect.value = false;
};

const showAddProspectModal = () => {
    showAddProspect.value = true;
};

const editProspect = (prospect) => {
    prospectToEdit.value = prospect;
    isEditing.value = true;
    showAddProspect.value = true;
};

const converted = computed(() =>
    props.prospects.filter((p) => p.status === "converted").length
);

const lost = computed(() =>
    props.prospects.filter((p) => p.status === "lost").length
);

const active = computed(() =>
    props.prospects.filter((p) =>
        ["new", "contacted", "follow_up"].includes(p.status)
    ).length
);



const deleteProspect = async (prospect) => {
    if (!confirm('Are you sure you want to delete this prospect?')) return;
    try {
        loading.value = true;
        const res = await axios.delete(`/dashboard/prospect/${prospect.id}`);
        message.value = res.data.message;
        error.value = '';
        router.visit(window.location.href, {preserveScroll: true})
    } catch (err) {
        error.value = err.response?.data?.message || 'Something went wrong';
        message.value = '';
    } finally {
        loading.value = false;
    }
};

const getLastActivity = (prospect) => {
    if (!prospect.prospect_activities?.length) return "No activity";

    const last = [...prospect.prospect_activities].sort(
        (a, b) => new Date(b.created_at) - new Date(a.created_at)
    )[0];

    const type = last.type.charAt(0).toUpperCase() + last.type.slice(1);
    const createdAt = dayjs(last.created_at);

    const time =
        dayjs().diff(createdAt, "day") <= 30
            ? createdAt.fromNow()
            : createdAt.format("D MMM, YYYY");

    return `${type} <br /> ${time}`;
};
</script>

