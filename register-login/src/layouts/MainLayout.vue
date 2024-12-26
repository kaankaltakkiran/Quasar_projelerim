<template>
  <q-layout view="lHh Lpr lFf">
    <q-header elevated>
      <q-toolbar>
        <q-toolbar-title> Login Register </q-toolbar-title>

        <q-space />
        <q-btn flat to="/" label="Home" />
        <q-btn flat to="/register" label="Register" />
        <q-btn flat to="/login" label="Login" />
        <q-btn flat label="Logout" @click="handleLogout" />
      </q-toolbar>
    </q-header>

    <q-page-container>
      <router-view />
    </q-page-container>
  </q-layout>
</template>

<script setup lang="ts">
const handleLogout = () => {
  // Will implement later with backend
  console.log('Logout clicked')
}
</script>
