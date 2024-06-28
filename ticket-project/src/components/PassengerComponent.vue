<template>
  <q-card>
    <q-card-section>
      <h5 class="text-center">Kişisel Bilgiler</h5>
      <q-separator />
      <div>
        <div v-for="index in passengerCount" :key="index">
          <div class="text-center">
            <span class="text-caption">Koltuk: {{ index }}</span>
          </div>
          <q-form @submit="onSubmit(index)" @reset="onReset(index)">
            <!-- index - 1 kullanımı listelerde 0'dan başladığı için -->
            <q-input
              filled
              v-model="nameSurnames[index - 1]"
              :label="`${index}. Yolcu adı ve soyadı * `"
              hint="Ad ve soyadınızı giriniz"
              :rules="nameSurnameRules"
            />
            <div class="row justify-end">
              <q-checkbox
                v-model="isNonCitizens[index - 1]"
                label="T.C vatandaşı değilim"
                class="text-caption"
              />
            </div>
            <div v-if="!isNonCitizens[index - 1]">
              <q-input
                filled
                type="tel"
                v-model="tcNos[index - 1]"
                :label="`${index}. Yolcu T.C kimlik no *`"
                hint="T.C kimlik
              numarası giriniz"
                lazy-rules
                :rules="tcNoRules"
                mask="###########"
              />
            </div>
            <div v-else>
              <q-select
                filled
                v-model="selectedCountries[index - 1]"
                :label="`${index}. Yolcu Ülke * `"
                :options="travelStore.countryOptions"
              />
              <q-input
                filled
                v-model="passportNos[index - 1]"
                class="q-mt-md"
                :label="`${index}. Yolcu Pasaport Numarası *`"
                hint="Pasaport numaranızı giriniz"
                lazy-rules
                :rules="passportNoRules"
              />
            </div>
          </q-form>
        </div>
      </div>
    </q-card-section>
  </q-card>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue';
import { useTravelStore } from 'src/stores/travel-info-store';

// T.C kimlik no
const tcNos = ref<string[]>([]);
// Ad ve soyad
const nameSurnames = ref<(string | null)[]>([]);
// Pasaport no
const passportNos = ref<string[]>([]);

// T.C vatandaşı değilim checkbox durumu
const isNonCitizens = ref<boolean[]>([false, false, false, false, false]);

// Seçilen ülke
const selectedCountries = ref<string[]>([]);

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

// Store'u kullan
const travelStore = useTravelStore();

// Passenger count'ı computed property olarak ayarla
const passengerCount = computed(() => {
  const count = parseInt(travelStore.travelInfo.passengerCount, 10);
  return isNaN(count) || count <= 0 ? 1 : count;
});

const onSubmit = (index: number) => {
  // Formu gönderme işlemleri burada yapılabilir
  console.log({
    nameSurname: nameSurnames.value[index - 1],
    tcNo: tcNos.value[index - 1],
    passportNo: passportNos.value[index - 1],
    isNonCitizen: isNonCitizens.value[index - 1],
    selectedCountry: selectedCountries.value[index - 1],
  });
};

const onReset = (index: number) => {
  nameSurnames.value[index - 1] = null;
  tcNos.value[index - 1] = '';
  passportNos.value[index - 1] = '';
  isNonCitizens.value[index] = false;
  selectedCountries.value[index - 1] = '';
};

// JSON verisini almak için onMounted kullanarak fetchCountries fonksiyonunu çağır
onMounted(async () => {
  await travelStore.fetchCountries();
});
</script>
