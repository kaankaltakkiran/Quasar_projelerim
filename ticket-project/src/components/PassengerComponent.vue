<template>
  <q-card>
    <q-card-section>
      <h5 class="text-center">Kişisel Bilgiler</h5>
      <q-separator />
      <div class="q-pa-md" style="max-width: 400px">
        <q-form @submit="onSubmit" @reset="onReset" class="q-gutter-md">
          <div class="text-center">
            <span class="text-caption">Koltuk:10</span>
          </div>
          <q-input
            filled
            v-model="nameSurname"
            label="Adı Ve Soyadı *"
            hint="Ad ve soyadınızı giriniz"
            :rules="nameSurnameRules"
          />
          <q-checkbox
            v-model="isNonCitizen"
            label="T.C vatandaşı değilim"
            class="q-mt-md"
          />
          <div v-if="!isNonCitizen">
            <q-input
              filled
              type="tel"
              v-model="tcNo"
              class="q-mt-md"
              label="T.C kimlik no *"
              hint="T.C kimlik numarası giriniz"
              lazy-rules
              :rules="tcNoRules"
              mask="###########"
            />
          </div>
          <div v-else>
            <q-select
              filled
              v-model="selectedCountry"
              label="Ülke"
              :options="countryOptions"
              class="q-mt-md"
            />
            <q-input
              filled
              v-model="passportNo"
              class="q-mt-md"
              label="Pasaport Numarası *"
              hint="Pasaport numaranızı giriniz"
              lazy-rules
              :rules="passportNoRules"
            />
          </div>
          <!-- <div>
            <q-btn label="Submit" type="submit" color="primary" />
          </div> -->
        </q-form>
      </div>
    </q-card-section>
  </q-card>
</template>

<script setup lang="ts">
import { ref, watch } from 'vue';

// T.C kimlik no
const tcNo = ref<string>('');
// Ad ve soyad
const nameSurname = ref<string | null>(null);
// Pasaport no
const passportNo = ref<string>('');

// T.C vatandaşı değilim checkbox durumu
const isNonCitizen = ref<boolean>(false);

// Seçilen ülke
const selectedCountry = ref<string>('');

// tc vatandaşı değilse tc no alanını temizle
watch(isNonCitizen, (newVal) => {
  if (newVal) {
    tcNo.value = '';
  }
});

// Ülke seçenekleri
const countryOptions = [
  { label: 'Almanya', value: 'DE' },
  { label: 'Fransa', value: 'FR' },
  { label: 'İngiltere', value: 'UK' },
  { label: 'Amerika', value: 'US' },
  { label: 'İtalya', value: 'IT' },
  { label: 'İspanya', value: 'ES' },
  { label: 'Kanada', value: 'CA' },
  { label: 'Avustralya', value: 'AU' },
  { label: 'Hollanda', value: 'NL' },
  { label: 'Belçika', value: 'BE' },
];

// Ad ve soyad doğrulama kuralları
const nameSurnameRules = [
  (val: string) => !!val || 'Boş bırakılamaz',
  (val: string) => val.length >= 3 || 'İsim en az 3 harfli olmalıdır',
  (val: string) => {
    if (val.length > 2) {
      const parts = val.trim().split(' ');
      if (parts.length < 2 || parts[1].length < 3) {
        return 'Yolcu soyadını yazmalısınız ve soyad en az 3 harfli olmalıdır';
      }
    }
    return true;
  },
];
// T.C kimlik no doğrulama kuralları
const tcNoRules = [
  (val: string) => !!val || 'Boş bırakılamaz',
  (val: string) => val.length === 11 || 'T.C kimlik numarası 11 hane olmalıdır',
  (val: string) => isValidTcNo(val) || 'Geçersiz T.C kimlik numarası',
];

const isValidTcNo = (tc: string) => {
  if (tc.length !== 11 || !/^[1-9][0-9]{10}$/.test(tc)) return false;
  const digits = tc.split('').map(Number);
  const sum1 = digits[0] + digits[2] + digits[4] + digits[6] + digits[8];
  const sum2 = digits[1] + digits[3] + digits[5] + digits[7];
  const check1 = (sum1 * 7 - sum2) % 10;
  const check2 = (sum1 + sum2 + digits[9]) % 10;
  return check1 === digits[9] && check2 === digits[10];
};

// Pasaport numarası doğrulama kuralları
const passportNoRules = [
  (val: string) => !!val || 'Boş bırakılamaz',
  (val: string) =>
    val.length >= 5 || 'Pasaport numarası en az 5 karakter olmalıdır',
];

const onSubmit = () => {
  // Formu gönderme işlemleri burada yapılabilir
};

const onReset = () => {
  nameSurname.value = '';
  tcNo.value = '';
  passportNo.value = '';
  isNonCitizen.value = false;
  selectedCountry.value = '';
};
</script>

<style scoped>
.button-container {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100%; /* Gerekirse yüksekliği ayarlayabilirsiniz */
}
</style>
