<template>
  <q-card class="bg-secondary text-white">
    <q-card-section>
      <div class="text-h6">Our Changing Planet</div>
      <div class="text-subtitle2">by John Doe</div>
    </q-card-section>
    <q-separator />
    <q-card-section>
      {{ lorem }}
    </q-card-section>

    <q-separator dark />

    <q-card-actions>
      <q-btn flat>Action 1</q-btn>
      <q-btn flat>Action 2</q-btn>
    </q-card-actions>
  </q-card>
</template>

<script setup lang="ts">
import { ref } from 'vue';

const lorem = ref(
  'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.'
);
</script>

<style lang="sass" scoped></style>
