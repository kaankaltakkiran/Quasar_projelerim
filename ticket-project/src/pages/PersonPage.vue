<template>
  <h1 class="text-h4 text-center text-red">
    Kişi Ekleme, Listeleme, Silme ,Güncelleme Sayfası
  </h1>
  <q-form @submit="onSubmit" @reset="onReset">
    <div class="row justify-center q-gutter-x-md q-mt-md">
      <div class="col-xs-12 col-sm-6 col-md-2">
        <q-input
          filled
          v-model="name"
          label="İsim *"
          lazy-rules
          :rules="[(val) => (val && val.length > 0) || 'Boş bırakılamaz']"
        />
      </div>
      <div class="col-xs-12 col-sm-6 col-md-2">
        <q-input
          filled
          v-model="email"
          label="Email *"
          lazy-rules
          :rules="emailRules"
        />
      </div>
      <div class="rounded-borders col-xs-12 col-sm-6 col-md-2 q-px-md">
        Kullanıcı Durumu Seç
        <q-option-group
          v-model="status"
          :options="options"
          color="red"
          inline
          dense
        >
        </q-option-group>
      </div>
    </div>
    <div
      class="col-xs-12 col-sm-6 col-md-2 q-mt-md row justify-center justify-md-start"
    >
      <q-btn
        label="Kullanıcı Ekle"
        type="submit"
        icon-right="add"
        color="primary"
        class="full-width-on-mobile"
      />
    </div>
  </q-form>
  <div class="row justify-center q-gutter-x-md q-mt-md">
    <div class="col-xs-12 col-sm-6 col-md-9 q-mt-md">
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
          <!-- Export butonu-->
          <q-btn
            color="secondary"
            icon-right="archive"
            label="Kişi Listesini İndir"
            no-caps
            class="q-mx-md"
            @click="exportTable"
          />
        </template>
        <!-- Kullanıcı adına tıklanınca tekil kullanıcı sayfasına git -->
        <template v-slot:body-cell-user_name="props">
          <q-td :props="props">
            <a :href="`/persons/${props.row.id}`">{{ props.row.user_name }}</a>
          </q-td>
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
              icon="edit"
            />
          </q-td>
        </template>
      </q-table>
    </div>
  </div>
  <!-- Silme işlemi için dialog -->
  <q-dialog v-model="confirm" persistent>
    <q-card style="width: 700px; max-width: 80vw">
      <div class="row justify-end">
        <q-btn dense icon="close" flat @click="cancelDeletion" />
      </div>
      <q-card-section class="row items-center">
        <span class="q-ml-sm">Kayıdı silmek istediğinizden emin misiniz ?</span>
      </q-card-section>
      <q-card-actions align="right">
        <q-btn
          label="İptal"
          icon-right="cancel"
          color="red"
          @click="cancelDeletion"
        />
        <!-- id null değilse silme işlemi çalışacak -->
        <q-btn
          label="Evet"
          color="primary"
          icon-right="check"
          @click="deleteUser(selectedUserId!)"
        />
      </q-card-actions>
    </q-card>
  </q-dialog>
  <!-- Güncelleme işlemi için dialog -->
  <q-dialog v-model="updateDialog" persistent>
    <q-card style="width: 700px; max-width: 80vw">
      <div class="row justify-end">
        <q-btn dense icon="close" flat @click="closeUpdateDialog" />
      </div>
      <q-card-section>
        <div class="text-h6 text-center text-deep-orange">
          Kullanıcıyı Güncelle
        </div>
      </q-card-section>
      <q-card-section>
        <q-input
          filled
          v-model="selectedUser.user_name"
          label="İsim *"
          lazy-rules
          :rules="[(val) => (val && val.length > 0) || 'Boş bırakılamaz']"
        />
        <q-input
          filled
          v-model="selectedUser.user_email"
          label="Email *"
          lazy-rules
          :rules="emailRules"
        />
        <div class="rounded-borders q-my-md">
          Select User Status
          <q-option-group
            v-model="selectedUser.user_status"
            :options="options"
            color="red"
            inline
            dense
          >
          </q-option-group>
        </div>
      </q-card-section>
      <q-card-actions align="right">
        <q-btn
          label="İptal"
          icon-right="cancel"
          color="negative"
          @click="closeUpdateDialog"
        />
        <q-btn
          icon-right="check_circle"
          label="Kaydet"
          color="primary"
          @click="updateUser"
        />
      </q-card-actions>
    </q-card>
  </q-dialog>
</template>

<script setup lang="ts">
import { useQuasar, QTableColumn, exportFile, QSpinnerFacebook } from 'quasar';
import { ref, Ref, onMounted, watch } from 'vue';
import { api } from 'boot/axios'; //axios instance
import { IPerson } from 'src/models/model';

const $q = useQuasar();
//form verileri
const filter = ref('');
const name = ref<string | null>(null);
const email = ref<string | null>(null);
const status = ref<string | null>('0');
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
  {
    name: 'actions',
    align: 'center',
    label: 'Sil /Güncelle',
    field: 'actions',
  },
];

//gelen get adına göre veri çekme
const fetchUsers = async () => {
  try {
    showLoading(); //loading göster
    const response = await api.post('api.php', {
      method: 'get-users',
    });
    // console.log('Response:', response);
    if (response.data.success === true) {
      rows.value = response.data.users; // tablo verilerini doldur
    }
  } catch (error) {
    console.error('Error fetching data:', error);
  } finally {
    $q.loading.hide(); //loading gizle
  }
};
// silme işlemi için onay verilirse id'si verilen kullanıcıyı sil
const confirmDeleteUser = (id: number) => {
  selectedUserId.value = id;
  confirm.value = true;
};
const cancelDeletion = () => {
  confirm.value = false;
  triggerInfo('Silme işlemi iptal edildi !');
};
// id'si verilen kullanıcıyı sil
const deleteUser = async (id: number) => {
  // console.log('Deleting user:', id);
  try {
    showLoading(); //loading göster
    await api.post('api.php', {
      id,
      method: 'delete-user',
    });
    confirm.value = false;
    triggerNotification('Kullanıcı başarıyla silindi', 'positive');
    fetchUsers(); // tabloyu güncelle
  } catch (error) {
    console.error('Error deleting user:', error);
  } finally {
    $q.loading.hide(); //loading gizle
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
      showLoading(); //loading göster
      //apiye güncelleme işlemi için verileri gönder
      const response = await api.post('api.php', {
        method: 'update-user',
        id: selectedUser.value.id,
        name: selectedUser.value.user_name,
        email: selectedUser.value.user_email,
        status: selectedUser.value.user_status,
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
    } finally {
      $q.loading.hide(); //loading gizle
    }
  }
};
// Dialog kapandığında notify mesajı göstermek için watch kullan
watch(updateDialog, (newVal, oldVal) => {
  if (oldVal === true && newVal === false) {
    if (wasCancelled.value) {
      triggerInfo('Güncelleme işlemi iptal edildi !');
    }
  }
});

// sayfa yüklendiğinde verileri çek
onMounted(() => {
  fetchUsers();
});
//form submit edilmişse
const onSubmit = async () => {
  try {
    //form verilerini apiye gönder
    showLoading(); //loading göster
    const response = await api.post('api.php', {
      method: 'add-user',
      name: name.value,
      email: email.value,
      status: status.value,
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
  } finally {
    $q.loading.hide(); //loading gizle
  }
};
//formu sıfırla
const onReset = () => {
  name.value = null;
  email.value = null;
  status.value = '0';
};

// Export işlemleri

type FormatFunction = (val: string, row: IPerson) => string;

const wrapCsvValue = (
  val: string,
  formatFn: FormatFunction | undefined,
  row: IPerson
) => {
  let formatted = formatFn !== undefined ? formatFn(val, row) : val;

  formatted =
    formatted === undefined || formatted === null ? '' : String(formatted);

  formatted = formatted.split('"').join('""');

  return `"${formatted}"`;
};

const exportTable = () => {
  const content = [
    columns.map((col) => wrapCsvValue(col.label, undefined, rows.value[0])),
  ]
    .concat(
      rows.value.map((row) =>
        columns
          .map((col) =>
            wrapCsvValue(
              typeof col.field === 'function'
                ? col.field(row)
                : row[col.field as keyof IPerson],
              col.format,
              row
            )
          )
          .join(',')
      )
    )
    .join('\r\n');

  const status = exportFile('table-export.csv', content, 'text/csv');

  if (status !== true) {
    showLoading();
    triggerNotification('Export işlemi başarısız oldu', 'negative');
  } else {
    triggerNotification('Export işlemi başarılı oldu', 'positive');
    $q.loading.hide();
  }
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

//loading göster
const showLoading = () => {
  $q.loading.show({
    spinner: QSpinnerFacebook,
    spinnerColor: 'primary',
    spinnerSize: 140,
    backgroundColor: 'rgba(0, 0, 0, 0.5)',
    message: 'Yükleniyor...',
    messageColor: 'white',
  });
};
</script>
