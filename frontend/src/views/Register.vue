<script setup>
import { ref } from 'vue'
import { useAuthStore } from '../stores/auth'

const auth = useAuthStore()
const form = ref({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
  role: 'applicant'
})

const handleRegister = async () => {
    if (form.value.password !== form.value.password_confirmation) {
        auth.error = "Passwords do not match";
        return;
    }
    await auth.register(form.value)
}
</script>

<template>
  <div class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="w-full max-w-md p-8 bg-white rounded-lg shadow-md">
      <h2 class="mb-6 text-2xl font-bold text-center text-gray-800">Register</h2>
      
      <div v-if="auth.error" class="p-3 mb-4 text-sm text-red-700 bg-red-100 rounded">
        {{ auth.error }}
      </div>

      <form @submit.prevent="handleRegister" class="space-y-4">
        <div>
          <label class="block mb-1 text-sm font-medium text-gray-600">Full Name</label>
          <input v-model="form.name" type="text" required 
            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
        </div>
        <div>
          <label class="block mb-1 text-sm font-medium text-gray-600">Email</label>
          <input v-model="form.email" type="email" required 
            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
        </div>
        <div>
           <label class="block mb-1 text-sm font-medium text-gray-600">Role</label>
           <select v-model="form.role" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
               <option value="applicant">Applicant</option>
               <option value="employer">Employer</option>
           </select>
        </div>
        <div>
          <label class="block mb-1 text-sm font-medium text-gray-600">Password</label>
          <input v-model="form.password" type="password" required 
            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
        </div>
        <div>
          <label class="block mb-1 text-sm font-medium text-gray-600">Confirm Password</label>
          <input v-model="form.password_confirmation" type="password" required 
            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
        </div>
        <button type="submit" 
          class="w-full px-4 py-2 font-bold text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition">
          Register
        </button>
      </form>
      
      <p class="mt-4 text-sm text-center text-gray-600">
        Already have an account? <router-link to="/login" class="text-blue-600 hover:underline">Login</router-link>
      </p>
    </div>
  </div>
</template>
