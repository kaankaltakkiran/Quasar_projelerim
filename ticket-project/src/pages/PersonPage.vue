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
        title="Kişi Listesi"
        :rows="rows"
        :columns="columns"
        row-key="id"
        :filter="filter"
      >
        <template v-slot:top-right>
          <q-input
            borderless
            dense
            debounce="300"
            v-model="filter"
            placeholder="Ara"
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
import { useQuasar, QTableColumn } from 'quasar';
import { ref, onMounted } from 'vue';
import { api } from 'boot/axios'; //axios instance
import { IPerson } from 'src/models/model';

const $q = useQuasar();
//form verileri
const filter = ref('');
const name = ref<string | null>(null);
const email = ref<string | null>(null);
const accept = ref<boolean>(false);
const status = ref<string[]>([]);
// tablo verileri
const rows = ref<IPerson[]>([]); // tablo verilerini tut

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

// tablo sütunlarını belirle
const columns: QTableColumn[] = [
  { name: 'id', align: 'left', label: 'Kayıt No', field: 'id', sortable: true },
  {
    name: 'user_name',
    align: 'left',
    label: 'İsim',
    field: 'user_name',
    sortable: true,
  },
  {
    name: 'user_email',
    align: 'center',
    label: 'Email',
    field: 'user_email',
    sortable: true,
  },
  {
    name: 'user_status',
    align: 'center',
    label: 'Durum',
    field: 'user_status',
    sortable: true,
    format: (val) => (val === '1' ? 'Aktif' : 'Pasif'), // durum değerini yazıya çevir
  },
];
//gelen get adına göre veri çekme
const fetchUsers = async () => {
  try {
    const response = await api.post('api.php', {
      method: 'get-users',
    });
    console.log('Response:', response);
    if (response.data.success === true) {
      rows.value = response.data.users; // tablo verilerini doldur
    }
  } catch (error) {
    console.error('Error fetching data:', error);
  }
};
// sayfa yüklendiğinde verileri çek
onMounted(() => {
  fetchUsers();
});
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
      fetchUsers(); //tabloyu güncelle
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
</script>
