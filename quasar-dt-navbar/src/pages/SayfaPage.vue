<template>
  <q-page padding>

    <div v-if="pageLoading">Yükleniyor, lütfen bekleyin...</div>
    <div v-else>
      <!-- Menü öğelerini listeleme -->
      <div v-for="menuItem in filteredMenuItems" :key="menuItem.id" v-html="menuItem.page_content"></div>
    </div>
  </q-page>
</template>

<script setup lang="ts">
import { ref, onMounted, watch, computed } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useMenuStore } from 'src/stores/menu-store';
import axios from 'axios';

// Router nesnesini kullan
const router = useRouter();
// Route'dan gelen slug değerini al
const route = useRoute();
const slug = ref<string>(''); // Slug değerini tutan değişken

// Menü store'unu kullan
const menuStore = useMenuStore();

// Veri yüklenme durumunu tutan değişken
const pageLoading = ref<boolean>(true); // Sayfa yüklenirken true, yüklendikten sonra false

// Veriyi getiren fonksiyon
const fetchData = async () => {
  slug.value = route.params.slug as string; // Route parametresinden slug değerini al

  try {
    // API'den menüleri al
    // Slug değerine göre menü verisini getir
    //bu kısımmdan veritabanından slug değerine göre menü verisini gelmiyor hepsi geliyor
    const response = await axios.get(
      `http://localhost/veri/navbar-project/navbar-data/get_menu.php?page_slug=${slug.value}` 
    );
    menuStore.menus = response.data; // Menü verisini güncelle
    console.log('Menus:', menuStore.menus);
    pageLoading.value = false; // Sayfa yükleme tamamlandı
  } catch (error) {
    console.error('Failed to fetch menus:', error);
  }

  // Eğer filtrelenmiş öğeler boş ise 404 sayfasına yönlendir
  if (filteredMenuItems.value.length === 0) {
    router.push('/404');
  }
};

// Bileşen DOM'a monte edildiğinde veya slug değeri değiştiğinde verileri getir
onMounted(fetchData);
watch(() => route.params.slug, fetchData);

// Filtrelenmiş menü öğelerini hesapla
const filteredMenuItems = computed(() => {
  if (!slug.value || !menuStore.menus) return []; // Slug veya menü verisi yoksa boş dizi döndür

  // Menü öğelerini slug değerine göre filtrele
  return menuStore.menus.filter(menuItem => menuItem.page_slug === slug.value);
});

</script>
