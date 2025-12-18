<script setup>
import { RouterView } from 'vue-router'
import NotificationToast from './components/NotificationToast.vue'
</script>

<template>
  <div class="min-h-screen bg-gray-50 text-gray-900 font-sans">
    <NotificationToast />
    <RouterView />
  </div>
</template>
