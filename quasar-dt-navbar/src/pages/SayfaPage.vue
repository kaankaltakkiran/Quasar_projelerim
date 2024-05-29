<template>
  <q-page padding>

      <!-- Menü öğelerini listeleme -->
      <div v-for="menuItem in filteredMenuItems" :key="menuItem.id" v-html="menuItem.page_content"></div>
  </q-page>
</template>

<script setup lang="ts">
import { ref, onMounted,onBeforeUnmount, watch, computed } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useMenuStore } from 'src/stores/menu-store';
import axios from 'axios';
import { useQuasar } from 'quasar'

// Quasar kullanımı için $q değişkenini tanımla
const $q = useQuasar()
// Loading göstergesini göstermek için timer değişkeni
let timer: ReturnType<typeof setTimeout> | undefined
// Router nesnesini kullan
const router = useRouter();
// Route'dan gelen slug değerini al
const route = useRoute();
const slug = ref<string>(''); // Slug değerini tutan değişken
// Menü store'unu kullan
const menuStore = useMenuStore();
// Veriyi getiren fonksiyon
const fetchData = async () => {
  showLoading(); // Loading göstergesini göster
    // 1 saniyelik bekleme süresi ekle
    await new Promise(resolve => setTimeout(resolve, 1000)); // 1 saniye bekle
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
  } catch (error) {
    console.error('Failed to fetch menus:', error);
    triggerWarning();
  }finally {
    hideLoading(); // İşlem tamamlandığında loading göstergesini gizle
    triggerPositive();
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
onBeforeUnmount(() => {
  if (timer !== void 0) {
    clearTimeout(timer)
    $q.loading.hide()
  }
})
// Loading göstergesini göstermek için fonksiyonlar
const showLoading = () => {
  $q.loading.show({
    message: 'Some important <b>process</b> is in progress.<br><span class="text-amber text-italic">Please wait...</span>',
    html: true
  })

  // hiding in 3s
  timer = setTimeout(() => {
    $q.loading.hide()
    timer = void 0
  }, 3000)
}
// Loading göstergesini gizlemek için fonksiyon
const hideLoading = () => {
  if (timer !== void 0) {
    clearTimeout(timer);
    timer = void 0;
  }
  $q.loading.hide();
}
// Bildirim göstermek için fonksiyonlar
const triggerPositive = () => {
  $q.notify({
    type: 'positive',
    message: 'Veri başarıyla yüklendi.',
    timeout: 750,
    position: 'top-right',
  })
}
const triggerWarning = () => {
  $q.notify({
    type: 'warning',
    message: 'Veri yüklenirken bir hata oluştu.',
    timeout: 750,
    position: 'top-right',
  })
}
</script>
