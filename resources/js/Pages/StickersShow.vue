<script setup>
import { Link, router } from '@inertiajs/vue3';

defineProps({
  category: String,
  stickers: Array,
  category_id: Number,
  type: String,
});

function confirmAndDelete(id) {
  if (confirm('Are you sure you want to delete this sticker?')) {
    router.visit(route('stickers.delete.index'), {
      method: 'get',
      data: { id },
      preserveScroll: true,
      preserveState: true,
    });
  }
}
</script>

<template>
  <Head :title="`${category} Page | `" />
  <div class="min-h-screen bg-gradient-to-br from-[#0f0c29] via-[#302b63] to-[#24243e] p-6 text-white flex flex-col items-center">
    <h1 class="text-4xl font-extrabold mb-10 text-center drop-shadow-xl">
      Explore <span class="underline underline-offset-4 decoration-purple-400">{{ category }}</span> Stickers
    </h1>

    <!-- Add Sticker Button -->
    <div class="text-center mb-10">
      <Link
        :href="route('stickers.create.index')"
        method="GET"
        :data="{ category_id: category_id, title: category, type: type }"
        class="inline-block bg-gradient-to-r from-purple-600 to-pink-500 text-white px-6 py-3 rounded-full font-semibold hover:from-purple-700 hover:to-pink-600 transition-all duration-300 shadow-md hover:scale-105"
      >
        Add New Sticker of {{ category }}
      </Link>
    </div>

    <!-- Sticker Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8 w-full max-w-7xl">
      <div
        v-for="sticker in stickers"
        :key="sticker.id"
        class="bg-white/10 backdrop-blur-md text-white rounded-2xl shadow-xl hover:shadow-2xl transform hover:-translate-y-1 transition-all duration-300 overflow-hidden border border-white/10"
      >
        <!-- Sticker Image -->
        <div class="relative bg-white/10 flex items-center justify-center h-40">
          <img
            :src="`/images/stickers/${sticker.image}`"
            :alt="sticker.title"
            class="max-h-32 object-contain transition-transform duration-300 hover:scale-110"
          />

          <!-- Status Badge -->
          <span
            class="absolute top-2 left-2 text-xs font-semibold px-3 py-1 rounded-full shadow"
            :class="sticker.status === 'active'
              ? 'bg-green-500/20 text-green-300'
              : 'bg-red-500/20 text-red-300'"
          >
            {{ sticker.status }}
          </span>
        </div>

        <!-- Card Content -->
        <div class="p-4 flex flex-col gap-3">
          <h2 class="font-semibold text-lg truncate text-white">{{ sticker.title }}</h2>

          <div class="flex items-center gap-2">
            <Link
              :href="route('stickers.edit.index')"
              method="GET"
              :data="{ title: sticker.type, id: sticker.id, image: sticker.image }"
              class="flex-1 text-center bg-yellow-500 text-white px-4 py-2 rounded-full font-medium hover:bg-yellow-600 transition"
            >
              Edit
            </Link>

            <button
              @click.prevent="confirmAndDelete(sticker.id)"
              class="flex-1 text-center bg-red-600 text-white px-4 py-2 rounded-full font-medium hover:bg-red-700 transition"
            >
              Delete
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
