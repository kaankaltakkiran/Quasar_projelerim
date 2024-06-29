<template>
  <q-card>
    <q-list bordered class="rounded-borders">
      <q-item>
        <q-item-section>
          <q-avatar
            icon="directions_bus"
            text-color="white"
            color="primary"
            class="q-ml-md"
          />
        </q-item-section>
        <!-- Side:yana al -->
        <q-item-section side>
          <q-item-label header class="text-caption">
            {{ travelStore.travelInfo.companyName }}
          </q-item-label>
        </q-item-section>
      </q-item>

      <q-separator />

      <q-item>
        <q-item-section>
          <q-item-label>Kalkış:</q-item-label>
          <q-item-label caption>
            {{ travelStore.travelInfo.departureStation }}
          </q-item-label>
        </q-item-section>

        <q-item-section>
          <q-item-label>Varış:</q-item-label>
          <q-item-label caption>{{ getArrivalStationValue }}</q-item-label>
        </q-item-section>
      </q-item>

      <q-item>
        <q-item-section>
          <q-item-label>Hareket Zamanı:</q-item-label>
          <q-item-label caption>{{ formattedDate }}</q-item-label>
        </q-item-section>
        <q-item-section>
          <q-item-label>Yolcu Sayısı:</q-item-label>
          <q-item-label caption>
            {{ travelStore.travelInfo.passengerCount }}
          </q-item-label>
        </q-item-section>
      </q-item>

      <q-item>
        <q-item-section>
          <q-item-label>Bilet İşlemleri:</q-item-label>
          <q-item-label caption>
            Seferin 6 saat öncesine kadar biletinizi iptal edebilir ve
            değiştirebilirsiniz. Biletiniz açığa alınamaz.
          </q-item-label>
        </q-item-section>
      </q-item>

      <q-item>
        <q-item-section>
          <q-item-label>Uyarı:</q-item-label>
          <q-item-label caption>
            Biletinizi iptal etmek için 0850 222 34 55 numaralı çağrı
            merkezimizi arayabilirsiniz.
          </q-item-label>
        </q-item-section>
      </q-item>
    </q-list>
  </q-card>
</template>

<script setup lang="ts">
import { computed } from 'vue';
import { useTravelStore } from 'src/stores/travel-info-store';

// Store'u kullan
const travelStore = useTravelStore();

// Tarih formatlama
const formattedDate = computed(() => {
  if (!travelStore.travelInfo.selectedDate) {
    return '';
  }

  let dateToShow: Date | string = travelStore.travelInfo.selectedDate;

  if (dateToShow === 'Seçilen Tarih') {
    return 'Seçilen Tarih';
  }

  // Eğer tarih string ise Date nesnesine çevir
  if (typeof dateToShow === 'string') {
    dateToShow = new Date(dateToShow.replace(/\//g, '-'));
  }

  const options: Intl.DateTimeFormatOptions = {
    day: 'numeric',
    month: 'long',
    year: 'numeric',
  };

  return dateToShow.toLocaleDateString('tr-TR', options);
});

/*  
index sayfasında çözülen sorundan dolayı(gidiş değiştiğinde dönüş istasyonu sıfırlanmıyordu).
{ "label": "ALİBEYKÖY", "value": "ALİBEYKÖY" } varış değeri bu şekilde geliyordu.
 bu sorunu çözmek için aşığıdaki saçma kod yazıldı.
 bu yapı kontrol edilip düzeltilmelidir.
*/
const getArrivalStationValue = computed(() => {
  const arrivalStation = travelStore.travelInfo.arrivalStation;

  // Eğer arrivalStation 'never' ise, null veya undefined olabilir
  if (arrivalStation === null || arrivalStation === undefined) {
    return 'Belirtilmemiş'; // veya uygun bir varsayılan değer
  }

  // Eğer arrivalStation bir nesne ise ve 'value' özelliği varsa, onu döndür
  if (typeof arrivalStation === 'object' && 'value' in arrivalStation) {
    return (arrivalStation as { value: string }).value;
  }

  // Diğer durumda, arrivalStation doğrudan değeri döndür
  return arrivalStation as string; // Varsayılan olarak string olarak kabul ediyoruz
});
</script>
