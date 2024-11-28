<template>
  <q-page class="row items-center justify-evenly">
    <div class="q-pa-md" style="max-width: 400px">
      <h3>İzin Hesaplama</h3>
      <q-form @submit="onSubmit" @reset="onReset" class="q-gutter-md">
        <div class="q-gutter-md">
          <q-date v-model="date" :events="events" :options="validDates" />
        </div>
        <q-checkbox
          v-model="official"
          label="Resmi Tatilleri Dahil Et"
          color="primary"
        />

        <q-checkbox
          v-model="weekend"
          label="Hafta Sonlarını Dahil Et"
          color="cyan"
        />
        <div v-if="holidayStore.officalHolidays.length > 0">
          <p v-for="holiday in holidayStore.officalHolidays" :key="holiday.id">
            {{ holiday.name }}
          </p>
        </div>

        <q-toggle v-model="accept" label="I accept the license and terms" />

        <div>
          <q-btn label="Submit" type="submit" color="primary" />
          <q-btn
            label="Reset"
            type="reset"
            color="primary"
            flat
            class="q-ml-sm"
          />
        </div>
      </q-form>
    </div>
  </q-page>
</template>

<script setup lang="ts">
defineOptions({
  name: 'IndexPage',
});

import { ref, onMounted } from 'vue';
import { useQuasar } from 'quasar';
// Store'u import etme
import { useHolidayStore } from 'src/stores/officalHoliday-store';

// Define reactive variables with appropriate types
const accept = ref<boolean>(false);
const official = ref<boolean>(true);
const weekend = ref<boolean>(true);

const $q = useQuasar();

// Define the onSubmit function
const onSubmit = () => {
  if (accept.value !== true) {
    $q.notify({
      color: 'red-5',
      textColor: 'white',
      icon: 'warning',
      message: 'You need to accept the license and terms first',
    });
  } else {
    $q.notify({
      color: 'green-4',
      textColor: 'white',
      icon: 'cloud_done',
      message: 'Submitted',
    });
  }
};

// Define reactive variables with appropriate types
const date = ref<string>(''); // Başlangıçta boş bırakıyoruz günümüz tarihini
const events = ref<string[]>([]); // Resmi tatil tarihleri buraya yüklenecek

// EntruseHolidayStore'u kullanma
const holidayStore = useHolidayStore();

// Mounted olduğunda entryStore'dan verileri çekme
onMounted(() => {
  holidayStore.fetchEntries();
  events.value = holidayStore.officalHolidays.map((holiday) => holiday.date); // Tatil tarihlerini al ve `events` dizisine ata
  console.log(events.value);
});

// Define the onReset function
const onReset = () => {
  accept.value = false;
};

// Geçerli tarih aralığını kontrol eden fonksiyon
const validDates = (date: string) => {
  const today = new Date();
  const formattedToday = `${today.getFullYear()}/${String(
    today.getMonth() + 1
  ).padStart(2, '0')}/${String(today.getDate()).padStart(2, '0')}`;

  const endOf2025 = '2025/12/31'; // 2025 yılı sonu
  return date >= formattedToday && date <= endOf2025;
};

// Güncel tarihi 'yyyy/mm/dd' formatında almak için fonksiyon
const getToday = (): string => {
  const today = new Date();
  return `${today.getFullYear()}/${String(today.getMonth() + 1).padStart(
    2,
    '0'
  )}/${String(today.getDate()).padStart(2, '0')}`;
};

// Başlangıç olarak bugünün tarihini belirleyelim
date.value = getToday();
</script>
