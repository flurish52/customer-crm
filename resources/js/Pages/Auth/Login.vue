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

        <div v-if="status" class="mb-4 text-sm font-medium text-green-400 bg-green-900/20 px-4 py-2 rounded-lg">
            {{ status }}
        </div>

        <form @submit.prevent="submit" class="max-w-md mx-auto p-8 bg-gray-900/30 backdrop-blur-sm border border-purple-500/20 rounded-2xl shadow-lg shadow-purple-900/20">
            <div class="mb-6 text-center">
                <h2 class="text-2xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-purple-300 to-pink-300">
                    Welcome to Zelvra CRM
                </h2>
                <p class="mt-2 text-purple-200 text-sm">
                    Manage your customers and jobs seamlessly
                </p>
            </div>

            <div>
                <InputLabel for="email" value="Email" class="text-purple-300" />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full bg-gray-800/50 border-gray-700/50 text-white focus:border-purple-400 focus:ring-purple-400/30 rounded-lg"
                    v-model="form.email"
                    required
                    autofocus
                    autocomplete="username"
                />

                <InputError class="mt-2 text-pink-300" :message="form.errors.email" />
            </div>

            <div class="mt-4">
                <InputLabel for="password" value="Password" class="text-purple-300" />

                <TextInput
                    id="password"
                    type="password"
                    class="mt-1 block w-full bg-gray-800/50 border-gray-700/50 text-white focus:border-purple-400 focus:ring-purple-400/30 rounded-lg"
                    v-model="form.password"
                    required
                    autocomplete="current-password"
                />

                <InputError class="mt-2 text-pink-300" :message="form.errors.password" />
            </div>

            <div class="mt-4 block">
                <label class="flex items-center">
                    <Checkbox name="remember" v-model:checked="form.remember"
                              class="border-gray-600 text-purple-400 focus:ring-purple-400/50" />
                    <span class="ms-2 text-sm text-purple-200">
                        Remember me
                    </span>
                </label>
            </div>

            <div class="mt-6 flex items-center justify-between">
                <Link
                    :href="route('register')"
                    class="text-sm text-purple-300 hover:text-purple-100 transition-colors duration-200 underline underline-offset-4 decoration-purple-400/50"
                >
                    Don't have account yet?
                </Link>
                <Link
                    v-if="canResetPassword"
                    :href="route('password.request')"
                    class="text-sm text-purple-300 hover:text-purple-100 transition-colors duration-200 underline underline-offset-4 decoration-purple-400/50"
                >
                    Forgot password?
                </Link>

                <PrimaryButton
                    class="ms-4 px-6 py-2 bg-gradient-to-r from-purple-500 to-pink-500 hover:from-purple-600 hover:to-pink-600 text-white rounded-full shadow-lg shadow-purple-500/20 transition-all duration-300 transform hover:scale-[1.02]"
                    :class="{ 'opacity-50': form.processing, 'cursor-not-allowed': form.processing }"
                    :disabled="form.processing"
                >
                    <span v-if="!form.processing">Log In</span>
                    <span v-else class="flex items-center justify-center">
                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Processing...
                    </span>
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>
