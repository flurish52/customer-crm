<template>
    <div class="max-w-4xl px-1 sm:px-6 lg:px-2 py-1">
        <!-- General Tab -->
        <div v-if="activeTab === 'General'" class="space-y-6">
           <UserUpdateProfile />
        </div>
        <div v-if="activeTab === 'Business info'" class="space-y-6">
           <BusinessInfo
           :business="user.business || {}"
           :user="user"
           />
        </div>
        <!-- Team Tab -->
        <div v-if="activeTab === 'Team'" class="space-y-6">
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Team Members</h3>
                    <p class="mt-1 text-sm text-gray-500">Manage who has access to your account</p>
                </div>
                <div class="px-6 py-4">
                    <div class="space-y-4">
                        <div v-for="member in teamMembers" :key="member.id" class="flex items-center justify-between p-3 border border-gray-200 rounded-lg">
                            <div class="flex items-center">
                                <img class="h-10 w-10 rounded-full" :src="member.avatar" alt="">
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-900">{{ member.name }}</p>
                                    <p class="text-sm text-gray-500">{{ member.email }}</p>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <select class="mr-4 rounded-md border-gray-300 py-1 pl-2 pr-8 text-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500">
                                    <option v-for="role in roles" :key="role" :value="role" :selected="member.role === role">{{ role }}</option>
                                </select>
                                <button type="button" class="text-gray-400 hover:text-gray-500">
                                    <TrashIcon class="h-5 w-5" />
                                </button>
                            </div>
                        </div>

                        <button type="button" class="w-full flex justify-center items-center rounded-md border border-gray-300 bg-white py-2 px-4 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                            <PlusIcon class="-ml-1 mr-2 h-5 w-5 text-gray-500" />
                            Add Team Member
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Billing Tab -->
        <div v-if="activeTab === 'Billing'" class="space-y-6">
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Billing Information</h3>
                    <p class="mt-1 text-sm text-gray-500">Manage your subscription and payment methods</p>
                </div>
                <div class="px-6 py-4">
                    <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg mb-6">
                        <div>
                            <h4 class="text-sm font-medium text-gray-900">Free Plan</h4>
                            <p class="mt-1 text-sm text-gray-500">NGN 0.00/month</p>
                            <p class="mt-1 text-xs font-medium text-green-600">Active</p>
                        </div>
                        <button type="button" class="rounded-md border border-gray-300 bg-white py-2 px-4 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">Change Plan</button>
                    </div>

<!--                    <h4 class="text-sm font-medium text-gray-900 mb-4">Payment Methods</h4>-->
<!--                    <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">-->
<!--                        <div class="flex items-center">-->
<!--                            <div class="h-10 w-10 flex items-center justify-center rounded-full bg-gray-100 text-gray-600">-->
<!--                                <CreditCardIcon class="h-6 w-6" />-->
<!--                            </div>-->
<!--                            <div class="ml-4">-->
<!--                                <p class="text-sm font-medium text-gray-900">VISA ending in 4242</p>-->
<!--                                <p class="text-xs text-gray-500">Expires 12/2025</p>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                        <button type="button" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">Remove</button>-->
<!--                    </div>-->

<!--                    <button type="button" class="mt-4 w-full flex justify-center items-center rounded-md border border-gray-300 bg-white py-2 px-4 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">-->
<!--                        <PlusIcon class="-ml-1 mr-2 h-5 w-5 text-gray-500" />-->
<!--                        Add Payment Method-->
<!--                    </button>-->
                </div>
            </div>
        </div>
        <!-- Notifications Tab -->
        <div v-if="activeTab === 'Notifications'" class="space-y-6">
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Notification Preferences</h3>
                    <p class="mt-1 text-sm text-gray-500">Configure how you receive notifications</p>
                </div>
                <div class="px-6 py-4">
                    <div class="space-y-4">
                        <div v-for="pref in notificationPrefs" :key="pref.id" class="flex items-center justify-between p-3 border border-gray-200 rounded-lg">
                            <div>
                                <h4 class="text-sm font-medium text-gray-900">{{ pref.title }}</h4>
                                <p class="text-xs text-gray-500">{{ pref.description }}</p>
                            </div>
                            <Switch v-model="pref.enabled" :class="[pref.enabled ? 'bg-indigo-600' : 'bg-gray-200', 'relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2']">
                                <span class="sr-only">Use setting</span>
                                <span :class="[pref.enabled ? 'translate-x-5' : 'translate-x-0', 'pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out']" />
                            </Switch>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Security Tab -->
        <div v-if="activeTab === 'Security'" class="space-y-6">
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Update Password</h3>
                    <p class="mt-1 text-sm text-gray-500">Ensure your account is using a secure password</p>
                </div>
                <div class="px-6 py-4">
                    <form class="space-y-6">
                        <div>
                            <label for="current-password" class="block text-sm font-medium text-gray-700">Current Password</label>
                            <input type="password" id="current-password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>

                        <div>
                            <label for="new-password" class="block text-sm font-medium text-gray-700">New Password</label>
                            <input type="password" id="new-password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>

                        <div>
                            <label for="confirm-password" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                            <input type="password" id="confirm-password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">Update Password</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Two-Factor Authentication</h3>
                    <p class="mt-1 text-sm text-gray-500">Add additional security to your account</p>
                </div>
                <div class="px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="text-sm font-medium text-gray-900">Authenticator App</h4>
                            <p class="text-xs text-gray-500">Use an authenticator app to generate one-time codes</p>
                        </div>
                        <button type="button" class="rounded-md border border-gray-300 bg-white py-2 px-4 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">Enable</button>
                    </div>
                </div>
            </div>

            <div class="bg-white shadow rounded-lg overflow-hidden border border-red-200">
                <div class="px-6 py-5 border-b border-red-200 bg-red-50">
                    <h3 class="text-lg font-medium text-gray-900">Delete Account</h3>
                    <p class="mt-1 text-sm text-gray-500">This action cannot be undone</p>
                </div>
                <div class="px-6 py-4">
                    <button type="button" class="rounded-md border border-transparent bg-red-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">Delete Account</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import { Switch } from '@headlessui/vue'
import { PencilIcon, TrashIcon, PlusIcon, CreditCardIcon } from '@heroicons/vue/24/outline'
import UserUpdateProfile from "@/Components/User/UserUpdateProfile.vue";
import BusinessInfo from "@/Components/User/BusinessInfo.vue";
const props = defineProps({
    activeTab: String,
    user: Object
})
let photoPreview = ref()

const previewPhoto = (e) => {
    const file = e.target.files?.[0];
    if (!file) return;
    console.log(file);
    photoPreview.value = URL.createObjectURL(file)
}
// Sample data
const teamMembers = ref([
    {
        id: 1,
        name: 'John Doe',
        email: 'john@example.com',
        avatar: 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80',
        role: 'Owner'
    },
    {
        id: 2,
        name: 'Jane Smith',
        email: 'jane@example.com',
        avatar: 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80',
        role: 'Admin'
    }
])
const roles = ['Owner', 'Admin', 'Member']
const notificationPrefs = ref([
    {
        id: 1,
        title: 'Email Notifications',
        description: 'Receive notifications via email',
        enabled: true
    },
    {
        id: 2,
        title: 'Push Notifications',
        description: 'Receive notifications on your device',
        enabled: false
    },
    {
        id: 3,
        title: 'SMS Notifications',
        description: 'Receive notifications via text message',
        enabled: false
    }
])
</script>
