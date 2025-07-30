<template>
  <q-layout view="lHh Lpr lFf">
    <q-header elevated>
      <q-toolbar class="bg-primary text-white shadow-2 rounded-borders">
        <q-btn flat label="E-ticaret" />
        <q-space />

        <q-tabs v-model="tab" shrink>
          <q-tab name="home" label="Ana Sayfa" />
          <q-tab name="products" label="Ürünler" />
          <q-tab name="register" label="Üye Ol" />
          <q-tab name="login" label="Giriş Yap" />
          <q-tab name="logout" label="Çıkış Yap" />
        </q-tabs>
      </q-toolbar>
    </q-header>

    <q-page-container>
      <router-view />
    </q-page-container>
  </q-layout>
</template>

<script setup lang="ts">
import { ref } from 'vue';

const tab = ref<string>('home');
</script>
