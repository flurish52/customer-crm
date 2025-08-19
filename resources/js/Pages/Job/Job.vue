<template>
    <AuthenticatedLayout>
        <CreateJob
            :showModal="showModal"
            :customer="customer"
        :isEditingJob="isEditingJob"
        :jobToEdit="jobToEdit"
            @close="closeModal"
        />
        <div class="min-h-screen">
            <!-- Header Section -->
            <div class="">
                <div class="max-w-7xl mx-auto flex justify-between items-center">
                    <h1 class="text-2xl font-bold text-primary-dark">Job Details</h1>
                </div>
            </div>


            <!-- Main Content -->
            <div class="max-w-7xl mx-auto">
                <!-- Action Buttons -->
                <div class="flex space-x-4 justify-end">
                    <button
                        @click="editJob(job)"
                        class="px-6 py-2 rounded-md font-medium transition-all flex items-center"
                        :style="{
            backgroundColor: 'transparent',
            color: 'var(--primary)',
            border: '2px solid var(--primary)'
          }"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Edit Job
                    </button>

                    <button
                        @click="deleteJob(job)"
                        class="px-6 py-2 rounded-md font-medium transition-all text-red-500 flex items-center"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-5 w-5"
                             viewBox="0 0 24 24"
                             fill="none"
                             stroke="currentColor"
                             stroke-width="2"
                             stroke-linecap="round"
                             stroke-linejoin="round">
                            <path d="M3 6h18"/>
                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                            <line x1="10" y1="11" x2="10" y2="17"/>
                            <line x1="14" y1="11" x2="14" y2="17"/>
                        </svg>
                        Delete
                    </button>
                </div>
                <!-- Job View Container -->
                <div
                    class="rounded-xl overflow-hidden shadow-lg transition-all duration-300"
                    :style="{
          border: '1px solid var(--tertiary)',
          backgroundColor: 'white'
        }"
                >
                    <ViewJob
                        :selectedJob="job"
                        :fullPage="fullPage"
                    />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import ViewJob from "@/Components/Job/ViewJob.vue";
import {ref} from "vue";
import axios from "axios";
import CreateJob from "@/Components/Job/CreateJob.vue";
defineProps({
    job: Object
})
const fullPage = ref(true)
const isEditingJob = ref(false)
let jobToEdit = ref({})
let customer = ref({})
let showModal =  ref(false)

const editJob = (job)=>{
    jobToEdit.value =  job
    customer.value = jobToEdit.value.customer
    isEditingJob.value =  true
    showModal.value = true
}

const closeModal = ()=>{
    jobToEdit.value = {}
    customer.value = {}
    isEditingJob.value =  false
    showModal.value = false
}
const deleteJob = (job) => {
    if (!confirm(`Are you sure you want to delete "${job.job_title}"?`)) {
        return;
    }
    axios.delete(`/job_delete/${job.id}`)
        .then(res => {
            if (res.status === 200) {
                alert(res.data.message)
                window.history.back()
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
