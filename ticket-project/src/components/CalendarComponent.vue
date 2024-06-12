<template>
  <div class="row justify-center">
    <div class="col-lg-3 col-md-6 col-12 q-pa-md">
      <q-date v-model="date" :events="events" />
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, Ref } from 'vue';

const date: Ref<string> = ref('2019/02/01');

const events = [
  '2019/02/01',
  '2019/02/05',
  '2019/02/06',
  '2019/02/09',
  '2019/02/23',
];
</script>
