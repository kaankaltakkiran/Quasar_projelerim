<template>
  <q-card>
    <q-card-section>
      <h5 class="text-center">Ödeme Bilgileri</h5>
      <q-separator />
      <!-- Kart -->
      <div class="button-container">
        <q-btn
          color="white"
          class="text-center text-red-14 text-caption q-mt-md"
          disable
          text-color="black"
          label="Banka/Kredi Kartı"
        />
      </div>
      <q-img
        src="https://s3.eu-central-1.amazonaws.com/static.obilet.com/images/web/cards-782.png"
        class="q-mt-md"
        spinner-color="white"
      />
      <!-- Kart Bilgileri -->
      <div class="q-pa-md">
        <q-form @submit="onSubmit" @reset="onReset" class="q-gutter-md">
          <q-input
            filled
            v-model="kartNo"
            label="Kart Numarası *"
            mask="####  ####  ####  ####"
            lazy-rules
            fill-mask="#"
            :rules="kartNoRules"
          />
          <q-card-section class="row q-col-gutter-md">
            <!-- Tarih Input -->
            <q-input
              filled
              v-model="expiryDate"
              label="AA/YY"
              hint="AA/YY formatında giriniz"
              mask="##/##"
              fill-mask
              :rules="expiryDateRules"
              class="col"
            />

            <!-- CVC2 Input -->
            <q-input
              filled
              v-model="cvc"
              label="CVC2"
              hint="Kartınızın arkasındaki 3 haneli numara"
              mask="###"
              :rules="cvcRules"
              lazy-rules
              class="col"
            >
              <!-- Ccv info -->
              <template v-slot:append>
                <q-icon
                  name="help_outline"
                  class="cursor-pointer"
                  @click="showDialog = true"
                />
              </template>
            </q-input>
          </q-card-section>
          <q-checkbox
            v-model="accept"
            class="text-caption q-pt-md"
            label=" Ön Bilgilendirme Formu'nu , Mesafeli Satış Sözleşmesi'ni okudum ve onaylıyorum. Kişisel verilerin işlenmesine ilişkin Aydınlatma Metni’ni ve Çerez Politikası Metni'ni okudum. Kullanım Koşulları’nı kabul ediyorum. "
          />

          <div class="button-container q-mt-md">
            <q-btn label="Ödeme Yap" type="submit" color="secondary" />
          </div>
        </q-form>
      </div>
    </q-card-section>
  </q-card>
  <q-dialog v-model="showDialog">
    <q-card style="width: 500px; max-width: 80vw">
      <div class="row justify-end">
        <q-btn dense icon="close" flat @click="showDialog = false" />
      </div>
      <q-card-section>
        <div class="text-h6">CVC2 Nedir?</div>
        <q-img
          src="https://www.obilet.com/Content/images/img_cvc.png"
          class="q-mt-md"
          spinner-color="white"
        />
        <p class="text-center">Kartınızın arkasındaki son 3 rakam</p>
      </q-card-section>
      <q-card-actions align="right">
        <q-btn flat label="Kapat" color="primary" v-close-popup />
      </q-card-actions>
    </q-card>
  </q-dialog>
</template>

<script setup lang="ts">
import { useQuasar } from 'quasar';
import { ref } from 'vue';

const $q = useQuasar();
// form elamanları
const kartNo = ref<string>('');
const accept = ref<boolean>(false);
// Dialog gösterme durumu
const showDialog = ref<boolean>(false);
// Son kullanma tarihi
const expiryDate = ref<string>('');
// CVC2 numarası
const cvc = ref<string>('');

// Kart numarası doğrulama kuralları
const kartNoRules = [
  (val: string) => !!val || 'Lütfen kart numaranızı giriniz',
  (val: string) =>
    isValidCreditCard(val) || 'Geçersiz banka/kredi kartı numarası',
];

const isValidCreditCard = (val: string) => {
  const cleanVal = val.replace(/\s+/g, ''); // Boşlukları temizle
  if (!/^\d{16}$/.test(cleanVal)) {
    return false; // 16 haneli bir sayı değilse geçersiz
  }

  let sum = 0;
  for (let i = 0; i < cleanVal.length; i++) {
    let digit = parseInt(cleanVal[i], 10);
    if ((cleanVal.length - i) % 2 === 0) {
      digit *= 2;
      if (digit > 9) {
        digit -= 9;
      }
    }
    sum += digit;
  }
  return sum % 10 === 0;
};

const expiryDateRules = [
  (val: string) => !!val || 'Boş bırakılamaz',
  (val: string) =>
    /^\d{2}\/\d{2}$/.test(val) || 'Geçersiz format, MM/YY formatında giriniz',
  (val: string) => {
    const [month, year] = val.split('/').map(Number);
    return (
      (month >= 1 && month <= 12 && year >= 20 && year <= 30) ||
      'Geçersiz tarih, MM/YY formatında giriniz'
    );
  },
  (val: string) => {
    const [month, year] = val.split('/').map(Number);
    const currentDate = new Date();
    const currentMonth = currentDate.getMonth() + 1; // getMonth() 0-11 arasında değer döner, bu yüzden +1 ekliyoruz
    const currentYear = currentDate.getFullYear() % 100; // Son iki rakamı almak için mod 100

    return (
      year > currentYear ||
      (year === currentYear && month > currentMonth) ||
      'Geçersiz son kullanma tarihi'
    );
  },
];

// CVC2 numarası doğrulama kuralları
const cvcRules = [
  (val: string) => !!val || 'Boş bırakılamaz',
  (val: string) => /^\d{3}$/.test(val) || 'Geçersiz CVC2 numarası',
];
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
  kartNo.value = '';
  expiryDate.value = '';
  cvc.value = '';
  accept.value = false;
};
</script>

<style scoped>
.button-container {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100%; /* yükseklik ayarı*/
}
</style>
