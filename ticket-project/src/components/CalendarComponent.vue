<template>
  <div class="q-mt-md row justify-center q-gutter-x-md">
    <div class="col-lg-3 col-md-6 col-12 q-pa-md">
      <q-input
        filled
        v-model="selectedDate"
        mask="date"
        @click="showDatepicker = true"
      >
        <template v-slot:append>
          <q-icon
            name="event"
            class="cursor-pointer"
            @click="showDatepicker = true"
          />
        </template>
      </q-input>

      <q-date
        v-model="selectedDate"
        :navigation-min-year-month="minYearMonth"
        :navigation-max-year-month="maxYearMonth"
        :options="options"
        @update:model-value="onDateUpdate"
        :events="eventDates"
        :event-color="getEventColor"
        :modal="true"
        :persistent="true"
        v-if="showDatepicker"
      />
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue';
import { date } from 'quasar'; // Quasar'dan date modülünü içe aktar

const holidays = [
  { date: '2024/01/01', label: 'Yılbaşı' },
  { date: '2024/04/09', label: 'Ramazan Bayramı Arifesi' },
  { date: '2024/04/10', label: 'Ramazan Bayramı 1. Gün' },
  { date: '2024/04/11', label: 'Ramazan Bayramı 2. Gün' },
  { date: '2024/04/12', label: 'Ramazan Bayramı 3. Gün' },
  { date: '2024/04/23', label: 'Ulusal Egemenlik ve Çocuk Bayramı' },
  { date: '2024/05/01', label: 'Emek ve Dayanışma Günü' },
  { date: '2024/05/19', label: 'Atatürk’ü Anma, Gençlik ve Spor Bayramı' },
  { date: '2024/06/15', label: 'Kurban Bayramı Arifesi' },
  { date: '2024/06/16', label: 'Kurban Bayramı 1. Gün' },
  { date: '2024/06/17', label: 'Kurban Bayramı 2. Gün' },
  { date: '2024/06/18', label: 'Kurban Bayramı 3. Gün' },
  { date: '2024/06/19', label: 'Kurban Bayramı 4. Gün' },
  { date: '2024/07/15', label: 'Demokrasi ve Millî Birlik Günü' },
  { date: '2024/08/30', label: 'Zafer Bayramı' },
  { date: '2024/10/28', label: 'Cumhuriyet Bayramı Arifesi' },
  { date: '2024/10/29', label: 'Cumhuriyet Bayramı' },
  { date: '2024/12/31', label: 'Yılbaşı Gecesi' },
];

// Tatil günlerinin tarihlerini al
const eventDates = holidays.map((event) => event.date);

// Event color getter function
const getEventColor = (date: string): string => {
  return eventDates.includes(date) ? 'teal' : '';
};

const showDatepicker = ref(false);
const selectedDate = ref(date.formatDate(new Date(), 'YYYY/MM/DD')); // Geçerli tarihi seçili tarih olarak ayarla

// Geçerli yıl ve ayı al
const currentYear = new Date().getFullYear();
const currentMonth = String(new Date().getMonth() + 1).padStart(2, '0'); // Ay değerini iki haneli yapmak için padStart kullanılır

// Min ve max yıl-ay değerlerini ayarla
const minYearMonth = ref(`${currentYear}/${currentMonth}`);
const maxYearMonth = ref(`${currentYear}/12`);

// Options fonksiyonu ile seçilebilir tarihleri tanımla
const options = computed(() => {
  const today = new Date();
  const endDate = new Date(2025, 0, 1); // 2024/12/31 tarihine kadar seçilir

  const optionsArray = [];
  let dateToCheck = new Date(today);

  while (dateToCheck <= endDate) {
    optionsArray.push(date.formatDate(dateToCheck, 'YYYY/MM/DD'));
    dateToCheck.setDate(dateToCheck.getDate() + 1);
  }

  return optionsArray;
});

const onDateUpdate = (value: string) => {
  selectedDate.value = value;
  showDatepicker.value = false; // Tarih seçildikten sonra datepicker'ı kapat
};
</script>
