<template>
  <q-page>
    <div class="q-pa-md">
      <q-list bordered separator>
        <q-item v-for="entry in entries" :key="entry.id">
          <q-item-section
            class="text-weight-bold"
            :class="useAmountColorClass(entry.amount)"
          >
            {{ entry.name }}
          </q-item-section>
          <q-item-section
            class="text-weight-bold"
            side
            :class="useAmountColorClass(entry.amount)"
          >
            {{ useCurrencify(entry.amount) }}
          </q-item-section>
        </q-item>
      </q-list>
    </div>
    <q-footer class="bg-transparent">
      <div class="row q-mb-sm q-px-md q-my-sm shadow-up-3">
        <div class="col text-grey-7 text-h6">Balance:</div>
        <div
          :class="useAmountColorClass(balance)"
          class="row text-h6 text-right"
        >
          {{ useCurrencify(balance) }}
        </div>
      </div>
      <div class="row q-px-sm q-pb-sm q-col-gutter-sm bg-primary">
        <div class="col">
          <q-input
            outlined
            dense
            v-model="text"
            placeholder="Name"
            bg-color="white"
          />
        </div>
        <div class="col">
          <q-input
            outlined
            input-class="text-right"
            dense
            type="number"
            step="0.01"
            v-model="amount"
            placeholder="Amount"
            bg-color="white"
          />
        </div>
        <div class="col col-auto">
          <q-btn color="primary" round icon="add" />
        </div>
      </div>
    </q-footer>
  </q-page>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue';
// use importları
import { useCurrencify } from 'src/use/useCurrencify';
import { useAmountColorClass } from 'src/use/useAmountColorClass';

const text = ref('');
const amount = ref();

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
/* Bakiye hesaplama */

/* reduce metodu, bir dizi üzerinde döngü yaparak her bir öğeyi birikimciye (accumulator) ekler. İlk olarak accumulator 0 olarak başlatılır ve her amount değeri accumulator'a eklenir. Sonuç olarak, balance hesaplanır. */
const balance = computed(() => {
  return entries.value.reduce((accumulator, { amount }) => {
    return accumulator + amount;
  }, 0);
});
</script>
