<template>
  <div class="q-mt-md row justify-center q-gutter-x-md">
    <!-- 1. Satır -->
    <div class="col-lg-3 col-md-6 col-12 q-pa-md">
      <InfoComponent
        :info="{
          icon: 'directions_bus',
          title: 'Şirket 1',
          departure: 'İstanbul',
          arrival: 'Ankara',
          date: '15 Haziran Perşembe',
          time: '12:00',
          seat: '24A',
          ticketInfo:
            'Seferin 6 saat öncesine kadar biletinizi iptal edebilir ve değiştirebilirsiniz. Biletiniz açığa alınamaz.',
          notice: 'Otobüs Perşembe günü kalkacaktır.',
        }"
      />
    </div>
    <div class="col-lg-3 col-md-6 col-12 q-pa-md">
      <ContactComponent />
    </div>
    <div class="col-lg-3 col-md-6 col-12 q-pa-md">
      <CreditCartComponent />
    </div>
  </div>
  <!-- 2. Satır -->
  <div class="q-mt-md row justify-center q-gutter-x-md">
    <div class="col-lg-3 col-md-6 col-12 q-pa-md"></div>
  </div>
</template>
<script setup lang="ts">
import InfoComponent from 'src/components/InfoComponent.vue';
import ContactComponent from 'src/components/ContactComponent.vue';
import CreditCartComponent from 'src/components/CreditCardComponent.vue';

defineOptions({
  name: 'PaymentPage',
});
</script>
