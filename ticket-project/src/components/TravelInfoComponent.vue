<template>
  <div>
    <q-input
      filled
      v-model="localName"
      label="Otobüs Firması Adı"
      lazy-rules
      :rules="[ (val: any) => val && val.length > 0 || 'Otobüs firması adınızı giriniz' ]"
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

//props tanımlama
const props = defineProps({
  name: {
    type: String,
    default: undefined,
  },
  select: {
    type: String,
    default: undefined,
  },
});
//emit ile değişiklikleri dışarıya bildirme
const emits = defineEmits(['update:name', 'update:select']);

//localSelect ile props.select değerini izleme
const localName = ref(props.name);
const localSelect = ref(props.select);

watch(localName, (newValue) => {
  emits('update:name', newValue);
});
watch(localSelect, (newValue) => {
  emits('update:select', newValue);
});

//props.select değiştiğinde localSelect değerini güncelleme
watch(props, (newProps) => {
  localSelect.value = newProps.select;
  localName.value = newProps.name;
});
//select seçenekleri
const options = ['1', '2', '3', '4', '5'];
</script>
