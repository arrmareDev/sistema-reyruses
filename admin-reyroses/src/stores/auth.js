import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import api from '@/lib/api'
import { requestForToken } from '@/firebase'

export const useAuthStore = defineStore('auth', () => {
  const token = ref(localStorage.getItem('auth_token'))
  const user = ref(null)

  const isAuthenticated = computed(() => !!token.value)

  async function login(email, password) {
    const response = await api.post('/login', { email, password })

    token.value = response.data.token
    user.value = response.data.user
    localStorage.setItem('auth_token', token.value)

    // Guardar el token FCM en segundo plano: si falla, no bloquea el login
    requestForToken()
      .then((fcmToken) => {
        if (fcmToken) return api.post('/save-fcm-token', { fcm_token: fcmToken })
      })
      .catch((error) => console.warn('No se pudo actualizar el token FCM:', error))

    return user.value
  }

  async function logout() {
    try {
      await api.post('/logout')
    } catch (error) {
      // Si el token ya venció igual limpiamos la sesión local
      console.warn('Error cerrando sesión en el servidor:', error)
    } finally {
      token.value = null
      user.value = null
      localStorage.removeItem('auth_token')
    }
  }

  return { token, user, isAuthenticated, login, logout }
})
