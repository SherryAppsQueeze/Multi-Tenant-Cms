<template>
    <div
        class="min-h-screen bg-gradient-to-br from-[#0f0c29] via-[#302b63] to-[#24243e] text-white flex flex-col items-center">

        <!-- Hero Section -->
        <div v-if="post.featured_image" class="relative w-full max-h-[450px] overflow-hidden shadow-xl">
            <img :src="`/storage/featured_images/${post.featured_image}`"
                class="w-full h-full object-cover transform scale-105 hover:scale-110 transition-transform duration-700" />
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent"></div>
            <h1
                class="absolute bottom-6 left-1/2 -translate-x-1/2 text-center text-5xl font-extrabold bg-clip-text text-transparent bg-gradient-to-r from-pink-400 to-purple-500 drop-shadow-xl animate-fade-in">
                {{ post.title }}
            </h1>
        </div>

        <!-- Content Card -->
        <div
            class="max-w-4xl w-full bg-white/10 backdrop-blur-xl rounded-2xl shadow-2xl p-8 mt-8 border mb-5 border-white/20 animate-slide-up">

            <!-- Category Badge -->
            <div class="flex justify-center mb-6">
                <span
                    class="flex items-center gap-2 bg-gradient-to-r from-purple-600 via-purple-500 to-pink-500 px-5 py-2 rounded-full border border-purple-400 shadow-lg text-white font-semibold">
                    <span>📂</span>
                    {{ post.category?.name || 'Uncategorized' }}
                </span>
            </div>

            <!-- Post Content -->
            <p class="text-lg leading-relaxed text-gray-200 whitespace-pre-line">
                {{ post.content }}
            </p>

            <!-- Action Buttons -->
            <div class="flex justify-center gap-6 mt-8">
                <button @click="isModalOpen = true"
                    class="px-6 py-3 rounded-xl bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 font-semibold shadow-lg hover:shadow-xl transform hover:scale-105 transition-all">
                    ✏️ Edit
                </button>
                <button @click="deletePost"
                    class="px-6 py-3 rounded-xl bg-gradient-to-r from-red-500 to-pink-600 hover:from-red-600 hover:to-pink-700 font-semibold shadow-lg hover:shadow-xl transform hover:scale-105 transition-all">
                    🗑 Delete
                </button>
            </div>
        </div>

        <!-- Edit Modal -->
        <transition name="fade">
            <div v-if="isModalOpen" class="fixed inset-0 flex items-center justify-center bg-black/70 z-50 p-4">
                <div
                    class="bg-gray-900 w-full max-w-lg shadow-2xl relative border border-white/10 rounded-2xl flex flex-col max-h-[90vh] overflow-hidden">

                    <!-- Modal Content -->
                    <div class="overflow-y-auto p-8 space-y-5 custom-scrollbar">
                        <button @click="isModalOpen = false"
                            class="absolute top-4 right-4 text-gray-400 hover:text-white text-2xl">&times;</button>
                        <h2
                            class="text-2xl font-bold mb-4 text-center bg-gradient-to-r from-blue-400 to-cyan-400 bg-clip-text text-transparent">
                            ✏️ Edit Post</h2>

                        <!-- Title -->
                        <div>
                            <label class="block mb-2 font-semibold text-gray-300">Title</label>
                            <input v-model="editForm.title"
                                class="w-full p-3 rounded-lg bg-gray-800 text-white border border-blue-500/50 focus:ring-2 focus:ring-blue-500 transition" />
                        </div>

                        <!-- Category -->
                        <div>
                            <label class="block mb-2 font-semibold text-gray-300">Category</label>
                            <select v-model="editForm.category_id"
                                class="w-full p-3 rounded-lg bg-gray-800 text-white border border-blue-500/50 focus:ring-2 focus:ring-blue-500 transition">
                                <option v-for="category in categories" :key="category.id" :value="category.id">
                                    {{ category.name }}
                                </option>
                            </select>
                        </div>

                        <!-- Featured Image -->
                        <div>
                            <label class="block mb-2 font-semibold text-gray-300">Featured Image</label>
                            <input type="file" @change="handleImageUpload"
                                class="w-full p-3 rounded-lg bg-gray-800 text-white border border-blue-500/50 focus:ring-2 focus:ring-blue-500 transition" />
                            <div v-if="editForm.featured_image_preview"
                                class="mt-4 rounded-xl overflow-hidden shadow-lg">
                                <img :src="editForm.featured_image_preview"
                                    class="w-full max-h-48 object-cover hover:scale-105 transition-transform duration-500" />
                            </div>
                        </div>

                        <!-- Content -->
                        <div>
                            <label class="block mb-2 font-semibold text-gray-300">Content</label>
                            <textarea v-model="editForm.content" rows="5"
                                class="w-full p-3 rounded-lg bg-gray-800 text-white border border-blue-500/50 focus:ring-2 focus:ring-blue-500 transition"></textarea>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="flex justify-end gap-4 p-4 border-t border-gray-700 bg-gray-800 rounded-b-2xl">
                        <button @click="isModalOpen = false"
                            class="bg-gray-600 px-5 py-2 rounded-lg font-semibold hover:bg-gray-700 transition">Cancel</button>
                        <button @click="updatePost"
                            class="bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 px-5 py-2 rounded-lg font-semibold shadow hover:shadow-lg transform hover:scale-105 transition">Save
                            Changes</button>
                    </div>
                </div>
            </div>
        </transition>

    </div>
</template>

<style scoped>
@keyframes fade-in {
    from {
        opacity: 0;
        transform: translateY(20px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in {
    animation: fade-in 1s ease-out;
}

.animate-slide-up {
    animation: fade-in 0.8s ease-out;
}
</style>


<script>
import { router } from '@inertiajs/vue3'

export default {
    props: {
        post: Object,
        postTitle: String,
        categories: Array,
    },
    data() {
        return {
            isModalOpen: false,
            editForm: {
                title: this.post.title,
                category_id: this.post.category_id,
                content: this.post.content,
                featured_image: null, // actual file
                featured_image_preview: this.post.featured_image
                    ? `/storage/featured_images/${this.post.featured_image}`
                    : null,
            }
        }
    },
    methods: {
        handleImageUpload(event) {
            const file = event.target.files[0];
            if (file) {
                this.editForm.featured_image = file;
                this.editForm.featured_image_preview = URL.createObjectURL(file);
            }
        },
        updatePost() {
            const formData = new FormData();
            formData.append('title', this.editForm.title);
            formData.append('category_id', this.editForm.category_id);
            formData.append('content', this.editForm.content);

            if (this.editForm.featured_image) {
                formData.append('featured_image', this.editForm.featured_image);
            }

            // Inertia PUT request with multipart form data
            router.post(`/post/update/${this.post.id}`, formData, {
                forceFormData: true, // ⬅️ Important
                onSuccess: () => {
                    this.isModalOpen = false;
                },
                // Laravel expects PUT, so override method
                headers: { 'X-HTTP-Method-Override': 'PUT' }
            });
        },
        deletePost() {
            if (confirm('Are you sure you want to delete this post?')) {
                router.delete(`/post/delete/${this.post.id}`)
            }
        }
    }
}
</script>
