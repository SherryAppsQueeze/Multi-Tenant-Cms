<template>
  <Head :title="`Create Post | ${categoryName}`" />
  <div
    class="min-h-screen bg-gradient-to-br from-[#0f0c29] via-[#302b63] to-[#24243e] p-6 flex items-center justify-center"
  >
    <div
      class="w-full max-w-3xl bg-white/10 backdrop-blur-md text-white rounded-2xl shadow-2xl p-8 border border-white/10"
    >
      <h2 class="text-3xl font-bold text-center mb-8">
        ✏️ Create New Post in {{ categoryName }}
      </h2>

      <form @submit.prevent="submit" class="space-y-6">
        <!-- Title -->
        <div>
          <label class="block text-sm font-medium mb-2">Post Title</label>
          <input
            type="text"
            v-model="form.title"
            placeholder="Enter post title"
            class="w-full bg-white/5 border border-white/20 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500"
          />
          <p v-if="form.errors.title" class="text-red-400 text-sm mt-1">
            {{ form.errors.title }}
          </p>
        </div>

        <!-- Content -->
        <div>
          <label class="block text-sm font-medium mb-2">Post Content</label>
          <textarea
            v-model="form.content"
            rows="6"
            placeholder="Write your post content here..."
            class="w-full bg-white/5 border border-white/20 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500"
          ></textarea>
          <p v-if="form.errors.content" class="text-red-400 text-sm mt-1">
            {{ form.errors.content }}
          </p>
        </div>

        <!-- Featured Image -->
        <div>
          <label class="block text-sm font-medium mb-2">Featured Image</label>
          <input
            type="file"
            accept="image/*"
            @change="handleImageUpload"
            class="w-full text-sm text-gray-300 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-purple-600 file:text-white hover:file:bg-purple-700"
          />
          <p v-if="form.errors.featured_image" class="text-red-400 text-sm mt-1">
            {{ form.errors.featured_image }}
          </p>

          <!-- Image Preview -->
          <div v-if="previewImage" class="mt-4">
            <img
              :src="previewImage"
              alt="Preview"
              class="w-full max-h-60 object-cover rounded-lg shadow-lg border border-white/20"
            />
          </div>
        </div>

        <!-- Hidden Category ID -->
        <input type="hidden" v-model="form.category_id" />

        <!-- Submit Button -->
        <div class="flex justify-center pt-4">
          <button
            type="submit"
            class="bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-semibold px-6 py-2 rounded-lg shadow-md transition-all duration-300"
            :disabled="form.processing"
          >
            Create Post
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref } from "vue";
import { useForm } from "@inertiajs/vue3";

const props = defineProps({
  flash: Object,
  categoryId: Number,
  categoryName: String,
});

const previewImage = ref(null);

const form = useForm({
  title: "",
  content: "",
  featured_image: null,
  category_id: props.categoryId,
});

function handleImageUpload(e) {
  const file = e.target.files[0];
  if (file) {
    form.featured_image = file;
    previewImage.value = URL.createObjectURL(file);
  }
}

function submit() {
  form.post(route("posts.store"), {
    onSuccess: () => {
      form.reset();
      previewImage.value = null;
      featured_image.value = null;
    },
    preserveScroll: true,
    preserveState: true,
  });
}
</script>
