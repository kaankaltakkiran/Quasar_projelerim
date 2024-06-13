<template>
  <div class="q-mt-md row justify-center q-gutter-x-md">
    <!-- 1. Satır -->
    <div class="col-lg-3 col-md-6 col-12 q-pa-md">
      <q-date
        v-model="date"
        :events="eventDates"
        :event-color="getEventColor"
      />
      {{ date }}
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';

const date = ref<string>('2024/06/01');
const holidays = [
  {
    date: '2024/01/01',
    label: 'Yılbaşı',
  },
  {
    date: '2024/04/09',
    label: 'Ramazan Bayramı Arifesi',
  },
  {
    date: '2024/04/10',
    label: 'Ramazan Bayramı 1. Gün',
  },
  {
    date: '2024/04/11',
    label: 'Ramazan Bayramı 2. Gün',
  },
  {
    date: '2024/04/12',
    label: 'Ramazan Bayramı 3. Gün',
  },
  {
    date: '2024/04/23',
    label: 'Ulusal Egemenlik ve Çocuk Bayramı',
  },
  {
    date: '2024/05/01',
    label: 'Emek ve Dayanışma Günü',
  },
  {
    date: '2024/05/19',
    label: 'Atatürk’ü Anma, Gençlik ve Spor Bayramı',
  },
  {
    date: '2024/06/15',
    label: 'Kurban Bayramı Arifesi',
  },
  {
    date: '2024/06/16',
    label: 'Kurban Bayramı 1. Gün',
  },
  {
    date: '2024/06/17',
    label: 'Kurban Bayramı 2. Gün',
  },
  {
    date: '2024/06/18',
    label: 'Kurban Bayramı 3. Gün',
  },
  {
    date: '2024/06/19',
    label: 'Kurban Bayramı 4. Gün',
  },
  {
    date: '2024/07/15',
    label: 'Demokrasi ve Millî Birlik Günü',
  },
  {
    date: '2024/08/30',
    label: 'Zafer Bayramı',
  },
  {
    date: '2024/10/28',
    label: 'Cumhuriyet Bayramı Arifesi',
  },
  {
    date: '2024/10/29',
    label: 'Cumhuriyet Bayramı',
  },
  {
    date: '2024/12/31',
    label: 'Yılbaşı Gecesi',
  },
];

// güncel tarihi al ve seçili olsun
const currentDate = new Date();

// Tatil günlerinin tarihlerini al
const eventDates = holidays.map((event) => event.date);

// Event color getter function
const getEventColor = (date: string): string => {
  const selectedDate = new Date(date);
  if (selectedDate.getTime() < currentDate.getTime()) {
    return 'teal';
  }
  return '';
};
</script>
