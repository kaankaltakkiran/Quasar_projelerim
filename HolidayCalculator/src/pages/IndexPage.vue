<template>
  <q-page class="row items-center justify-evenly">
    <div class="q-pa-md" style="max-width: 400px">
      <h3>İzin Hesaplama</h3>
      <q-form @submit="onSubmit" @reset="onReset" class="q-gutter-md">
        <div class="q-gutter-md">
          <q-date v-model="date" :events="events" />
        </div>
        <q-checkbox
          v-model="official"
          label="Resmi Tatilleri Dahil Et"
          color="primary"
        />

        <q-checkbox
          v-model="weekend"
          label="Hafta Sonlarını Dahil Et"
          color="cyan"
        />

        <q-toggle v-model="accept" label="I accept the license and terms" />

        <div>
          <q-btn label="Submit" type="submit" color="primary" />
          <q-btn
            label="Reset"
            type="reset"
            color="primary"
            flat
            class="q-ml-sm"
          />
        </div>
      </q-form>
    </div>
  </q-page>
</template>

<script setup lang="ts">
defineOptions({
  name: 'IndexPage',
});

import { ref } from 'vue';
import { useQuasar } from 'quasar';

// Define reactive variables with appropriate types
const accept = ref<boolean>(false);
const official = ref<boolean>(true);
const weekend = ref<boolean>(true);

const $q = useQuasar();

// Define the onSubmit function
const onSubmit = () => {
  if (accept.value !== true) {
    $q.notify({
      color: 'red-5',
      textColor: 'white',
      icon: 'warning',
      message: 'You need to accept the license and terms first',
    });
  } else {
    $q.notify({
      color: 'green-4',
      textColor: 'white',
      icon: 'cloud_done',
      message: 'Submitted',
    });
  }
};

// Define reactive variables with appropriate types
const date = ref<string>('2019/02/01');
const events = ref<string[]>([
  '2019/02/01',
  '2019/02/05',
  '2019/02/06',
  '2019/02/09',
  '2019/02/23',
]);

// Define the onReset function
const onReset = () => {
  accept.value = false;
};
</script>
