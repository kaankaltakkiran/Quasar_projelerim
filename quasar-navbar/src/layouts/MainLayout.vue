<template>
  <q-toolbar class="bg-purple text-white shadow-2 rounded-borders">
    <q-btn flat label="Homepage" />
    <q-space />
    <q-tabs v-model="tab" shrink stretch>
      <template v-for="page in parentPages" :key="page.id">
           <!--
       !hasChildren(page.id) alt sayfaları var mı kontrol et
      -->
        <q-tab v-if="!hasChildren(page.id)" :name="page.page_slug" :label="page.page_name" />
        <q-btn-dropdown v-else auto-close flat :label="page.page_name">
          <q-list>
            <q-item v-for="child in getChildren(page.id)" :key="child.id" clickable @click="tab = child.page_slug">
              <q-item-section>{{ child.page_name }}</q-item-section>
            </q-item>
          </q-list>
        </q-btn-dropdown>
      </template>
    </q-tabs>
  </q-toolbar>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue';
import { useNavbarStore } from 'src/stores/navbarPage-store';

const tab = ref('tab1');
const navbarStore = useNavbarStore();
//parent idsi 0 olan sayfaları al
const parentPages = computed(() => navbarStore.pages.filter(page => page.parent_id === 0));
//id si verilen sayfanın alt sayfalarını var mı kontrol et
const hasChildren = (id: number) => navbarStore.pages.some(page => page.parent_id === id);
//id si verilen sayfanın alt sayfalarını getir
const getChildren = (parentId: number) => navbarStore.pages.filter(page => page.parent_id === parentId);

onMounted(() => {
  navbarStore.loadData();
});
</script>
