<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import { useAuthStore } from '../stores/auth'
import { useNotificationStore } from '../stores/notification'
import { MapPinIcon, BanknotesIcon, BriefcaseIcon, CheckCircleIcon } from '@heroicons/vue/24/outline'

const jobs = ref([])
const pagination = ref({})
const loading = ref(false)
const applyModalOpen = ref(false)
const selectedJob = ref(null)
const applicationMessage = ref('')
const resumeFile = ref(null)
const auth = useAuthStore()
const notification = useNotificationStore()

const fetchJobs = async (page = 1) => {
    loading.value = true
    try {
        const { data } = await axios.get(`http://localhost:8000/api/jobs?page=${page}`)
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
        notification.notify('Application submitted successfully!', 'success')
        applyModalOpen.value = false
        applicationMessage.value = ''
        resumeFile.value = null
    } catch (e) {
        let errorMsg = e.message
        if (e.response?.data?.errors) {
            // Flatten validation errors into a string
            const errors = e.response.data.errors
            errorMsg = Object.values(errors).flat().join(' ')
        } else if (e.response?.data?.message) {
            errorMsg = e.response.data.message
        }
        notification.notify('Failed to apply: ' + errorMsg, 'error')
    }
}

onMounted(fetchJobs)
</script>

<template>
    <div>
        <div class="mb-8 text-center">
            <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">Explore Opportunities</h2>
            <p class="mt-2 text-lg text-slate-600">Find your next career move from our curated list of jobs.</p>
        </div>

        <div v-if="loading" class="flex justify-center py-20">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600"></div>
        </div>

        <div v-else>
            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                <div v-for="job in jobs" :key="job.id" 
                    class="group flex flex-col bg-white border border-slate-200 rounded-2xl shadow-sm hover:shadow-xl hover:border-indigo-200 transition-all duration-300 relative overflow-hidden">
                    
                    <div class="p-6 flex-grow">
                        <div class="flex items-start justify-between mb-4">
                             <div class="p-3 bg-indigo-50 text-indigo-600 rounded-xl">
                                 <BriefcaseIcon class="h-6 w-6" />
                             </div>
                             <span v-if="job.is_remote" class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 border border-emerald-200">
                                 <CheckCircleIcon class="h-3 w-3" />
                                 Remote
                             </span>
                        </div>
    
                        <h3 class="text-xl font-bold text-slate-900 mb-1 group-hover:text-indigo-600 transition-colors">{{ job.title }}</h3>
                        <p class="text-sm font-medium text-slate-500 mb-4">{{ job.employer.name }}</p>
    
                        <p class="text-slate-600 text-sm line-clamp-3 mb-6">{{ job.description }}</p>
    
                        <div class="space-y-2 mb-2">
                            <div class="flex items-center text-sm text-slate-500">
                                <MapPinIcon class="h-4 w-4 mr-2 text-slate-400" />
                                {{ job.location }}
                            </div>
                            <div class="flex items-center text-sm text-slate-500">
                                <BanknotesIcon class="h-4 w-4 mr-2 text-slate-400" />
                                {{ job.salary_range }}
                            </div>
                        </div>
                    </div>
    
                    <div class="px-6 py-4 bg-slate-50 border-t border-slate-100">
                        <button v-if="auth.isAuthenticated && !auth.isEmployer" 
                            @click="openApplyModal(job)"
                            class="w-full inline-flex justify-center items-center px-4 py-2 bg-slate-900 text-white font-medium rounded-xl hover:bg-slate-800 transition-colors focus:ring-2 focus:ring-offset-2 focus:ring-slate-900">
                            Apply Now
                        </button>
                        <div v-else class="text-center text-sm text-slate-400 italic">
                            {{ auth.isEmployer ? 'Employer View' : 'Login to Apply' }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pagination Controls -->
            <div v-if="pagination.last_page > 1" class="flex justify-center items-center gap-4 mt-12">
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
        </div>

        <!-- Apply Modal -->
        <Teleport to="body">
            <div v-if="applyModalOpen" class="fixed inset-0 z-[100] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <div class="fixed inset-0 bg-slate-500/75 transition-opacity" aria-hidden="true" @click="applyModalOpen = false"></div>
                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                    <div class="relative z-50 inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                    <h3 class="text-lg leading-6 font-medium text-slate-900" id="modal-title">
                                        Apply for <span class="text-indigo-600">{{ selectedJob.title }}</span>
                                    </h3>
                                    <div class="mt-2 text-sm text-slate-500 mb-4">
                                        Send a message and your resume to the employer.
                                    </div>
                                    <form @submit.prevent="submitApplication" class="space-y-4">
                                        <div>
                                            <label class="block text-sm font-medium text-slate-700">Message</label>
                                            <textarea v-model="applicationMessage" required rows="4" 
                                                class="mt-1 block w-full border border-slate-300 rounded-xl shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-slate-700">Resume (PDF/DOC)</label>
                                            <input type="file" @change="handleFileChange" accept=".pdf,.doc,.docx"
                                                class="mt-1 block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" />
                                        </div>
                                        <div class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense">
                                            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:col-start-2 sm:text-sm">
                                                Submit Application
                                            </button>
                                            <button type="button" @click="applyModalOpen = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-slate-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-slate-700 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:col-start-1 sm:text-sm">
                                                Cancel
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </Teleport>
    </div>
</template>
