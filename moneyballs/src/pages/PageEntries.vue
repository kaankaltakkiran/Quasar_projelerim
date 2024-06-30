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
      <q-form
        @submit="addEntry"
        class="row q-px-sm q-pb-sm q-col-gutter-sm bg-primary"
      >
        <div class="col">
          <q-input
            outlined
            dense
            v-model="addEntryForm.name"
            placeholder="Name"
            bg-color="white"
          />
        </div>
        <div class="col">
          <q-input
            outlined
            input-class="text-right"
            dense
            step="0.01"
            v-model.number="addEntryForm.amount"
            placeholder="Amount"
            bg-color="white"
          />
        </div>
        <div class="col col-auto">
          <q-btn color="primary" type="submit" round icon="add" />
        </div>
      </q-form>
    </q-footer>
  </q-page>
</template>

<script setup lang="ts">
import { ref, computed, reactive } from 'vue';
import { uid } from 'quasar';
// use importları
import { useCurrencify } from 'src/use/useCurrencify';
import { useAmountColorClass } from 'src/use/useAmountColorClass';

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

/* Ekleme işlemi */

// addEntryFormDefault objesi, formun varsayılan değerlerini içerir
const addEntryFormDefault = {
  name: '',
  amount: null as number | null, // amount alanı number veya null olabilir
};

// addEntryForm objesi, formun değerlerini içerir
const addEntryForm = reactive({
  ...addEntryFormDefault,
});

// addEntryFormReset fonksiyonu, formu sıfırlar
const addEntryFormReset = () => {
  Object.assign(addEntryForm, addEntryFormDefault);
};

//Object.assign() metodu, bir veya daha fazla kaynaktan hedefe özellikleri kopyalar.
const addEntry = () => {
  const newEntry = Object.assign(
    {},
    {
      id: uid(),
      name: addEntryForm.name,
      amount: addEntryForm.amount !== null ? addEntryForm.amount : 0,
    }
  );

  entries.value.push(newEntry); // yeni girdiyi ekle
  addEntryFormReset(); // formu sıfırla
};
</script>
