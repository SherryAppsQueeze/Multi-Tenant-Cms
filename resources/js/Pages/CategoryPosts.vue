<template>

    <Head :title="`Category Posts | ${categoryName}`" />

    <!-- Page Wrapper -->
    <div class="min-h-screen bg-gradient-to-br from-[#0f0c29] via-[#302b63] to-[#24243e] py-10 px-4 text-white">
        <!-- Category Title Banner -->
        <div class="max-w-7xl mx-auto text-center mb-5">
            <h1 class="text-4xl font-extrabold drop-shadow-lg">
                {{ categoryName }}
            </h1>
            <p class="mt-2 text-gray-300 text-lg">
                Explore all posts in this category
            </p>
        </div>
        <div class="max-w-7xl mx-auto text-center mt-2">
            <Link :href="route('post.create.index')" method="get"
                :data="{ categoryId: categoryId, categoryName: categoryName }"
                class="mt-4 inline-block bg-gradient-to-r from-green-500 to-emerald-600 px-6 py-3 rounded-full font-semibold hover:scale-105 transition">
            ➕ Create New Post
            </Link>
        </div>

        <!-- Posts Grid -->
        <div v-if="posts.length" class="max-w-7xl mx-auto grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8 mt-10">
            <div v-for="post in posts" :key="post.id"
                class="bg-white/10 backdrop-blur-lg rounded-xl shadow-lg overflow-hidden hover:scale-[1.02] transition-transform duration-300">
                <!-- Post Image -->
                <img v-if="post.featured_image" :src="`/storage/featured_images/${post.featured_image}`"
                    alt="Post Image" class="w-full h-48 object-cover" />

                <!-- Post Content -->
                <div class="p-5">
                    <h2 class="text-xl font-bold mb-2 truncate">
                        {{ post.title }}
                    </h2>
                    <p class="text-sm text-gray-300 line-clamp-3">
                        {{ post.content }}
                    </p>

                    <!-- Actions -->
                    <div class="mt-4 flex justify-between items-center">
                        <span class="text-xs text-gray-400">
                            {{ new Date(post.created_at).toLocaleDateString() }}
                        </span>
                        <Link :href="route('post.create.index', post.id)"
                            class="bg-gradient-to-r from-purple-600 to-pink-500 px-4 py-1 rounded-full text-sm font-semibold hover:from-purple-700 hover:to-pink-600 transition-all">
                        Read More
                        </Link>
                    </div>
                </div>
            </div>
        </div>
        <div v-else class="max-w-7xl mx-auto flex flex-col items-center justify-center mt-20">
            <svg class="w-16 h-16 text-yellow-400 mb-4" fill="none" stroke="currentColor" stroke-width="2"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M13 16h-1v-4h-1m1-4h.01M12 20a8 8 0 100-16 8 8 0 000 16z" />
            </svg>
            <p class="text-lg text-yellow-400 font-semibold mb-2">
                No posts found in this category.
            </p>
            <p class="text-gray-300 mb-4">
                Click the
                <span class="font-bold text-green-400">Create New Post</span>
                button above to add your first post.
            </p>
        </div>
    </div>
</template>

<script setup>
defineProps({
    posts: Array,
    categoryId: Number,
    categoryName: String,
});
</script>
