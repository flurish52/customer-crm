<template>
    <div>
        <!-- Trigger Button -->
        <button @click="openModal"
                class="bg-indigo-600 text-white px-5 py-2.5 rounded-lg hover:bg-indigo-700 transition-all duration-200 flex items-center gap-2 shadow-md hover:shadow-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                 stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M16 12H8m0 0H6a2 2 0 00-2 2v4a2 2 0 002 2h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2"/>
            </svg>
            Share via Email
        </button>

        <!-- Modal -->
        <div
            @click.self="closeModal"
            v-if="show"
            class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 p-4 backdrop-blur-sm">
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-hidden flex flex-col">
                <!-- Header Section -->
                <div class="flex items-center justify-between p-6 border-b border-gray-200">
                    <h2 class="text-2xl font-semibold text-gray-800">Send Invoice</h2>
                    <button @click="closeModal" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <!-- Scrollable Content -->
                <div class="overflow-y-auto flex-1 px-6">
                    <!-- Customer Email Section -->
                    <div class="py-5 border-b border-gray-100">
                        <h3 class="text-lg font-medium text-gray-800 mb-3 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-500" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Client Email
                        </h3>

                        <label class="flex items-center space-x-3 cursor-pointer">
                            <div class="relative inline-block w-12 h-6 rounded-full bg-gray-300">
                                <input type="checkbox" v-model="useCustomerEmail" class="absolute w-0 h-0 opacity-0">
                                <span
                                    class="toggle-label absolute cursor-pointer top-0 left-0 right-0 bottom-0 rounded-full transition duration-200"
                                    :class="{'bg-indigo-100': useCustomerEmail}"></span>
                                <span
                                    class="absolute bg-white border border-gray-300 rounded-full h-5 w-5 top-0.5 transition-all duration-200 transform"
                                    :class="{'translate-x-6 bg-indigo-500': useCustomerEmail}"></span>
                            </div>
                            <span class="text-gray-700">Use Client email
                <span class="text-indigo-600 font-semibold ml-1">{{
                        JSON.parse(invoice.business_snapshot).email
                    }}</span>
              </span>
                        </label>
                    </div>

                    <!-- Recipient Information Section -->
                    <div class="py-5 border-b border-gray-100">
                        <h3 class="text-lg font-medium text-gray-800 mb-4 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-500" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            Recipient Information
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Recipient Email -->
                            <div>
                                <label class="block mb-2 text-sm font-medium text-gray-700">Recipient Email</label>
                                <input
                                    type="email"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 transition-colors duration-200"
                                    :disabled="useCustomerEmail"
                                    v-model="recipientEmail"
                                    placeholder="Enter email address"
                                />
                            </div>

                            <!-- From Name -->
                            <div>
                                <label class="block mb-2 text-sm font-medium text-gray-700">From Name</label>
                                <input
                                    type="text"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 transition-colors duration-200"
                                    v-model="fromName"
                                    placeholder="e.g. Business name"
                                    required
                                />
                            </div>
                        </div>
                    </div>

                    <!-- Reply-To Section -->
                    <div class="py-1 border-b border-gray-100">
                        <h3 class="text-lg font-medium text-gray-800 mb-3 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-500" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                            </svg>
                            Reply-To Email
                        </h3>
                        <p class="px-4 text-red-600 text-xs italic">Where do you want your client to reply to??</p>

                        <div class="space-y-3 bg-gray-50 p-2 rounded-lg border border-gray-100">
                            <label
                                class="flex items-center space-x-3 p-1 rounded-md hover:bg-white transition-colors duration-150 cursor-pointer">
                                <input
                                    type="radio"
                                    value="business"
                                    v-model="replyToOption"
                                    class="text-indigo-600 focus:ring-indigo-500 h-4 w-4"
                                />
                                <span class="text-gray-700">Business email <span
                                    class="text-indigo-600 font-medium">({{ businessEmail }})</span></span>
                            </label>
                            <label
                                class="flex items-center space-x-3 p-1 rounded-md hover:bg-white transition-colors duration-150 cursor-pointer">
                                <input
                                    type="radio"
                                    value="personal"
                                    v-model="replyToOption"
                                    class="text-indigo-600 focus:ring-indigo-500 h-4 w-4"
                                />
                                <span class="text-gray-700">Personal email <span
                                    class="text-indigo-600 font-medium">({{ personalEmail }})</span></span>
                            </label>
                            <label
                                class="flex items-center space-x-3 p-1 rounded-md hover:bg-white transition-colors duration-150 cursor-pointer">
                                <input
                                    type="radio"
                                    value="receipt"
                                    v-model="replyToOption"
                                    class="text-indigo-600 focus:ring-indigo-500 h-4 w-4"
                                />
                                <span class="text-gray-700">Email on invoice <span class="text-indigo-600 font-medium">({{
                                        emailOnInvoice
                                    }})</span></span>
                            </label>
                        </div>
                    </div>

                    <!-- Message Content Section -->
                    <div class="py-5">
                        <h3 class="text-lg font-medium text-gray-800 mb-4 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-500" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Message Content
                        </h3>

                        <!-- Subject -->
                        <div class="mb-4">
                            <label class="block mb-2 text-sm font-medium text-gray-700">Subject</label>
                            <input
                                type="text"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 transition-colors duration-200"
                                v-model="subject"
                                placeholder="Enter email subject"
                                required
                            />
                        </div>

                        <!-- Optional Message -->
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-700">Message <span
                                class="text-gray-500 font-normal">(optional)</span></label>
                            <textarea
                                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 transition-colors duration-200"
                                rows="3"
                                v-model="message"
                                placeholder="Write a message"
                            ></textarea>
                        </div>
                    </div>
                </div>

                <!-- Buttons Section -->
                <div class="w-full">
                    <p class="text-center text-red-600 text-xs p-1">{{ returnMessage }}</p>
                    <div class="flex justify-end space-x-3 p-6 border-t border-gray-200 bg-gray-50">
                        <button @click="closeModal"
                                class="px-5 py-2.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition-colors duration-200 font-medium">
                            Cancel
                        </button>
                        <button
                            @click="sendInvoice"
                            :disabled="sending"
                            :class="sending ? 'bg-gray-500 cursor-not-allowed' : 'bg-indigo-600 hover:bg-indigo-700'"
                            class="text-white px-5 py-2.5 rounded-lg transition-all duration-200 font-medium shadow-md flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                            </svg>
                            Send Invoice
                        </button>

                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import {ref} from 'vue'
import axios from 'axios'
import {computed} from "vue";
import {router} from "@inertiajs/vue3";

const props = defineProps({
    invoice: Object,
    personalEmail: String,
    businessEmail: String,
})

const show = ref(false)
const useCustomerEmail = ref(false)
const fromName = ref('')
const subject = ref('')
const message = ref('')
const recipientEmail = ref('')
let emailOnInvoice = ref(JSON.parse(props.invoice.business_snapshot).email)
let returnMessage = ref('')
let replyToOption = ref('')
let sending = ref(false)


const openModal = () => {
    show.value = true

}
const closeModal = () => {
    show.value = false
    recipientEmail.value = ''
    replyToOption.value = ''
    returnMessage.value = ''
    subject.value = ''

}


const sendInvoice = async () => {
    const replyToEmail = computed(() => {
        if (replyToOption.value === 'business') return props.businessEmail;
        if (replyToOption.value === 'personal') return props.personalEmail;
        if (replyToOption.value === 'receipt') return emailOnInvoice.value;
        return null
    });

    const emailToSend = useCustomerEmail.value
        ? JSON.parse(props.invoice.business_snapshot).email
        : recipientEmail.value

    if (!emailToSend) {
        alert('Please provide a client email.')
        return
    }
    sending.value = true
    try {
        await axios.post(`/invoice/${props.invoice.id}/send`, {
            replyToEmail: replyToEmail.value,
            email: emailToSend,
            from_name: fromName.value || JSON.parse(props.invoice.business_snapshot).name,
            subject: subject.value || `Invoice #${props.invoice.id}`,
            message: message.value
        })
        alert('Email sent successfully')
        sending.value = false
        closeModal()
        router.visit(`/dashboard/invoice/${props.invoice.id}`)
    } catch (error) {
        returnMessage.value = error.response?.data.message || error.message
        alert('Failed to send email,')
        sending.value = false
    }
}
</script>
