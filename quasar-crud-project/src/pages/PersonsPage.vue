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
         <template v-slot:body-cell-delete="props">
        <q-td :props="props">
          <q-btn color="negative" dense @click="deleteUser(props.row.id)">Delete</q-btn>
        </q-td>
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
  { name: 'id', align: 'left', label: 'id', field: 'id', sortable: true },
    { name: 'user_name', align: 'left', label: 'Name', field: 'user_name', sortable: true },
  { name: 'user_age', align: 'center', label: 'Age', field: 'user_age', sortable: true },
   { name: 'delete',align: 'center', label: 'Delete',field: 'delete'}
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
// id'si verilen kullanıcıyı sil
const deleteUser = async (id: number) => {
  console.log('Deleting user:', id)
  try {
    // Silinecek kullanıcının id'sini içeren JSON verisi gönderiliyor
    //normal url de kabul etmedi
    //await axios.delete(`http://localhost/veri/crud-project/api.php?id=${id}`)
    //bu şekil de olmalı  await axios.delete('http://localhost/veri/crud-project/api.php', { params: { id: id } })
    await axios.delete('http://localhost/veri/crud-project/api.php', { data: { id: id } })
    rows.value = rows.value.filter((user) => user.id !== id)
    console.log('User deleted:', id)
  } catch (error) {
    console.error('Error deleting user:', error)
  }
}
// tablo verilerini sayfa yüklendiğinde çek
onMounted(() => {
  fetchData()
})
</script>
