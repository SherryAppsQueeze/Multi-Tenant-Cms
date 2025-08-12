<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    loggedInUser: Object
});

const form = useForm({
    current_password: '',
    new_password: '',
    name: props.loggedInUser.name,
    email: props.loggedInUser.email,
});

const password_form = useForm({
    current_password: '',
    new_password: '',
});

const submitProfile = () => {
    password_form.post(route('profile.password.update'), {
        preserveScroll: true,
    });
};

const submit = () => {
    form.post(route('profile.update'), {
        preserveScroll: true,
    });
};
</script>

<template>

    <Head :title="`Admin Profile Page | `" />

    <div
        class="container mt-5 mb-5 mx-auto p-6 max-w-4xl animate-fade-in text-white bg-white/10 min-h-screen rounded-xl shadow-lg">
        <h1 class="text-3xl font-bold mb-8 text-center text-purple-200 drop-shadow-md">Welcome, {{ loggedInUser.name }}
        </h1>

        <!-- Profile Info Card -->
        <div
            class="bg-gray-800 rounded-2xl shadow-xl p-8 mb-8 border border-gray-700 transition-transform hover:scale-[1.02]">
            <h2 class="text-2xl font-bold mb-6 text-indigo-400 drop-shadow">Your Info</h2>
            <div class="space-y-3 text-gray-200 text-lg">
            <div class="flex items-center gap-2">
                <span class="font-semibold text-indigo-400">Name:</span>
                <span>{{ loggedInUser.name }}</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="font-semibold text-indigo-400">Email:</span>
                <span>{{ loggedInUser.email }}</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="font-semibold text-indigo-400">Joined:</span>
                <span>{{ new Date(loggedInUser.created_at).toLocaleDateString() }}</span>
            </div>
            </div>
            <div class="mt-8 flex justify-end">
            <Link :href="route('logout')" method="post" as="button"
                class="px-6 py-2 bg-gradient-to-r from-red-500 to-pink-600 text-white rounded-lg hover:scale-105 hover:bg-red-700 transition duration-300 shadow-lg font-semibold">
                Logout
            </Link>
            </div>
        </div>

        <!-- Edit Profile Form -->
        <Transition name="fade" mode="out-in">
            <div class="bg-gray-800 rounded-2xl shadow-md p-6 border border-gray-700">
                <h2 class="text-xl font-semibold mb-4 text-indigo-400">Edit Profile</h2>
                <form @submit.prevent="submit" class="space-y-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-300">Name</label>
                        <input type="text" id="name" v-model="form.name"
                            class="mt-1 block w-full border border-gray-600 rounded-md focus:ring-indigo-500 focus:border-indigo-500 px-4 py-2 bg-gray-700 text-white placeholder-gray-400" />
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-300">Email</label>
                        <input type="email" id="email" v-model="form.email"
                            class="mt-1 block w-full border border-gray-600 rounded-md focus:ring-indigo-500 focus:border-indigo-500 px-4 py-2 bg-gray-700 text-white placeholder-gray-400" />
                    </div>
                    <button type="submit"
                        class="w-full bg-gradient-to-r from-indigo-500 to-purple-600 text-white px-4 py-2 rounded-lg shadow-lg hover:scale-105 transition duration-300 font-semibold">
                        Save Changes
                    </button>
                </form>
            </div>
        </Transition>

        <!-- Change Password Form -->
        <Transition name="fade" mode="out-in">
            <div class="bg-gray-800 rounded-2xl shadow-md p-6 mt-8 border border-gray-700">
                <h2 class="text-xl font-semibold mb-4 text-pink-400">Change Password</h2>
                <form @submit.prevent="submitProfile" class="space-y-4">
                    <div>
                        <label for="current_password" class="block text-sm font-medium text-gray-300">Current
                            Password</label>
                        <input type="password" id="current_password" v-model="password_form.current_password"
                            class="mt-1 block w-full border border-gray-600 rounded-md focus:ring-pink-500 focus:border-pink-500 px-4 py-2 bg-gray-700 text-white placeholder-gray-400" />
                            <p v-if="form.errors.current_password" class="text-red-400 text-sm mt-1">{{ form.errors.current_password }}</p>
                    </div>
                    <div>
                        <label for="new_password" class="block text-sm font-medium text-gray-300">New Password</label>
                        <input type="password" id="new_password" v-model="password_form.new_password"
                            class="mt-1 block w-full border border-gray-600 rounded-md focus:ring-pink-500 focus:border-pink-500 px-4 py-2 bg-gray-700 text-white placeholder-gray-400" />
                            <p v-if="form.errors.new_password" class="text-red-400 text-sm mt-1">{{ form.errors.new_password }}</p>
                    </div>
                    <button type="submit"
                        class="w-full bg-gradient-to-r from-pink-500 to-red-500 text-white px-4 py-2 rounded-lg shadow-lg hover:scale-105 transition duration-300 font-semibold">
                        Update Password
                    </button>
                </form>
            </div>
        </Transition>
    </div>
</template>

<style scoped>
/* Entrance animation */
@keyframes fade-in {
    from {
        opacity: 0;
        transform: translateY(15px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in {
    animation: fade-in 0.5s ease-out;
}

.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.4s;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>
