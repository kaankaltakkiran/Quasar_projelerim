import { boot } from 'quasar/wrappers'
import { useAuthStore } from 'stores/auth'
import { api } from './axios'

export default boot(({ router }) => {
  // Request interceptor
  api.interceptors.request.use(
    (config) => {
      const authStore = useAuthStore()
      const token = authStore.getToken

      if (token) {
        config.headers.Authorization = `Bearer ${token}`
      }

      return config
    },
    (error) => {
      return Promise.reject(error)
    }
  )

  // Response interceptor
  api.interceptors.response.use(
    (response) => {
      return response
    },
    async (error) => {
      if (error.response?.status === 401) {
        const authStore = useAuthStore()
        authStore.logout()
        router.push('/login')
      }
      return Promise.reject(error)
    }
  )
})
