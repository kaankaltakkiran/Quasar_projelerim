<!-- DenemeComponent.vue -->

<template>
  <div>
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
import { ref, watch, defineProps, defineEmits } from 'vue';

// Props tanımlama
const props = defineProps({
  passengerCount: {
    type: String,
    default: undefined,
  },
});

const emits = defineEmits(['update:passengerCount']);
// Yerel değişken
const localSelect = ref(props.passengerCount);

// Props değiştiğinde yerel değeri güncelle
watch(localSelect, (newValue) => {
  emits('update:passengerCount', newValue);
});

//props.select değiştiğinde localSelect değerini güncelleme
watch(props, (newProps) => {
  localSelect.value = newProps.passengerCount;
});
//passengerCount seçenekleri
const options = ['1', '2', '3', '4', '5'];
</script>
