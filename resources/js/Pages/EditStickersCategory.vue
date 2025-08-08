<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    category: Object,
    flash: Object,
});

const form = useForm({
    id: props.category.id,
    title: props.category.title || '',
    identifier: props.category.identifier || '',
    type: props.category.type,
    username: props.category.username || '',
    link: props.category.link || '',
    status: props.category.status == 'active' ? 'active' : 'inactive',
    image: props.category.image || '',
    resized_image: null,
});

const previewImage = ref(null);

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
            const canvas = document.createElement('canvas');
            canvas.width = 96;
            canvas.height = 96;
            const ctx = canvas.getContext('2d');
            ctx.drawImage(img, 0, 0, 96, 96);

            canvas.toBlob((blob) => {
                if (blob.size > 50 * 1024) {
                    alert('Resized image exceeds 50KB. Try a smaller image.');
                    return;
                }

                form.resized_image = new File([blob], 'resized.webp', { type: 'image/webp' });
                previewImage.value = URL.createObjectURL(blob);
            }, 'image/webp', 1);
        };
        img.src = event.target.result;
    };
    reader.readAsDataURL(file);
}

function submit() {
    form.post(route('stickers.update'), {
        onFinish: () => {
            if (props.flash.newImage) {
                form.image = props.flash.newImage
                previewImage.value = `/images/stickersCategory/${props.flash.newImage}?t=${Date.now()}`
            } else {
                previewImage.value = null
            }
        },
        preserveScroll: true,
        preserveState: true,
    });
}
</script>

<template>
    <Head :title="`${props.category.identifier} Page | `" />

    <div class="container mt-5 mb-5 mx-auto p-6 max-w-4xl animate-fade-in text-white bg-gradient-to-br from-gray-900 to-gray-600 min-h-screen rounded-xl shadow-lg">
        <h2 class="text-3xl font-bold text-center text-indigo-400 mb-8">Edit Sticker Category</h2>

        <form @submit.prevent="submit">
            <input type="hidden" v-model="form.id" />

            <div class="mb-5">
                <label class="block text-gray-300 font-semibold mb-2">Title</label>
                <input type="text" v-model="form.title"
                    class="w-full px-4 py-2 bg-gray-800 border border-gray-600 text-white rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none placeholder-gray-500"
                    placeholder="Category Name" />
                <p v-if="form.errors.title" class="text-red-500 text-sm mt-1">{{ form.errors.title }}</p>
            </div>

            <div class="mb-5">
                <label class="block text-gray-300 font-semibold mb-2">Username</label>
                <input type="text" v-model="form.username"
                    class="w-full px-4 py-2 bg-gray-800 border border-gray-600 text-white rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none placeholder-gray-500"
                    placeholder="Username" />
                <p v-if="form.errors.username" class="text-red-500 text-sm mt-1">{{ form.errors.username }}</p>
            </div>

            <div class="mb-5">
                <label class="block text-gray-300 font-semibold mb-2">Identifier</label>
                <input type="text" v-model="form.identifier"
                    class="w-full px-4 py-2 bg-gray-800 border border-gray-600 text-white rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none placeholder-gray-500"
                    placeholder="Unique Identifier" />
                <p v-if="form.errors.identifier" class="text-red-500 text-sm mt-1">{{ form.errors.identifier }}</p>
            </div>

            <div class="mb-5">
                <label class="block text-gray-300 font-semibold mb-2">Type</label>
                <select v-model="form.type"
                    class="w-full px-4 py-2 bg-gray-800 border border-gray-600 text-white rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                    <option value="0">Static</option>
                    <option value="1">Animated</option>
                </select>
                <p v-if="form.errors.type" class="text-red-500 text-sm mt-1">{{ form.errors.type }}</p>
            </div>

            <div class="mb-5">
                <label class="block text-gray-300 font-semibold mb-2">Source URL</label>
                <input type="text" v-model="form.link"
                    class="w-full px-4 py-2 bg-gray-800 border border-gray-600 text-white rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none placeholder-gray-500"
                    placeholder="Sticker source url" />
                <p v-if="form.errors.link" class="text-red-500 text-sm mt-1">{{ form.errors.link }}</p>
            </div>

            <div class="mb-5">
                <label class="block text-gray-300 font-semibold mb-2">Status</label>
                <select v-model="form.status"
                    class="w-full px-4 py-2 bg-gray-800 border border-gray-600 text-white rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
                <p v-if="form.errors.status" class="text-red-500 text-sm mt-1">{{ form.errors.status }}</p>
            </div>

            <div class="mb-6">
                <label class="block text-gray-300 font-semibold mb-2">Sticker</label>
                <div class="bg-gray-800 border border-gray-700 flex items-center justify-center h-36 rounded-lg">
                    <img :src="previewImage || `/images/stickersCategory/${form.image}`" alt="Sticker preview"
                        class="max-h-28 object-contain transition-transform duration-300 hover:scale-110" />
                </div>

                <label
                    class="flex flex-col items-center justify-center mt-3 w-full px-4 py-6 bg-gray-800 border border-gray-600 rounded-lg shadow cursor-pointer hover:bg-gray-700 transition">
                    <span class="text-indigo-400 font-semibold mb-1">Upload a new sticker for the category</span>
                    <span class="text-xs text-gray-400 mb-1">WebP 96 x 96 • Max 50KB</span>
                    <input type="file" accept="image/webp" class="hidden" @change="handleImageUpload" />
                    <p v-if="form.resized_image" class="text-green-400 text-sm mt-2">Ready to upload: {{ form.resized_image.name }}</p>
                    <p v-if="form.errors.resized_image" class="text-red-500 text-sm mt-1">{{ form.errors.resized_image }}</p>
                </label>
            </div>

            <div class="flex justify-center mt-8">
                <button type="submit"
                    class="bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white font-semibold px-6 py-2 rounded-lg shadow-lg transition duration-300"
                    :disabled="form.processing">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</template>
