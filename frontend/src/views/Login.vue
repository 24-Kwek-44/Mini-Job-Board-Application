<script setup>
import { ref } from 'vue'
import { useAuthStore } from '../stores/auth'
import { EnvelopeIcon, LockClosedIcon } from '@heroicons/vue/24/outline'

const auth = useAuthStore()
const email = ref('')
const password = ref('')

const handleLogin = async () => {
  await auth.login({ email: email.value, password: password.value })
}
</script>

<template>
  <div class="flex items-center justify-center min-h-screen bg-slate-50 relative overflow-hidden">
    <!-- Decorative Background Blobs -->
    <div class="absolute top-0 left-0 w-96 h-96 bg-indigo-300 rounded-full mix-blend-multiply filter blur-3xl opacity-20 -translate-x-1/2 -translate-y-1/2 animate-blob"></div>
    <div class="absolute bottom-0 right-0 w-96 h-96 bg-violet-300 rounded-full mix-blend-multiply filter blur-3xl opacity-20 translate-x-1/2 translate-y-1/2 animate-blob animation-delay-2000"></div>

    <div class="w-full max-w-md p-8 bg-white/80 backdrop-blur-xl rounded-2xl shadow-xl border border-white/50 relative z-10 transition-all duration-300 hover:shadow-2xl">
      <div class="text-center mb-8">
        <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">Welcome Back</h2>
        <p class="text-slate-500 mt-2">Sign in to access your dashboard</p>
      </div>
      
      <div v-if="auth.error" class="p-4 mb-6 text-sm text-red-700 bg-red-50 border border-red-100 rounded-xl flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
        {{ auth.error }}
      </div>

      <form @submit.prevent="handleLogin" class="space-y-6">
        <div>
          <label class="block mb-2 text-sm font-semibold text-slate-700">Email Address</label>
          <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <EnvelopeIcon class="h-5 w-5 text-slate-400" />
            </div>
            <input v-model="email" type="email" required 
              class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 text-slate-900 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 placeholder-slate-400"
              placeholder="you@example.com" />
          </div>
        </div>
        <div>
          <label class="block mb-2 text-sm font-semibold text-slate-700">Password</label>
          <div class="relative">
             <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <LockClosedIcon class="h-5 w-5 text-slate-400" />
            </div>
            <input v-model="password" type="password" required 
              class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 text-slate-900 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 placeholder-slate-400"
              placeholder="••••••••" />
          </div>
        </div>
        <button type="submit" 
          class="w-full px-4 py-3.5 font-bold text-white bg-gradient-to-r from-indigo-600 to-violet-600 rounded-xl hover:from-indigo-700 hover:to-violet-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 shadow-lg shadow-indigo-200 transform hover:-translate-y-0.5 transition-all duration-200">
          Sign In
        </button>
      </form>
      
      <p class="mt-8 text-sm text-center text-slate-600">
        Don't have an account? <router-link to="/register" class="font-semibold text-indigo-600 hover:text-indigo-500 hover:underline">Create Account</router-link>
      </p>
    </div>
  </div>
</template>

<style>
@keyframes blob {
  0% { transform: translate(0px, 0px) scale(1); }
  33% { transform: translate(30px, -50px) scale(1.1); }
  66% { transform: translate(-20px, 20px) scale(0.9); }
  100% { transform: translate(0px, 0px) scale(1); }
}
.animate-blob {
  animation: blob 7s infinite;
}
.animation-delay-2000 {
  animation-delay: 2s;
}
</style>
