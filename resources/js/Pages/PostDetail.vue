<template>
    <div
        class="min-h-screen bg-gradient-to-br from-[#0f0c29] via-[#302b63] to-[#24243e] text-white p-6 flex flex-col items-center">
        <div
            class="max-w-3xl w-full bg-white/10 backdrop-blur-lg rounded-2xl shadow-2xl p-8 relative border border-white/20">

            <!-- Post Image -->
            <div v-if="post.featured_image" class="mb-6 rounded-xl overflow-hidden shadow-lg">
                <img :src="`/storage/featured_images/${post.featured_image}`"
                    class="w-full max-h-96 object-cover hover:scale-105 transition-transform duration-500" />
            </div>

            <!-- Title -->
            <h1
                class="text-4xl font-extrabold mb-4 text-center bg-gradient-to-r from-pink-400 to-purple-500 bg-clip-text text-transparent drop-shadow-lg">
                {{ post.title }}
            </h1>

            <!-- Meta Info -->
            <div class="flex flex-wrap justify-center items-center gap-4 mb-6 text-sm">
                <span class="bg-purple-900/50 px-4 py-1 rounded-full border border-purple-500 shadow">
                    📂 {{ post.category?.name || 'Uncategorized' }}
                </span>
                <span class="text-gray-300 italic">🆔 ID: {{ post.id }}</span>
            </div>

            <!-- Content -->
            <p class="mb-8 text-lg leading-relaxed text-gray-200 whitespace-pre-line">
                {{ post.content }}
            </p>

            <!-- Action Buttons -->
            <div class="flex justify-center gap-4">
                <button @click="isModalOpen = true"
                    class="bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 px-6 py-3 rounded-xl font-semibold shadow-lg hover:shadow-xl transition-transform hover:scale-105">
                    ✏️ Edit
                </button>
                <button @click="deletePost"
                    class="bg-gradient-to-r from-red-500 to-pink-600 hover:from-red-600 hover:to-pink-700 px-6 py-3 rounded-xl font-semibold shadow-lg hover:shadow-xl transition-transform hover:scale-105">
                    🗑 Delete
                </button>
            </div>
        </div>

        <transition name="fade">
            <div v-if="isModalOpen" class="fixed inset-0 flex items-center justify-center bg-black/60 z-50 p-4">
                <div
                    class="bg-gray-900 w-full max-w-lg shadow-2xl relative border border-white/10 flex flex-col max-h-[90vh]">

                    <!-- Scrollable Content -->
                    <div class="overflow-y-auto p-8 space-y-5 custom-scrollbar">
                        <!-- Close Button -->
                        <button @click="isModalOpen = false"
                            class="absolute top-4 right-4 text-gray-400 hover:text-white text-2xl">
                            &times;
                        </button>

                        <h2
                            class="text-2xl font-bold mb-4 text-center bg-gradient-to-r from-blue-400 to-cyan-400 bg-clip-text text-transparent">
                            ✏️ Edit Post
                        </h2>

                        <!-- Title -->
                        <div>
                            <label class="block mb-2 font-semibold text-gray-300">Title</label>
                            <input v-model="editForm.title"
                                class="w-full p-3 rounded-lg bg-gray-800 text-white border border-blue-500/50 focus:outline-none focus:ring-2 focus:ring-blue-500 transition" />
                        </div>

                        <!-- Category -->
                        <div>
                            <label class="block mb-2 font-semibold text-gray-300">Category</label>
                            <select v-model="editForm.category_id"
                                class="w-full p-3 rounded-lg bg-gray-800 text-white border border-blue-500/50 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                                <option v-for="category in categories" :key="category.id" :value="category.id">
                                    {{ category.name }}
                                </option>
                            </select>
                        </div>

                        <!-- Featured Image Upload -->
                        <div>
                            <label class="block mb-2 font-semibold text-gray-300">Featured Image</label>
                            <input type="file" @change="handleImageUpload"
                                class="w-full p-3 rounded-lg bg-gray-800 text-white border border-blue-500/50 focus:outline-none focus:ring-2 focus:ring-blue-500 transition" />

                            <!-- Preview -->
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
                                class="w-full p-3 rounded-lg bg-gray-800 text-white border border-blue-500/50 focus:outline-none focus:ring-2 focus:ring-blue-500 transition"></textarea>
                        </div>
                    </div>

                    <!-- Sticky Footer Buttons -->
                    <div class="flex justify-end gap-4 p-4 border-t border-gray-700 bg-gray-800 rounded-b-2xl">
                        <button type="button" @click="isModalOpen = false"
                            class="bg-gray-600 px-5 py-2 rounded-lg font-semibold hover:bg-gray-700 transition">
                            Cancel
                        </button>
                        <button @click="updatePost"
                            class="bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 px-5 py-2 rounded-lg font-semibold shadow hover:shadow-lg transition-transform hover:scale-105">
                            Save Changes
                        </button>
                    </div>
                </div>
            </div>
        </transition>

    </div>
</template>

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
            router.put(`/post/update/${this.post.id}`, this.editForm, {
                onSuccess: () => {
                    this.isModalOpen = false
                }
            })
        },
        deletePost() {
            if (confirm('Are you sure you want to delete this post?')) {
                router.delete(`/post/delete/${this.post.id}`)
            }
        }
    }
}
</script>

<style>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}

.custom-scrollbar::-webkit-scrollbar {
    width: 8px;
}

.custom-scrollbar::-webkit-scrollbar-track {
    background: rgba(255, 255, 255, 0.05);
    border-radius: 4px;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
    background: linear-gradient(180deg, #4f46e5, #06b6d4);
    border-radius: 4px;
}

.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(180deg, #4338ca, #0891b2);
}

/* Firefox */
.custom-scrollbar {
    scrollbar-width: thin;
    scrollbar-color: #4f46e5 rgba(255, 255, 255, 0.05);
}
</style>
