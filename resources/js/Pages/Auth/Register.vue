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

        <form @submit.prevent="submit" class="space-y-6">
            <div class="text-center mb-8">
                <h2 class="text-2xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-purple-300 to-pink-300">
                    Create Your Zelvra Account
                </h2>
                <p class="mt-2 text-purple-200 text-sm">
                    Start managing your customers in minutes
                </p>
            </div>

            <div>
                <InputLabel for="name" value="Name" class="text-purple-300" />
                <TextInput
                    id="name"
                    type="text"
                    class="mt-1 block w-full bg-gray-800/50 border-gray-700/50 text-white focus:border-purple-400 focus:ring-purple-400/30 rounded-lg"
                    v-model="form.name"
                    required
                    autofocus
                    autocomplete="name"
                />
                <InputError class="mt-2 text-pink-300" :message="form.errors.name" />
            </div>

            <div>
                <InputLabel for="email" value="Email" class="text-purple-300" />
                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full bg-gray-800/50 border-gray-700/50 text-white focus:border-purple-400 focus:ring-purple-400/30 rounded-lg"
                    v-model="form.email"
                    required
                    autocomplete="username"
                />
                <InputError class="mt-2 text-pink-300" :message="form.errors.email" />
            </div>

            <div>
                <InputLabel for="password" value="Password" class="text-purple-300" />
                <TextInput
                    id="password"
                    type="password"
                    class="mt-1 block w-full bg-gray-800/50 border-gray-700/50 text-white focus:border-purple-400 focus:ring-purple-400/30 rounded-lg"
                    v-model="form.password"
                    required
                    autocomplete="new-password"
                />
                <InputError class="mt-2 text-pink-300" :message="form.errors.password" />
            </div>

            <div>
                <InputLabel for="password_confirmation" value="Confirm Password" class="text-purple-300" />
                <TextInput
                    id="password_confirmation"
                    type="password"
                    class="mt-1 block w-full bg-gray-800/50 border-gray-700/50 text-white focus:border-purple-400 focus:ring-purple-400/30 rounded-lg"
                    v-model="form.password_confirmation"
                    required
                    autocomplete="new-password"
                />
                <InputError class="mt-2 text-pink-300" :message="form.errors.password_confirmation" />
            </div>

            <div class="flex items-center justify-between mt-8">
                <Link
                    :href="route('login')"
                    class="text-sm text-purple-300 hover:text-purple-100 transition-colors duration-200 underline underline-offset-4 decoration-purple-400/50"
                >
                    Already registered?
                </Link>

                <PrimaryButton
                    class="px-6 py-2 bg-gradient-to-r from-purple-500 to-pink-500 hover:from-purple-600 hover:to-pink-600 text-white rounded-full shadow-lg shadow-purple-500/20 transition-all duration-300 transform hover:scale-[1.02]"
                    :class="{ 'opacity-50': form.processing, 'cursor-not-allowed': form.processing }"
                    :disabled="form.processing"
                >
                    <span v-if="!form.processing">Register</span>
                    <span v-else class="flex items-center justify-center">
                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Creating account...
                    </span>
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>
