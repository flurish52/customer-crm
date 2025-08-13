<template>
        <!-- Profile Information -->
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Profile Information</h3>
                <p class="mt-1 text-sm text-gray-500">Update your account's profile information</p>
            </div>
            <div class="px-6 py-4">
                <form @submit.prevent="submitForm" class="space-y-6">
                    <!-- Profile Photo -->
                    <div class="bg-white shadow rounded-lg overflow-hidden">
                        <div class="px-6 py-5 border-b border-gray-200">
                            <p class="mt-1 text-sm text-gray-500">Update your profile photo</p>
                        </div>
                        <div class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="relative h-16 w-16 rounded-full overflow-hidden">
                                    <img
                                        v-if="photoPreview"
                                        class="h-full w-full object-cover"
                                        :src="photoPreview"
                                        alt="Preview"
                                    />
                                    <div v-else>
                                        <img
                                            v-if="user.avatar"
                                            class="h-full w-full object-cover"
                                            :src="`/storage/${user.avatar}`"
                                            alt="User avatar"
                                        />
                                        <svg
                                            v-else
                                            width="100%"
                                            height="100%"
                                            viewBox="0 0 24 24"
                                            fill="none"
                                            stroke="#0E9C9A"
                                            stroke-width="2"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            xmlns="http://www.w3.org/2000/svg"
                                        >
                                            <circle cx="12" cy="8" r="4" />
                                            <path d="M4 20c0-4 8-4 8-4s8 0 8 4" />
                                        </svg>
                                    </div>
                                    <div
                                        class="absolute inset-0 bg-black bg-opacity-30 flex items-center justify-center opacity-0 hover:opacity-100 transition-opacity cursor-pointer"
                                        @click="$refs.fileInput.click()"
                                    >
                                        <PencilIcon class="h-5 w-5 text-white" />
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <input
                                        ref="fileInput"
                                        type="file"
                                        @change="previewPhoto"
                                        accept="image/*"
                                        class="hidden"
                                    />
                                    <button
                                        type="button"
                                        class="ml-3 rounded-md border border-transparent bg-white py-2 px-3 text-sm font-medium text-red-700 hover:text-gray-500"
                                        @click="removePhoto"
                                    >
                                        Remove
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                        <div class="sm:col-span-3">
                            <label for="name" class="block text-sm font-medium text-gray-700">Full name</label>
                            <input
                                type="text"
                                v-model="user.name"
                                id="name"
                                required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                            />
                        </div>

                        <div class="sm:col-span-4">
                            <label for="email" class="block text-sm font-medium text-gray-700">Email address</label>
                            <input
                                type="email"
                                id="email"
                                v-model="user.email"
                                disabled
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                            />
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button
                            type="button"
                            class="rounded-md border border-gray-300 bg-white py-2 px-4 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                            @click="resetForm"
                        >
                            Cancel
                        </button>
                        <button
                            type="submit"
                            class="ml-3 inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                        >
                            Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
</template>

<script setup>
import { ref, reactive, watch } from 'vue'
// import { PencilIcon } from '@heroicons/vue/outline'
import { usePage } from '@inertiajs/vue3'

const page = usePage()

// Clone user data to a reactive object
const user = reactive({ ...page.props.auth.user })

const photoPreview = ref(null)
const photoFile = ref(null)

// Preview selected photo
const previewPhoto = (e) => {
    const file = e.target.files?.[0]
    if (!file) {
        photoPreview.value = null
        photoFile.value = null
        return
    }
    photoFile.value = file
    photoPreview.value = URL.createObjectURL(file)
}

// Remove selected photo
const removePhoto = () => {
    photoFile.value = null
    photoPreview.value = null
    user.avatar = null
}

// Reset form to initial user data
const resetForm = () => {
    Object.assign(user, page.props.auth.user)
    removePhoto()
}

// Submit the form
const submitForm = () => {
    const formData = new FormData()
    formData.append('name', user.name)

    if (photoFile.value) {
        formData.append('avatar', photoFile.value)
    } else if (user.avatar === null) {
        formData.append('remove_avatar', '1')
    }
    for (const pair of formData.entries()) {
        console.log(pair[0]+ ': ' + pair[1]);
    }
    axios.patch('/profile/update', formData, {
        headers: {
            'Content-Type': 'multipart/form-data'
        }
    })
        .then(response => {
            alert('Profile updated successfully')
        })
        .catch(error => {
            console.error('Update failed:', error)
        })
}

</script>
