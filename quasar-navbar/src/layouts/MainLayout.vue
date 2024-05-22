<template>
    <q-toolbar class="bg-purple text-white shadow-2 rounded-borders">
      <q-btn flat label="Homepage" />
      <q-space />
      <q-tabs v-model="tab" shrink stretch>
        <q-tab name="tab1" label="Tab 1" />
        <q-tab name="tab2" label="Tab 2" />
        <q-tab name="tab3" label="Tab 3" />
        <q-btn-dropdown auto-close flat label="More">
          <q-list>
            <q-item clickable @click="tab = 'tab4'">
              <q-item-section>Tab 4</q-item-section>
            </q-item>
            <q-item clickable @click="tab = 'tab5'">
              <q-item-section>Tab 5</q-item-section>
            </q-item>
          </q-list>
        </q-btn-dropdown>
      </q-tabs>
    </q-toolbar>
</template>

<script setup lang="ts">
import { ref } from 'vue';

const tab = ref('tab1');
</script>
