<template>
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
</template>

<script>
export default {
    name: "ActivityWithClient"
}
</script>

<style scoped>

</style>
