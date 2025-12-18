import { defineStore } from 'pinia'
import { ref } from 'vue'

export const useNotificationStore = defineStore('notification', () => {
  const message = ref('')
  const type = ref('success') // success, error
  const show = ref(false)
  let timeout = null

  const notify = (msg, msgType = 'success') => {
    message.value = msg
    type.value = msgType
    show.value = true

    if (timeout) clearTimeout(timeout)
    timeout = setTimeout(() => {
      show.value = false
    }, 3000)
  }

  return { message, type, show, notify }
})
