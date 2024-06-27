<template>
  <div class="q-mt-md row justify-center">
    <div class="col-lg-3 col-md-6 col-12 q-pa-md">
      <h4 class="text-red text-center">Bilgiler</h4>
      <q-form @submit.prevent="onSubmit" @reset.prevent="onReset">
        <!-- Location Component -->
        <LocationComponent
          v-model:departureStation="departureStation"
          v-model:arrivalStation="arrivalStation"
        />
        <!-- Travel Info Component -->
        <TravelInfoComponent
          v-model:name="name"
          v-model:age="age"
          v-model:select="select"
        />

        <!--Calendar Component-->
        <CalendarComponent />
        <!-- Submit and Reset Buttons -->
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
  </div>
</template>

<script setup lang="ts">
import TravelInfoComponent from '../components/TravelInfoComponent.vue';
import LocationComponent from '../components/LocationComponent.vue';
import CalendarComponent from '../components/CalendarComponent.vue';
import { ref } from 'vue';
import { useQuasar } from 'quasar';

const $q = useQuasar();
const departureStation = ref<string | undefined>(undefined);
const arrivalStation = ref<string | undefined>(undefined);
const name = ref<string | undefined>(undefined);
const age = ref<number | undefined>(undefined);
const select = ref<string | undefined>(undefined);

const onSubmit = () => {
  if (
    !name.value ||
    !age.value ||
    !select.value ||
    !departureStation.value ||
    !arrivalStation.value
  ) {
    onReset();
    $q.notify({
      color: 'red-5',
      textColor: 'white',
      icon: 'warning',
      message: 'Tüm alanları doldurmanız gerekiyor',
    });
  } else {
    $q.notify({
      color: 'green-4',
      textColor: 'white',
      icon: 'cloud_done',
      message: 'Form başarıyla gönderildi',
    });
    // Form verileri burada işlenebilir
  }
};

const onReset = () => {
  name.value = undefined;
  age.value = undefined;
  select.value = undefined;
  departureStation.value = undefined;
  arrivalStation.value = undefined;
};
</script>
