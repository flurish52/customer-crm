<template>
    <div v-if="visible"
         @click.self="$emit('close')"
         class="fixed inset-0 flex items-center justify-center bg-black/40 z-50">
        <div class="bg-white rounded-2xl shadow-lg w-full max-w-md p-6 relative">
            <!-- Close button -->
            <button @click="$emit('close')" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700">
                ✕
            </button>
            <!-- Title -->
            <h2 class="text-xl font-semibold mb-4">
                {{ isEditing ? 'Edit Activity' : 'Add Activity' }}
            </h2>
            <!-- Activity Form -->
            <form @submit.prevent="handleSubmit" class="space-y-4">
                <div v-if="error" class="bg-red-100 text-red-700 border border-red-200 px-4 py-2 rounded mb-3">
                    {{ error }}
                </div>

                <div v-if="message" class="bg-green-100 text-green-700 border border-green-200 px-4 py-2 rounded mb-3">
                    {{ message }}
                </div>

                <!-- Type -->
                <div>
                    <label class="block text-sm font-medium mb-1">Type</label>
                    <select v-model="form.type" required
                            class="w-full rounded-lg border border-gray-300 p-2 focus:ring-2 focus:ring-primary focus:outline-none">
                        <option value="">Select type</option>
                        <option value="call">Call</option>
                        <option value="email">Email</option>
                        <option value="note">Note</option>
                        <option value="meeting">Meeting</option>
                    </select>
                </div>

                <!-- Content -->
                <div>
                    <label class="block text-sm font-medium mb-1">Activity</label>
                    <textarea v-model="form.content" rows="3" required
                              class="w-full rounded-lg border border-gray-300 p-2 focus:ring-2 focus:ring-primary focus:outline-none"></textarea>
                </div>

                <!-- Date -->
                <div>
                    <label class="block text-sm font-medium mb-1">Date</label>
                    <input type="datetime-local" v-model="form.date"
                           class="w-full rounded-lg border border-gray-300 p-2 focus:ring-2 focus:ring-primary focus:outline-none"/>
                </div>

                <!-- Buttons -->
                <div class="flex justify-end space-x-3 pt-4">
                    <button type="button" @click="closeModal"
                            class="px-4 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-100"
                            :disabled="loading">
                        Cancel
                    </button>

                    <button type="submit"
                            class="px-4 py-2 rounded-lg bg-primary text-white hover:bg-primary/90 flex items-center justify-center"
                            :disabled="loading">
                        <svg v-if="loading" class="animate-spin h-5 w-5 text-white mr-2" xmlns="http://www.w3.org/2000/svg"
                             fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                  d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                        </svg>
                        {{ loading ? 'Saving...' : isEditing ? 'Update' : 'Add' }}
                    </button>
                </div>

                <!-- Delete button if editing -->
                <div v-if="isEditing" class="flex justify-start pt-2">
                    <button type="button" @click="deleteActivity"
                            class="px-4 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700">
                        Delete Activity
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { reactive, ref, watch } from "vue";
import axios from "axios";
import dayjs from "dayjs";
import { router } from "@inertiajs/vue3";

const props = defineProps({
    visible: Boolean,
    isEditing: Boolean,
    activity: Object,
    clientId: Number,
});

const emit = defineEmits(["close", "saved"]);

const loading = ref(false);
const error = ref(null);
const message = ref(null);
const form = reactive({
    type: "",
    content: "",
    date: dayjs().format("YYYY-MM-DDTHH:mm"),
    id: null,
});
const closeModal =()=>{
       form.value = {
           type: "",
           content: "",
           date: dayjs().format("YYYY-MM-DDTHH:mm"),
           id: null,
       }
       emit('close')

}

// Populate form if editing
watch(
    () => props.activity,
    (val) => {
        if (props.isEditing && val) {
            form.type = val.type;
            form.content = val.content;
            form.date = dayjs(val.date).format("YYYY-MM-DDTHH:mm");
            form.id = val.id;
        } else {
            form.type = "";
            form.content = "";
            form.date = dayjs().format("YYYY-MM-DDTHH:mm");
            form.id = null;
        }
    },
    { immediate: true }
);

const handleSubmit = async () => {
    if (new Date(form.date) > new Date()) {
        error.value = 'Date cannot be greater than today';
        return;
    }
    try {
        loading.value = true;
        console.log(props.clientId)
        let url = "/client/activity";
        let method = props.isEditing ? "patch" : "post";
        let payload = { ...form, client_id: props.clientId };
        if (props.isEditing) {
            url += `/${form.id}`;
        }
        const res = await axios({ method, url, data: payload });
        message.value = res.data.message;
        error.value = '';
        setTimeout(() => {
            message.value = null;
            emit("saved", res.data);
            emit("close");
        }, 2000);
        router.visit(window.location.href, { preserveScroll: true });
    } catch (err) {
        console.error(err);
        error.value = err.response?.data?.message || "Something went wrong";
        message.value = '';
    } finally {
        loading.value = false;
    }
};

const deleteActivity = async () => {
    if (!confirm("Are you sure you want to delete this activity?")) return;
    try {
        loading.value = true;
        await axios.delete(`/client/activity/${form.id}`);
        emit("saved", { deleted: true, id: form.id });
        emit("close");
        router.visit(window.location.href, { preserveScroll: true });
    } catch (err) {
        console.error(err);
        alert(err.response?.data?.message || "Something went wrong");
    } finally {
        loading.value = false;
    }
};
</script>
