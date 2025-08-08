<template>

    <Head :title="`Home Page | `" />
    <div class="min-h-screen bg-gradient-to-br from-[#0f0c29] via-[#302b63] to-[#24243e] py-10 px-4 text-white">
        <!-- Page Title -->
        <h1 class="text-4xl font-extrabold mb-10 text-center drop-shadow-xl">
            Explore Tenant
        </h1>

        <!-- Add Sticker Button -->
        <!-- <div class="text-center mb-10">
            <Link :href="route('stickers.category.create.index')"
                class="inline-block bg-gradient-to-r from-purple-600 to-pink-500 text-white px-6 py-3 rounded-full font-semibold hover:from-purple-700 hover:to-pink-600 transition-all duration-300 shadow-lg hover:scale-105">
            Add New Sticker Pack
            </Link>
        </div> -->



        <!-- Sticker Categories Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 max-w-7xl mx-auto">
            <div v-for="category in categories" :key="category.id"
                class="bg-white/10 backdrop-blur-md rounded-2xl shadow-xl hover:shadow-2xl overflow-hidden transition-all duration-300 flex flex-col border border-white/10">
                <!-- Image -->
                <div class="bg-white/10 flex items-center justify-center h-40">
                    <img :src="`/images/stickersCategory/${category.image}`" :alt="category.title"
                        class="max-h-28 object-contain transition-transform duration-300 hover:scale-110" />
                </div>

                <!-- Content -->
                <div class="p-4 text-gray-200 flex-1 flex flex-col justify-between">
                    <div>
                        <h2 class="text-xl font-bold truncate mb-1 text-white">{{ category.title }}</h2>
                        <p class="text-sm text-gray-400 mb-1">Uploaded by <strong>{{ category.username }}</strong></p>
                        <p class="text-xs text-gray-500 mb-2">Version: <strong>{{ category.version }}</strong></p>
                    </div>

                    <!-- Badges -->
                    <div class="flex flex-wrap gap-2 mt-2">
                        <span class="text-xs font-semibold px-3 py-1 rounded-full"
                            :class="category.status === 'active' ? 'bg-green-500/20 text-green-300' : 'bg-red-500/20 text-red-300'">
                            {{ category.status }}
                        </span>
                        <span class="text-xs font-semibold px-3 py-1 rounded-full"
                            :class="category.type === 1 ? 'bg-purple-500/20 text-purple-300' : 'bg-yellow-500/20 text-yellow-300'">
                            {{ category.type === 1 ? 'Animated' : 'Static' }}
                        </span>
                    </div>

                    <!-- Buttons -->
                    <div class="mt-4 flex flex-wrap gap-2">
                        <Link :href="route('stickers.show', category.identifier)"
                            class="flex-1 text-center px-4 py-2 bg-purple-600 text-white rounded-full hover:bg-purple-700 transition-all duration-300">
                        View Stickers
                        </Link>
                        <Link :href="route('stickers.edit')" method="GET"
                            :data="{ title: category.title, id: category.id, identifier: category.identifier }"
                            class="flex-1 text-center px-4 py-2 bg-yellow-600 text-white rounded-full hover:bg-yellow-700 transition-all duration-300">
                        Edit
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
defineProps({
    categories: Array,
    flash: Object,
    errors: Object,
});
</script>
