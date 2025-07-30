<template>
  <q-toolbar class="bg-primary text-white shadow-2 rounded-borders">
    <!--  <q-btn flat label="E-ticaret" /> -->

    <q-toolbar-title> Quasar App </q-toolbar-title>
    <q-space />

    <q-tabs v-model="tab" shrink>
      <q-route-tab name="home" to="/" exact label="Ana Sayfa" />
      <q-route-tab name="products" to="/products" exact label="Ürünler" />
      <q-route-tab name="register" to="/register" exact label="Üye Ol" />
      <q-route-tab name="login" to="/login" exact label="Giriş Yap" />
      <q-route-tab name="logout" to="/logout" exact label="Çıkış Yap" />
    </q-tabs>
  </q-toolbar>
</template>

<script setup lang="ts">
import { ref } from 'vue';

const tab = ref<string>('home');
</script>
