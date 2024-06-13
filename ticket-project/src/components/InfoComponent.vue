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
        <!--Side:yana al-->
        <q-item-section side>
          <q-item-label header class="text-caption">Metro Turizm </q-item-label>
        </q-item-section>
      </q-item>

      <q-separator />

      <q-item>
        <q-item-section>
          <q-item-label>Kalkış:</q-item-label>
          <q-item-label caption>İstanbul</q-item-label>
        </q-item-section>

        <q-item-section>
          <q-item-label>Varış:</q-item-label>
          <q-item-label caption>Ankara</q-item-label>
        </q-item-section>
      </q-item>

      <q-item>
        <q-item-section>
          <q-item-label>Hareket Zamanı:</q-item-label>
          <q-item-label caption>15 Haziran Perşembe,Saat: 02:00</q-item-label>
        </q-item-section>
        <q-item-section>
          <q-item-label>Koltuk No:</q-item-label>
          <q-item-label caption>1,2,3</q-item-label>
        </q-item-section>
      </q-item>

      <q-item>
        <q-item-section>
          <q-item-label>Bilet İşlemleri:</q-item-label>
          <q-item-label caption
            >Seferin 6 saat öncesine kadar biletinizi iptal edebilir ve
            değiştirebilirsiniz. Biletiniz açığa alınamaz.
          </q-item-label>
        </q-item-section>
      </q-item>

      <q-item>
        <q-item-section>
          <q-item-label>Uyarı:</q-item-label>
          <q-item-label caption
            >Otobüs,Perşembe'yi Cuma'ya bağlayan gece hareket edecektir.
          </q-item-label>
        </q-item-section>
      </q-item>
    </q-list>
  </q-card>
</template>

<script setup lang="ts"></script>
