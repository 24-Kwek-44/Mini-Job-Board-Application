<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import { useAuthStore } from '../stores/auth'
import ApplicationsList from './ApplicationsList.vue'

const auth = useAuthStore()
const jobs = ref([])
const showForm = ref(false)
const viewingApplicationsJobId = ref(null)
const loading = ref(false)
const editingJob = ref(null)

const form = ref({
    title: '',
    description: '',
    location: '',
    salary_range: '',
    is_remote: false
})

const fetchJobs = async () => {
    loading.value = true
    try {
        const { data } = await axios.get('http://localhost:8000/api/my-jobs', {
            headers: { Authorization: `Bearer ${auth.token}` }
        })
        jobs.value = data
    } catch (e) {
        console.error(e)
    } finally {
        loading.value = false
    }
}

const resetForm = () => {
    form.value = { title: '', description: '', location: '', salary_range: '', is_remote: false }
    editingJob.value = null
    showForm.value = false
}

const openCreate = () => {
    resetForm()
    showForm.value = true
}

const openEdit = (job) => {
    form.value = { ...job, is_remote: !!job.is_remote }
    editingJob.value = job
    showForm.value = true
}

const submitJob = async () => {
    try {
        const headers = { Authorization: `Bearer ${auth.token}` }
        if (editingJob.value) {
            await axios.put(`http://localhost:8000/api/jobs/${editingJob.value.id}`, form.value, { headers })
        } else {
            await axios.post('http://localhost:8000/api/jobs', form.value, { headers })
        }
        await fetchJobs()
        resetForm()
    } catch (e) {
        alert('Error: ' + (e.response?.data?.message || e.message))
    }
}

const deleteJob = async (id) => {
    if (!confirm('Are you sure?')) return
    try {
        await axios.delete(`http://localhost:8000/api/jobs/${id}`, {
            headers: { Authorization: `Bearer ${auth.token}` }
        })
        fetchJobs()
    } catch (e) {
        alert('Error deleting job')
    }
}

const viewApplications = (jobId) => {
    viewingApplicationsJobId.value = jobId
}

onMounted(fetchJobs)
</script>

<template>
    <div>
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-bold">My Jobs</h2>
            <button @click="openCreate" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Post New Job</button>
        </div>

        <!-- Form Modal -->
        <div v-if="showForm" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4">
             <div class="bg-white rounded-lg p-6 w-full max-w-lg overflow-y-auto max-h-[90vh]">
                <h3 class="text-lg font-bold mb-4">{{ editingJob ? 'Edit Job' : 'Post Job' }}</h3>
                <form @submit.prevent="submitJob" class="space-y-4">
                    <input v-model="form.title" placeholder="Job Title" required class="w-full border p-2 rounded" />
                    <textarea v-model="form.description" placeholder="Description" required class="w-full border p-2 rounded"></textarea>
                    <input v-model="form.location" placeholder="Location" required class="w-full border p-2 rounded" />
                    <input v-model="form.salary_range" placeholder="Salary Range" required class="w-full border p-2 rounded" />
                    <label class="flex items-center space-x-2">
                        <input type="checkbox" v-model="form.is_remote" />
                        <span>Remote Position</span>
                    </label>
                    <div class="flex justify-end gap-2">
                        <button type="button" @click="resetForm" class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Save</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- List -->
        <div class="grid gap-4">
            <div v-for="job in jobs" :key="job.id" class="p-4 bg-white border rounded shadow flex justify-between">
                <div>
                     <h3 class="text-xl font-semibold">{{ job.title }}</h3>
                     <p class="text-gray-600">{{ job.location }} ({{ job.status }})</p>
                </div>
                <div class="flex items-start space-x-2">
                    <button @click="viewApplications(job.id)" class="px-3 py-1 bg-indigo-100 text-indigo-700 rounded">Applications</button>
                    <button @click="openEdit(job)" class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded">Edit</button>
                    <button @click="deleteJob(job.id)" class="px-3 py-1 bg-red-100 text-red-700 rounded">Delete</button>
                </div>
            </div>
        </div>
        <!-- Applications Modal -->
        <div v-if="viewingApplicationsJobId" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4">
             <div class="bg-white rounded-lg p-6 w-full max-w-lg overflow-y-auto max-h-[90vh]">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold">Job Applications</h3>
                    <button @click="viewingApplicationsJobId = null" class="text-gray-500 hover:text-gray-700">✕</button>
                </div>
                <ApplicationsList :jobId="viewingApplicationsJobId" />
            </div>
        </div>
    </div>
</template>
