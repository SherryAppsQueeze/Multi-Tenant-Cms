<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    flash: Object,
});

const form = useForm({
    title: null,
    identifier: null,
    type: null,
    username: null,
    link: null,
    status: null,
    image: null,
    resized_image: null,
});

const previewImage = ref(null);

// Image Upload Handler
function handleImageUpload(e) {
    const file = e.target.files[0];

    if (!file) return;
    if (!file.type.includes('image/webp')) {
        alert('Only WEBP format is allowed');
        return;
    }

    const reader = new FileReader();
    reader.onload = (event) => {
        const img = new Image();
        img.onload = () => {
            // Create canvas and resize
            const canvas = document.createElement('canvas');
            canvas.width = 96;
            canvas.height = 96;
            const ctx = canvas.getContext('2d');
            ctx.drawImage(img, 0, 0, 96, 96);

            // Convert canvas to blob
            canvas.toBlob((blob) => {
                if (blob.size > 50 * 1024) {
                    alert('Resized image exceeds 50KB. Try a smaller image.');
                    return;
                }

                form.resized_image = new File([blob], 'resized.webp', { type: 'image/webp' });
                previewImage.value = URL.createObjectURL(blob);
            }, 'image/webp', 1); // Quality 1 = best
        };
        img.src = event.target.result;
    };
    reader.readAsDataURL(file);
}

// Submit the form
function submit() {
    form.post(route('stickers.category.create'), {

        onFinish: () => {
            // Reset form after successfull submission

        },
        onSuccess: () => {
            // Handle success, maybe show a message or redirect
            form.reset();
            previewImage.value = null;
        },
        onError: () => {

        },
        preserveScroll: true,
        preserveState: true,
    });
}
</script>


<template>

    <Head :title="`Category Sticker Create Page | `" />
    <div
        class="min-h-screen bg-gradient-to-br from-[#0f0c29] via-[#302b63] to-[#24243e] p-6 flex items-center justify-center">
        <div
            class="w-full max-w-2xl bg-white/10 backdrop-blur-md text-white rounded-2xl shadow-2xl p-8 border border-white/10">
            <h2 class="text-3xl font-bold text-center mb-8">Create Sticker Category</h2>

            <form @submit.prevent="submit" class="space-y-6">

                <!-- Title -->
                <div>
                    <label class="block text-sm font-medium mb-2">Title</label>
                    <input type="text" v-model="form.title" placeholder="Category Name"
                        class="w-full bg-white/5 border border-white/20 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                    <p v-if="form.errors.title" class="text-red-400 text-sm mt-1">{{ form.errors.title }}</p>
                </div>

                <!-- Username -->
                <div>
                    <label class="block text-sm font-medium mb-2">Username</label>
                    <input type="text" v-model="form.username" placeholder="Username"
                        class="w-full bg-white/5 border border-white/20 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                    <p v-if="form.errors.username" class="text-red-400 text-sm mt-1">{{ form.errors.username }}</p>
                </div>

                <!-- Type -->
                <div>
                    <label class="block text-sm font-medium mb-2">Type</label>
                    <select v-model="form.type"
                        class="w-full bg-white/5 border border-white/20 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option class="bg-gradient-to-br from-[#0f0c29] via-[#302b63] to-[#24243e] bg-gray-800" value="0">Static</option>
                        <option class="bg-gradient-to-br from-[#0f0c29] via-[#302b63] to-[#24243e] bg-gray-800" value="1">Animated</option>
                    </select>
                    <p v-if="form.errors.type" class="text-red-400 text-sm mt-1">{{ form.errors.type }}</p>
                </div>

                <!-- Source URL -->
                <div>
                    <label class="block text-sm font-medium mb-2">Source URL</label>
                    <input type="url" v-model="form.link" placeholder="Sticker source url" pattern="https?://.+"
                        class="w-full bg-white/5 border border-white/20 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                    <p v-if="form.errors.link" class="text-red-400 text-sm mt-1">{{ form.errors.link }}</p>
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-sm font-medium mb-2">Status</label>
                    <select v-model="form.status"
                        class="w-full bg-white/5 border border-white/20 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option class="bg-gradient-to-br from-[#0f0c29] via-[#302b63] to-[#24243e] bg-gray-800" value="active">Active</option>
                        <option class="bg-gradient-to-br from-[#0f0c29] via-[#302b63] to-[#24243e] bg-gray-800" value="inactive">Inactive</option>
                    </select>
                    <p v-if="form.errors.status" class="text-red-400 text-sm mt-1">{{ form.errors.status }}</p>
                </div>

                <!-- Sticker Upload -->
                <div>
                    <label class="block text-sm font-medium mb-2">Sticker Preview</label>
                    <div
                        class="bg-white/10 border border-white/20 flex items-center justify-center h-36 rounded-lg mb-2">
                        <img :src="previewImage || '/images/default-image.webp'" alt="Preview"
                            class="max-h-28 object-contain" />
                    </div>

                    <label
                        class="flex flex-col items-center justify-center w-full px-4 py-6 bg-white/5 border border-white/20 rounded-lg cursor-pointer hover:bg-white/10 transition">
                        <span class="text-indigo-400 font-semibold mb-1">Upload a WEBP Sticker</span>
                        <span class="text-xs text-gray-400 mb-2">96x96px • Max 50KB</span>
                        <input type="file" accept="image/webp" class="hidden" @change="handleImageUpload" />
                        <p v-if="form.resized_image" class="text-green-400 text-sm">{{ form.resized_image.name }}</p>
                        <p v-if="form.errors.resized_image" class="text-red-400 text-sm mt-1">{{
                            form.errors.resized_image }}</p>
                    </label>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-center pt-4">
                    <button type="submit"
                        class="bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-semibold px-6 py-2 rounded-lg shadow-md transition-all duration-300"
                        :disabled="form.processing">
                        Create Sticker Category
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>
