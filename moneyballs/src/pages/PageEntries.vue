<template>
  <q-page>
    <div class="q-pa-md">
      <q-list bordered separator>
        <q-item v-for="entry in entries" :key="entry.id">
          <q-item-section> {{ entry.name }} </q-item-section>
          <q-item-section side>
            {{ useCurrencify(entry.amount) }}
          </q-item-section>
        </q-item>
      </q-list>
    </div>
  </q-page>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { useCurrencify } from 'src/use/useCurrencify';

const entries = ref([
  {
    id: '1',
    name: 'Salary',
    amount: 4999.99,
  },
  {
    id: '2',
    name: 'Rent',
    amount: -900,
  },
  {
    id: '3',
    name: 'Phone Bill',
    amount: -14.99,
  },
  {
    id: '4',
    name: 'Unknown',
    amount: 0,
  },
]);
</script>
