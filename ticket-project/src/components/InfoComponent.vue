<template>
  <q-card class="q-pa-md">
    <q-card-section class="q-gutter-md">
      <div class="row items-center">
        <div style="flex-shrink: 0">
          <q-avatar
            :icon="props.info.icon"
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
          <div>{{ props.info.departure }}</div>
          <div>{{ props.info.arrival }}</div>
          <div>{{ props.info.date }}</div>
          <div>{{ props.info.time }}</div>
          <div>{{ props.info.seat }}</div>
        </div>
      </div>
    </q-card-section>
    <q-separator />
    <q-card-section class="q-gutter-md">
      <div class="text-h6">Bilet İşlemleri</div>
      <div>{{ props.info.ticketInfo }}</div>
    </q-card-section>

    <q-separator dark />

    <q-card-actions>
      <div class="text-subtitle2 text-red">
        {{ props.info.notice }}
      </div>
    </q-card-actions>
  </q-card>
</template>

<script setup lang="ts">
import { defineProps } from 'vue';
import { ITravelInfo } from 'components/models';

const props = defineProps<{
  info: ITravelInfo;
}>();
</script>
