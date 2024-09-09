<template>
  <q-layout view="hHh lpR lFf">
    <q-header elevated>
      <q-toolbar>
        <q-btn
          flat
          dense
          round
          icon="menu"
          aria-label="Menu"
          @click="toggleLeftDrawer"
        />

        <q-toolbar-title>
          <div class="absolute-center">
            <q-icon name="savings" />
            Moneyballs
          </div>
        </q-toolbar-title>
        <!-- Edit Mode(lg ve xl ekranda görün) -->
        <q-btn
          v-if="$q.screen.lg || ($q.screen.xl && isEntriesPage && hasEntries)"
          flat
          round
          @click="toggleEditMode"
        >
          <q-icon :name="isEditMode ? 'done' : 'edit'" />
        </q-btn>
      </q-toolbar>
    </q-header>

    <q-drawer
      v-model="leftDrawerOpen"
      class="bg-primary"
      :width="250"
      :breakpoint="767"
      show-if-above
      bordered
    >
      <q-list>
        <q-item-label class="text-white" header>
          {{ navigationLabel }}
        </q-item-label>

        <NavLink
          v-for="link in navLinks"
          :key="link.link"
          :title="link.title"
          :icon="link.icon"
          :link="link.link"
        />
      </q-list>
    </q-drawer>

    <q-page-container>
      <!-- Props ile veri gönderme -->
      <router-view
        :is-edit-mode="isEditMode"
        @update:is-edit-mode="updateEditMode"
      />
    </q-page-container>
  </q-layout>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue';

import NavLink, { NavLinkProps } from 'components/Nav/NavLink.vue';
import { useRoute } from 'vue-router';
import { useEntryStore } from '../stores/entries-store';
import { useI18n } from 'vue-i18n'; // useI18n fonksiyonunu ekleyin

// i18n kullanımı
const { t } = useI18n();

// store kullanımı
const entryStore = useEntryStore();

onMounted(() => {
  entryStore.fetchEntries();
});

defineOptions({
  name: 'MainLayout',
});

// Çevrilmiş metni bir değişkende saklayın
const navigationLabel = computed(() => t('Navigation'));
//dinamik olarak çeviri yapmak için computed kullandım
const navLinks = computed((): NavLinkProps[] => [
  {
    title: t('Entries'), // Çeviri burada yapılır
    icon: 'savings',
    link: '/',
  },
  {
    title: t('Settings'), // Çeviri burada yapılır
    icon: 'settings',
    link: '/settings',
  },
]);

// sol drawer açık mı kapalı mı
const leftDrawerOpen = ref(false);

function toggleLeftDrawer() {
  leftDrawerOpen.value = !leftDrawerOpen.value;
}

// edit modu açık mı kapalı mı
const isEditMode = ref(false);

const toggleEditMode = () => {
  isEditMode.value = !isEditMode.value;
};

// edit modu güncelleme
const updateEditMode = (value: boolean) => {
  isEditMode.value = value;
};

// sayfa kontrolü ile edit modu kontrolü
const route = useRoute();
const isEntriesPage = computed(() => route.path === '/');

// entries kontrolü
const hasEntries = computed(() => entryStore.entries.length > 0);
</script>
