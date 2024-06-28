<!-- DenemeComponent.vue -->

<template>
  <div>
    <q-select
      label="Firma Adı *"
      transition-show="flip-up"
      transition-hide="flip-down"
      filled
      v-model="localCompanyName"
      :options="companyOptions"
      lazy-rules
      :rules="[ (val: any) => val && val.length > 0 || 'Lütfen bir seçim yapınız' ]"
    />
    <q-select
      label="Yolcu Sayısı *"
      transition-show="flip-up"
      transition-hide="flip-down"
      filled
      v-model="localPassengerCount"
      :options="passengerCountOptions"
      lazy-rules
      :rules="[ (val: any) => val && val.length > 0 || 'Lütfen bir seçim yapınız' ]"
    />
  </div>
</template>

<script setup lang="ts">
import { ref, watch } from 'vue';

//yolcu sayısıseçenekleri
const passengerCountOptions = ['1', '2', '3', '4', '5'];

//firma seçenekleri
const companyOptions = [
  'Kamil Koç',
  'Metro Turizm',
  'Ulusoy',
  'Pamukkale Turizm',
  'Varan',
  'Özkaymak',
  'Nilüfer Turizm',
];

// Props tanımlama
const props = defineProps({
  companyName: {
    type: String,
    default: undefined,
  },
  passengerCount: {
    type: String,
    default: undefined,
  },
});

const emits = defineEmits(['update:passengerCount', 'update:companyName']);
// Yerel değişken
const localPassengerCount = ref(props.passengerCount);
const localCompanyName = ref(props.companyName);

// Props değiştiğinde yerel değeri güncelle
watch(localPassengerCount, (newValue) => {
  emits('update:passengerCount', newValue);
});
watch(localCompanyName, (newValue) => {
  emits('update:companyName', newValue);
});

//props.select değiştiğinde localPassengerCount değerini güncelleme
watch(props, (newProps) => {
  localPassengerCount.value = newProps.passengerCount;
});
watch(props, (newProps) => {
  localCompanyName.value = newProps.companyName;
});
</script>
