<template>
  <q-page class="flex flex-center">
    <q-card class="auth-card" style="min-width: 300px">
      <q-card-section>
        <div class="text-h6">Register</div>
      </q-card-section>

      <q-card-section>
        <q-form @submit="onSubmit" class="q-gutter-md">
          <q-input
            v-model="username"
            type="text"
            label="Username"
            filled
            :rules="[(val: string) => !!val || 'Username is required']"
          />

          <q-input
            v-model="email"
            type="email"
            label="Email"
            filled
            :rules="[(val: string) => !!val || 'Email is required']"
          />

          <q-input
            v-model="password"
            type="password"
            label="Password"
            filled
            :rules="[(val: string) => !!val || 'Password is required']"
          />

          <q-toggle v-model="accept" label="I accept the license and terms" />

          <div>
            <q-btn label="Register" type="submit" color="primary" :loading="loading">
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
const username = ref('')
const accept = ref<boolean>(false)
const loading = ref(false)

const $q = useQuasar()
const router = useRouter()

//form submit edilmişse
const onSubmit = async () => {
  if (!accept.value) {
    $q.notify({
      color: 'red-5',
      textColor: 'white',
      icon: 'warning',
      message: 'You need to accept the license and terms first',
      position: 'top-right',
    })
    return
  }

  loading.value = true
  //form verilerini apiye gönder json formatında
  try {
    const response = await api.post('/auth.php', {
      method: 'register',
      email: email.value,
      password: password.value,
      username: username.value,
    })
    // işlem basarılı ise
    console.log('Response:', response)
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
        router.push('/login')
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
    console.error('Error sending form:', error)
    $q.notify({
      color: 'red-5',
      textColor: 'white',
      icon: 'warning',
      message: 'An error occurred while sending the form',
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
