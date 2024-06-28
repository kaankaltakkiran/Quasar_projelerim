<!-- DenemeComponent.vue -->

<template>
  <div>
    <q-input
      filled
      v-model="localCompanyName"
      label="Firma Adı *"
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
const localSelect = ref(props.passengerCount);
const localCompanyName = ref(props.companyName);

// Props değiştiğinde yerel değeri güncelle
watch(localSelect, (newValue) => {
  emits('update:passengerCount', newValue);
});
watch(localCompanyName, (newValue) => {
  emits('update:companyName', newValue);
});

//props.select değiştiğinde localSelect değerini güncelleme
watch(props, (newProps) => {
  localSelect.value = newProps.passengerCount;
});
watch(props, (newProps) => {
  localCompanyName.value = newProps.companyName;
});
</script>
