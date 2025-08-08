<template>
    <nav
        class="bg-gradient-to-r from-[#0f0c29] via-[#302b63] to-[#24243e] backdrop-blur-md border-b border-white/10 shadow-md">
        <div class="container mx-auto px-4 py-3 flex justify-between items-center">

            <template v-if="loggedInUser">
                <!-- Logo / Home Link -->
                <div class="text-white text-xl font-extrabold tracking-wide">
                    <Link :href="route('home')" class="hover:text-purple-300 transition duration-300">
                    Home
                    </Link>
                </div>
            </template>

            <!-- User Area -->
            <div class="flex items-center space-x-4 text-sm">
                <template v-if="loggedInUser">
                    <span class="text-gray-300">Hi, {{ loggedInUser.name }}</span>
                    <Link :href="route('profile')" class="text-white hover:text-purple-400 transition duration-300">
                    Profile
                    </Link>
                    <Link :href="route('logout')" method="post" as="button"
                        class="text-red-400 hover:text-red-500 transition duration-300">
                    Logout
                    </Link>
                </template>

                <template v-else>
                    <Link :href="route('login')"
                        class="text-white hover:text-purple-300 transition duration-300">
                    Login
                    </Link>
                    <Link :href="route('user-register')"
                        class="text-white hover:text-purple-300 transition duration-300">
                    Register
                    </Link>
                </template>

            </div>
        </div>
    </nav>
</template>
<script setup>
defineProps({
    loggedInUser: Object
})
</script>
