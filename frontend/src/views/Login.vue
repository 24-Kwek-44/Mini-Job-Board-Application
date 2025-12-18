<script setup>
import { ref } from 'vue'
import { useAuthStore } from '../stores/auth'

const auth = useAuthStore()
const email = ref('')
const password = ref('')

const handleLogin = async () => {
  await auth.login({ email: email.value, password: password.value })
}
</script>

<template>
  <div class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="w-full max-w-md p-8 bg-white rounded-lg shadow-md">
      <h2 class="mb-6 text-2xl font-bold text-center text-gray-800">Login to Job Board</h2>
      
      <div v-if="auth.error" class="p-3 mb-4 text-sm text-red-700 bg-red-100 rounded">
        {{ auth.error }}
      </div>

      <form @submit.prevent="handleLogin" class="space-y-4">
        <div>
          <label class="block mb-1 text-sm font-medium text-gray-600">Email</label>
          <input v-model="email" type="email" required 
            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
        </div>
        <div>
          <label class="block mb-1 text-sm font-medium text-gray-600">Password</label>
          <input v-model="password" type="password" required 
            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
        </div>
        <button type="submit" 
          class="w-full px-4 py-2 font-bold text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition">
          Login
        </button>
      </form>
      
      <p class="mt-4 text-sm text-center text-gray-600">
        Don't have an account? <router-link to="/register" class="text-blue-600 hover:underline">Register</router-link>
      </p>
    </div>
  </div>
</template>
