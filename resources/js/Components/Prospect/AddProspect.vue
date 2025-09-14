<template>
    <div
        v-if="visible"
        @click.self="$emit('close')"
        class="fixed inset-0 flex items-center justify-center bg-black/40 z-50"
    >
        <div class="bg-white rounded-2xl shadow-lg w-full max-w-lg p-6 relative">
            <!-- Close Button -->
            <button
                @click="$emit('close')"
                class="absolute top-3 right-3 text-gray-500 hover:text-gray-700"
            >
                ✕
            </button>

            <!-- Title -->
            <h2 class="text-xl font-semibold mb-4">
                {{ isEditing ? 'Update Prospect' : 'Add Prospect' }}
            </h2>

            <!-- Form -->
            <form @submit.prevent="handleSubmit" class="space-y-4">
                <div v-if="error" class="mb-4 rounded-lg bg-red-50 border border-red-200 text-red-700 px-4 py-2 text-sm">
                    {{ error }}
                </div>

                <div v-if="message" class="mb-4 rounded-lg bg-green-50 border border-green-200 text-green-700 px-4 py-2 text-sm">
                    {{ message }}
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Name</label>
                    <input
                        v-model="form.name"
                        type="text"
                        class="w-full rounded-lg border border-gray-300 p-2 focus:ring-2 focus:ring-primary focus:outline-none"
                        required
                    />
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Email</label>
                    <input
                        v-model="form.email"
                        type="email"
                        class="w-full rounded-lg border border-gray-300 p-2 focus:ring-2 focus:ring-primary focus:outline-none"
                    />
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Phone</label>
                    <input
                        v-model="form.phone"
                        type="text"
                        class="w-full rounded-lg border border-gray-300 p-2 focus:ring-2 focus:ring-primary focus:outline-none"
                    />
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Company</label>
                    <input
                        v-model="form.company"
                        type="text"
                        class="w-full rounded-lg border border-gray-300 p-2 focus:ring-2 focus:ring-primary focus:outline-none"
                    />
                </div>

                <div class="flex items-start justify-between ">
                    <div class="w-1/2 mx-1">
                        <label class="block text-sm font-medium mb-1">Source</label>
                        <select
                            v-model="form.source"
                            class="w-full rounded-lg border border-gray-300 p-2 focus:ring-2 focus:ring-primary focus:outline-none"
                        >
                            <option disabled value="">Select source</option>
                            <option value="website">Website</option>
                            <option value="referral">Referral</option>
                            <option value="ads">Ads</option>
                            <option value="social_media">Social media</option>
                            <option value="Event">Event</option>
                            <option value="other">Other</option>
                        </select>
                        <input
                            v-if="form.source === 'other'"
                            v-model="form.customSource"
                            type="text"
                            placeholder="Enter source"
                            class="mt-2 w-full rounded-lg border border-gray-300 p-2 focus:ring-2 focus:ring-primary focus:outline-none"
                        />
                    </div>

                    <div class="w-1/2 mx-1">
                        <label class="block text-sm font-medium mb-1">Status</label>
                        <select
                            v-model="form.status"
                            class="w-full rounded-lg border border-gray-300 p-2 focus:ring-2 focus:ring-primary focus:outline-none"
                            required
                        >
                            <option value="new">New</option>
                            <option value="contacted">Contacted</option>
                            <option value="follow_up">Follow Up</option>
                            <option value="converted">Converted</option>
                            <option value="lost">Lost</option>
                        </select>
                    </div>
                </div>
                <!-- Submit -->
                <div class="flex justify-end space-x-3 pt-4">
                    <button
                        type="button"
                        @click="$emit('close')"
                        class="px-4 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-100"
                        :disabled="loading"
                    >
                        Cancel
                    </button>

                    <button
                        type="submit"
                        class="px-4 py-2 rounded-lg bg-primary text-white hover:bg-primary/90 flex items-center justify-center"
                        :disabled="loading"
                    >
                        <svg
                            v-if="loading"
                            class="animate-spin h-5 w-5 text-white mr-2"
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
                            ></circle>
                            <path
                                class="opacity-75"
                                fill="currentColor"
                                d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"
                            ></path>
                        </svg>
                        {{ loading ? 'Saving...' : isEditing ? 'Update' : 'Create' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import {reactive, ref, watch} from "vue";
import axios from "axios";
import {router} from "@inertiajs/vue3";

const props = defineProps({
    visible: Boolean,
    isEditing: Boolean,
    prospect: Object,
});

const emit = defineEmits(["close", "saved"]);
const form = reactive({
    name: "",
    email: "",
    phone: "",
    company: "",
    source: "",
    customSource: "",
    status: "new",
});
let error =  ref('')
let message =  ref('')


// populate form when editing
watch(
    () => props.prospect,
    (newVal) => {
        if (props.isEditing && newVal) {
            Object.assign(form, newVal);
        } else {
            form.name = "";
            form.email = "";
            form.phone = "";
            form.company = "";
            form.source = "";
            form.status = "new";
        }
    },
    {immediate: true}
);

const loading = ref(false);

const handleSubmit = async () => {
    try {
        loading.value = true
        let res
        if (props.isEditing && props.prospect?.id) {
            res = await axios.patch(`/dashboard/prospect/${props.prospect.id}`, form)
        } else {
            res = await axios.post("/dashboard/prospect", form)
        }
        if (res) {
            message.value = res.data.message
            error.value = ''

            setTimeout(()=>{
            emit("saved")
                router.visit(window.location.href, {preserveScroll: true})
            emit("close")


            form.name = "";
            form.email = "";
            form.phone = "";
            form.company = "";
            form.source = "";
            form.status = "new";
            }, 3000)
        }

    } catch (err) {
        error.value = err.response?.data?.message || 'Something went wrong'
        message.value = ''
    } finally {
        loading.value = false
    }

};

</script>
