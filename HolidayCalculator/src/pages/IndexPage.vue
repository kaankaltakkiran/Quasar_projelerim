<template>
  <q-page class="row items-center justify-evenly">
    <div class="q-pa-md" style="max-width: 400px">
      <h3>İzin Hesaplama</h3>
      <q-form @submit="onSubmit" @reset="onReset" class="q-gutter-md">
        <q-input
          v-model.number="dayNumber"
          outlined
          label="İzin Gün Sayısı"
          type="number"
          filled
          style="max-width: 290px"
          :rules="[(val) => val > 0 || 'Gün sayısı 0\'dan büyük olmalıdır !']"
          :min="1"
        />

        <q-date
          subtitle="İzin Aralığını Seçiniz"
          v-model="localSelectedDate"
          range
          :navigation-min-year-month="minYearMonth"
          :navigation-max-year-month="maxYearMonth"
          :events="events"
          :options="validDates"
          @update:model-value="onDateUpdate"
          @navigation="onNavigation"
        >
          <!-- takvim içine resmi tatil günleri -->
          <template v-slot:default>
            <div v-if="totalDays > 0" class="q-pa-md text-center">
              <div>
                <span class="text-h6 text-primary"
                  >İzin Başlangıç Tarihi: <br />
                </span>
                <span>{{
                  localSelectedDate.from
                    ? formatToFullDate(localSelectedDate.from)
                    : 'Seçilmedi'
                }}</span>
              </div>
              <div>
                <span class="text-h6 text-red">İzin Bitiş Tarihi: <br /></span>
                <span>{{
                  localSelectedDate.to
                    ? formatToFullDate(localSelectedDate.to)
                    : 'Seçilmedi'
                }}</span>
              </div>
              <div v-if="totalDays > 0" class="q-mt-md">
                <span class="text-h6 text-amber">
                  Toplam Gün Sayısı: <br />
                </span>
                <span>{{ totalDays }}</span>
              </div>
            </div>
            <!-- resmi tatil günü ve tarihi eşleşen veriler -->
            <div v-if="filteredHolidays.length" class="q-mt-md">
              <template v-for="holiday in filteredHolidays" :key="holiday.date">
                <span class="text-caption text-teal"
                  >- {{ holiday.label }}</span
                >
                <br />
              </template>
            </div>
          </template>
        </q-date>

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

        <div class="q-gutter-md">
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

import { ref, onMounted, computed, watch } from 'vue';
import { useQuasar } from 'quasar';
import { date as quasarDate } from 'quasar';

// Store'u import etme
import { useHolidayStore } from 'src/stores/officalHoliday-store';

// reaktif değişkenler
const accept = ref<boolean>(false);
const official = ref<boolean>(true);
const weekend = ref<boolean>(true);
const dayNumber = ref<number>(1);

// store'u kullanma
const holidayStore = useHolidayStore();

// Seçilen tarih aralığını tutan değişken (range özelliği için)
const localSelectedDate = ref<{ from: string; to: string }>({
  from: '',
  to: '',
});

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
    emits('update:modelValue', `${newDate.from} - ${newDate.to}`);
    const [year, month] = newDate.from.split('/').map(Number);
    selectedMonthYear.value = `${year} / ${String(month).padStart(2, '0')}`;
    filteredHolidays.value = getMonthEvents(year, month - 1);
  },
  // immediate: true ile başlangıçta çalışmasını sağlar
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
  localSelectedDate.value = {
    from: value.split(' - ')[0], // from tarihini ayır
    to: value.split(' - ')[1], // to tarihini ayır
  };
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

// reaktif değişkenler
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
  localSelectedDate.value = { from: '', to: '' }; // Tarih aralığını sıfırlar
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

/* const formatToDDMMYYYY = (date: string) => {
  return quasarDate.formatDate(date, 'DD/MM/YYYY');
}; */

// Toplam gün sayısını hesaplayan `computed` değişken
const totalDays = computed(() => {
  if (!localSelectedDate.value.from || !localSelectedDate.value.to) return 0;
  const fromDate = new Date(localSelectedDate.value.from);
  const toDate = new Date(localSelectedDate.value.to);
  const diffTime = toDate.getTime() - fromDate.getTime();
  return Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1; // İzin günlerini dahil eder
});

//seçilen tarihi tam tarih formatına çevirir
const formatToFullDate = (dateString: string) => {
  if (!dateString) return '';
  const date = new Date(dateString);

  return new Intl.DateTimeFormat('tr-TR', {
    weekday: 'long', // Haftanın günü
    day: 'numeric', // Gün (1-31)
    month: 'long', // Ay adı
    year: 'numeric', // Yıl (2024)
  }).format(date);
};

// Bugün tarihini alır
date.value = getToday();
</script>
