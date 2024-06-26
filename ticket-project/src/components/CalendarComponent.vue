<template>
  <q-input
    filled
    v-model="localSelectedDate"
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
    v-model="localSelectedDate"
    :navigation-min-year-month="minYearMonth"
    :navigation-max-year-month="maxYearMonth"
    :options="options"
    @update:model-value="onDateUpdate"
    @navigation="onNavigation"
    :events="eventDates"
    :event-color="getEventColor"
    :modal="true"
    :persistent="true"
    v-if="showDatepicker"
    class="custom-q-date"
  >
    <template v-slot:default>
      <div class="row items-center justify-end q-mt-md">
        <q-btn
          @click="showDatepicker = false"
          label="Close"
          color="primary"
          flat
        />
      </div>
      <div v-if="filteredHolidays.length" class="q-mt-md">
        <template v-for="holiday in filteredHolidays" :key="holiday.date">
          <span class="text-caption">- {{ formatHoliday(holiday) }}</span>
          <br />
        </template>
      </div>
    </template>
  </q-date>
  <div class="q-mt-md">
    <q-btn
      label="Bugün"
      color="primary"
      @click="setSelectedDate(todayDate)"
      class="q-mr-sm q-mb-md"
      icon-right="today"
    />
    <q-btn
      label="Yarın"
      color="secondary"
      @click="setSelectedDate(tomorrowDate)"
      class="q-mb-md"
      icon-right="date_range"
    />
  </div>
</template>

<script setup lang="ts">
import { ref, computed, watch } from 'vue';
import { date } from 'quasar';

//emit ile parent component'e veri göndermek için kullanılır
const emits = defineEmits(['update:modelValue']);
//props tanımlanır
const props = defineProps({
  modelValue: String,
});

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

//tüm tatil tarihlerini alır
const eventDates = holidays.map((event) => event.date);

//tatil tarihlerine göre renk belirler
const getEventColor = (date: string): string => {
  return eventDates.includes(date) ? 'teal' : '';
};

//datepicker'ın gösterilip gösterilmeyeceğini belirler
const showDatepicker = ref(false);
const localSelectedDate = ref<string>(
  props.modelValue || date.formatDate(new Date(), 'YYYY/MM/DD')
);

//şu anki yıl ve ayı alır
const currentYear = new Date().getFullYear();
const currentMonth = String(new Date().getMonth() + 1).padStart(2, '0');
//min ve max yıl ve ayı belirler
const minYearMonth = ref<string>(`${currentYear}/${currentMonth}`);
const maxYearMonth = ref<string>(`${currentYear}/12`);

//tüm tarih seçeneklerini alır
const options = computed<string[]>(() => {
  const today = new Date();
  const endDate = new Date(2025, 0, 1);

  const optionsArray: string[] = [];
  let dateToCheck = new Date(today);

  while (dateToCheck <= endDate) {
    optionsArray.push(date.formatDate(dateToCheck, 'YYYY/MM/DD'));
    dateToCheck.setDate(dateToCheck.getDate() + 1);
  }

  return optionsArray;
});
//bugün ve yarın tarihlerini alır
const todayDate: string = date.formatDate(new Date(), 'YYYY/MM/DD');
const tomorrowDate: string = (() => {
  const tomorrow = new Date();
  tomorrow.setDate(tomorrow.getDate() + 1);
  return date.formatDate(tomorrow, 'YYYY/MM/DD');
})();

//seçilen yıl ve aydaki tatil tarihlerini alır
const getMonthEvents = (
  year: number,
  month: number
): { date: string; label: string }[] => {
  return holidays.filter((holiday) => {
    const holidayDate = new Date(holiday.date);
    return (
      holidayDate.getFullYear() === year && holidayDate.getMonth() === month
    );
  });
};
//filtrelenmiş tatil tarihlerini alır
const filteredHolidays = ref<{ date: string; label: string }[]>([]);
const selectedMonthYear = ref<string>('');

//seçilen tarihi günceller
watch(
  localSelectedDate,
  (newDate) => {
    emits('update:modelValue', newDate);
    const [year, month] = newDate.split('/').map(Number);
    selectedMonthYear.value = `${year} / ${String(month).padStart(2, '0')}`;
    filteredHolidays.value = getMonthEvents(year, month - 1);
  },
  { immediate: true }
);
//navigasyon işlemlerini yapar
const onNavigation = (view: { year: number; month: number }) => {
  selectedMonthYear.value = `${view.year} / ${String(view.month).padStart(
    2,
    '0'
  )}`;
  filteredHolidays.value = getMonthEvents(view.year, view.month - 1);
};

//seçilen tarihi günceller
const setSelectedDate = (dateString: string) => {
  localSelectedDate.value = dateString;
  showDatepicker.value = false;
};

//tarihi günceller
const onDateUpdate = (value: string) => {
  localSelectedDate.value = value;
  showDatepicker.value = false;
};

//tatil tarihini biçimler
const formatHoliday = (holiday: { date: string; label: string }): string => {
  const dateObj = new Date(holiday.date);
  const day = dateObj.getDate();
  const monthName = date.formatDate(dateObj, 'MMMM');
  return `${day} ${monthName} - ${holiday.label}`;
};
</script>

<style>
.custom-q-date .q-date__header {
  display: none;
}
</style>
