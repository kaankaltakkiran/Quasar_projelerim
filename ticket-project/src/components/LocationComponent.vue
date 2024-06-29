<template>
  <div>
    <q-select
      v-model="localDepartureStation"
      :options="stationNames"
      label="Gidiş"
      transition-show="scale"
      transition-hide="scale"
      filled
      @update:model-value="handleDepartureChange"
      :rules="[(val) => !!val || 'Lütfen seçim yapınız']"
    />
    <q-select
      v-model="localArrivalStation"
      :options="arrivalOptions"
      label="Dönüş"
      transition-show="scale"
      transition-hide="scale"
      filled
      :rules="[(val) => !!val || 'Lütfen seçim yapınız']"
    />
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, computed, watch } from 'vue';
import { useStationStore } from 'src/stores/location-store';

// Props tanımlandı
const props = defineProps({
  departureStation: {
    type: String,
    default: undefined,
  },
  arrivalStation: {
    type: String,
    default: undefined,
  },
});

// Emits tanımlandı
const emits = defineEmits(['update:departureStation', 'update:arrivalStation']);

// Store tanımlandı
const stationStore = useStationStore();

// Reaktif değişkenler tanımlandı
const localDepartureStation = ref<string>(props.departureStation ?? '');
const localArrivalStation = ref<string>(props.arrivalStation ?? '');

// Store'dan istasyon isimleri ve varış seçenekleri alındı
const stationNames = computed(() => stationStore.stationNames);
const arrivalOptions = ref<Array<{ label: string; value: string }>>([]);

// Gidiş istasyonu değiştiğinde store güncelleniyor
const handleDepartureChange = (selected: string) => {
  stationStore.handleDepartureChange(selected);
  localArrivalStation.value = ''; // Dönüş istasyonunu sıfırla
  updateArrivalStationOptions();
};

// Varış istasyon seçeneklerini güncelle
const updateArrivalStationOptions = () => {
  stationStore.updateArrivalOptions(localDepartureStation.value);
  arrivalOptions.value = stationStore.arrivalOptions.map((option) => ({
    label: option,
    value: option,
  }));
};

// Bileşen mount edildiğinde store'dan veriler çekildi
onMounted(async () => {
  await stationStore.fetchStations();
  if (!props.departureStation) {
    localDepartureStation.value = stationNames.value[0] ?? ''; // İlk değeri ayarla
    handleDepartureChange(localDepartureStation.value); // Gidiş istasyonunu değiştir
  }
  updateArrivalStationOptions();
});

// Watchers tanımlandı
watch(localDepartureStation, (newValue) => {
  emits('update:departureStation', newValue);
  updateArrivalStationOptions();
});
watch(localArrivalStation, (newValue) => {
  emits('update:arrivalStation', newValue);
});

// Props değişikliklerini izleyin
watch(
  () => props.departureStation,
  (newDeparture) => {
    localDepartureStation.value = newDeparture ?? '';
    if (!newDeparture) {
      localArrivalStation.value = '';
    }
  }
);

watch(
  () => props.arrivalStation,
  (newArrival) => {
    localArrivalStation.value = newArrival ?? '';
  }
);
</script>
