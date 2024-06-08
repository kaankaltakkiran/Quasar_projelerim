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
            @click="deleteSelectedRows"
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
      // Başarılı silme işlemi sonrasında tabloyu güncelle
      selected.value = []; // seçilen verileri temizle
      fetchData(); // verileri tekrar çek
    }
  } catch (error) {
    console.error('Error deleting data:', error);
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
</script>
