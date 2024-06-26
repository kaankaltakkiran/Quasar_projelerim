<template>
  <h2 class="text-center text-red">Gidiş/Dönüş Yeri Seçimi</h2>
  <div class="q-mt-md row justify-center q-gutter-x-md">
    <div class="col-lg-3 col-md-6 col-12 q-pa-md">
      <q-select
        v-model="departureStation"
        :options="stationNames"
        label="Gidiş"
        transition-show="scale"
        transition-hide="scale"
        filled
        style="width: 250px"
        @update:model-value="handleDepartureChange"
      />
      <q-badge color="secondary" multi-line>
        Gidiş Yeri: {{ departureStation }}
      </q-badge>
    </div>
    <div class="col-lg-3 col-md-6 col-12 q-pa-md">
      <q-select
        v-model="arrivalStation"
        :options="arrivalOptions"
        label="Dönüş"
        transition-show="scale"
        transition-hide="scale"
        filled
        style="width: 250px"
      />
      <q-badge color="secondary" multi-line>
        Varış Yeri: {{ arrivalStation }}
      </q-badge>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, computed } from 'vue';
import { useStationStore } from 'src/stores/location-store';

//store tanımlandı
const stationStore = useStationStore();

//gidiş ve varış yerleri tanımlandı
const departureStation = ref<string | null>(null);
const arrivalStation = ref<string | null>(null);
//gidiş ve varış yerleri storendan alındı

const stationNames = computed(() => stationStore.stationNames);
const arrivalOptions = computed(() => stationStore.arrivalOptions);

//gidiş ve varış yerleri değiştiğinde store güncellendi
const handleDepartureChange = (selected: string | null) => {
  stationStore.handleDepartureChange(selected);
  arrivalStation.value = stationStore.arrivalStation;
};

//component mount edildiğinde store'dan veriler çekildi
onMounted(async () => {
  await stationStore.fetchStations();
  departureStation.value = stationNames.value[0];
  stationStore.updateArrivalOptions(departureStation.value);
  arrivalStation.value =
    arrivalOptions.value.length > 0 ? arrivalOptions.value[0] : null;
});
</script>
