<template>
  <q-form @submit="onSubmit" @reset="onReset">
    <div class="row justify-center q-gutter-x-md q-mt-md">
      <div class="col-xs-12 col-sm-6 col-md-2">
        <q-input
          filled
          v-model="name"
          label="Your name *"
          hint="Name "
          lazy-rules
          :rules="[(val) => (val && val.length > 0) || 'Boş bırakılamaz']"
        />
      </div>
      <div class="col-xs-12 col-sm-6 col-md-2">
        <q-input
          filled
          v-model="email"
          label="Email *"
          hint="Email adresinizi giriniz"
          lazy-rules
          :rules="emailRules"
        />
      </div>
      <div class="col-xs-12 col-sm-6 col-md-2">
        <q-select
          label="Kullanıcı Durumu Seçiniz *"
          transition-show="flip-up"
          transition-hide="flip-down"
          filled
          v-model="status"
          :options="options"
          style="width: 250px"
          :rules="[(val) => !!val || 'Please select a status']"
        />
      </div>
      <div class="col-xs-12 col-sm-6 col-md-2">
        <q-toggle v-model="accept" label="I accept the license and terms" />
      </div>
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
import { api } from 'boot/axios'; //axios instance

const $q = useQuasar();
//form verileri
const filter = ref('');
const name = ref<string | null>(null);
const email = ref<string | null>(null);
const accept = ref<boolean>(false);
const status = ref<string[]>([]);

//select options
const options = [
  {
    label: 'Pasif',
    value: '0',
  },
  {
    label: 'Aktif',
    value: '1',
  },
];
//email için kurallar
const emailRules = [
  (val: string) => !!val || 'Lütfen email adresinizi giriniz',
  (val: string) => {
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailPattern.test(val) || 'Geçersiz email adresi';
  },
];

//form submit edilmişse
const onSubmit = async () => {
  if (!accept.value) {
    $q.notify({
      color: 'red-5',
      textColor: 'white',
      icon: 'warning',
      message: 'You need to accept the license and terms first',
      position: 'top-right',
    });
    return;
  }
  try {
    //form verilerini apiye gönder
    const response = await api.post('api.php', {
      name: name.value,
      email: email.value,
      status: status.value,
      method: 'add-user',
    });
    console.log('Response:', response);
    //işlem başarılıysa
    if (response.data.success === true) {
      $q.notify({
        color: 'green-4',
        textColor: 'white',
        icon: 'cloud_done',
        message: 'User added successfully',
        position: 'top-right',
      });
      onReset(); //formu sıfırla
    } else {
      $q.notify({
        color: 'red-5',
        textColor: 'white',
        icon: 'warning',
        message: 'An error occurred while sending the form',
        position: 'top-right',
      });
    }
  } catch (error) {
    console.error('Error sending form:', error);
    $q.notify({
      color: 'red-5',
      textColor: 'white',
      icon: 'warning',
      message: 'An error occurred while sending the form',
      position: 'top-right',
    });
  }
};
//formu sıfırla
const onReset = () => {
  name.value = null;
  email.value = null;
  accept.value = false;
  status.value = [];
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
</script>
