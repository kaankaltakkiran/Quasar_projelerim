<template>
    <div v-html="pageContent"></div>
  </template>
  
  <script setup lang="ts">
  import { computed } from 'vue';
  import { useRoute } from 'vue-router';
  import { useNavbarStore } from 'src/stores/navbarPage-store';
  
  const route = useRoute();
  const navbarStore = useNavbarStore();
  //store içersindeki page_content'i getir
  const pageContent = computed(() => {
    const slug = route.params.slug as string;
    const page = navbarStore.pages.find(p => p.page_slug === slug);
    return page ? page.page_content : '<h1>Page Not Found</h1>';
  });
  </script>
  
  