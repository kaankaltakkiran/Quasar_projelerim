<template>
  <q-layout view="lHh Lpr lFf">
    <q-header elevated>
      <q-toolbar>
        <q-toolbar-title> Login Register </q-toolbar-title>

        <q-space />
        <q-btn flat to="/" label="Home" />
        <template v-if="!isAuthenticated">
          <q-btn flat to="/register" label="Register" />
          <q-btn flat to="/login" label="Login" />
        </template>
        <q-btn v-else flat label="Logout" @click="handleLogout" />
      </q-toolbar>
    </q-header>

    <q-page-container>
      <router-view />
    </q-page-container>
  </q-layout>
</template>

<script setup lang="ts">
import { useAuthStore } from 'stores/auth'
import { storeToRefs } from 'pinia'

const authStore = useAuthStore()
const { isAuthenticated } = storeToRefs(authStore)

const handleLogout = () => {
  authStore.logout()
}
</script>
