<template>
  <q-page class="flex flex-center">
    <q-card class="auth-card" style="min-width: 300px">
      <q-card-section>
        <div class="text-h6">Login</div>
      </q-card-section>

      <q-card-section>
        <q-form @submit="onSubmit" class="q-gutter-md">
          <q-input
            v-model="email"
            type="email"
            label="Email"
            filled
            :rules="[(val) => !!val || 'Email is required']"
          />

          <q-input
            v-model="password"
            type="password"
            label="Password"
            filled
            :rules="[(val) => !!val || 'Password is required']"
          />

          <div>
            <q-btn label="Login" type="submit" color="primary" :loading="loading">
              <template v-slot:loading>
                <q-spinner-facebook />
              </template>
            </q-btn>
          </div>
        </q-form>
      </q-card-section>
    </q-card>
  </q-page>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { useQuasar } from 'quasar'
import { useRouter } from 'vue-router'
import { api } from 'boot/axios'

const email = ref('')
const password = ref('')
const loading = ref(false)

const $q = useQuasar()
const router = useRouter()

const onSubmit = async () => {
  loading.value = true

  try {
    const response = await api.post('/auth.php', {
      method: 'login',
      email: email.value,
      password: password.value,
    })

    if (response.data.success) {
      $q.notify({
        color: 'green-4',
        textColor: 'white',
        icon: 'cloud_done',
        message: response.data.message,
        position: 'top-right',
      })

      // Wait for 1.5 seconds before redirecting
      setTimeout(() => {
        router.push('/')
      }, 1500)
    } else {
      $q.notify({
        color: 'red-5',
        textColor: 'white',
        icon: 'warning',
        message: response.data.error,
        position: 'top-right',
      })
    }
  } catch (error) {
    console.error('Error during login:', error)
    $q.notify({
      color: 'red-5',
      textColor: 'white',
      icon: 'warning',
      message: 'An error occurred during login',
      position: 'top-right',
    })
  } finally {
    loading.value = false
  }
}
</script>

<style scoped>
.auth-card {
  padding: 20px;
}
</style>
