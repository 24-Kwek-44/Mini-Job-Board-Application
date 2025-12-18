<script setup>
import { ref, onMounted, watch } from 'vue'
import axios from 'axios'
import { useAuthStore } from '../stores/auth'
import { PaperClipIcon } from '@heroicons/vue/24/outline'

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
        <h3 class="text-xl font-bold text-slate-800 mb-6 px-1">Recent Applications</h3>
        
        <div v-if="loading" class="flex justify-center py-8">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600"></div>
        </div>
        
        <div v-else-if="applications.length === 0" class="text-center py-12 bg-slate-50 rounded-xl border border-dashed border-slate-300">
            <p class="text-slate-500">No applications received yet.</p>
        </div>

        <div v-else class="space-y-4">
            <div v-for="app in applications" :key="app.id" class="p-4 bg-slate-50 border border-slate-200 rounded-xl hover:bg-white hover:shadow-md transition-all duration-200">
                <div class="flex justify-between items-start">
                    <div>
                         <p class="font-bold text-slate-900">{{ app.applicant.name }}</p>
                         <p class="text-sm text-slate-500">{{ app.applicant.email }}</p>
                    </div>
                    <span class="text-xs text-slate-400 bg-white px-2 py-1 rounded border border-slate-100">
                        {{ new Date(app.created_at).toLocaleDateString() }}
                    </span>
                </div>
                
                <div class="mt-3 p-3 bg-white rounded-lg border border-slate-100 text-sm text-slate-700 italic">
                    "{{ app.message }}"
                </div>

                <div class="mt-4 flex justify-end">
                     <a v-if="app.resume_path" :href="`http://localhost:8000/storage/${app.resume_path}`" target="_blank" 
                        class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-lg text-indigo-700 bg-indigo-50 border border-indigo-100 hover:bg-indigo-100">
                        <PaperClipIcon class="h-4 w-4 mr-1.5" />
                        Download Resume
                    </a>
                    <span v-else class="text-xs text-slate-400">No resume attached</span>
                </div>
            </div>
        </div>
    </div>
</template>
