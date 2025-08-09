<template>
    <footer class="text-center p-4 bg-gradient-to-r from-[#1f1f47] via-[#2c2c54] to-[#1f1f47] text-sm text-gray-200 border-t border-indigo-700 shadow-inner">
        © {{ currentYear }} Multi-Tenant CMS Powered by Laravel REST API
    </footer>
</template>

<script setup>
const currentYear = new Date().getFullYear()
</script>
