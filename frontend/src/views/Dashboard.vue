<script setup>
import { onMounted } from 'vue'
import { useAuthStore } from '../stores/auth'
import { useRouter } from 'vue-router'
import JobBoard from '../components/JobBoard.vue'
import MyJobs from '../components/MyJobs.vue'
import { ArrowRightStartOnRectangleIcon, BriefcaseIcon, UserCircleIcon } from '@heroicons/vue/24/outline'

const auth = useAuthStore()
const router = useRouter()

onMounted(() => {
    auth.fetchUser()
})

const handleLogout = () => {
    auth.logout()
}
</script>

<template>
    <div class="min-h-screen bg-slate-50 font-sans text-slate-800">
        <!-- Professional Navbar -->
        <nav class="bg-white border-b border-slate-200 shadow-sm sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center gap-3">
                        <div class="flex-shrink-0 flex items-center justify-center h-10 w-10 rounded-xl bg-indigo-600 text-white shadow-lg shadow-indigo-200">
                             <BriefcaseIcon class="h-6 w-6" />
                        </div>
                        <h1 class="text-xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-indigo-600 to-violet-600">
                            JobSphere
                        </h1>
                    </div>
                    <div class="flex items-center space-x-6">
                        <div class="flex items-center gap-2 text-slate-600 bg-slate-100 px-3 py-1.5 rounded-full border border-slate-200">
                            <UserCircleIcon class="h-5 w-5 text-slate-400" />
                            <span class="text-sm font-medium">{{ auth.user?.name }}</span>
                            <span class="text-xs uppercase font-bold tracking-wider text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-md border border-indigo-100">
                                {{ auth.user?.role }}
                            </span>
                        </div>
                        <button @click="handleLogout" 
                            class="flex items-center gap-2 text-sm font-medium text-slate-500 hover:text-red-600 transition-colors duration-200 group">
                            <span>Logout</span>
                            <ArrowRightStartOnRectangleIcon class="h-5 w-5 group-hover:translate-x-1 transition-transform" />
                        </button>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content Area with Fade-in Animation -->
        <main class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8 animate-fade-in-up">
            <div v-if="auth.isEmployer">
                <MyJobs />
            </div>
            <div v-else>
                <JobBoard />
            </div>
        </main>
    </div>
</template>

<style>
@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}
.animate-fade-in-up {
    animation: fadeInUp 0.5s ease-out forwards;
}
</style>
