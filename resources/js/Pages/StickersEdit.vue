<template>
    <Head :title="`${props.sticker.status.charAt(0).toUpperCase()}${props.sticker.status.slice(1)} Sticker | `" />

    <div class="max-w-2xl mx-auto mt-5 mb-5 bg-gradient-to-br from-gray-900 to-gray-600 text-white rounded-2xl shadow-2xl p-8">
        <h2 class="text-2xl font-bold text-white text-center mb-6">Edit Sticker</h2>

        <form @submit.prevent="submit">
            <input type="hidden" v-model="form.id" />
            <input type="hidden" v-model="form.category_id" />

            <div v-for="(emoji, index) in editableEmojis" :key="emoji.id" class="relative mb-4">
                <input type="text" v-model="emoji.emoji"
                    class="bg-gray-800 border border-gray-700 text-white p-2 rounded w-full pr-10 focus:outline-none focus:ring-2 focus:ring-blue-600"
                    :data-id="emoji.id" />
                <p v-if="form.errors[`emojis.${index}.emoji`]" class="text-red-400 text-sm mt-1">
                    {{ form.errors[`emojis.${index}.emoji`] }}
                </p>

                <button type="button" @click="activeEmojiIndex = activeEmojiIndex === index ? null : index"
                    class="absolute right-2 top-2 text-xl text-white">
                    😊
                </button>

                <div v-if="activeEmojiIndex === index" class="absolute z-10 mt-2">
                    <EmojiPicker @select="emoji => selectEmoji(index, emoji)" />
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-gray-300 font-semibold mb-2">Source Url</label>
                <input type="text" v-model="form.link"
                    class="w-full px-4 py-2 bg-gray-800 border border-gray-700 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600"
                    placeholder="Sticker source url" />
                <p v-if="form.errors.link" class="text-red-400 text-sm mt-1">{{ form.errors.link }}</p>
            </div>

            <div class="mb-4 hidden">
                <label class="block text-gray-300 font-semibold mb-2">Type</label>
                <select v-model="form.type"
                    class="w-full px-4 py-2 bg-gray-800 border border-gray-700 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600">
                    <option value="static">Static</option>
                    <option value="animated">Animated</option>
                </select>
                <p v-if="form.errors.type" class="text-red-400 text-sm mt-1">{{ form.errors.type }}</p>
            </div>

            <div class="mb-4">
                <label class="block text-gray-300 font-semibold mb-2">Status</label>
                <select v-model="form.status"
                    class="w-full px-4 py-2 bg-gray-800 border border-gray-700 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
                <p v-if="form.errors.status" class="text-red-400 text-sm mt-1">{{ form.errors.status }}</p>
            </div>

            <div class="mb-4">
                <label class="block text-gray-300 font-semibold mb-2">Sticker</label>
                <div class="bg-gray-800 flex items-center justify-center h-36 rounded">
                    <img :src="previewImage || `/images/stickers/${form.image}`" alt="Sticker preview"
                        class="max-h-28 object-contain transition-transform duration-300 hover:scale-110" />
                </div>

                <label
                    class="flex flex-col items-center justify-center mt-2 w-full px-4 py-6 bg-gray-800 border border-gray-700 rounded-lg shadow cursor-pointer hover:bg-gray-700 transition">
                    <span class="text-blue-400 font-semibold mb-2">Upload a new sticker for the category</span>
                    <span class="text-xs text-gray-400 mb-2">
                        WEBP 512×512 pixels • Static max size: 100KB • Animated max size: 500KB
                    </span>
                    <input type="file" accept="image/webp" class="hidden" @change="handleImageUpload" />
                    <p v-if="form.resized_image" class="text-green-400 text-sm mt-2">
                        Image ready to upload: {{ form.resized_image.name }}
                    </p>
                    <p v-if="form.errors.resized_image" class="text-red-400 text-sm mt-1">
                        {{ form.errors.resized_image }}
                    </p>
                </label>
            </div>

            <div class="flex justify-center mt-6">
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg shadow-md transition duration-300"
                    :disabled="form.processing">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</template>



<script setup>
import { ref } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import EmojiPicker from 'vue3-emoji-picker';
import 'vue3-emoji-picker/css';

const props = defineProps({
    sticker: Object,
    emojis: Array,
});

const form = useForm({
    id: props.sticker.id,
    type: props.sticker.type == 'static' ? 'static' : 'animated',
    link: props.sticker.link || '',
    status: props.sticker.status == 'active' ? 'active' : 'inactive',
    image: props.sticker.image || '',
    resized_image: null,
    emojis: props.sticker.emojis || [],
    category_id: props.sticker.category_id || null,
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

    const isStatic = form.type === 'static';
    const maxSize = isStatic ? 100 * 1024 : 500 * 1024; // 100KB or 500KB

    const reader = new FileReader();
    reader.onload = (event) => {
        const img = new Image();
        img.onload = () => {
            const canvas = document.createElement('canvas');
            canvas.width = 512;
            canvas.height = 512;
            const ctx = canvas.getContext('2d');

            // Resize and center the image
            ctx.clearRect(0, 0, 512, 512);
            ctx.drawImage(img, 0, 0, 512, 512);

            // Try compressing (with decreasing quality)
            const tryCompress = (quality) => {
                canvas.toBlob((blob) => {
                    if (!blob) {
                        alert('Error processing image.');
                        return;
                    }

                    if (blob.size > maxSize) {
                        if (quality > 0.1) {
                            tryCompress(quality - 0.1); // try lower quality
                        } else {
                            alert(`Compressed image still exceeds ${isStatic ? '100KB' : '500KB'}. Please upload a smaller image.`);
                        }
                    } else {
                        form.resized_image = new File([blob], 'resized.webp', { type: 'image/webp' });
                        previewImage.value = URL.createObjectURL(blob);
                    }
                }, 'image/webp', quality);
            };

            tryCompress(1); // start with best quality
        };

        img.onerror = () => {
            alert('Invalid image.');
        };

        img.src = event.target.result;
    };

    reader.readAsDataURL(file);
}


// Submit the form
async function submit() {
    form.emojis = editableEmojis.value.map(e => ({
        id: e.id,
        emoji: e.emoji
    }));
    form.post(route('stickers.edit.sticker'), {

        onFinish: () => {
            if (props.flash.newImage) {
                form.image = props.flash.newImage
                previewImage.value = `/images/stickers/${props.flash.newImage}?t=${Date.now()}`
            } else {
                previewImage.value = null
            }
        },
        preserveScroll: true,
        preserveState: true,
    });
}

const editableEmojis = ref(props.emojis.map(e => ({ ...e })));
const activeEmojiIndex = ref(null);

function selectEmoji(index, emoji) {
    editableEmojis.value[index].emoji = emoji.i;
    activeEmojiIndex.value = null;
}

</script>
