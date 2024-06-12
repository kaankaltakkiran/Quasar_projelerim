<template>
  <div class="row justify-center">
    <div class="col-lg-3 col-md-6 col-12 q-pa-md">
      <q-date
        v-model="date"
        :events="eventDates"
        :event-color="getEventColor"
        mask="DD/MM/YYYY"
        :navigation-min-year-month="formattedCurrentDate"
        navigation-max-year-month="2024/12"
        :day-content="formatDayContent"
      />
      {{ date }}
      <!-- Aya göre tatil günleri -->
      <div v-if="selectedMonthEvents.length">
        <ul>
          <li v-for="event in selectedMonthEvents" :key="event.date">
            {{ event.label }}

            {{ event.date }}
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue';
import { date as quasarDate } from 'quasar';

// güncel tarihi al ve seçili olsun
const currentDate = new Date();
const formattedCurrentDate = quasarDate.formatDate(currentDate, 'YYYY/MM');
const date = ref(
  `${currentDate.getDate().toString().padStart(2, '0')}/${(
    currentDate.getMonth() + 1
  )
    .toString()
    .padStart(2, '0')}/${currentDate.getFullYear()}`
);
// Tatil günleri
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

// seçili tarihi formatla
const formatDayContent = (date: string): string => {
  return quasarDate.formatDate(date, 'DD/MM/YYYY');
};

// Seçili aydaki tatil günlerini getir
const selectedMonthEvents = computed(() => {
  const selectedMonth = date.value.split('/')[1]; // Ay bilgisini alma
  return holidays.filter((event) => event.date.split('/')[1] === selectedMonth);
});
</script>
