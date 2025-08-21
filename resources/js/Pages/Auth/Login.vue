<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});
const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Log in" />

        <div v-if="status" class="mb-6 p-4 bg-primary-light border-l-4 border-primary-dark rounded">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-primary-dark mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2h-1V9z" clip-rule="evenodd" />
                </svg>
                <span class="text-sm font-medium text-primary-dark">{{ status }}</span>
            </div>
        </div>

        <form @submit.prevent="submit" class="max-w-md mx-auto p-10 bg-white rounded-xl shadow-lg border border-tertiary-light">
            <div class="mb-8 text-center">
                <h2 class="text-3xl font-bold text-primary-dark">
                    Welcome to Zelvra
                </h2>
                <p class="mt-2 text-primary-dark">
                    Sign in to your CRM dashboard
                </p>
            </div>

            <div class="mb-6">
                <InputLabel for="email" value="Email" class="block mb-2 text-sm font-medium text-primary-dark" />

                <TextInput
                    id="email"
                    type="email"
                    class="block w-full px-4 py-3 bg-tertiary border border-tertiary-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary text-primary-dark"
                    :class="{ 'border-red-500': form.errors.email }"
                    v-model="form.email"
                    required
                    autofocus
                    autocomplete="username"
                />

                <InputError v-if="form.errors.email"
                            :message="form.errors.email"
                            class="mt-2 flex items-center text-sm text-red-600">
                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2h-1V9z" clip-rule="evenodd" />
                    </svg>
                </InputError>
            </div>

            <div class="mb-6">
                <InputLabel for="password" value="Password" class="block mb-2 text-sm font-medium text-primary-dark" />

                <TextInput
                    id="password"
                    type="password"
                    class="block w-full px-4 py-3 bg-tertiary-light border border-tertiary rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary text-primary-dark"
                    :class="{ 'border-red-500': form.errors.password }"
                    v-model="form.password"
                    required
                    autocomplete="current-password"
                />

                <InputError v-if="form.errors.password"
                            :message="form.errors.password"
                            class="mt-2 flex items-center text-sm text-red-600">
                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2h-1V9z" clip-rule="evenodd" />
                    </svg>
                </InputError>
            </div>

            <div class="flex items-center justify-between mb-8">
                <label class="flex items-center">
                    <Checkbox name="remember" v-model:checked="form.remember"
                              class="rounded border-tertiary-light text-primary focus:ring-primary" />
                    <span class="ms-2 text-sm text-primary">
                        Remember me
                    </span>
                </label>

                <Link
                    v-if="canResetPassword"
                    :href="route('password.request')"
                    class="text-sm text-primary-dark hover:text-primary transition-colors"
                >
                    Forgot password?
                </Link>
            </div>

            <PrimaryButton
                class="w-full px-6 py-3 bg-primary-600 hover:bg-primary-dark text-white font-medium rounded-lg shadow-md transition-colors"
                :class="{ 'opacity-70': form.processing }"
                :disabled="form.processing"
            >
                <span v-if="!form.processing">Sign In</span>
                <span v-else class="flex items-center justify-center">
                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Signing In...
                </span>
            </PrimaryButton>

            <div class="mt-6 text-center text-sm text-primary">
                Don't have an account?
                <Link :href="route('register')" class="font-medium text-primary hover:text-primary-dark ml-1">
                    Create account
                </Link>
            </div>
        </form>
    </GuestLayout>
</template>
