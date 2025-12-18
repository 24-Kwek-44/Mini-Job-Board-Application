<script setup>
import { onMounted } from 'vue'
import { useAuthStore } from '../stores/auth'
import { useRouter } from 'vue-router'
import JobBoard from '../components/JobBoard.vue'
import MyJobs from '../components/MyJobs.vue'

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
    <div class="min-h-screen bg-gray-50">
        <nav class="bg-white shadow">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <h1 class="text-xl font-bold text-gray-800">Mini Job Board</h1>
                    </div>
                    <div class="flex items-center space-x-4">
                        <span class="text-gray-600">Welcome, {{ auth.user?.name }} ({{ auth.user?.role }})</span>
                        <button @click="handleLogout" class="text-red-600 hover:text-red-800">Logout</button>
                    </div>
                </div>
            </div>
        </nav>

        <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            <div v-if="auth.isEmployer">
                <MyJobs />
            </div>
            <div v-else>
                <JobBoard />
            </div>
        </main>
    </div>
</template>
