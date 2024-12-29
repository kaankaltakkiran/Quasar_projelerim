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

import { useRouter } from 'vue-router'
import { useAuthStore } from 'stores/auth'

const email = ref('')
const password = ref('')
const loading = ref(false)

const router = useRouter()
const authStore = useAuthStore()

const onSubmit = async () => {
  loading.value = true

  try {
    const success = await authStore.login(email.value, password.value)
    if (success) {
      // Wait for 1.5 seconds before redirecting to index page
      setTimeout(() => {
        router.push('/')
      }, 1500)
    }
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
