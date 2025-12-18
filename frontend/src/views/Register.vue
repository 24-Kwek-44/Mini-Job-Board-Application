<script setup>
import { ref } from 'vue'
import { useAuthStore } from '../stores/auth'
import { UserIcon, EnvelopeIcon, LockClosedIcon, FingerPrintIcon } from '@heroicons/vue/24/outline'

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
  <div class="flex items-center justify-center min-h-screen bg-slate-50 relative overflow-hidden py-10">
    <!-- Decorative Background Blobs -->
    <div class="absolute top-0 left-0 w-96 h-96 bg-emerald-300 rounded-full mix-blend-multiply filter blur-3xl opacity-20 -translate-x-1/2 -translate-y-1/2 animate-blob"></div>
    <div class="absolute bottom-0 right-0 w-96 h-96 bg-teal-300 rounded-full mix-blend-multiply filter blur-3xl opacity-20 translate-x-1/2 translate-y-1/2 animate-blob animation-delay-2000"></div>

    <div class="w-full max-w-md p-8 bg-white/80 backdrop-blur-xl rounded-2xl shadow-xl border border-white/50 relative z-10 transition-all duration-300 hover:shadow-2xl">
      <div class="text-center mb-6">
        <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">Create Account</h2>
        <p class="text-slate-500 mt-2">Join us to find your dream job</p>
      </div>
      
      <div v-if="auth.error" class="p-4 mb-6 text-sm text-red-700 bg-red-50 border border-red-100 rounded-xl flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
        {{ auth.error }}
      </div>

      <form @submit.prevent="handleRegister" class="space-y-4">
        <div>
          <label class="block mb-1.5 text-sm font-semibold text-slate-700">Full Name</label>
          <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <UserIcon class="h-5 w-5 text-slate-400" />
            </div>
            <input v-model="form.name" type="text" required 
               class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-900 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-200 placeholder-slate-400"
               placeholder="John Doe" />
          </div>
        </div>

        <div>
           <label class="block mb-1.5 text-sm font-semibold text-slate-700">I want to...</label>
           <div class="grid grid-cols-2 gap-3">
               <label class="cursor-pointer relative">
                   <input type="radio" v-model="form.role" value="applicant" class="peer sr-only" />
                   <div class="p-3 text-center rounded-xl border-2 border-slate-200 peer-checked:border-emerald-500 peer-checked:bg-emerald-50 transition-all hover:bg-slate-50">
                       <span class="block text-sm font-bold text-slate-700 peer-checked:text-emerald-700">Find a Job</span>
                   </div>
               </label>
               <label class="cursor-pointer relative">
                   <input type="radio" v-model="form.role" value="employer" class="peer sr-only" />
                   <div class="p-3 text-center rounded-xl border-2 border-slate-200 peer-checked:border-indigo-500 peer-checked:bg-indigo-50 transition-all hover:bg-slate-50">
                       <span class="block text-sm font-bold text-slate-700 peer-checked:text-indigo-700">Post Jobs</span>
                   </div>
               </label>
           </div>
        </div>

        <div>
          <label class="block mb-1.5 text-sm font-semibold text-slate-700">Email</label>
          <div class="relative">
             <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <EnvelopeIcon class="h-5 w-5 text-slate-400" />
            </div>
            <input v-model="form.email" type="email" required 
               class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-900 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-200 placeholder-slate-400"
               placeholder="john@example.com" />
          </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block mb-1.5 text-sm font-semibold text-slate-700">Password</label>
               <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <LockClosedIcon class="h-5 w-5 text-slate-400" />
                </div>
                <input v-model="form.password" type="password" required 
                   class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-900 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-200 placeholder-slate-400"
                   placeholder="••••••••" />
              </div>
            </div>
            <div>
              <label class="block mb-1.5 text-sm font-semibold text-slate-700">Confirm</label>
               <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <FingerPrintIcon class="h-5 w-5 text-slate-400" />
                </div>
                <input v-model="form.password_confirmation" type="password" required 
                   class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-900 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-200 placeholder-slate-400"
                   placeholder="••••••••" />
              </div>
            </div>
        </div>

        <button type="submit" 
          class="w-full px-4 py-3.5 font-bold text-white bg-gradient-to-r from-emerald-600 to-teal-600 rounded-xl hover:from-emerald-700 hover:to-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 shadow-lg shadow-emerald-200 transform hover:-translate-y-0.5 transition-all duration-200 mt-4">
          Create Account
        </button>
      </form>
      
      <p class="mt-6 text-sm text-center text-slate-600">
        Already have an account? <router-link to="/login" class="font-semibold text-emerald-600 hover:text-emerald-500 hover:underline">Log In</router-link>
      </p>
    </div>
  </div>
</template>
