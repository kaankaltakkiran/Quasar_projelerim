<template>
  <q-card>
    <q-card-section>
      <h5 class="text-center">İletişim Bilgileri</h5>
      <q-separator />
      <div class="q-pa-md" style="max-width: 400px">
        <q-form @submit="onSubmit" @reset="onReset" class="q-gutter-md">
          <q-input
            filled
            v-model="email"
            label="Email *"
            hint="Email adresinizi giriniz"
            lazy-rules
            :rules="emailRules"
          />

          <q-input
            filled
            type="tel"
            v-model="formattedPhoneNumber"
            class="q-mt-md"
            label="Telefon Numarası *"
            hint="Telefon Numaranızı giriniz"
            mask="# ### ### ####"
            lazy-rules
            :rules="phoneRules"
            @input="formatPhoneNumber"
          />
          <q-checkbox
            v-model="accept"
            class="text-caption q-pt-md"
            label="Biletimi ÜCRETSİZ SMS ile alıp, kampanya ve duyuruları ticari elektronik ileti olarak almayı ve kişisel verilerimin pazarlama amacıyla işlenmesini onaylıyorum"
          />

          <div>
            <q-btn label="Submit" type="submit" color="primary" />
          </div>
        </q-form>
      </div>
    </q-card-section>
  </q-card>
</template>

<script setup lang="ts">
import { useQuasar } from 'quasar';
import { ref } from 'vue';

const $q = useQuasar();
// form elamanları
const email = ref<string | null>(null);
const phoneNumber = ref<string | null>(null);
const formattedPhoneNumber = ref<string>('');
const accept = ref<boolean>(false);

//email ve telefon numarası için kurallar
const emailRules = [
  (val: string) => !!val || 'Lütfen email adresinizi giriniz',
  (val: string) => {
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailPattern.test(val) || 'Geçersiz email adresi';
  },
];

const phoneRules = [
  (val: string) =>
    (val && val.length > 0) || 'Lütfen telefon numaranızı giriniz',
  (val: string) =>
    (val && val.replace(/\D/g, '').length === 11) ||
    'Telefon numarası 11 hane olmalıdır', // 11 hane kontrolü
  (val: string) =>
    (val && val.startsWith('0 50')) || 'Telefon numarası 0 50 ile başlamalıdır', // 0 50 ile başlama kontrolü
];

const formatPhoneNumber = (val: string | null) => {
  if (val === null) return;

  const rawPhoneNumber = val.replace(/\D/g, '').slice(0, 11);

  let formatted = rawPhoneNumber.replace(/(\d{1})/, '$1 ').trim(); // İlk rakamdan sonra bir boşluk ekle
  if (formatted.length > 4 && formatted.length <= 8) {
    // İlk üç rakamdan sonra bir boşluk ekle
    formatted = formatted.replace(/(\d{4})/, '$1 ').trim();
  }
  if (formatted.length > 8 && formatted.length <= 12) {
    // İlk altı rakamdan sonra bir boşluk ekle
    formatted = formatted.replace(/(\d{7})/, '$1 ').trim();
  }
  if (formatted.length > 12 && formatted.length <= 15) {
    // İlk dokuz rakamdan sonra bir boşluk ekle
    formatted = formatted.replace(/(\d{10})/, '$1 ').trim();
  }

  formattedPhoneNumber.value = formatted;
};
const onSubmit = () => {
  if (!accept.value) {
    $q.notify({
      color: 'red-5',
      textColor: 'white',
      icon: 'warning',
      message: 'Lütfen onay kutucuğunu işaretleyiniz',
    });
  } else {
    $q.notify({
      color: 'green-4',
      textColor: 'white',
      icon: 'cloud_done',
      message: 'Form başarıyla gönderildi',
    });
  }
};

const onReset = () => {
  email.value = null;
  phoneNumber.value = null;
  accept.value = false;
};
</script>
