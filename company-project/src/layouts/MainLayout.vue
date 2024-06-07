<template>
  <navbar-component>
    <q-page-container>
      <router-view />
    </q-page-container>
  </navbar-component>
</template>

<script setup lang="ts">
import NavbarComponent from 'src/components/NavbarComponent.vue';
defineOptions({
  name: 'MainLayout',
});
</script>
