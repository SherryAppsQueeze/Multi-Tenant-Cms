<script setup>
import { useForm } from '@inertiajs/vue3';

const form = useForm({
    name: null,
});

// Submit the form
function submit() {
    form.post(route('category.create'), {
        onSuccess: () => form.reset(),
        preserveScroll: true,
    });
}
</script>


<template>

    <Head :title="`Category Create Page | `" />
    <div
        class="min-h-screen bg-gradient-to-br from-[#0f0c29] via-[#302b63] to-[#24243e] p-6 flex items-center justify-center">
        <div
            class="w-full max-w-2xl bg-white/10 backdrop-blur-md text-white rounded-2xl shadow-2xl p-8 border border-white/10">
            <h2 class="text-3xl font-bold text-center mb-8">Create Category</h2>

            <form @submit.prevent="submit" class="space-y-6">

                <!-- Title -->
                <div>
                    <label class="block text-sm font-medium mb-2">Name</label>
                    <input type="text" v-model="form.name" placeholder="Category Name"
                        class="w-full bg-white/5 border border-white/20 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                    <p v-if="form.errors.name" class="text-red-400 text-sm mt-1">{{ form.errors.name }}</p>
                </div>
                <div v-if="Object.keys(form.errors).length" class="mb-4">
                    <ul class="text-red-400 text-sm list-disc pl-5">
                        <li v-for="(message, field) in form.errors" :key="field">{{ message }}</li>
                    </ul>
                </div>


                <!-- Submit Button -->
                <div class="flex justify-center pt-4">
                    <button type="submit"
                        class="bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-semibold px-6 py-2 rounded-lg shadow-md transition-all duration-300"
                        :disabled="form.processing">
                        Create Category
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>
