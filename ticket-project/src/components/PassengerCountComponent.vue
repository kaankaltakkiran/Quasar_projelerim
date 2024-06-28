<!-- DenemeComponent.vue -->

<template>
  <div>
    <q-input
      filled
      v-model="localBusName"
      label="Otobüs Adı *"
      lazy-rules
      :rules="[ (val: any) => val && val.length > 0 || 'Lütfen boş bırakmayınız']"
    />
    <q-select
      label="Yolcu Sayısı *"
      transition-show="flip-up"
      transition-hide="flip-down"
      filled
      v-model="localSelect"
      :options="options"
      lazy-rules
      :rules="[ (val: any) => val && val.length > 0 || 'Lütfen bir seçim yapınız' ]"
    />
  </div>
</template>

<script setup lang="ts">
import { ref, watch } from 'vue';

//passengerCount seçenekleri
const options = ['1', '2', '3', '4', '5'];

// Props tanımlama
const props = defineProps({
  busName: {
    type: String,
    default: undefined,
  },
  passengerCount: {
    type: String,
    default: undefined,
  },
});

const emits = defineEmits(['update:passengerCount', 'update:busName']);
// Yerel değişken
const localSelect = ref(props.passengerCount);
const localBusName = ref(props.busName);

// Props değiştiğinde yerel değeri güncelle
watch(localSelect, (newValue) => {
  emits('update:passengerCount', newValue);
});
watch(localBusName, (newValue) => {
  emits('update:busName', newValue);
});

//props.select değiştiğinde localSelect değerini güncelleme
watch(props, (newProps) => {
  localSelect.value = newProps.passengerCount;
});
watch(props, (newProps) => {
  localBusName.value = newProps.busName;
});
</script>
