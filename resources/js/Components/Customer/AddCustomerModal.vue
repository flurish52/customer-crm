<template>
        <div class="bg-white rounded-xl shadow-lg max-w-md w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 v-if="!isEditing" class="text-lg font-bold text-primary-dark">Add new client</h3>
                    <h3 v-else class="text-lg font-bold text-primary-dark">Update client details</h3>
                    <button @click="closeAddCustomerModal" class="text-red-400 hover:text-red-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="red" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <form @submit.prevent="saveCustomer">
                    <div class="space-y-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-primary-dark">Full Name *</label>
                            <input
                                type="text"
                                id="name"
                                v-model="newCustomer.name"
                                required
                                class="mt-1 block w-full border border-tertiary-dark rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary-light focus:border-primary-light"
                            >
                        </div>

                        <div>
                            <label for="company" class="block text-sm font-medium text-primary-dark">Company</label>
                            <input
                                type="text"
                                id="company"
                                v-model="newCustomer.company"
                                class="mt-1 block w-full border border-tertiary-dark rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary-light focus:border-primary-light"
                            >
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-primary-dark">Email *</label>
                            <input
                                type="email"
                                id="email"
                                required
                                v-model="newCustomer.email"
                                class="mt-1 block w-full border border-tertiary-dark rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary-light focus:border-primary-light"
                            >
                        </div>

                        <div>
                            <label for="phone" class="block text-sm font-medium text-primary-dark">Phone *</label>
                            <input
                                type="tel"
                                id="phone"
                                v-model="newCustomer.phone"
                                required
                                class="mt-1 block w-full border border-tertiary-dark rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary-light focus:border-primary-light"
                            >
                        </div>
                        <div>
                            <label for="address" class="block text-sm font-medium text-primary-dark">Address</label>
                            <textarea
                                id="address"
                                v-model="newCustomer.address"
                                rows="2"
                                class="mt-1 block w-full border border-tertiary-dark rounded-md shadow-sm py-1 px-3 focus:outline-none focus:ring-primary-light focus:border-primary-light"
                            ></textarea>
                        </div>
                        <div>
                            <label for="address" class="block text-sm font-medium text-primary-dark">Note</label>
                            <textarea
                                id="address"
                                v-model="newCustomer.note"
                                rows="3"
                                class="mt-1 block w-full border border-tertiary-dark rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary-light focus:border-primary-light"
                            ></textarea>
                        </div>
                        <div class="flex items-center justify-between gap-4 w-full">
                            <div class="w-1/3">
                                <label for="photo" class="block text-sm font-medium text-primary-dark">Photo</label>
                                <input
                                    id="photo"
                                    type="file"
                                    @change="previewPhoto"
                                    accept="image/*"
                                    class="mt-1 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary-light focus:border-primary-light"
                                >
                            </div>

                            <div v-if="photoPreview" class="mt-6 w-fit">
                                <img  :src="photoPreview" alt="Preview" class="w-20 h-20 rounded object-cover border" />
                            </div>

                            <div v-else class="mt-6 w-fit">
                                <img  :src="`/storage/${newCustomer.avatar}`" alt="Preview" class="w-20 h-20 rounded object-cover border" />
                            </div>
                        </div>
                        <div class="flex justify-end space-x-3 pt-4">
                            <button
                                type="button"
                                @click="closeAddCustomerModal"
                                class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-light"
                            >
                                Cancel
                            </button>
                            <div>
                                <button
                                    type="submit"
                                    :disabled="loading"
                                    class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-primary-light flex items-center justify-center"
                                >
                                    <span v-if="!loading">Save Customer</span>
                                    <svg v-else class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                                    </svg>
                                </button>
                            </div>

                        </div>
                    </div>
                </form>
            </div>
        </div>
</template>

<script setup>
import {ref} from "vue";
let props = defineProps({
    customerToEdit: Object,
    isEditing: Boolean,
})
let newCustomer = ref()
if (props.isEditing === true){
    newCustomer.value = {...props.customerToEdit}
}else {
newCustomer.value = {
    name: '',
    company: '',
    email: '',
    phone: '',
    address: '',
    avatar: null,
}

}
let emit = defineEmits(['closeAddCustomerModal', 'customerCreated_Updated'])
const resetNewCustomer = () => {
    newCustomer.value = {
        name: '',
        company: '',
        email: '',
        phone: '',
        address: '',
        note: '',
        avatar: null,
    }
}
const photoPreview = ref(null)
const previewPhoto = (event) => {
    const file = event.target.files[0]
    if (!file) return

    const maxSizeMB = 2
    const maxSizeBytes = maxSizeMB * 1024 * 1024

    if (file.size > maxSizeBytes) {
        alert(`File size should not exceed ${maxSizeMB} MB`)
        event.target.value = ''
        return
    }
    newCustomer.value.avatar = file
    photoPreview.value = URL.createObjectURL(file)
}

const loading = ref(false);

const saveCustomer = () => {
    loading.value = true;

    const formData = new FormData()
    formData.append('name', newCustomer.value.name)
    formData.append('email', newCustomer.value.email)
    formData.append('avatar', newCustomer.value.avatar)
    formData.append('company', newCustomer.value.company)
    formData.append('phone', newCustomer.value.phone)
    formData.append('address', newCustomer.value.address)
    formData.append('note', newCustomer.value.note)

    const request = props.isEditing
        ? axios.post(`/customer_update/${props.customerToEdit.id}?_method=PATCH`, formData)
        : axios.post('/customer_store', formData, { headers: { 'Content-Type': 'multipart/form-data' } });

    request.then(res => {
        alert('Success! Click the view icon to manage the client.')
        if (!props.isEditing) resetNewCustomer();
        closeAddCustomerModal();
        emit('customerCreated_Updated');
    }).catch(error => {
        const msg = error.response?.data?.message || 'Something went wrong';
        if (msg.includes('Duplicate entry')) {
            alert('This phone number is already used for another client')
        } else if (msg.includes('email')) {
            alert('This email has already been used for a different client')
        } else if (msg.includes('required') && msg.includes('email')) {
            alert('Email field cannot be empty')
        } else {
            alert(msg)
        }
    }).finally(() => {
        loading.value = false;
    });
}

const closeAddCustomerModal=()=>{
    resetNewCustomer()
    emit('closeAddCustomerModal')
}
</script>

<style scoped>

</style>
