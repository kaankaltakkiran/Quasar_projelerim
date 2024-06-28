<template>
  <h4 class="text-red text-center">Yolcuk Bilgileri</h4>
  <div class="q-mt-md row justify-center">
    <div class="col-lg-3 col-md-6 col-12 q-pa-md q-gutter-md">
      <q-form @submit="onSubmit" @reset="onReset">
        <!--Gidiş Dönüş Componenti Başlangıç-->
        <LocationComponent
          v-model:departureStation="departureStation"
          v-model:arrivalStation="arrivalStation"
        />
        <!--Gidiş Dönüş Componenti Bitiş-->

        <!--Yolcu Sayısı Componenti Başlangıç-->
        <TravelInfoComponent
          v-model:passengerCount="passengerCount"
          v-model:companyName="companyName"
        />
        <!--Yolcu Sayısı Componenti Bitiş-->

        <!--Tarih Componenti Başlangıç-->
        <CalendarComponent v-model="selectedDate" />
        <!--Tarih Componenti Bitiş-->
        <div>
          <q-btn label="Submit" type="submit" color="primary" />
        </div>
      </q-form>
    </div>
  </div>
</template>

<script setup lang="ts">
import { useQuasar } from 'quasar';
import { ref, onBeforeUnmount } from 'vue';
import { useRouter } from 'vue-router';
import { useTravelStore } from 'src/stores/travel-info-store';
//component import
import TravelInfoComponent from '../components/TravelInfoComponent.vue';
import LocationComponent from '../components/LocationComponent.vue';
import CalendarComponent from '../components/CalendarComponent.vue';

// Store'u kullan
const travelStore = useTravelStore();

const router = useRouter();

//form elamanları için ref tanımlamaları
const companyName = ref<string | undefined>(undefined);
const passengerCount = ref<string | undefined>(undefined);
const departureStation = ref<string | undefined>(undefined);
const arrivalStation = ref<string | undefined>(undefined);
const selectedDate = ref<string | undefined>(undefined);

const $q = useQuasar();

//form submit işlemi
const onSubmit = () => {
  if (
    !departureStation.value ||
    !arrivalStation.value ||
    !companyName.value ||
    !passengerCount.value ||
    !selectedDate.value
  ) {
    $q.notify({
      color: 'red-5',
      textColor: 'white',
      icon: 'warning',
      message: 'Tüm alanları doldurunuz',
    });
  } else {
    // Store'a verileri güncelle
    travelStore.updateTravelInfo({
      companyName: companyName.value,
      departureStation: departureStation.value,
      arrivalStation: arrivalStation.value,
      passengerCount: passengerCount.value,
      selectedDate: selectedDate.value,
    });
    console.log('travelStore.travelInfo', travelStore.travelInfo);

    showLoadingAndRedirect(); //loading göster ve yönlendir
    onReset(); //form reset
  }
};

//loading ve yönlendirme işlemi
let timer: ReturnType<typeof setTimeout> | undefined;

const showLoadingAndRedirect = () => {
  $q.loading.show({
    message:
      'Lütfen Bekleyin !<br><span class="text-amber text-italic">Ödeme sayfasına yönlendiriliyorsunuz...</span>',
    html: true,
  });

  timer = setTimeout(() => {
    $q.loading.hide();
    timer = undefined;
    router.push('/payment');
  }, 4000);
};

onBeforeUnmount(() => {
  if (timer !== undefined) {
    clearTimeout(timer);
    $q.loading.hide();
  }
});

//form reset işlemi
const onReset = () => {
  companyName.value = undefined;
  passengerCount.value = undefined;
  // departureStation.value = undefined;
  //arrivalStation.value = undefined;
  selectedDate.value = undefined;
};
</script>
