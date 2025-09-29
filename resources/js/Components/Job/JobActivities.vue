<template>
    <div>
        <AddJobActivity
            :visible="showAddActivityModal"
            :isEditing="isEditingActivity"
            :activity="activityToEdit"
            @close="closeAddClientActivityModal"
            :jobId="jobId"
            :clientId="clientId"
        />
        <div class="flex justify-between items-center gap-2 flex-wrap">
            <h3 class="text-gray-700 font-semibold truncate max-w-xs">
                Job notes
            </h3>
            <button
                @click="openAddActivityModal"
                class="bg-primary hover:bg-primary-dark text-white px-4 py-2 rounded-lg shadow-sm transition-colors text-sm">
                Add a note
            </button>
        </div>
        <div class="flex-1 overflow-y-auto max-h-[400px] space-y-3">
            <div v-if="!sortedActivities.length" class="text-gray-400 italic text-sm">
                No activity yet.
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

                <!-- Edit button -->
                <button
                    @click="editActivity(activity)"
                    class="absolute right-3 top-3 bg-primary-dark text-white px-2 py-1 rounded text-xs font-medium opacity-0 group-hover:opacity-100 transition-opacity">
                    Edit
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import dayjs from 'dayjs'
import relativeTime from 'dayjs/plugin/relativeTime'
import axios from 'axios'
import AddJobActivity from "@/Components/Job/AddJobActivity.vue";
dayjs.extend(relativeTime)
const props = defineProps({
    jobId: {
        type: Number,
        required: true
    },
    clientId: {
        type: Number,
        required: true
    },
    activities: {
        type: Array,
        default: () => []
    }
})
// State
const activityToEdit = ref(null)
const showAddActivityModal = ref(false)
const isEditingActivity = ref(false)
const error = ref('')
const message = ref('')
let closeAddClientActivityModal = ()=>{
    isEditingActivity.value = false
    activityToEdit.value = false
    showAddActivityModal.value = false
}

// Helpers
function capitalize(text) {
    if (!text) return ''
    return text.charAt(0).toUpperCase() + text.slice(1)
}

function formatActivityTime(dateString) {
    const d = dayjs(dateString)
    return dayjs().diff(d, 'day') <= 30 ? d.fromNow() : d.format('D MMM, YYYY')
}

const sortedActivities = computed(() => {
    return [...props.activities].sort(
        (a, b) => new Date(b.activity_date) - new Date(a.activity_date)
    )
})

// Actions
function openAddActivityModal() {
    showAddActivityModal.value = true
}

function editActivity(activity) {
    activityToEdit.value = activity
    isEditingActivity.value = true
    showAddActivityModal.value = true
}

async function deleteActivity(activity) {
    if (!confirm('Are you sure you want to delete this activity?')) return
    try {
        await axios.delete(`/dashboard/client/${props.clientId}/activities/${activity.id}`)
        message.value = 'Activity deleted successfully'
        error.value = ''
        // Remove from local list
        const index = props.activities.findIndex(a => a.id === activity.id)
        if (index !== -1) props.activities.splice(index, 1)
    } catch (err) {
        error.value = err.response?.data?.message || 'Something went wrong'
        message.value = ''
    }
}
</script>

<style scoped>
</style>
