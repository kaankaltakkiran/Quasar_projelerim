<template>
  <q-card>
    <q-card-section class="q-gutter-md">
      <div class="row items-center">
        <div style="flex-shrink: 0">
          <q-avatar
            icon="directions_bus"
            text-color="white"
            color="primary"
            class="q-ml-md"
          />
        </div>
        <div style="flex-grow: 1">
          <div class="text-h6 text-center">Metro Turizm</div>
        </div>
      </div>
    </q-card-section>

    <q-separator />
    <q-card-section class="q-gutter-md">
      <div class="row items-center justify-between">
        <div>
          <div>Kalkış:</div>
          <div>Varış:</div>
          <div>Otogarlar:</div>
          <div>Hareket Zamanı:</div>
          <div>Koltuk:</div>
        </div>
        <div>
          <div>Esenler Otogarı</div>
          <div>Ankara (Aşti) Otogarı</div>
          <div>12 Haziran Çarşamba</div>
          <div>00:05</div>
          <div>---</div>
        </div>
      </div>
    </q-card-section>
    <q-separator />
    <q-card-section class="q-gutter-md">
      <div class="text-h6">Bilet İşlemleri</div>
      <div>
        Seferin 6 saat öncesine kadar biletinizi iptal edebilir ve
        değiştirebilirsiniz. Biletiniz açığa alınamaz.
      </div>
    </q-card-section>

    <q-separator dark />

    <q-card-actions>
      <div class="text-subtitle2 text-red">
        Otobüs Salı'yı Çarşamba'ya bağlayan gece kalkacaktır.
      </div>
    </q-card-actions>
  </q-card>
</template>

<script setup lang="ts"></script>
