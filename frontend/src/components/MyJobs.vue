<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import { useAuthStore } from '../stores/auth'
import { useNotificationStore } from '../stores/notification'
import ApplicationsList from './ApplicationsList.vue'
import { PlusIcon, PencilSquareIcon, TrashIcon, DocumentDuplicateIcon } from '@heroicons/vue/24/outline'

const auth = useAuthStore()
const notification = useNotificationStore()
const jobs = ref([])
const pagination = ref({})
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

const fetchJobs = async (page = 1) => {
    loading.value = true
    try {
        const { data } = await axios.get(`http://localhost:8000/api/my-jobs?page=${page}`, {
            headers: { Authorization: `Bearer ${auth.token}` }
        })
        jobs.value = data.data
        pagination.value = {
            current_page: data.current_page,
            last_page: data.last_page,
            prev_page_url: data.prev_page_url,
            next_page_url: data.next_page_url
        }
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
        notification.notify(editingJob.value ? 'Job updated successfully' : 'Job posted successfully', 'success')
    } catch (e) {
        notification.notify('Error: ' + (e.response?.data?.message || e.message), 'error')
    }
}

const deleteJob = async (id) => {
    if (!confirm('Are you sure you want to delete this job?')) return
    try {
        await axios.delete(`http://localhost:8000/api/jobs/${id}`, {
            headers: { Authorization: `Bearer ${auth.token}` }
        })
        fetchJobs()
        notification.notify('Job deleted successfully', 'success')
    } catch (e) {
        notification.notify('Error deleting job', 'error')
    }
}

const viewApplications = (jobId) => {
    viewingApplicationsJobId.value = jobId
}

onMounted(fetchJobs)
</script>

<template>
    <div>
        <div class="flex flex-col sm:flex-row justify-between items-center mb-8 gap-4">
            <div>
                 <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">Job Listings</h2>
                 <p class="text-slate-500 mt-1">Manage your active job posts and applications.</p>
            </div>
            
            <button @click="openCreate" class="inline-flex items-center px-5 py-2.5 bg-indigo-600 text-white font-medium rounded-xl hover:bg-indigo-700 transition shadow-lg shadow-indigo-200">
                <PlusIcon class="h-5 w-5 mr-2" />
                Post New Job
            </button>
        </div>

        <div v-if="loading" class="flex justify-center py-20">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600"></div>
        </div>

        <div v-else class="grid gap-4">
            <div v-if="jobs.length === 0" class="text-center py-16 bg-white rounded-2xl border border-dashed border-slate-300">
                 <p class="text-slate-500 text-lg">You haven't posted any jobs yet.</p>
                 <button @click="openCreate" class="mt-4 text-indigo-600 font-medium hover:underline">Create your first job</button>
            </div>

            <div v-for="job in jobs" :key="job.id" class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm flex flex-col md:flex-row justify-between items-start md:items-center gap-4 hover:border-indigo-200 transition-colors">
                <div>
                     <div class="flex items-center gap-3">
                        <h3 class="text-xl font-bold text-slate-900">{{ job.title }}</h3>
                        <span :class="[
                            'px-2.5 py-0.5 rounded-full text-xs font-medium border',
                            job.status === 'open' ? 'bg-green-100 text-green-800 border-green-200' : 'bg-slate-100 text-slate-600 border-slate-200'
                        ]">
                            {{ job.status }}
                        </span>
                     </div>
                     <p class="text-slate-500 mt-1 flex items-center gap-2 text-sm">
                         {{ job.location }} <span class="text-slate-300">•</span> {{ job.salary_range }}
                         <span v-if="job.is_remote">(Remote)</span>
                     </p>
                </div>
                
                <div class="flex items-center gap-2 w-full md:w-auto">
                    <button @click="viewApplications(job.id)" class="flex-1 md:flex-none inline-flex justify-center items-center px-3 py-2 bg-indigo-50 text-indigo-700 font-medium rounded-lg text-sm hover:bg-indigo-100 transition border border-indigo-100">
                        <DocumentDuplicateIcon class="h-4 w-4 mr-2" />
                        Applications
                    </button>
                    <button @click="openEdit(job)" class="p-2 text-slate-400 hover:text-amber-500 hover:bg-amber-50 rounded-lg transition" title="Edit">
                        <PencilSquareIcon class="h-5 w-5" />
                    </button>
                    <button @click="deleteJob(job.id)" class="p-2 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition" title="Delete">
                        <TrashIcon class="h-5 w-5" />
                    </button>
                </div>
            </div>
        </div>

        <!-- Pagination Controls -->
        <div v-if="pagination.last_page > 1" class="flex justify-center items-center gap-4 mt-8">
            <button 
                @click="fetchJobs(pagination.current_page - 1)" 
                :disabled="!pagination.prev_page_url"
                class="px-4 py-2 border border-slate-300 rounded-lg text-sm font-medium text-slate-700 bg-white hover:bg-slate-50 disabled:opacity-50 disabled:cursor-not-allowed">
                Previous
            </button>
            <span class="text-sm text-slate-700">
                Page <span class="font-medium">{{ pagination.current_page }}</span> of <span class="font-medium">{{ pagination.last_page }}</span>
            </span>
            <button 
                @click="fetchJobs(pagination.current_page + 1)" 
                :disabled="!pagination.next_page_url"
                class="px-4 py-2 border border-slate-300 rounded-lg text-sm font-medium text-slate-700 bg-white hover:bg-slate-50 disabled:opacity-50 disabled:cursor-not-allowed">
                Next
            </button>
        </div>

        <!-- Form Modal -->
        <Teleport to="body">
            <div v-if="showForm" class="fixed inset-0 z-[100] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <div class="fixed inset-0 bg-slate-500/75 transition-opacity" aria-hidden="true" @click="resetForm"></div>
                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                    <div class="relative z-50 inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                                    <h3 class="text-lg leading-6 font-bold text-slate-900 mb-6" id="modal-title">
                                        {{ editingJob ? 'Edit Job' : 'Post New Job' }}
                                    </h3>
                                    <form @submit.prevent="submitJob" class="space-y-4">
                                        <input v-model="form.title" placeholder="Job Title" required 
                                            class="w-full px-4 py-2 border border-slate-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                                        <textarea v-model="form.description" placeholder="Description" rows="3" required 
                                            class="w-full px-4 py-2 border border-slate-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500"></textarea>
                                        <div class="grid grid-cols-2 gap-4">
                                            <input v-model="form.location" placeholder="Location" required 
                                                class="w-full px-4 py-2 border border-slate-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                                            <input v-model="form.salary_range" placeholder="Salary Range" required 
                                                class="w-full px-4 py-2 border border-slate-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                                        </div>
                                        <label class="flex items-center space-x-3 p-3 border border-slate-200 rounded-xl cursor-pointer hover:bg-slate-50">
                                            <input type="checkbox" v-model="form.is_remote" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded" />
                                            <span class="text-sm font-medium text-slate-700">Remote Position</span>
                                        </label>
                                        <div class="flex justify-end gap-3 mt-6">
                                            <button type="button" @click="resetForm" class="px-4 py-2 text-slate-700 bg-white border border-slate-300 hover:bg-slate-50 rounded-lg font-medium">Cancel</button>
                                            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg font-medium hover:bg-indigo-700">Save Job</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </Teleport>

        <!-- Applications Modal -->
        <Teleport to="body">
            <div v-if="viewingApplicationsJobId" class="fixed inset-0 z-[100] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <div class="fixed inset-0 bg-slate-500/75 transition-opacity" aria-hidden="true" @click="viewingApplicationsJobId = null"></div>
                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                    <div class="relative z-50 inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full">
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-medium text-slate-900">Job Applications</h3>
                                <button @click="viewingApplicationsJobId = null" class="text-slate-400 hover:text-slate-500">
                                    <span class="sr-only">Close</span>
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                            <ApplicationsList :jobId="viewingApplicationsJobId" />
                        </div>
                    </div>
                </div>
            </div>
        </Teleport>
    </div>
</template>
