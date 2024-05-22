<template>
  <q-layout view="lHh Lpr lFf">
    <q-header elevated>
      <q-toolbar class="bg-purple text-white shadow-2 rounded-borders ">
        <q-btn flat label="Homepage" @click="goHome" />
        <q-space />
        <q-tabs v-model="tab" shrink stretch>
          <template v-for="page in parentPages" :key="page.id">
                <!--
     hasChildren fonksiyonu ile sayfanın alt sayfaları var mı kontrol edilir
     navigateToPage fonskiyonu ile sayfa slug'ı alınır ve router.push ile sayfa yönlendirilir
      -->
            <q-tab
              v-if="!hasChildren(page.id)"
              :name="page.page_slug"
              :label="page.page_name"
              @click="navigateToPage(page.page_slug)"
            />
            <q-btn-dropdown v-else auto-close flat :label="page.page_name">
              <q-list>
                <q-item
                  v-for="child in getChildren(page.id)"
                  :key="child.id"
                  clickable
                  @click="navigateToPage(child.page_slug)"
                >
                  <q-item-section>{{ child.page_name }}</q-item-section>
                </q-item>
              </q-list>
            </q-btn-dropdown>
          </template>
        </q-tabs>
      </q-toolbar>
    </q-header>
    <q-page-container>
      <router-view />
    </q-page-container>
  </q-layout>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue';
import { useNavbarStore } from 'src/stores/navbarPage-store';
import { useRouter } from 'vue-router';

const tab = ref('tab1');
const navbarStore = useNavbarStore();

//parent idsi 0 olan sayfaları al
const parentPages = computed(() => navbarStore.pages.filter(page => page.parent_id === 0));
//id si verilen sayfanın alt sayfalarını var mı kontrol et
// some() fonksiyonu, bir dizide en az bir elemanın belirli bir koşulu sağlayıp sağlamadığını kontrol eden bir dizi yöntemidir. 
const hasChildren = (id: number) => navbarStore.pages.some(page => page.parent_id === id);
//id si verilen sayfanın alt sayfalarını getir
const getChildren = (parentId: number) => navbarStore.pages.filter(page => page.parent_id === parentId);

onMounted(() => {
  navbarStore.loadData();
});
// slug'a göre sayfa yönlendirme
const router = useRouter();
const navigateToPage = (slug: string) => { // sayfa slug'ına göre yönlendirme
  tab.value = slug;
  router.push({ path: `/sayfalar/${slug}` }); 
};
// anasayfaya git
const goHome = () => {
  tab.value = '';
  router.push({ path: '/' });
};

</script>
