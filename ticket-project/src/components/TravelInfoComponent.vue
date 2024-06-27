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
import { ref, watch } from 'vue';

//props tanımlama
const props = defineProps({
  select: {
    type: String,
    default: undefined,
  },
});
//emit ile değişiklikleri dışarıya bildirme
const emits = defineEmits(['update:name', 'update:age', 'update:select']);

//localSelect ile props.select değerini izleme
const localSelect = ref(props.select);

watch(localSelect, (newValue) => {
  emits('update:select', newValue);
});

//props.select değiştiğinde localSelect değerini güncelleme
watch(props, (newProps) => {
  localSelect.value = newProps.select;
});
//select seçenekleri
const options = ['1', '2', '3', '4', '5'];
</script>
