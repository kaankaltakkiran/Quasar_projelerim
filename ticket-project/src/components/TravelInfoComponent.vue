<template>
  <h4 class="text-red text-center">Yolculuk Bilgileri</h4>
  <div class="q-pa-md" style="max-width: 400px">
    <q-form @submit="onSubmit" @reset="onReset" class="q-gutter-md">
      <q-input
        filled
        v-model="name"
        label="Your name *"
        hint="Name and surname"
        lazy-rules
        :rules="[ (val: any) => val && val.length > 0 || 'Please type something']"
      />

      <q-input
        filled
        type="number"
        v-model="age"
        label="Your age *"
        lazy-rules
        :rules="[
          (val: any) => val !== null && val !== '' || 'Please type your age',
          (val: any) => val > 0 && val < 100 || 'Please type a real age'
        ]"
      />
      <q-input filled v-model="date" mask="date" :rules="dateRules">
        <template v-slot:append>
          <q-icon name="event" class="cursor-pointer">
            <q-popup-proxy
              cover
              transition-show="scale"
              transition-hide="scale"
            >
              <q-date v-model="date">
                <div class="row items-center justify-end">
                  <q-btn v-close-popup label="Close" color="primary" flat />
                </div>
              </q-date>
            </q-popup-proxy>
          </q-icon>
        </template>
      </q-input>
      <q-select
        label="Flip up/down"
        transition-show="flip-up"
        transition-hide="flip-down"
        filled
        v-model="model"
        :options="options"
        style="width: 250px"
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
</template>

<script setup lang="ts">
import { useQuasar } from 'quasar';
import { ref } from 'vue';

const date = ref<string>('2019/02/01');

const dateRules = [
  (val: string) => {
    const regex = /^\d{4}\/\d{2}\/\d{2}$/;
    if (!val.match(regex)) {
      return 'Please enter a valid date (YYYY/MM/DD)';
    }
    return true;
  },
];

const model = ref<string | null>(null);

const options = ['Google', 'Facebook', 'Twitter', 'Apple', 'Oracle'];

const $q = useQuasar();
const name = ref<string | null>(null);
const age = ref<number | null>(null);
const accept = ref<boolean>(false);

const onSubmit = () => {
  if (!accept.value) {
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

const onReset = () => {
  name.value = null;
  age.value = null;
  accept.value = false;
};
</script>
