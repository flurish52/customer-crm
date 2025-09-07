<template>
    <div class="fixed inset-0 top-10 flex items-center mx-3 p-3 justify-center z-50">
        <!-- Overlay -->
        <div
            @click.self="$emit('cancel')"
            class="absolute inset-0 bg-black bg-opacity-50"></div>

        <!-- Modal -->
        <div class="bg-white rounded-lg shadow-lg z-10 w-full max-w-md p-3">
            <p class="text-green-600 text-center">{{ successMessage }}</p>
            <h2 class="text-lg font-semibold text-gray-800 mb-1">Notify Client</h2>
            <p class="text-sm text-gray-600 mb-3">
                Do you want to notify the client that this {{subject}}?
            </p>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Subject</label>
                    <input
                        v-model="emailSubject"
                        type="text"
                        class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary"
                        placeholder="Your job has started"
                    />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Message</label>
                    <textarea
                        v-model="emailMessage"
                        rows="5"
                        class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary"
                        placeholder="Write your message here..."
                    ></textarea>
                </div>
            </div>

            <div class="flex justify-end space-x-3 mt-6">
                <button
                    @click="$emit('cancel')"
                    class="px-4 py-2 rounded-md bg-gray-200 text-gray-800 hover:bg-gray-300 transition"
                >
                    Later
                </button>
                <div>
                    <button
                        @click="confirmSend"
                        :disabled="loading"
                        class="px-4 py-2 rounded-md bg-primary-dark text-white hover:bg-primary transition flex items-center justify-center"
                    >
                        <span v-if="!loading">Send</span>
                        <span v-else class="flex items-center space-x-2">
      <svg
          class="animate-spin h-5 w-5 text-white"
          xmlns="http://www.w3.org/2000/svg"
          fill="none"
          viewBox="0 0 24 24"
      >
        <circle
            class="opacity-25"
            cx="12"
            cy="12"
            r="10"
            stroke="currentColor"
            stroke-width="4"
        />
        <path
            class="opacity-75"
            fill="currentColor"
            d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"
        />
      </svg>
      <span>Sending...</span>
    </span>
                    </button>
                </div>

            </div>
        </div>
    </div>
</template>

<script setup>
import {ref, defineEmits} from 'vue'
import {router} from "@inertiajs/vue3";
const props = defineProps({
    item: Object,
    successMessage: String,
    message: String,
    subject: String
})

const emit = defineEmits(['sendEmail', 'cancel'])
let emailMessage = ref(props.message)
const emailSubject = ref(props.subject)
const loading = ref(false)


function confirmSend() {
    loading.value = true
    const emailBody = {
        subject: emailSubject.value,
        from_name: props.item?.business.business_name,
        message: emailMessage.value,
        sendToEmail: props.item?.customer.email,
        replyToEmail: props.item?.business.business_email,
    }
    axios.post(`/dashboard/user/notify_client`, emailBody)
        .then(res => {
            if (res.status === 200) {
                alert(res.data.message)
                emit('cancel')
                router.visit(window.location.pathname, {
                    replace: true,
                    preserveScroll: true
                })
            }
        })
        .catch(err =>{
                if (err.response) {
                    // If server responded with a validation error or other error
                    const msg = err.response.data?.message || 'Unable to notify client, please check the details'
                    alert(msg)
                } else if (err.request) {
                    // Request was made but no response received
                    alert('No response from server, please try again')
                } else {
                    // Something else happened
                    alert('Unable to notify client, please try again')
                }
        })
                setTimeout(() => {
                    loading.value = false
                }, 2000)
}

</script>

<style scoped>
</style>
