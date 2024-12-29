import { defineStore } from 'pinia'
import { api } from 'boot/axios'
import { Notify } from 'quasar'
import { useRouter } from 'vue-router'

interface User {
  id: number
  username: string
  email: string
}

interface UserState {
  token: string | null
  user: User | null
  isAuthenticated: boolean
}

export const useAuthStore = defineStore('auth', {
  state: (): UserState => ({
    token: localStorage.getItem('token'),
    user: null,
    isAuthenticated: !!localStorage.getItem('token'),
  }),

  getters: {
    getUser: (state): User | null => state.user,
    getToken: (state): string | null => state.token,
    isLoggedIn: (state): boolean => state.isAuthenticated,
  },

  actions: {
    async login(email: string, password: string): Promise<boolean> {
      try {
        const response = await api.post('/auth.php', {
          method: 'login',
          email,
          password,
        })

        if (response.data.success) {
          const { token, user } = response.data
          this.setToken(token)
          this.setUser(user)
          this.isAuthenticated = true

          // Set JWT token in axios headers
          api.defaults.headers.common['Authorization'] = `Bearer ${token}`

          Notify.create({
            color: 'positive',
            message: response.data.message,
            position: 'top-right',
          })

          return true
        } else if (response.data.error) {
          Notify.create({
            color: 'negative',
            message: response.data.error,
            position: 'top-right',
          })
        }
        return false
      } catch (error) {
        console.error('Login error:', error)
        Notify.create({
          color: 'negative',
          message: 'Error logging in',
          position: 'top-right',
        })
        return false
      }
    },

    async register(email: string, password: string, username: string): Promise<boolean> {
      try {
        const response = await api.post('/auth.php', {
          method: 'register',
          email,
          password,
          username,
        })

        if (response.data.success) {
          Notify.create({
            color: 'positive',
            message: response.data.message,
            position: 'top-right',
          })
          return true
        }

        // Always show error message if success is false
        Notify.create({
          color: 'negative',
          message: response.data.error,
          position: 'top-right',
        })
        return false
      } catch (error) {
        console.error('Register error:', error)
        Notify.create({
          color: 'negative',
          message: 'Error registering',
          position: 'top-right',
        })
        return false
      }
    },

    logout(): void {
      // Clear auth state
      this.token = null
      this.user = null
      this.isAuthenticated = false

      // Remove token from localStorage
      localStorage.removeItem('token')

      // Remove token from axios headers
      delete api.defaults.headers.common['Authorization']

      // Show notification
      Notify.create({
        color: 'info',
        message: 'Çıkış yapıldı',
        position: 'top-right',
      })

      // Redirect to login page using router
      const router = useRouter()
      router.push('/login')
    },

    setToken(token: string): void {
      this.token = token
      localStorage.setItem('token', token)
    },

    setUser(user: User): void {
      this.user = user
    },
  },
})
