<template>
  <div class="row justify-center">
    <div class="col-lg-3 col-md-6 col-12 q-pa-md">
      <q-date
        v-model="date"
        :events="eventDates"
        :event-color="getEventColor"
        mask="DD/MM/YYYY"
      />
      {{ date }}
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';

const currentDate = new Date();
const formattedCurrentDate = `${currentDate
  .getDate()
  .toString()
  .padStart(2, '0')}/${(currentDate.getMonth() + 1)
  .toString()
  .padStart(2, '0')}/${currentDate.getFullYear()}`;
const date = ref(formattedCurrentDate);

const events = [
  {
    date: '2024/06/11',
    label: 'İstanbul - Ankara',
  },
];

// Extract event dates
const eventDates = events.map((event) => event.date);

// Event color getter function
const getEventColor = (date: string): string => {
  const selectedDate = new Date(date);
  if (selectedDate.getTime() < currentDate.getTime()) {
    return 'teal';
  }
  return '';
};
</script>
