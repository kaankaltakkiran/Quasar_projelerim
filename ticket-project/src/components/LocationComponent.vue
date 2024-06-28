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

// Store tanımlandı
const stationStore = useStationStore();

// Reaktif değişkenler tanımlandı
const localDepartureStation = ref<string>(props.departureStation ?? '');
const localArrivalStation = ref<string>(props.arrivalStation ?? '');

// Store'dan istasyon isimleri ve varış seçenekleri alındı
const stationNames = computed(() => stationStore.stationNames);
const arrivalOptions = computed(() => stationStore.arrivalOptions);

// Gidiş istasyonu değiştiğinde store güncelleniyor
const handleDepartureChange = (selected: string) => {
  stationStore.handleDepartureChange(selected);
  localArrivalStation.value = stationStore.arrivalStation ?? '';
};

// Bileşen mount edildiğinde store'dan veriler çekildi
onMounted(async () => {
  await stationStore.fetchStations();
  localDepartureStation.value = stationNames.value[0] ?? '';
  stationStore.updateArrivalOptions(localDepartureStation.value);
  localArrivalStation.value =
    arrivalOptions.value.length > 0 ? arrivalOptions.value[0] : '';
});

// Emits tanımlandı
const emits = defineEmits(['update:departureStation', 'update:arrivalStation']);

// Watchers tanımlandı
watch(localDepartureStation, (newValue) =>
  emits('update:departureStation', newValue)
);
watch(localArrivalStation, (newValue) =>
  emits('update:arrivalStation', newValue)
);
// Reset için
watch(props, (newProps) => {
  localDepartureStation.value = newProps.departureStation ?? '';
  localArrivalStation.value = newProps.arrivalStation ?? '';
});
</script>
