import { defineStore } from 'pinia'
import { ref, computed } from 'vue'

export const useKioskStore = defineStore('kiosk', () => {
  const currentUser = ref(null)
  const userToken = ref(null)

  const isLoggedIn = computed(() => !!userToken.value)

  function setUserSession(userData, token) {
    currentUser.value = userData
    userToken.value = token
  }

  function clearUserSession() {
    currentUser.value = null
    userToken.value = null
  }

  return {
    currentUser,
    userToken,
    isLoggedIn,
    setUserSession,
    clearUserSession,
  }
})