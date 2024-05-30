<template>
  <div class="q-pa-md">
    <h1 class="text-center text-red">Persons</h1>
    <q-table
      :grid="$q.screen.xs"
      flat
      bordered
      title="Persons"
      :rows="rows"
      :columns="columns"
      row-key="name"
      :filter="filter"
    >
      <template v-slot:top-right>
        <q-input borderless dense debounce="300" v-model="filter" placeholder="Search">
          <template v-slot:append>
            <q-icon name="search" />
          </template>
        </q-input>
      </template>
    </q-table>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { QTableColumn } from 'quasar'
import axios from 'axios'

// veri adını ve türlerini belirle
interface Person {
  id: number
  user_name: string
  user_age: number
}
// tablo sütunlarını belirle
const columns: QTableColumn[] = [
  { name: 'user_name', align: 'left', label: 'Name', field: 'user_name', sortable: true },
  { name: 'user_age', align: 'center', label: 'Age', field: 'user_age', sortable: true }
]
// tablo verilerini çek
const rows = ref<Person[]>([]);
// tablo verilerini filtrele
const filter = ref('');

const fetchData = async () => {
  try {
    const response = await axios.get('http://localhost/veri/crud-project/api.php')
    rows.value = response.data // tablo verilerini doldur
    console.log('Data fetched:', response.data)
  } catch (error) {
    console.error('Error fetching data:', error)
  }
}
// tablo verilerini sayfa yüklendiğinde çek
onMounted(() => {
  fetchData()
})
</script>
