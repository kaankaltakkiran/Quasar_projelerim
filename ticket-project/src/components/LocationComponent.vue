<template>
  <h2 class="text-center text-red">Gidiş/Dönüş Yeri Seçimi</h2>
  <div class="q-mt-md row justify-center q-gutter-x-md">
    <!-- 1. Satır -->
    <div class="col-lg-3 col-md-6 col-12 q-pa-md">
      <q-select
        v-model="model"
        :options="options"
        label="Gidiş"
        transition-show="scale"
        transition-hide="scale"
        filled
        style="width: 250px"
      />
      seçilen: {{ model }}
    </div>
    <div class="col-lg-3 col-md-6 col-12 q-pa-md">
      <q-select
        v-model="model"
        :options="options"
        label="Dönüş"
        transition-show="scale"
        transition-hide="scale"
        filled
        style="width: 250px"
      />
      seçilen: {{ model }}
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';

const model = ref<string | null>(null);
const options = ['Google', 'Facebook', 'Twitter', 'Apple', 'Oracle'];
</script>
