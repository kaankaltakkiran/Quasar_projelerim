<template>
  <div>
    <q-select
      v-model="localDepartureStation"
      :options="stationStore.stationNames"
      label="Gidiş"
      transition-show="scale"
      transition-hide="scale"
      filled
      class="q-mb-md"
    />
    <q-select
      v-model="localArrivalStation"
      :options="arrivalOptions"
      label="Dönüş"
      transition-show="scale"
      transition-hide="scale"
      filled
      class="q-mb-md"
    />
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, watchEffect } from 'vue';
import { useStationStore } from 'src/stores/location-store';

// piniadaki store'u kullanmak için
const stationStore = useStationStore();

// Gidiş ve dönüş yerlerini tutacak değişkenler
const localDepartureStation = ref<string | null>(null);
const localArrivalStation = ref<string | null>(null);
const arrivalOptions = ref<string[]>([]);

// Dönüş seçeneklerini güncelleyen fonksiyon
const updateArrivalOptions = (departureStation: string | null) => {
  if (departureStation) {
    stationStore.updateArrivalOptions(departureStation);
    arrivalOptions.value = stationStore.arrivalOptions;
    localArrivalStation.value =
      arrivalOptions.value.length > 0 ? arrivalOptions.value[0] : null;
  }
};
// İlk yüklemede istasyonları çekelim
onMounted(async () => {
  await stationStore.fetchStations();
  localDepartureStation.value =
    stationStore.stationNames.length > 0 ? stationStore.stationNames[0] : null;
  updateArrivalOptions(localDepartureStation.value); // İlk yüklemede dönüş seçeneklerini güncelleyelim
});

// Gidiş yeri değiştiğinde dönüş yerlerini güncelleyelim
watchEffect(() => {
  updateArrivalOptions(localDepartureStation.value);
  // Gidiş değiştiğinde dönüşü sıfırla
  localArrivalStation.value = null;
});
</script>
