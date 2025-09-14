<template>
    <AuthenticatedLayout>
        <AddProspect
            :isEditing="isEditing"
            :visible="showAddProspect"
            @close="closeAddProspectModal"
            :prospect="prospectToEdit"
        />
        <AddProspectActivity
            :visible="activityModalIsVisible"
            :isEditing="isEditingActivity"
            :activity="activityToEdit"
            @close="closeAddProspectActivityModal"
            :prospectId="prospectId"
        />
        <div class="px-6 space-y-8 bg-gray-50 min-h-screen">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <h1 class="text-2xl font-bold text-gray-800">Prospect Profile</h1>
            </div>
            <!-- Profile & Activities -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 flex flex-col md:flex-row  gap-6">
                <!-- Left: Prospect Details -->
                <div class="flex-1 space-y-3">
                    <h2 class="text-xl font-bold text-gray-800">{{ prospect.name }}</h2>
                    <p class="text-gray-600">{{ prospect.company || 'No company' }}</p>

                    <div class="space-y-1 text-sm text-gray-600">
                        <div class="flex items-center gap-2">
                            <svg class="h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <span>{{ prospect.email || 'No email' }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            <span>{{ prospect.phone || 'No phone' }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>{{ prospect.source || prospect.customSource || 'No source' }}</span>
                        </div>
                    </div>

                    <div class="flex gap-3 mt-4">
                        <button @click="editProspect(prospect)"
                                class="bg-yellow-100 text-yellow-700 px-3 py-2 rounded-lg hover:bg-yellow-200 transition">
                            Edit
                        </button>
                        <button @click="deleteProspect(prospect)"
                                class="bg-red-100 text-red-700 px-3 py-2 rounded-lg hover:bg-red-200 transition">Delete
                        </button>

                    </div>

                    <div>
                        <h3 class="text-xl font-semibold text-gray-600 my-2">Status</h3>

                        <div>
                            <div v-if="error" class="mb-2 px-4 py-2 rounded-md bg-red-100 text-red-700 text-sm">
                                {{ error }}
                            </div>

                            <div v-if="message" class="mb-2 px-4 py-2 rounded-md bg-green-100 text-green-700 text-sm">
                                {{ message }}
                            </div>
                        </div>

                        <div class="flex g flex-wrap">
                            <br>

                            <button
                                v-for="(status, index) in prospectStatus"
                                :key="status"
                                @click="updateProspectStatus(index)"
                                type="button"
                                :disabled="prospect.is_customer && status === 'converted'"
                                :class="[
        'px-3 py-1 rounded-lg text-sm transition-colors',
        prospect.status === status
          ? 'bg-green-600 text-white font-medium'
          : 'bg-gray-100 text-gray-700 hover:bg-gray-200',
        prospect.status === 'converted' && status === 'converted'
          ? 'opacity-50 cursor-not-allowed'
          : ''
      ]"
                            >
                                {{ status.charAt(0).toUpperCase() + status.slice(1).toLowerCase() }}
                            </button>
                        </div>
                    </div>

                </div>
                <!-- Right: Activity Timeline -->
                <div>
                    <div class="flex justify-between items-center gap-2 flex-wrap">
                        <h3 class="text-gray-700 font-semibold truncate max-w-xs">
                            Activities with prospect
                        </h3>
                        <button
                            @click="openAddActivityModal"
                            class="bg-primary hover:bg-primary-dark text-white px-4 py-2 rounded-lg shadow-sm transition-colors text-sm">
                            Add Activity
                        </button>
                    </div>

                    <div class="flex-1 overflow-y-auto max-h-[400px] space-y-3">
                        <div v-if="!prospect.prospect_activities?.length" class="text-gray-400 italic text-sm">
                            No activity yet
                        </div>
                        <div v-for="activity in sortedActivities"
                             :key="activity.id"
                             class="group p-3 rounded-lg bg-gray-50 flex justify-between items-start relative hover:bg-gray-100 transition-colors">

                            <div>
                                <p class="text-gray-800 font-medium">{{ capitalize(activity.type) }}</p>
                                <p class="text-gray-600 text-sm">{{ activity.content }}</p>
                            </div>

                            <span class="text-gray-400 text-xs">
                                {{ formatActivityTime(activity.activity_date) }}
                            </span>
                            <!-- Edit button, only visible on hover -->
                            <button
                                @click="editActivity(activity)"
                                class="absolute right-3 top-3 bg-primary-dark text-white px-2 py-1 rounded text-xs font-medium opacity-0 group-hover:opacity-100 transition-opacity">
                                Edit
                            </button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
<script setup>
import TagBadge from "@/Components/AlertsAndPrompts/TagBadge.vue"
import {ref, computed} from "vue"
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue"
import AddProspect from "@/Components/Prospect/AddProspect.vue"
import axios from "axios"
import dayjs from "dayjs"
import relativeTime from "dayjs/plugin/relativeTime"
import AddProspectActivity from "@/Components/Prospect/AddProspectActivity.vue";

dayjs.extend(relativeTime)
const props = defineProps({
    prospect: {type: Object, required: true}
})
const isEditing = ref(false)
const showAddProspect = ref(false)
const prospectToEdit = ref(null)
const loading = ref(false)
const error = ref('')
const message = ref('')
const activityToEdit = ref('')
let activityModalIsVisible = ref(false)
let isEditingActivity = ref(false)
let activity = ref({})
let prospectId = ref(false)
let prospectStatus = ref([
    'new',
    'contacted',
    'follow_up',
    'converted',
    'lost'
])
const closeAddProspectModal = () => {
    prospectToEdit.value = null
    isEditing.value = false
    showAddProspect.value = false
}
const openAddActivityModal = () => {
    activityModalIsVisible.value = true
    prospectId.value = props.prospect.id
}
const editActivity = (activity) => {
    activityToEdit.value = activity
    prospectId.value = props.prospect.id
    isEditingActivity.value = true
    activityModalIsVisible.value = true
}
const closeAddProspectActivityModal = () => {
    isEditing.value = false
    activityToEdit.value = null
    prospectId.value = null
    activityModalIsVisible.value = false
}
const editProspect = () => {
    prospectToEdit.value = props.prospect
    isEditing.value = true
    showAddProspect.value = true
}
const deleteProspect = async (prospect) => {
    if (!confirm('Are you sure you want to delete this prospect?')) return
    try {
        loading.value = true
        const res = await axios.delete(`/dashboard/prospect/${prospect.id}`)
        message.value = res.data.message
        error.value = ''
        window.history.back()
    } catch (err) {
        error.value = err.response?.data?.message || 'Something went wrong'
        message.value = ''
    } finally {
        loading.value = false
    }
}
const sortedActivities = computed(() => {
    return [...props.prospect.prospect_activities].sort(
        (a, b) => new Date(b.activity_date) - new Date(a.activity_date)
    )
})
const formatActivityTime = (date) => {
    const d = dayjs(date)
    return dayjs().diff(d, 'day') <= 30 ? d.fromNow() : d.format("D MMM, YYYY")
}

const updateProspectStatus = (index) => {
    const newStatus = prospectStatus.value[index]
    if (confirm(`Are you sure you want to update this client status to ${newStatus}?`)) {
        axios.patch(`/prospect_update/status/${props.prospect.id}`, { status: newStatus })
            .then(res => {
                message.value = res.data.message
                error.value = ''
                props.prospect.status = newStatus
                setTimeout(()=>{
                    message.value = ''
                }, 3000)
            })
            .catch(err => {
                console.error(err)
                error.value = err.response.data.message
                message.value = ''
                setTimeout(()=>{
                    error.value = ''
                }, 3000)
            })
    }
}

const capitalize = (str) => str.charAt(0).toUpperCase() + str.slice(1)
</script>
