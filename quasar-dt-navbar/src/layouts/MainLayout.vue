<template>
    <NavbarComponent>

    <q-page-container>
      <router-view />
    </q-page-container>
  </NavbarComponent>
</template>

<script setup lang="ts">
import NavbarComponent from 'src/components/NavbarComponent.vue';
defineOptions({
  name: 'MainLayout'
});


</script>
