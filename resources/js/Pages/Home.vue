<template>

    <Head :title="'Dashboard | Categories & Posts'" />

    <div class="min-h-screen bg-gradient-to-br from-[#0f0c29] via-[#302b63] to-[#24243e] py-10 px-4 text-white">
        <!-- Add Category -->
        <div class="text-center mb-12">
            <Link :href="route('category.create.index')"
                class="inline-block bg-gradient-to-r from-purple-500 to-pink-500 text-white px-8 py-3 rounded-full font-semibold shadow-lg hover:shadow-pink-500/50 hover:scale-105 transition-all duration-300">
            ➕ Add New Category
            </Link>
        </div>

        <!-- Featured Categories -->
        <div v-if="categories?.length" class="mb-16 max-w-6xl mx-auto">
            <h2 class="text-3xl font-bold mb-8 text-center drop-shadow-md">
                Featured Categories & Posts
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
                <div v-for="cat in categories" :key="cat.id"
                    class="bg-white/10 backdrop-blur-lg p-6 rounded-2xl shadow-lg border border-white/10 hover:scale-105 hover:shadow-purple-500/30 transition duration-300">
                    <div class="flex justify-between items-center mb-2">
                        <h3 class="text-lg font-semibold">{{ cat.name }}</h3>
                        <Link :href="route('category.edit')" method="get" :data="{ id: cat.id, categoryName: cat.name }"
                            class="ml-2 text-xs bg-purple-500 hover:bg-purple-600 text-white px-3 py-1 rounded-full font-semibold transition">
                        Edit
                        </Link>
                    </div>
                    <span
                        class="inline-block bg-white text-pink-600 text-xs font-semibold px-3 py-1 rounded-full mb-4 shadow">
                        {{ cat.posts_count }} Posts
                    </span>
                    <div v-if="cat.posts?.length">
                        <div v-for="post in cat.posts.slice(0, 2)" :key="post.id"
                            class="bg-white/20 p-3 rounded-lg mb-2 flex items-center">
                            <p class="truncate font-medium">{{ post.title }}</p>
                        </div>
                    </div>
                    <div v-else class="text-gray-300 text-sm italic">No posts yet</div>
                    <Link :href="route('category.post.show')" method="get"
                        :data="{ id: cat.id, categoryName: cat.name }"
                        class="inline-block mt-4 text-sm font-semibold underline hover:text-white">
                    View All Posts →
                    </Link>
                </div>
            </div>
        </div>

        <!-- All Posts -->
        <div class="max-w-7xl mx-auto">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-6">
                <h2 class="text-3xl font-bold drop-shadow-md">📰 All Posts</h2>

                <!-- Sorting -->
                <div class="flex items-center gap-2 relative mt-4 sm:mt-0">
                    <svg class="w-5 h-5 text-pink-300" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 7h18M3 12h12M3 17h6" />
                    </svg>
                    <select v-model="localSortBy" @change="applySort"
                        class="bg-gradient-to-r from-purple-500 to-pink-500 text-white font-semibold rounded-full px-5 py-2 pr-10 shadow-lg focus:outline-none focus:ring-2 focus:ring-pink-400 transition-all duration-300 appearance-none">
                        <option class="bg-[#302b63] text-white" value="created_at">
                            Created Date
                        </option>
                        <option class="bg-[#302b63] text-white" value="created_by">Created By</option>
                        <option class="bg-[#302b63] text-white" value="updated_at">
                            Updated Date
                        </option>
                        <option class="bg-[#302b63] text-white" value="updated_by">Updated By</option>
                    </select>
                    <svg class="w-4 h-4 text-white absolute right-4 top-1/2 transform -translate-y-1/2 pointer-events-none"
                        fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </div>
            </div>

            <!-- Posts Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                <div v-for="post in posts.data" :key="post.id"
                    class="bg-white/10 backdrop-blur-lg p-5 rounded-2xl shadow-lg border border-white/10 hover:scale-105 hover:shadow-pink-500/40 transition">
                    <img v-if="post.featured_image" :src="`/storage/featured_images/${post.featured_image}`"
                        class="w-full h-40 object-cover rounded-lg mb-3 border border-white/20" />
                    <h3 class="font-semibold text-lg mb-1">{{ post.title }}</h3>
                    <p class="text-sm opacity-80 line-clamp-2">{{ post.content }}</p>
                    <div class="mt-3 flex items-center justify-between text-gray-400 text-sm">
                        <span>{{ new Date(post.created_at).toLocaleDateString() }}</span>
                    </div>

                    <Link :href="route('post.show')" method="get" :data="{ id: post.id, postTitle: post.title }"
                        class="inline-block mt-3 text-sm font-semibold text-pink-500 hover:underline">
                    Read More →
                    </Link>
                </div>
                <div v-if="posts.data.length === 0" class="col-span-3 text-center text-gray-300 italic">
                    No posts available
                </div>
            </div>

            <!-- Pagination -->
            <div class="mt-8 flex justify-center space-x-2">
                <button v-for="link in posts.links" :key="link.label" :class="[
                    'px-4 py-2 rounded-lg border transition-all duration-300',
                    link.active
                        ? 'bg-pink-500 text-white shadow-lg'
                        : 'bg-white/10 text-gray-300 hover:bg-white/20',
                ]" @click.prevent="gotoPage(link.url)" v-html="link.label"></button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from "vue";
import { router } from "@inertiajs/vue3";

const props = defineProps({
    categories: Array,
    posts: Object,
    sortBy: String,
    direction: String,
});

const localSortBy = ref(props.sortBy);
const localDirection = ref(props.direction);

const applySort = () => {
    router.get(
        route("home"),
        {
            sort_by: localSortBy.value,
            direction: localDirection.value,
        },
        { preserveState: true, preserveScroll: true }
    );
};

const gotoPage = (url) => {
    if (url) {
        router.visit(url, { preserveState: true, preserveScroll: true });
    }
};

</script>
