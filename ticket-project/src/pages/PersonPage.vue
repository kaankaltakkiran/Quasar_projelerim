<template>
  <q-form @submit="onSubmit" @reset="onReset">
    <div class="row justify-center q-gutter-x-md q-mt-md">
      <div class="col-xs-12 col-sm-6 col-md-2">
        <q-input
          filled
          v-model="name1"
          label="Your name *"
          hint="Name and surname"
          lazy-rules
          :rules="[(val) => (val && val.length > 0) || 'Please type something']"
        />
      </div>
      <div class="col-xs-12 col-sm-6 col-md-2">
        <q-input
          filled
          v-model="name2"
          label="Your name *"
          hint="Name and surname"
          lazy-rules
          :rules="[(val) => (val && val.length > 0) || 'Please type something']"
        />
      </div>
      <div class="col-xs-12 col-sm-6 col-md-2">
        <q-input
          filled
          v-model="name3"
          label="Your name *"
          hint="Name and surname"
          lazy-rules
          :rules="[(val) => (val && val.length > 0) || 'Please type something']"
        />
      </div>
      <q-toggle v-model="accept" label="I accept the license and terms" />
      <div class="col-xs-12 col-sm-6 col-md-2 q-mt-md">
        <q-btn label="Submit" type="submit" color="primary" />
      </div>
    </div>
  </q-form>
  <div class="row justify-center q-gutter-x-md q-mt-md">
    <div class="col-xs-12 col-sm-6 col-md-10 q-mt-md">
      <q-table
        :grid="$q.screen.xs"
        flat
        bordered
        title="Treats"
        :rows="rows"
        :columns="columns"
        row-key="name"
        :filter="filter"
        hide-header
      >
        <template v-slot:top-right>
          <q-input
            borderless
            dense
            debounce="300"
            v-model="filter"
            placeholder="Search"
          >
            <template v-slot:append>
              <q-icon name="search" />
            </template>
          </q-input>
        </template>
      </q-table>
    </div>
  </div>
</template>

<script setup lang="ts">
import { useQuasar } from 'quasar';
import { ref } from 'vue';
import { QTableColumn } from 'quasar';

const $q = useQuasar();

const name1 = ref<string | null>(null);
const name2 = ref<string | null>(null);
const name3 = ref<string | null>(null);
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
  name1.value = null;
  name2.value = null;
  name3.value = null;
  accept.value = false;
};

interface Row {
  name: string;
  calories: number;
  fat: number;
  carbs: number;
}
const columns: QTableColumn[] = [
  {
    name: 'desc',
    required: true,
    label: 'Dessert (100g serving)',
    align: 'left',
    field: (row: Row) => row.name,
    format: (val: string) => `${val}`,
    sortable: true,
  },
  {
    name: 'calories',
    align: 'center',
    label: 'Calories',
    field: 'calories',
    sortable: true,
  },
  { name: 'fat', label: 'Fat (g)', field: 'fat', sortable: true },
  { name: 'carbs', label: 'Carbs (g)', field: 'carbs' },
];
const rows: Row[] = [
  {
    name: 'Frozen Yogurt',
    calories: 159,
    fat: 6.0,
    carbs: 24,
  },
  {
    name: 'Ice cream sandwich',
    calories: 237,
    fat: 9.0,
    carbs: 37,
  },
  {
    name: 'Eclair',
    calories: 262,
    fat: 16.0,
    carbs: 23,
  },
  {
    name: 'Cupcake',
    calories: 305,
    fat: 3.7,
    carbs: 67,
  },
  {
    name: 'Gingerbread',
    calories: 356,
    fat: 16.0,
    carbs: 49,
  },
  {
    name: 'Jelly bean',
    calories: 375,
    fat: 0.0,
    carbs: 94,
  },
  {
    name: 'Lollipop',
    calories: 392,
    fat: 0.2,
    carbs: 98,
  },
  {
    name: 'Honeycomb',
    calories: 408,
    fat: 3.2,
    carbs: 87,
  },
  {
    name: 'Donut',
    calories: 452,
    fat: 25.0,
    carbs: 51,
  },
  {
    name: 'KitKat',
    calories: 518,
    fat: 26.0,
    carbs: 65,
  },
];
const filter = ref('');
</script>
