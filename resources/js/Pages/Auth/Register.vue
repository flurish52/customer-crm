<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Register" />

        <div class="max-w-md mx-auto p-8 bg-white rounded-xl shadow-md border border-gray-100">
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-primary-dark">
                    Create Your Account
                </h2>
                <p class="mt-2 text-primary">
                    Join Zelvra CRM to streamline your client management
                </p>
            </div>

            <form @submit.prevent="submit" class="space-y-6">
                <div>
                    <InputLabel for="name" value="Full Name" class="block mb-2 text-sm font-medium text-primary-dark" />
                    <TextInput
                        id="name"
                        type="text"
                        class="block w-full px-4 py-3 border border-primary rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary"
                        :class="{ 'border-red-500': form.errors.name }"
                        v-model="form.name"
                        required
                        autofocus
                        autocomplete="name"
                    />
                    <InputError v-if="form.errors.name" class="mt-2 flex items-center text-sm text-red-600">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2h-1V9z" clip-rule="evenodd" />
                        </svg>
                        {{ form.errors.name }}
                    </InputError>
                </div>

                <div>
                    <InputLabel for="email" value="Email" class="block mb-2 text-sm font-medium text-primary-dark" />
                    <TextInput
                        id="email"
                        type="email"
                        class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary-500"
                        :class="{ 'border-red-500': form.errors.email }"
                        v-model="form.email"
                        required
                        autocomplete="email"
                    />
                    <InputError v-if="form.errors.email" class="mt-2 flex items-center text-sm text-red-600">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2h-1V9z" clip-rule="evenodd" />
                        </svg>
                        {{ form.errors.email }}
                    </InputError>
                </div>

                <div>
                    <InputLabel for="password" value="Password" class="block mb-2 text-sm font-medium text-primary" />
                    <TextInput
                        id="password"
                        type="password"
                        class="block w-full px-4 py-3 border border-primary-light rounded-lg focus:ring-2 focus:ring-primary focus:border-primary"
                        :class="{ 'border-red-500': form.errors.password }"
                        v-model="form.password"
                        required
                        autocomplete="new-password"
                    />
                    <InputError v-if="form.errors.password" class="mt-2 flex items-center text-sm text-red-600">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2h-1V9z" clip-rule="evenodd" />
                        </svg>
                        {{ form.errors.password }}
                    </InputError>
                </div>

                <div>
                    <InputLabel for="password_confirmation" value="Confirm Password" class="block mb-2 text-sm font-medium text-primary" />
                    <TextInput
                        id="password_confirmation"
                        type="password"
                        class="block w-full px-4 py-3 border border-primary rounded-lg focus:ring-2 focus:ring-primary focus:border-primary"
                        :class="{ 'border-red-500': form.errors.password_confirmation }"
                        v-model="form.password_confirmation"
                        required
                        autocomplete="new-password"
                    />
                    <InputError v-if="form.errors.password_confirmation" class="mt-2 flex items-center text-sm text-red-600">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2h-1V9z" clip-rule="evenodd" />
                        </svg>
                        {{ form.errors.password_confirmation }}
                    </InputError>
                </div>

                <div class="flex items-center justify-between mt-8">
                    <Link
                        :href="route('login')"
                        class="text-sm text-primary hover:text-primary-dark transition-colors"
                    >
                        Already have an account?
                    </Link>

                    <PrimaryButton
                        class="px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-lg shadow-sm transition-colors"
                        :class="{ 'opacity-70': form.processing }"
                        :disabled="form.processing"
                    >
                        <span v-if="!form.processing">Register</span>
                        <span v-else class="flex items-center justify-center">
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Registering...
                        </span>
                    </PrimaryButton>
                </div>
            </form>
        </div>
    </GuestLayout>
</template>
