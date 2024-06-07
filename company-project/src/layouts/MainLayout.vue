<template>
  <q-layout view="lHh Lpr lFf">
    <p>Header</p>
    <q-page-container>
      <router-view />
    </q-page-container>
  </q-layout>
</template>

<script setup lang="ts">
defineOptions({
  name: 'MainLayout',
});
</script>
