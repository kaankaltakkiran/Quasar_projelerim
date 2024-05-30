<template>
  <q-layout view="lHh Lpr lFf">
    <q-header elevated>
      <q-toolbar>

        <q-toolbar-title>
          Quasar App
        </q-toolbar-title>
<q-tabs dense align="right" v-model="tab">
    <q-route-tab
   label="Ana Sayfa"
    name="home"
    to="/"
    exact
  />
  <q-route-tab
   label="Persons"
    name="persons"
    to="/persons"
    exact
  />
</q-tabs>
        <div>Quasar v{{ $q.version }}</div>
        
      </q-toolbar>
    </q-header>

    <q-page-container>
      <router-view />
    </q-page-container>
  </q-layout>
</template>

<script setup lang="ts">
import {ref, Ref } from 'vue'
defineOptions({
  name: 'MainLayout'
});

const tab: Ref<string> = ref('persons')
</script>
