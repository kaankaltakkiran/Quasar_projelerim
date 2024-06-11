<template>
  <div class="q-mt-md row justify-center q-gutter-x-md">
    <!-- 1. Satır -->
    <div class="col-lg-3 col-md-6 col-12 q-pa-md">
      <InfoComponent></InfoComponent>
    </div>
    <div class="col-lg-3 col-md-6 col-12 q-pa-md">
      <InfoComponent></InfoComponent>
    </div>
    <div class="col-lg-3 col-md-6 col-12 q-pa-md">
      <InfoComponent></InfoComponent>
    </div>
  </div>
  <!-- 2. Satır -->
  <div class="q-mt-md row justify-center q-gutter-x-md">
    <div class="col-lg-3 col-md-6 col-12 q-pa-md">
      <InfoComponent></InfoComponent>
    </div>
  </div>
</template>
<script setup lang="ts">
import InfoComponent from 'src/components/InfoComponent.vue';
defineOptions({
  name: 'PaymentPage',
});
</script>
