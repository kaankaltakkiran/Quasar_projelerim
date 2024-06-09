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
          <q-route-tab to="/" exact name="home" label="Home Page" />
          <q-route-tab
            to="/persons"
            exact
            name="persons"
            label="Persons Page"
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
        <q-item clickable to="/">
          <q-item-section>Home Page</q-item-section>
        </q-item>
        <q-item clickable to="/persons">
          <q-item-section>Persons Page</q-item-section>
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
import { ref } from 'vue';
//ref ile tab ve leftDrawerOpen değişkenlerini oluşturduk
const tab = ref('home');
const leftDrawerOpen = ref(false);
//toggleLeftDrawer fonksiyonu ile leftDrawer'ı açıp kapatıyoruz
function toggleLeftDrawer() {
  leftDrawerOpen.value = !leftDrawerOpen.value;
}
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
