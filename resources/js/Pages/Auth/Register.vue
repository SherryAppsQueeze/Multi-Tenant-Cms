<template>

    <Head :title="`Register Page | `" />
    <div class="flex mt-5 items-center justify-center p-12 from-[#0f0c29] via-[#302b63] to-[#24243e]">
        <div
            class="bg-white/10 backdrop-blur-xl rounded-2xl shadow-2xl p-10 max-w-md w-full border border-white/10 animate__animated animate__fadeInDown">
            <h2 class="text-3xl font-extrabold text-white text-center mb-8 tracking-wide">Create Your Account</h2>

            <!-- Register Form -->
            <form @submit.prevent="submit" class="space-y-6 animate__animated animate__fadeIn" autocomplete="off">
                <!-- Name -->
                <div class="transition-all duration-300">
                    <label for="name" class="block text-sm font-medium text-gray-300 mb-1">Name</label>
                    <input type="text" id="name" v-model="form.name"
                        class="w-full px-4 py-2 bg-white/10 text-white placeholder-gray-400 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-300"
                        required placeholder="Enter your name" />
                    <p v-if="form.errors.name" class="text-red-500 text-sm mt-1">{{ form.errors.name }}</p>
                </div>

                <!-- Email -->
                <div class="transition-all duration-300">
                    <label for="email" class="block text-sm font-medium text-gray-300 mb-1">Email</label>
                    <input type="email" id="email" v-model="form.email"
                        class="w-full px-4 py-2 bg-white/10 text-white placeholder-gray-400 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-300"
                        required placeholder="Enter your email" />
                    <p v-if="form.errors.email" class="text-red-500 text-sm mt-1">{{ form.errors.email }}</p>
                </div>

                <div class="transition-all duration-300">
                    <label for="password" class="block text-sm font-medium text-gray-300 mb-1">Password</label>
                    <input type="password" id="password" v-model="form.password"
                        class="w-full px-4 py-2 bg-white/10 text-white placeholder-gray-400 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-300"
                        required placeholder="Enter your password" />
                    <p v-if="form.errors.password" class="text-red-500 text-sm mt-1">{{ form.errors.password }}</p>
                </div>
                <!-- Confirm Password -->
                <div class="transition-all duration-300">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-300 mb-1">Confirm
                        Password</label>
                    <input type="password" id="password_confirmation" v-model="form.password_confirmation"
                        class="w-full px-4 py-2 bg-white/10 text-white placeholder-gray-400 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-300"
                        required placeholder="Confirm your password" />
                    <p v-if="form.errors.password_confirmation" class="text-red-500 text-sm mt-1">{{
                        form.errors.password_confirmation }}</p>
                    <p v-if="passwordMismatch" class="text-red-500 text-sm mt-1">Passwords do not match</p>

                </div>

                <!-- Register Button -->
                <button type="submit" :disabled="passwordMismatch"
                    class="w-full bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 hover:from-indigo-700 hover:to-pink-700 text-white font-semibold py-2 rounded-lg shadow-lg hover:scale-105 transition-transform duration-300 disabled:opacity-50 disabled:cursor-not-allowed">
                    Register
                </button>

                <!-- Already have an account? -->
                <div class="text-center mt-4 text-sm text-white">
                    Already have an account?
                    <Link :href="route('login')" class="text-purple-400 hover:underline hover:text-purple-300 ml-1">
                    Login
                    </Link>
                </div>
            </form>
        </div>
    </div>
</template>



<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { computed } from 'vue';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    errors: {},
});

const passwordMismatch = computed(() => {
    return form.password !== form.password_confirmation;
});

const submit = () => {
    if (passwordMismatch.value) {
        form.errors.password_confirmation = "Passwords do not match";
        return;
    }

    form.post(route('register'), {
        onSuccess: () => {
            form.reset();
        },
        onError: (errors) => {
            form.errors = errors;
        },
    });
};
</script>
