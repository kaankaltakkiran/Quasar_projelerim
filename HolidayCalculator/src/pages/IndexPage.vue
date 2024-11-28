<template>
  <q-page class="row items-center justify-evenly">
    <div class="q-pa-md" style="max-width: 400px">
      <h3>İzin Hesaplama</h3>
      <q-form @submit="onSubmit" @reset="onReset" class="q-gutter-md">
        <div class="q-gutter-md">
          <q-date
            subtitle="İzin Aralığını Seçiniz"
            v-model="localSelectedDate"
            :navigation-min-year-month="minYearMonth"
            :navigation-max-year-month="maxYearMonth"
            :events="events"
            :options="validDates"
            @update:model-value="onDateUpdate"
            @navigation="onNavigation"
          >
            <!-- takvim içine resmi tatil günleri -->
            <template v-slot:default>
              <div class="row items-center justify-end q-mt-md"></div>

              <!--    resmi tatil günü ve tarihi eşleşen veriler -->
              <div v-if="filteredHolidays.length" class="q-mt-md">
                <template
                  v-for="holiday in filteredHolidays"
                  :key="holiday.date"
                >
                  <span class="text-caption text-teal"
                    >- {{ holiday.label }}</span
                  >
                  <br />
                </template>
              </div>
            </template>
          </q-date>
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

// props tanımlama
const props = defineProps({
  modelValue: String,
});

import { ref, onMounted, watch } from 'vue';
import { useQuasar } from 'quasar';
import { date as quasarDate } from 'quasar';

// Store'u import etme
import { useHolidayStore } from 'src/stores/officalHoliday-store';

// reakitf değişkenler
const accept = ref<boolean>(false);
const official = ref<boolean>(true);
const weekend = ref<boolean>(true);

// store'u kullanma
const holidayStore = useHolidayStore();

// Seçilen tarihi tutan değişken
const localSelectedDate = ref<string>(
  props.modelValue || quasarDate.formatDate(new Date(), 'YYYY/MM/DD')
);

// Emit ile parent component'e veri göndermek için kullanılır
const emits = defineEmits(['update:modelValue']);
// takvimdeki tatilleri göstermek için kullanılır
const showDatepicker = ref(true);
const filteredHolidays = ref<{ date: string; label: string }[]>([]);
const selectedMonthYear = ref<string>('');

// Resmi tatilleri almak için kullanılır
const getMonthEvents = (
  year: number,
  month: number
): { date: string; label: string }[] => {
  return holidayStore.officalHolidays
    .filter((holiday) => {
      const holidayDate = new Date(holiday.date);
      return (
        holidayDate.getFullYear() === year && holidayDate.getMonth() === month
      );
    })
    .map((holiday) => {
      const holidayDate = new Date(holiday.date);
      const formattedDate = `${holidayDate.getDate()} ${quasarDate.formatDate(
        holidayDate,
        'MMMM'
      )}`;

      // Tarih ve tatil adı birleştiriyoruz
      return {
        date: holiday.date,
        label: `${formattedDate} - ${holiday.name}`, // Tarih ve tatil adı
      };
    });
};

// watch ile seçilen tarihi günceller
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

// Navigasyon işlemlerini yapar
const onNavigation = (view: { year: number; month: number }) => {
  selectedMonthYear.value = `${view.year} / ${String(view.month).padStart(
    2,
    '0'
  )}`;
  filteredHolidays.value = getMonthEvents(view.year, view.month - 1);
};

// Seçilen tarihi günceller
const onDateUpdate = (value: string) => {
  localSelectedDate.value = value;
  showDatepicker.value = false;
};

const $q = useQuasar();

// Handle form submission
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

// reakitf değişkenler
const date = ref<string>(''); // Başlangıçta boş bırakıyoruz günümüz tarihini
const events = ref<string[]>([]); // Resmi tatil tarihleri buraya yüklenecek

// Store'dan resmi tatil tarihlerini alır
onMounted(async () => {
  await holidayStore.fetchEntries(); // fetchEntries'in tamamlanmasını bekle
  if (holidayStore.officalHolidays.length > 0) {
    events.value = holidayStore.officalHolidays.map((holiday) => holiday.date);
    console.log('Resmi tatil sayısı ' + events.value.length);
  } else {
    console.error('Holidays are empty after fetchEntries');
  }
});

// Reset the form
const onReset = () => {
  accept.value = false;
};

// min ve max yıl ve ay aralığını ayarlar
const currentYear = new Date().getFullYear();
const currentMonth = String(new Date().getMonth() + 1).padStart(2, '0');

// min ve max yıl ve ay aralığını ayarlar
const minYearMonth = ref<string>(`${currentYear}/${currentMonth}`);
const maxYearMonth = ref<string>('2025/12'); // son tarih 2025 yılının sonu

// Tüm tarih seçeneklerini alır
const validDates = (date: string) => {
  const today = new Date();
  const formattedToday = `${today.getFullYear()}/${String(
    today.getMonth() + 1
  ).padStart(2, '0')}/${String(today.getDate()).padStart(2, '0')}`;

  const endOf2025 = '2025/12/31'; // 2025 yılı sonu
  return date >= formattedToday && date <= endOf2025;
};

// Bugün ve yarın tarihlerini alır
const getToday = (): string => {
  const today = new Date();
  return `${today.getFullYear()}/${String(today.getMonth() + 1).padStart(
    2,
    '0'
  )}/${String(today.getDate()).padStart(2, '0')}`;
};

// Bugün tarihini alır
date.value = getToday();
</script>
