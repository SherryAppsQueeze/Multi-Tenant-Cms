<template>

    <Head :title="`My App | ${appName}`" />
    <div class="min-h-screen flex flex-col">
        <Navbar :logged-in-user="loggedInUser" />

        <main class="flex-1 bg-gradient-to-br from-[#0f0c29] via-[#302b63] to-[#24243e]">


            <div class="mt-3 text-slate-100" id="flash-messages">
                <!-- Flash Message -->
                <transition name="fade" mode="out-in">
                    <div v-if="visible && flash?.success"
                        class="bg-green-500/10 text-green-300 px-4 py-3 rounded-lg mb-6 max-w-xl mx-auto shadow flex items-center gap-2 animate-fade-in-down">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-400 flex-shrink-0" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>{{ flash.success }}</span>
                    </div>
                </transition>

                <transition name="fade" mode="out-in">
                    <div v-if="visible && flash?.error"
                        class="bg-red-500/10 text-red-300 px-4 py-3 rounded-lg mb-6 max-w-xl mx-auto shadow flex items-center gap-2 animate-fade-in-down">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-400 flex-shrink-0" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        <span>{{ flash.error || 'An error occurred. Please try again.' }}</span>
                    </div>
                </transition>

            </div>
            <slot />
        </main>

        <Footer />
    </div>
</template>

<script setup>
import { ref, watch } from 'vue';
import Navbar from './Navbar.vue'
import Footer from './Footer.vue'



const visible = ref(true);
const props = defineProps({
    errors: Object,
    flash: Object,
    appName: String,
    loggedInUser: Object,
});

// Watch flash to reset visibility and auto-hide
watch(() => props.flash, (newFlash) => {
    if (newFlash?.success || newFlash?.error) {
        visible.value = true;
        setTimeout(() => {
            visible.value = false;
        }, 2000);
    }
}, { immediate: true });

</script>

<style>
/* Smooth fade-in/out transition */
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.5s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}

/* Optional: subtle down-slide */
@keyframes fade-in-down {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in-down {
    animation: fade-in-down 0.4s ease-out;
}
</style>
