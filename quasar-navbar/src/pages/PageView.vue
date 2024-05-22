<template>
    <div v-html="pageContent"></div>
  </template>
  
  <script setup lang="ts">
  import { computed } from 'vue';
  import { useRoute,useRouter } from 'vue-router';
  import { useNavbarStore } from 'src/stores/navbarPage-store';
  
  const route = useRoute();
  const router = useRouter();
  const navbarStore = useNavbarStore();
  //store içersindeki page_content'i getir
  const pageContent = computed(() => {
    const slug = route.params.slug as string;
    const page = navbarStore.pages.find(p => p.page_slug === slug);
    return page ? page.page_content : redirectToNotFound(); // Sayfa bulunamazsa doğrudan yönlendirme yap
  });
  // Sayfa bulunamadığında /kaan adresine yönlendirme yap
const redirectToNotFound = () => {
  
  router.push('/404');
  return ''; // Boş içerik döndür
};
  </script>
  
  