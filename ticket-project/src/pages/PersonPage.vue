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
        <!-- Silme ve güncelleme butonları -->
        <template v-slot:body-cell-actions="props">
          <q-td :props="props">
            <q-btn
              size="md"
              round
              color="negative"
              class="q-mr-sm"
              icon="delete"
              @click="confirmDeleteUser(props.row.id)"
            />
            <q-btn
              size="md"
              color="positive"
              @click="openUpdateDialog(props.row)"
              icon="update"
            />
          </q-td>
        </template>
      </q-table>
    </div>
  </div>
  <!-- Silme işlemi için dialog -->
  <q-dialog v-model="confirm" persistent>
    <q-card style="width: 700px; max-width: 80vw">
      <q-card-section class="row items-center">
        <span class="q-ml-sm">Kayıdı silmek istediğinizden emin misiniz ?</span>
      </q-card-section>
      <q-card-actions align="right">
        <q-btn flat label="İptal" color="red" @click="cancelDeletion" />
        <!-- id null değilse silme işlemi çalışacak -->
        <q-btn
          flat
          label="Evet"
          color="primary"
          @click="deleteUser(selectedUserId!)"
        />
      </q-card-actions>
    </q-card>
  </q-dialog>
  <!-- Güncelleme işlemi için dialog -->
  <q-dialog v-model="updateDialog" persistent>
    <q-card style="width: 700px; max-width: 80vw">
      <q-card-section>
        <div class="text-h6 text-center text-deep-orange">
          Kullanıcıyı Güncelle
        </div>
      </q-card-section>
      <q-card-section>
        <q-input
          filled
          v-model="selectedUser.user_name"
          label="Your name *"
          hint="Name "
          lazy-rules
          :rules="[(val) => (val && val.length > 0) || 'Boş bırakılamaz']"
        />
        <q-input
          filled
          v-model="selectedUser.user_email"
          label="Email *"
          hint="Email adresinizi giriniz"
          lazy-rules
          :rules="emailRules"
        />
        <div class="rounded-borders q-my-md">Select User Status</div>
        <q-option-group
          v-model="selectedUser.user_status"
          :options="options"
          color="red"
          inline
          dense
        >
        </q-option-group>
      </q-card-section>
      <q-card-actions align="right">
        <q-btn flat label="İptal" color="negative" @click="closeUpdateDialog" />
        <q-btn flat label="Kaydet" color="primary" @click="updateUser" />
      </q-card-actions>
    </q-card>
  </q-dialog>
</template>

<script setup lang="ts">
import { useQuasar, QTableColumn } from 'quasar';
import { ref, Ref, onMounted, watch } from 'vue';
import { api } from 'boot/axios'; //axios instance
import { IPerson } from 'src/models/model';

const $q = useQuasar();
//form verileri
const filter = ref('');
const name = ref<string | null>(null);
const email = ref<string | null>(null);
const accept = ref<boolean>(false);
const status = ref<string[]>([]);
// iptal durumunu belirlemek için bir değişken ekleyin
const wasCancelled = ref(false);

// tablo verileri
const rows = ref<IPerson[]>([]); // tablo verilerini tut
// silme işlemi için onay kutusu
const confirm: Ref<boolean> = ref(false);
// silinecek kullanıcının id'si
const selectedUserId: Ref<number | null> = ref(null);
// Partial(typesciprt değişkeni) ile sadece belirli alanları güncelleyebiliriz(isteğe bağlı)
const selectedUser: Ref<Partial<IPerson>> = ref({});
// güncelleme işlemi için dialog
const updateDialog: Ref<boolean> = ref(false);

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
  { name: 'actions', align: 'center', label: 'İşlemler', field: 'actions' },
];

//gelen get adına göre veri çekme
const fetchUsers = async () => {
  try {
    const response = await api.post('api.php', {
      method: 'get-users',
    });
    // console.log('Response:', response);
    if (response.data.success === true) {
      rows.value = response.data.users; // tablo verilerini doldur
    }
  } catch (error) {
    console.error('Error fetching data:', error);
  }
};
// silme işlemi için onay verilirse id'si verilen kullanıcıyı sil
const confirmDeleteUser = (id: number) => {
  selectedUserId.value = id;
  confirm.value = true;
};
const cancelDeletion = () => {
  confirm.value = false;
  triggerInfo('İşlem iptal edildi');
};
// id'si verilen kullanıcıyı sil
const deleteUser = async (id: number) => {
  // console.log('Deleting user:', id);
  try {
    await api.post('api.php', {
      id,
      method: 'delete-user',
    });
    confirm.value = false;
    triggerNotification('Kullanıcı başarıyla silindi', 'positive');
    fetchUsers(); // tabloyu güncelle
  } catch (error) {
    console.error('Error deleting user:', error);
  }
};

// ...user, user objesinin tüm özelliklerinin selectedUser.value'a kopyalanmasını sağlar.
const openUpdateDialog = (user: IPerson) => {
  selectedUser.value = { ...user };
  updateDialog.value = true;
  wasCancelled.value = false; // dialog açıldığında iptal durumu resetlenir
};
// güncelleme işlemi için dialogu kapat
const closeUpdateDialog = () => {
  updateDialog.value = false;
  wasCancelled.value = true; // dialog açıldığında iptal durumu resetlenir
};

// güncelleme işlemi için kullanıcıyı güncelle
const updateUser = async () => {
  //gelen verilerin boş olup olmadığını kontrol et
  if (
    selectedUser.value.id !== undefined &&
    selectedUser.value.user_name !== undefined &&
    selectedUser.value.user_email !== undefined &&
    selectedUser.value.user_status !== undefined
  ) {
    try {
      //apiye güncelleme işlemi için verileri gönder
      const response = await api.post('api.php', {
        id: selectedUser.value.id,
        name: selectedUser.value.user_name,
        email: selectedUser.value.user_email,
        status: selectedUser.value.user_status,
        method: 'update-user',
      });
      //console.log('Response:', response);
      if (response.data.success === true) {
        // Kullanıcıyı bul ve yerel rows dizisini güncelle
        const index = rows.value.findIndex(
          (user) => user.id === selectedUser.value.id
        );
        //eğer kullanıcı bulunursa
        if (index !== -1) {
          rows.value[index] = {
            id: selectedUser.value.id,
            user_name: selectedUser.value.user_name,
            user_email: selectedUser.value.user_email,
            user_status: selectedUser.value.user_status,
          };
        }
        updateDialog.value = false;
        triggerNotification('Kullanıcı başarıyla güncellendi', 'positive');
      } else {
        triggerNotification(
          'Kullanıcı güncellenirken bir hata oluştu',
          'negative'
        );
      }
    } catch (error) {
      console.error('Error updating user:', error);
      triggerNotification(
        'Kullanıcı güncellenirken bir hata oluştu',
        'negative'
      );
    }
  }
};
// Dialog kapandığında notify mesajı göstermek için watch kullan
watch(updateDialog, (newVal, oldVal) => {
  if (oldVal === true && newVal === false) {
    if (wasCancelled.value) {
      triggerInfo('İşlem iptal edildi');
    }
  }
});

// sayfa yüklendiğinde verileri çek
onMounted(() => {
  fetchUsers();
});
//form submit edilmişse
const onSubmit = async () => {
  if (!accept.value) {
    triggerNotification('Lisans ve şartları kabul etmelisiniz !', 'negative');
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
    // console.log('Response:', response);
    //işlem başarılıysa
    if (response.data.success === true) {
      triggerNotification('Kullanıcı başarıyla eklendi', 'positive');
      onReset(); //formu sıfırla
      fetchUsers(); //tabloyu güncelle
    } else {
      triggerNotification('form gönderilirken bir hata oluştu', 'negative');
    }
  } catch (error) {
    console.error('Error sending form:', error);
    triggerNotification('form gönderilirken bir hata oluştu', 'negative');
  }
};
//formu sıfırla
const onReset = () => {
  name.value = null;
  email.value = null;
  accept.value = false;
  status.value = [];
};

//mesaj durumuna göre bildirim göster
const triggerNotification = (
  message: string,
  type: 'positive' | 'negative'
) => {
  $q.notify({
    color: type === 'positive' ? 'green-4' : 'red-5',
    textColor: 'white',
    icon: type === 'positive' ? 'cloud_done' : 'warning',
    message,
    position: 'top-right',
    timeout: 1000,
  });
};
//bilgi mesajı göster
const triggerInfo = (message: string) => {
  $q.notify({
    color: 'blue-5',
    textColor: 'white',
    icon: 'info',
    message,
    position: 'top-right',
    timeout: 1000,
  });
};
</script>
