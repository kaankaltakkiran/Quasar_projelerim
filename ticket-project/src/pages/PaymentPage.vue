<template>
  <div class="q-mt-md row justify-center q-gutter-x-md">
    <!-- 1. Satır -->
    <div class="col-lg-3 col-md-6 col-12 q-pa-md">
      <InfoComponent />
    </div>
    <div class="col-lg-3 col-md-6 col-12 q-pa-md">
      <ContactComponent />
      <div class="col-lg-3 col-md-6 col-12 q-pa-md">
        <PassengerComponent />
      </div>
    </div>
    <div class="col-lg-3 col-md-6 col-12 q-pa-md">
      <CreditCartComponent />
    </div>
  </div>
</template>
<script setup lang="ts">
import InfoComponent from 'src/components/InfoComponent.vue';
import ContactComponent from 'src/components/ContactComponent.vue';
import CreditCartComponent from 'src/components/CreditCardComponent.vue';
import PassengerComponent from 'src/components/PassengerComponent.vue';

defineOptions({
  name: 'PaymentPage',
});
</script>
