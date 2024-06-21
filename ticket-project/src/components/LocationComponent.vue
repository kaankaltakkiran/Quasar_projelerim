<template>
  <h2 class="text-center text-red">Gidiş/Dönüş Yeri Seçimi</h2>
  <div class="q-mt-md row justify-center q-gutter-x-md">
    <!-- 1. Satır -->
    <div class="col-lg-3 col-md-6 col-12 q-pa-md">
      <q-select
        v-model="departureStation"
        :options="stationNames"
        label="Gidiş"
        transition-show="scale"
        transition-hide="scale"
        filled
        style="width: 250px"
      />

      <q-badge color="secondary" multi-line>
        Gidiş Yeri: {{ departureStation }}
      </q-badge>
    </div>
    <div class="col-lg-3 col-md-6 col-12 q-pa-md">
      <q-select
        v-model="arrivalStation"
        :options="stationNames"
        label="Dönüş"
        transition-show="scale"
        transition-hide="scale"
        filled
        style="width: 250px"
      />

      <q-badge color="secondary" multi-line>
        Varış Yeri: {{ arrivalStation }}
      </q-badge>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { IStation } from 'src/models/model';

// Gidiş ve dönüş yerlerini tutacak değişkenler
const departureStation = ref<string | null>(null);
const arrivalStation = ref<string | null>(null);

// Station bilgileri
const stations: IStation[] = [
  { id: '9', name: 'ANKARA AŞTİ', targets: [3, 25, 12, 19, 11] },
  { id: '2', name: 'İSTANBUL( AVRUPA )', targets: [19, 11] },
  { id: '4', name: 'İSTANBUL( DUDULLU )', targets: [12, 11] },
  { id: '3', name: 'ÇANKIRI OTOGAR', targets: [3, 11] },
  { id: '6', name: 'KASTAMONU OTOGAR', targets: [3, 19, 11] },
  { id: '25', name: 'ALİBEYKÖY', targets: [3, 19, 11] },
  { id: '20', name: 'ARAÇ', targets: [3, 25, 12, 19, 11] },
  { id: '16', name: 'ATKARACALAR', targets: [3, 25, 12, 19, 11] },
  { id: '15', name: 'ÇERKEŞ', targets: [3, 12] },
  { id: '14', name: 'DEVREKANİ', targets: [19, 11] },
  { id: '17', name: 'GEBZE OTOGAR', targets: [3, 19] },
  { id: '24', name: 'GÜZELYALI', targets: [3, 5] },
  { id: '7', name: 'HAREM TERMİNALİ', targets: [7] },
  { id: '12', name: 'ILGAZ DOĞRUYOL', targets: [5, 7] },
  { id: '28', name: 'ILGAZ AKBAK', targets: [3, 25, 12, 19, 11] },
  { id: '27', name: 'ILGAZ KAYAK MERKEZİ', targets: [21, 28] },
  { id: '18', name: 'İZMİT ( KOCAELİ )', targets: [8, 9] },
  { id: '21', name: 'KARABÜK OTOGAR', targets: [17, 19, 21] },
  { id: '46', name: 'SAFRANBOLU', targets: [1, 11] },
  { id: '23', name: 'KAYNARCA', targets: [5, 7] },
  { id: '10', name: 'KORGUN', targets: [5, 46] },
  { id: '5', name: 'KURŞUNLU', targets: [13, 31] },
  { id: '22', name: 'SOĞUKSU IŞIKDAĞI', targets: [8, 19, 23] },
  { id: '26', name: 'OLUKBAŞI', targets: [7, 8, 21] },
  { id: '8', name: 'PURSAKLAR', targets: [19, 29, 30] },
  { id: '19', name: 'SAKARYA ( ADAPAZARI )', targets: [11, 13, 15] },
  { id: '13', name: 'SEYDİLER', targets: [1, 5] },
  { id: '1', name: 'TÜNEY ', targets: [5, 8, 13] },
  { id: '11', name: 'YAMUÇLAR', targets: [10, 11, 17] },
  { id: '29', name: 'İĞDİR', targets: [8, 10, 15] },
  { id: '30', name: 'BOLU', targets: [9, 11, 13, 15] },
  { id: '31', name: 'DÜZCE', targets: [1, 5, 9] },
];

// Stationdaki isimleri listeliyor
const stationNames = stations.map((station) => station.name);
</script>
