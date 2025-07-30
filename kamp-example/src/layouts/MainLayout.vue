<template>
  <q-layout view="lHh Lpr lFf">
    <q-header elevated>
      <NavbarComponent />
    </q-header>

    <q-page-container>
      <router-view />
    </q-page-container>
  </q-layout>
</template>

<script setup lang="ts">
import NavbarComponent from 'components/navbar/NavbarComponent.vue';
</script>
