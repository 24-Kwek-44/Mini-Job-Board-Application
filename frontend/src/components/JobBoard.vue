<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import { useAuthStore } from '../stores/auth'

const jobs = ref([])
const loading = ref(false)
const applyModalOpen = ref(false)
const selectedJob = ref(null)
const applicationMessage = ref('')
const resumeFile = ref(null)
const auth = useAuthStore()

const fetchJobs = async () => {
    loading.value = true
    try {
        const { data } = await axios.get('http://localhost:8000/api/jobs')
        jobs.value = data
    } catch (e) {
        console.error(e)
    } finally {
        loading.value = false
    }
}

const openApplyModal = (job) => {
    selectedJob.value = job
    applyModalOpen.value = true
}

const handleFileChange = (e) => {
    resumeFile.value = e.target.files[0]
}

const submitApplication = async () => {
    if (!selectedJob.value) return
    
    const formData = new FormData()
    formData.append('job_id', selectedJob.value.id)
    formData.append('message', applicationMessage.value)
    if (resumeFile.value) {
        formData.append('resume', resumeFile.value)
    }

    try {
        await axios.post('http://localhost:8000/api/applications', formData, {
            headers: { 
                Authorization: `Bearer ${auth.token}`,
                'Content-Type': 'multipart/form-data'
            }
        })
        alert('Application submitted successfully!')
        applyModalOpen.value = false
        applicationMessage.value = ''
        resumeFile.value = null
    } catch (e) {
        alert('Failed to apply: ' + (e.response?.data?.message || e.message))
    }
}

onMounted(fetchJobs)
</script>

<template>
    <div>
        <h2 class="text-2xl font-bold mb-4">Available Jobs</h2>
        <div v-if="loading" class="text-gray-600">Loading jobs...</div>
        <div v-else class="grid gap-4">
            <div v-for="job in jobs" :key="job.id" class="p-4 bg-white border rounded shadow">
                <h3 class="text-xl font-semibold">{{ job.title }}</h3>
                <p class="text-gray-600">{{ job.employer.name }} • {{ job.location }}</p>
                <div class="mt-2 text-sm text-gray-500">
                    <span class="mr-2 px-2 py-1 bg-gray-100 rounded">Salary: {{ job.salary_range }}</span>
                    <span v-if="job.is_remote" class="px-2 py-1 bg-green-100 text-green-800 rounded">Remote</span>
                </div>
                <p class="mt-2 text-gray-700">{{ job.description }}</p>
                <button v-if="auth.isAuthenticated && !auth.isEmployer" 
                    @click="openApplyModal(job)"
                    class="mt-4 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    Apply Now
                </button>
            </div>
        </div>

        <!-- Apply Modal -->
        <div v-if="applyModalOpen" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-lg p-6 w-full max-w-md">
                <h3 class="text-lg font-bold mb-4">Apply for {{ selectedJob.title }}</h3>
                <form @submit.prevent="submitApplication" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium">Message</label>
                        <textarea v-model="applicationMessage" required class="w-full border rounded p-2"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Resume (Optional)</label>
                        <input type="file" @change="handleFileChange" class="w-full border rounded p-2" />
                    </div>
                    <div class="flex justify-end gap-2">
                        <button type="button" @click="applyModalOpen = false" class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
