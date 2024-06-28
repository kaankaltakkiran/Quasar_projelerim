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
          v-model:busName="busName"
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
import { ref } from 'vue';
//component import
import TravelInfoComponent from '../components/TravelInfoComponent.vue';
import LocationComponent from '../components/LocationComponent.vue';
import CalendarComponent from '../components/CalendarComponent.vue';

//form elamanları için ref tanımlamaları
const busName = ref<string | undefined>(undefined);
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
    !busName.value ||
    !passengerCount.value ||
    !selectedDate.value
  ) {
    $q.notify({
      color: 'red-5',
      textColor: 'white',
      icon: 'warning',
      message: 'All fields are required',
    });
  } else {
    console.log(
      departureStation.value,
      arrivalStation.value,
      busName.value || passengerCount.value,
      selectedDate.value
    );
    $q.notify({
      color: 'green-4',
      textColor: 'white',
      icon: 'cloud_done',
      message: 'Submitted',
    });
    onReset();
  }
};

//form reset işlemi
const onReset = () => {
  busName.value = undefined;
  passengerCount.value = undefined;
  // departureStation.value = undefined;
  //arrivalStation.value = undefined;
  selectedDate.value = undefined;
};
</script>
