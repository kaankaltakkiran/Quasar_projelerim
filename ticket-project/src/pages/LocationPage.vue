<template>
  <h2 class="text-center text-red">Gidiş/Dönüş Yeri Seçimi</h2>
  <div class="q-mt-md row justify-center q-gutter-x-md">
    <div class="col-lg-3 col-md-6 col-12 q-pa-md q-mb-md">
      <location-component />
    </div>
  </div>
</template>

<script setup lang="ts">
import LocationComponent from 'src/components/LocationComponent.vue';
</script>
