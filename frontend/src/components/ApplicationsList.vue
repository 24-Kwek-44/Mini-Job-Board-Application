<script setup>
import { ref, onMounted, watch } from 'vue'
import axios from 'axios'
import { useAuthStore } from '../stores/auth'

const props = defineProps({
    jobId: { type: Number, required: true }
})

const applications = ref([])
const loading = ref(false)
const auth = useAuthStore()

const fetchApplications = async () => {
    loading.value = true
    try {
        const { data } = await axios.get(`http://localhost:8000/api/jobs/${props.jobId}/applications`, {
            headers: { Authorization: `Bearer ${auth.token}` }
        })
        applications.value = data
    } catch (e) {
        console.error(e)
    } finally {
        loading.value = false
    }
}

watch(() => props.jobId, fetchApplications)
onMounted(fetchApplications)
</script>

<template>
    <div>
        <h3 class="text-lg font-bold mb-2">Applications</h3>
        <div v-if="loading">Loading...</div>
        <div v-else-if="applications.length === 0">No applications yet.</div>
        <div v-else class="space-y-2">
            <div v-for="app in applications" :key="app.id" class="p-2 border rounded bg-gray-50">
                <p class="font-semibold">{{ app.applicant.name }} ({{ app.applicant.email }})</p>
                <p class="text-gray-700 mt-1">{{ app.message }}</p>
                <a v-if="app.resume_path" :href="`http://localhost:8000/storage/${app.resume_path}`" target="_blank" class="text-blue-600 text-sm hover:underline">View Resume</a>
            </div>
        </div>
    </div>
</template>
