<template>
  <q-page class="flex flex-center column">
    <template v-if="user">
      <h4 class="text-h4 q-mb-md">Merhaba, {{ user.username }}!</h4>
      <p class="text-subtitle1">Başarıyla giriş yaptınız.</p>
    </template>
    <template v-else>
      <h4 class="text-h4">Lütfen giriş yapın</h4>
    </template>
  </q-page>
</template>

<script setup lang="ts">
import { useAuthStore } from 'stores/auth'
import { storeToRefs } from 'pinia'

const authStore = useAuthStore()
const { user } = storeToRefs(authStore)
</script>

<style scoped>
.flex.flex-center {
  min-height: 50vh;
}
</style>
