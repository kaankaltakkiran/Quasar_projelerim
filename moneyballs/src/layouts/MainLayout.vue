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
        <!-- Edit Mode -->
        <q-btn
          v-if="isEntriesPage && hasEntries"
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
        <q-item-label class="text-white" header> Navigation </q-item-label>

        <NavLink v-for="link in NavLinks" :key="link.title" v-bind="link" />
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

//store kullanımı
const entryStore = useEntryStore();

onMounted(() => {
  entryStore.fetchEntries();
});

defineOptions({
  name: 'MainLayout',
});

const NavLinks: NavLinkProps[] = [
  {
    title: 'Entries',
    icon: 'savings',
    link: '/',
  },
  {
    title: 'Settings',
    icon: 'settings',
    link: '/settings',
  },
];

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
//sayfa kontrolü ile edit modu kontrolü
const route = useRoute();
const isEntriesPage = computed(() => route.path === '/');

// entries kontrolü
const hasEntries = computed(() => entryStore.entries.length > 0);
</script>
