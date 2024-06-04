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
    <!-- Tablo başlığında arama kutusu -->
      <template v-slot:top-right>
        <q-input borderless dense debounce="300" v-model="filter" placeholder="Search">
          <template v-slot:append>
            <q-icon name="search" />
          </template>
        </q-input>
      </template>
      <!-- Silme butonu -->
         <template v-slot:body-cell-delete="props">
        <q-td :props="props">
       <q-btn color="negative" icon-right="delete" dense @click="confirmDeleteUser(props.row.id)">Delete User</q-btn>
        </q-td>
      </template>
      <!-- Güncelleme butonu -->
        <template v-slot:body-cell-update="props">
        <q-td :props="props">
          <q-btn color="secondary" icon-right="update" dense @click="openUpdateDialog(props.row)">Update User</q-btn>
        </q-td>
      </template>
    </q-table>
  </div>
  <!-- Silme işlemi için dialog -->  
   <q-dialog v-model="confirm"  persistent>
      <q-card style="width: 700px; max-width: 80vw;">
        <q-card-section class="row items-center">
          <span class="q-ml-sm">Kayıdı silmek istediğinizden emin misiniz ?</span>
        </q-card-section>
        <q-card-actions align="right">
         <q-btn flat label="İptal" color="red" @click="confirm = false" />
         <!-- id null değilse silme işlemi çalışacak -->
        <q-btn flat label="Evet" color="primary" @click="deleteUser(selectedUserId!)" />
        </q-card-actions>
      </q-card>
    </q-dialog>
    <!-- Güncelleme işlemi için dialog -->
      <q-dialog v-model="updateDialog" persistent>
    <q-card style="width: 700px; max-width: 80vw;">
      <q-card-section>
        <div class="text-h6 text-center text-deep-orange">Kullanıcıyı Güncelle</div>
      </q-card-section>
      <q-card-section>
        <q-input v-model="selectedUser.user_name" label="Name" />
        <q-input v-model="selectedUser.user_age" label="Age" type="number" />
      </q-card-section>
      <q-card-actions align="right">
        <q-btn flat label="İptal" color="negative" @click="closeUpdateDialog" />
        <q-btn flat label="Kaydet" color="primary" @click="updateUser" />
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
  { name: 'delete', align: 'center', label: 'Delete', field: 'delete' },
  { name: 'update', align: 'center', label: 'Update', field: 'update' },
]
// tablo verilerini çek
const rows = ref<Person[]>([]);
// tablo verilerini filtrele
const filter = ref('');
// silme işlemi için onay kutusu
const confirm: Ref<boolean> = ref(false)
// silinecek kullanıcının id'si
const selectedUserId: Ref<number | null> = ref(null)
// Partial(typesciprt değişkeni) ile sadece belirli alanları güncelleyebiliriz(isteğe bağlı)
const selectedUser: Ref<Partial<Person>> = ref({});
// güncelleme işlemi için dialog
const updateDialog: Ref<boolean> = ref(false);

  //gelen get adına göre veri çekme
const fetchData = async (action:string) => {
  try {
    //process.env.CRUD_API_URL
 const response = await axios.get(`http://localhost/veri/crud-project/api.php?action=${action}`)
    if (response.status == 200) {
      if (response.data.length > 0) {
        rows.value = response.data // tablo verilerini doldur
        console.log('Data fetched:', response.data)
      }
      else {
       triggerNotification('Veritabanında kayıt bulunamadı!', 'negative')
      }
    }
  } catch (error) {
    console.error('Error fetching data:', error)
      triggerNotification('Veri çekme sırasında hata oluştu!', 'negative')
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
 triggerNotification('Kayıt başarıyla silindi', 'positive')
  } catch (error) {
    console.error('Error deleting user:', error)
  }
}
/*  güncelleme işlemi için dialogu aç
 ...user, user objesinin tüm özelliklerinin selectedUser.value'a kopyalanmasını sağlar. Bu şekilde, selectedUser objesi, diyaloğun içinde gösterilecek olan mevcut kullanıcının verilerini içerir.
*/
const openUpdateDialog = (user: Person) => {
  selectedUser.value = { ...user }
  updateDialog.value = true
}
// güncelleme işlemi için dialogu kapat
const closeUpdateDialog = () => {
  updateDialog.value = false
}

// güncelleme işlemi için kullanıcıyı güncelle
const updateUser = async () => {
  try {
    const response = await axios.put('http://localhost/veri/crud-project/api.php', selectedUser.value)
    if (response.status ==200) {
      const index = rows.value.findIndex(user => user.id === selectedUser.value.id)
      if (index !== -1) {
        rows.value[index] = { ...selectedUser.value } as Person
      }
      updateDialog.value = false
      triggerNotification('Kayıt başarıyla güncellendi', 'positive')
    } else {
        triggerNotification('Güncelleme başarısız oldu!', 'negative')
    }
  } catch (error) {
    console.error('Error updating user:', error)
    triggerNotification('Güncelleme sırasında hata oluştu!', 'negative')
  }
}

//mesaj durumuna göre bildirim göster
const triggerNotification = (message: string, type: 'positive' | 'negative') => {
  $q.notify({
    color: type === 'positive' ? 'green-4' : 'red-5',
    textColor: 'white',
    icon: type === 'positive' ? 'cloud_done' : 'warning',
    message,
    position: 'top-right',
    timeout: 1000
  })
}


// tablo verilerini sayfa yüklendiğinde çek
onMounted(() => {
  fetchData('users'); //api deki users tablosundan veri çek
})
</script>
