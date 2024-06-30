<template>
  <q-page>
    <div class="q-pa-md">
      <q-list bordered separator>
        <q-slide-item
          v-for="entry in entries"
          :key="entry.id"
          @right="onEntrySlideRight($event, entry.id)"
          left-color="positive"
          right-color="negative"
        >
          <template v-slot:right>
            <q-icon name="delete" />
          </template>

          <q-item>
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
        </q-slide-item>
      </q-list>
    </div>
    <!-- Footer Section -->
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
      <!-- Form Section -->
      <q-form
        @submit="addEntry"
        class="row q-px-sm q-pb-sm q-col-gutter-sm bg-primary"
      >
        <div class="col">
          <q-input
            outlined
            dense
            v-model="addEntryForm.name"
            ref="nameRef"
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
import { uid, useQuasar } from 'quasar';
// use importları
import { useCurrencify } from 'src/use/useCurrencify';
import { useAmountColorClass } from 'src/use/useAmountColorClass';

// useQuasar fonksiyonu, Quasar fonksiyonlarını kullanmamızı sağlar
const $q = useQuasar();

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

// nameRef, name alanını referans alır
const nameRef = ref<HTMLElement | null>(null);

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
  nameRef.value?.focus(); // name alanına odaklan
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
/* Silme işlemi */

const onEntrySlideRight: (
  { reset }: { reset: () => void },
  entryId: string // entryId, silinecek girdinin id'sini temsil eder
) => void = ({ reset }, entryId) => {
  $q.dialog({
    title: 'Delete Entry',
    message: 'Are you sure you want to delete this entry?',
    cancel: {
      color: 'primary',
      noCaps: true,
    },
    persistent: true,
    ok: {
      label: 'delete',
      color: 'negative',
      noCaps: true,
    },
  })
    .onOk(() => {
      // console.log('>>>> OK')
      deleteEntry(entryId);
    })
    .onCancel(() => {
      // console.log('>>>> Cancel')
      reset(); // Call the reset function
    });
};

/* Silme fonksiyonu */

const deleteEntry = (entryId: string) => {
  //console.log('Deleting entry with id:', entryId);
  const index = entries.value.findIndex((entry) => entry.id === entryId); // entryId'ye göre girdinin index'ini bul
  entries.value.splice(index, 1); // girdiyi sil
};
</script>
