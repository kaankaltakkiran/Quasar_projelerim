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
        <q-tabs v-model="tab" dense align="right" class="desktop-tabs">
          <!--Dinamik menü-->
          <q-route-tab
            v-for="menu in menus"
            :key="menu.id"
            :to="menu.page_slug"
            :name="menu.page_name"
            :label="menu.page_name"
          />
        </q-tabs>
        <q-btn
          flat
          dense
          @click="$q.dark.toggle()"
          :icon="$q.dark.isActive ? 'light_mode' : 'dark_mode'"
          :label="$q.dark.isActive ? 'Dark' : 'Light'"
          :no-caps="true"
          class="q-mx-md"
        />
        <!--Navbar menu end-->
      </q-toolbar>
    </q-header>
    <!--Navbar end-->
    <!--Drawer  for mobile start-->
    <q-drawer
      v-model="leftDrawerOpen"
      side="left"
      overlay
      behavior="mobile"
      bordered
    >
      <q-list>
        <!--Mobile dinamik menü-->
        <q-item
          v-for="menu in menus"
          :key="menu.id"
          clickable
          :to="menu.page_slug"
        >
          <q-item-section>{{ menu.page_name }}</q-item-section>
        </q-item>
      </q-list>
    </q-drawer>
    <!--Drawer  for mobile end-->
    <q-page-container>
      <router-view />
    </q-page-container>
  </q-layout>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { IMenu } from '../interfaces/IMenu';

//ref ile tab ve leftDrawerOpen değişkenlerini oluşturduk
const tab = ref('home');
const leftDrawerOpen = ref(false);
//toggleLeftDrawer fonksiyonu ile leftDrawer'ı açıp kapatıyoruz

function toggleLeftDrawer() {
  leftDrawerOpen.value = !leftDrawerOpen.value;
}
const menus = ref<IMenu[]>([]); // menü verileri
//gelen get adına göre veri çekme
const fetchMenus = async () => {
  try {
    const response = await axios.post(
      'http://localhost/veri/ornek_api/menu_api.php',
      {
        method: 'get-menu',
      }
    );
    console.log(response.data);
    if (response.data.success === true) {
      menus.value = response.data.menu; // tablo verilerini doldur
    }
  } catch (error) {
    console.error('Error fetching data:', error);
  }
};
onMounted(() => {
  fetchMenus();
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
