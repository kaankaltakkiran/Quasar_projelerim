<template>
  <q-layout view="hHh LpR fFf">
    <q-header reveal bordered class="bg-primary text-white" height-hint="98">
      <q-toolbar>
        <!--Navbar start-->
        <q-btn dense flat round icon="menu" @click="toggleLeftDrawer" />
         <!--Navbar title start-->
        <q-toolbar-title>
          <a
            href="/"
            class="router-link-active router-link-exact-active doc-header__logo row items-center no-wrap cursor-pointer"
            aria-current="page"
          >
            <img
              class="doc-header__logo-img"
              src="https://cdn.quasar.dev/logo-v2/svg/logo-dark.svg"
              alt="Quasar Logo"
              width="48"
              height="48"
            />
            <img
              class="doc-header__logo-text q-ml-md"
              src="https://cdn.quasar.dev/logo-v2/svg/logotype-dark.svg"
              alt="Quasar Logo"
              width="150"
              height="20"
            />
          </a>
        </q-toolbar-title>
               <!--Navbar title end-->
                      <!--Navbar menu start-->
        <q-tabs v-model="tab" dense align="right" class="desktop-tabs"> <!--Static-->
          <q-route-tab to="/" exact name="anasayfa" label="Ana Sayfa" />
          <q-btn-dropdown v-for="menu in filteredMenus" :key="menu.id" auto-close stretch flat :label="menu.page_name">
            <q-list>
              <q-item v-for="child in menu.children" :key="child.id" :to="child.page_slug" clickable @click="setTab(child.page_slug)">
                <q-item-section>{{ child.page_name }}</q-item-section>
              </q-item>
            </q-list>
          </q-btn-dropdown>
        </q-tabs>
        <!--Navbar menu end-->
      </q-toolbar>
    </q-header>
    <!--Navbar end-->
    <!--Drawer  for mobile start-->
    <q-drawer v-model="leftDrawerOpen" side="left" overlay behavior="mobile" bordered>
      <q-list>
        <q-item to="/" clickable @click="setTab('anasayfa')">
          <q-item-section>Anasayfa</q-item-section>
        </q-item>
        <q-btn-dropdown v-for="menu in filteredMenus" :key="menu.id" auto-close stretch flat :label="menu.page_name">
            <q-list>
              <q-item v-for="child in menu.children" :key="child.id" :to="child.page_slug" clickable @click="setTab(child.page_slug)">
                <q-item-section>{{ child.page_name }}</q-item-section>
              </q-item>
            </q-list>
          </q-btn-dropdown>
      </q-list>
    </q-drawer>
    <!--Drawer  for mobile end-->
    <q-page-container>
      <router-view />
    </q-page-container>
  </q-layout>
</template>

<script setup lang="ts">
import { ref,onMounted,computed } from 'vue'
//ref ile tab ve leftDrawerOpen değişkenlerini oluşturduk
const tab = ref('anasayfa')
const leftDrawerOpen = ref(false)
//toggleLeftDrawer fonksiyonu ile leftDrawer'ı açıp kapatıyoruz
function toggleLeftDrawer() {
  leftDrawerOpen.value = !leftDrawerOpen.value
}
//setTab fonksiyonu ile tab'ı değiştiriyoruz
function setTab(newTab: string) {
  tab.value = newTab
  leftDrawerOpen.value = false // Drawer'ı kapat
}
import { useMenuStore } from 'src/stores/menu-store';
// Menu store'u kullan
const menuStore = useMenuStore();

// Menüleri getiren fonksiyon
const fetchMenus = async () => {
  try {
    // API'den menüleri al
    await menuStore.fetchMenus();
  } catch (error) {
    console.error('Failed to fetch menus:', error);
  }
};

// Bileşen DOM'a monte edildiğinde verileri getir

onMounted(() => {
  if (menuStore.menus.length === 0) {
    //data yoksa yükle
    fetchMenus();
  }
});
// Menüleri filtrele
const filteredMenus = computed(() => {
  //ana menüleri al
  return menuStore.menus.filter(menu => menu.parent_id == 0).map(menu => {
    //her ana menüye ait alt menüleri al
    const children = menuStore.menus.filter(child => child.parent_id === menu.id)
    return {
      ...menu,  //ifadesi, ana menü nesnesinin tüm özelliklerini (id, parent_id, vb.) yeni nesneye kopyalar.
      children
    }
  })
});
</script>

<style scoped lang="scss">
.desktop-tabs {
  display: none;
}

@media (min-width: 600px) {
  .desktop-tabs {
    display: flex;
  }

  .mobile-tabs {
    display: none;
  }
}
</style>
