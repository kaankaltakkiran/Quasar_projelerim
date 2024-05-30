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
       <q-btn color="negative" dense @click="confirmDeleteUser(props.row.id)">Delete User</q-btn>
        </q-td>
      </template>
    </q-table>
  </div>
   <q-dialog v-model="confirm" medium persistent>
      <q-card>
        <q-card-section class="row items-center">
          <span class="q-ml-sm">Kayıdı silmek istediğinizden emin misiniz ?</span>
        </q-card-section>

        <q-card-actions align="right">
         <q-btn flat label="Hayır" color="red" @click="confirm = false" />
         <!-- id null değilse silme işlemi çalışacak -->
        <q-btn flat label="Evet" color="primary" @click="deleteUser(selectedUserId!)" />
        </q-card-actions>
      </q-card>
    </q-dialog>
</template>

<script setup lang="ts">
import { ref,Ref, onMounted } from 'vue'
import { QTableColumn } from 'quasar'
import axios from 'axios'
import { useQuasar } from 'quasar';

// Quasar'dan kullanılacak fonksiyonları al
const $q = useQuasar();
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
// silme işlemi için onay kutusu
const confirm: Ref<boolean> = ref(false)
// silinecek kullanıcının id'si
const selectedUserId: Ref<number | null> = ref(null)

const fetchData = async () => {
  try {
    const response = await axios.get('http://localhost/veri/crud-project/api.php')
    rows.value = response.data // tablo verilerini doldur
    console.log('Data fetched:', response.data)
  } catch (error) {
    console.error('Error fetching data:', error)
  }
}

// silme işlemi için onay verilirse id'si verilen kullanıcıyı sil
const confirmDeleteUser = (id: number) => {
  selectedUserId.value = id
  confirm.value = true
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
    //console.log('User deleted:', id)
    confirm.value = false
    triggerPositive();
  } catch (error) {
    console.error('Error deleting user:', error)
  }
}
const triggerPositive = () => {
    $q.notify({
      color: 'green-4',
      textColor: 'white',
      icon: 'cloud_done',
      message: 'Kayıt başarıyla silindi', 
      position: 'top-right',
      timeout: 750
    })
}
// tablo verilerini sayfa yüklendiğinde çek
onMounted(() => {
  fetchData()
})
</script>
