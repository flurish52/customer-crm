<script setup>
import {useForm, Head} from '@inertiajs/vue3';
import {ref} from "vue";
const props = defineProps({
    user: Object,
    business: {
        type: Object,
        default: () => ({
            id: null,
            name: '',
            email: '',
            phone: '',
            address: '',
            website: '',
            tax_id: '',
            logo_path: null
        })
    },
    saveRoute: {type: String, default: 'business.save'}
});
const form = useForm({
    id: props.business.id,
    name: props.business.business_name,
    email: props.business.business_email,
    phone: props.business.business_phone,
    address: props.business.business_address,
    website: props.business.business_website,
    tax_id: props.business.tax_id,
    logo: props.business.logo
});
let isEditable = ref(true)
let preview = ref('')
let sameAsPersonal = ref('')
const sameAsPersonalFunc = ()=>{
        form.name = props.user?.name
        form.email = props.user?.email
        form.phone = props.user?.phone
        form.address = props.user?.address
        form.website = props.user?.website
        form.tax_id = null
        form.logo = null
    isEditable.value = !isEditable.value
    sameAsPersonal.value = !sameAsPersonal.value
}
const   handleLogoChange = (e) =>{
    const file = e.target.files[0];
    if (file) {
        form.logo = file;
        const reader = new FileReader();
        reader.onload = (event) => {
            preview.value = event.target.result;
        };
        reader.readAsDataURL(file);
    }
}

const submit = () => {
    const options = {
        forceFormData: true,
        onSuccess: () => alert(form.id ? 'Updated successfully!' : 'Saved successfully!'),
        onError: () => {}
    };
    if (form.id) {
        form.post(`/save/business/info/${form.id}?_method=PATCH`, options)
    } else {
        form.post('/save/business/info', options)
    }
};

</script>
<template>
    <Head title="Business Info"/>

    <div class="max-w-3xl mx-auto">
        <form @submit.prevent="submit" enctype="multipart/form-data"
              class="bg-white rounded-xl shadow-md border border-tertiary-light p-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-3">
                <div>
                    <h2 class="text-2xl font-semibold text-primary-dark">Business Information</h2>
                    <p
                        v-show="business"
                        class="text-xs text-red-500 italic mt-1">
                        After updating any details, click <strong>Save</strong> to apply changes.
                    </p>
                </div>


<div>

                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" class="sr-only peer" @change="sameAsPersonalFunc">
                    <div class="w-11 h-6 bg-gray-500 rounded-full peer peer-checked:bg-primary transition-colors"></div>
                    <span class="absolute left-0.5 top-0.5 w-5 h-5 bg-white rounded-full shadow-md transform transition-transform peer-checked:translate-x-5"></span>
                    <p class="text-sm text-primary-dark">Same as personal details</p>
                </label>
                    <p
                        v-show="sameAsPersonal"
                        class="text-xs text-red-500 italic mt-1">
                        Please Click <strong>Save</strong> to apply changes.
                    </p>
</div>
            </div>
            <!-- Row 1: Business Name & Email -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                <div>
                    <label class="block text-sm font-medium text-primary-dark mb-1">Business Name</label>
                    <input
                        type="text"
                        v-model="form.name"
                        :disabled="!isEditable"
                        class="w-full px-4 py-2 rounded-lg border border-tertiary-light text-primary-dark focus:ring-2 focus:ring-primary focus:border-primary"
                        :class="[
                                form.errors.name ? 'border-red-500' : '',
                                !isEditable ? 'bg-gray-100 cursor-not-allowed' : 'bg-tertiary'
                        ]"
                        required
                    />
                    <p v-if="form.errors.name" class="mt-1 text-sm text-red-600">{{ form.errors.name }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-primary-dark mb-1">Email</label>
                    <input
                        type="email"
                        v-model="form.email"
                        :disabled="!isEditable"
                        class="w-full px-4 py-2 rounded-lg border border-tertiary-light text-primary-dark focus:ring-2 focus:ring-primary focus:border-primary"
                        :class="[
        form.errors.email ? 'border-red-500' : '',
        !isEditable ? 'bg-gray-100 cursor-not-allowed' : 'bg-tertiary'
      ]"
                        required
                    />
                    <p v-if="form.errors.email" class="mt-1 text-sm text-red-600">{{ form.errors.email }}</p>
                </div>
            </div>


            <!-- Row 2: Phone & Website -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                <div>
                    <label class="block text-sm font-medium text-primary-dark mb-1">Phone</label>
                    <input
                        type="text"
                        v-model="form.phone"
                        class="w-full px-4 py-2 rounded-lg border border-tertiary-light  text-primary-dark focus:ring-2 focus:ring-primary focus:border-primary"
                        :disabled="!isEditable"
                        :class="[
        form.errors.phone ? 'border-red-500' : '',
        !isEditable ? 'bg-gray-100 cursor-not-allowed' : 'bg-tertiary'
      ]"
                    />
                    <p v-if="form.errors.phone" class="mt-1 text-sm text-red-600">{{ form.errors.phone }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-primary-dark mb-1">Website</label>
                    <input
                        type="url"
                        v-model="form.website"
                        class="w-full px-4 py-2 rounded-lg border border-tertiary-light text-primary-dark focus:ring-2 focus:ring-primary focus:border-primary"
                        placeholder="https://example.com"
                        :disabled="!isEditable"
                        :class="[
        form.errors.website ? 'border-red-500' : '',
        !isEditable ? 'bg-gray-100 cursor-not-allowed' : 'bg-tertiary'
      ]"
                    />
                    <p v-if="form.errors.website" class="mt-1 text-sm text-red-600">{{ form.errors.website }}</p>
                </div>
            </div>

            <!-- Row 3: Address & Tax ID -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-primary-dark mb-1">Address</label>
                    <textarea
                        v-model="form.address"
                        rows="3"
                        class="w-full px-4 py-2 rounded-lg border border-tertiary-light text-primary-dark focus:ring-2 focus:ring-primary focus:border-primary"
                        :disabled="!isEditable"
                        :class="[
        form.errors.address ? 'border-red-500' : '',
        !isEditable ? 'bg-gray-100 cursor-not-allowed' : 'bg-tertiary'
      ]"
                    ></textarea>
                    <p v-if="form.errors.address" class="mt-1 text-sm text-red-600">{{ form.errors.address }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-primary-dark mb-1">Tax ID</label>
                    <input
                        type="text"
                        v-model="form.tax_id"
                        class="w-full px-4 py-2 rounded-lg border border-tertiary-light text-primary-dark focus:ring-2 focus:ring-primary focus:border-primary"
                        :disabled="!isEditable"
                        :class="[
        form.errors.tax_id ? 'border-red-500' : '',
        !isEditable ? 'bg-gray-100 cursor-not-allowed' : 'bg-tertiary'
      ]"
                    />
                    <p v-if="form.errors.tax_id" class="mt-1 text-sm text-red-600">{{ form.errors.tax_id }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-primary-dark mb-1">Logo</label>

                    <!-- Preview Image -->
                    <div class="mb-2">
                        <img
                            v-if="preview || (business && business.logo_path)"
                            :src="preview ? preview : `/storage/${business.logo_path}`"
                            alt="Logo Preview"
                            class="w-24 h-24 object-cover rounded border"
                        />
                    </div>

                    <!-- File Input -->
                    <input
                        type="file"
                        accept="image/*"
                        @change="handleLogoChange"
                        class="block w-full text-sm file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border file:border-primary file:bg-primary file:text-white hover:file:bg-primary-dark cursor-pointer"
                        :disabled="!isEditable"
                        :class="[
            form.errors.logo ? 'border-red-500' : '',
            !isEditable ? 'bg-gray-100 cursor-not-allowed' : 'bg-tertiary'
        ]"
                    />

                    <p v-if="form.errors.logo" class="mt-1 text-sm text-red-600">{{ form.errors.logo }}</p>
                </div>

            </div>

            <!-- Buttons -->
            <div class="mt-6 flex items-center justify-end gap-3">
                <button
                    type="submit"
                    :disabled="form.processing"
                    class="px-6 py-2 rounded-lg bg-primary text-white hover:bg-primary-dark disabled:opacity-70 shadow-md transition-colors"
                >
                    <span v-if="!form.processing">Save</span>
                    <span v-else>Saving...</span>
                </button>
            </div>
        </form>
    </div>
</template>
