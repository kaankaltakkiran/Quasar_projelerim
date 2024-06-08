<template>
  <h2 class="text-red text-center">Person List</h2>
  <div class="row justify-center">
    <div class="q-pa-md col-12 col-sm-10 col-md-8 col-lg-6">
      <q-table
        flat
        bordered
        title="Persons"
        :rows="rows"
        :columns="columns"
        row-key="id"
        :selected-rows-label="getSelectedString"
        selection="multiple"
        v-model:selected="selected"
        :filter="filter"
      >
        <template v-slot:body-cell-selection="props">
          <q-checkbox v-model="props.selected" />
        </template>
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
        <!-- Silme butonu -->
        <template v-slot:top>
          <q-btn
            label="Delete Person"
            color="negative"
            @click="confirmDelete"
          />
        </template>
      </q-table>
      <div class="q-mt-md">Selected: {{ JSON.stringify(selected) }}</div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { QTableColumn } from 'quasar';
import axios from 'axios';
import { IPerson } from '../interfaces/IPerson';
import { useQuasar } from 'quasar';

const $q = useQuasar();

// tablo değişkenleri
const rows = ref<IPerson[]>([]);
const selected = ref<IPerson[]>([]); //seçilen verileri tut
const filter = ref('');

// tablo sütunlarını belirle
const columns: QTableColumn[] = [
  { name: 'id', align: 'left', label: 'id', field: 'id', sortable: true },
  {
    name: 'user_name',
    align: 'left',
    label: 'Name',
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
    label: 'Status',
    field: 'user_status',
    sortable: true,
  },
];
//gelen get adına göre veri çekme
const fetchData = async () => {
  try {
    const response = await axios.post(
      'http://localhost/veri/ornek_api/api.php',
      {
        method: 'get-users',
      }
    );
    // console.log(response);
    if (response.data.success === true) {
      rows.value = response.data.users; // tablo verilerini doldur
    }
  } catch (error) {
    console.error('Error fetching data:', error);
  }
};
// seçilen satırları silme fonksiyonu
const deleteSelectedRows = async () => {
  try {
    // seçilen verilerin id'lerini al
    const selectedIds = selected.value.map((IPerson) => IPerson.id);
    console.log(selectedIds);
    const response = await axios.post(
      'http://localhost/veri/ornek_api/api.php',
      {
        method: 'delete-user',
        ids: selectedIds, // id parametresi bir dizi olarak gönderiliyor
      }
    );
    console.log(response);
    if (response.data.success === true) {
      selected.value = []; // seçilen verileri temizle
      fetchData(); // verileri tekrar çek
      triggerPositive(); // Başarılı silme işlemi sonrasında bildirim göster
    }
  } catch (error) {
    console.error('Error deleting data:', error);
    triggerNegative(); // Hata oluştuğunda bildirim göster
  }
};
// seçilen verileri string olarak döndür
function getSelectedString() {
  return selected.value.length === 0
    ? ''
    : `${selected.value.length} record${
        selected.value.length > 1 ? 's' : ''
      } selected of ${rows.value.length}`;
}
// sayfa yüklendiğinde verileri çek
onMounted(() => {
  fetchData();
});
const triggerPositive = () => {
  $q.notify({
    color: 'green-4',
    textColor: 'white',
    icon: 'cloud_done',
    message: 'Person deleted successfully',
    position: 'top-right',
    timeout: 750,
  });
};
const triggerNegative = () => {
  $q.notify({
    color: 'red-5',
    textColor: 'white',
    icon: 'warning',
    message: 'error occurred when deleting the user !',
    position: 'top-right',
    timeout: 1000,
  });
};
const triggerInfo = () => {
  $q.notify({
    color: 'blue-5',
    textColor: 'white',
    icon: 'info',
    message: 'please delete the person you want to delete !',
    position: 'top-right',
    timeout: 1000,
  });
};
// Delete onay kutusu
const confirmDelete = () => {
  if (selected.value.length === 0) {
    triggerInfo(); // Eğer hiçbir satır seçilmediyse bilgi mesajı göster
    return;
  }
  $q.dialog({
    title: 'Confirm',
    message: 'Are you sure you want to delete the selected users?',
    cancel: true,
    persistent: true,
  })
    .onOk(() => {
      deleteSelectedRows();
    })
    .onCancel(() => {
      console.log('Delete operation cancelled');
    })
    .onDismiss(() => {
      console.log('Dialog dismissed');
    });
};
</script>
