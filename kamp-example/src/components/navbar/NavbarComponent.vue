<template>
  <q-toolbar class="bg-primary text-white shadow-2 rounded-borders">
    <!--  <q-btn flat label="E-ticaret" /> -->

    <q-toolbar-title> Quasar App </q-toolbar-title>
    <q-space />

    <q-tabs v-model="tab" shrink>
      <q-tab name="home" label="Ana Sayfa" />
      <q-tab name="products" label="Ürünler" />
      <q-tab name="register" label="Üye Ol" />
      <q-tab name="login" label="Giriş Yap" />
      <q-tab name="logout" label="Çıkış Yap" />
    </q-tabs>
  </q-toolbar>
</template>

<script setup lang="ts">
import { ref } from 'vue';

const tab = ref<string>('home');
</script>
