<template>
  <h2 class="text-red text-center">Person List</h2>
  <div class="row justify-center">
    <!-- Table start-->
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
        <!-- checkbox-->
        <template v-slot:body-cell-selection="props">
          <q-checkbox v-model="props.selected" />
        </template>
        <template v-slot:body-cell-user_name="props">
          <q-td :props="props">
            <!-- Kullanıcı adına tıklanınca tekil kullanıcı sayfasına git -->
            <a :href="`/persons/${props.row.id}`">{{ props.row.user_name }}</a>
          </q-td>
        </template>
        <!-- Delete butonu -->
        <template v-slot:top="props">
          <q-btn
            label="Delete Person"
            color="negative"
            icon-right="delete"
            dense
            @click="confirmDelete"
          />
          <!-- Update butonu-->
          <q-btn
            class="q-ml-md"
            label="Update Person"
            color="primary"
            icon-right="update"
            dense
            @click="confirmUpdate"
          />
          <q-space />
          <!-- Search butonu-->
          <q-input
            borderless
            dense
            debounce="300"
            color="primary"
            v-model="filter"
          >
            <template v-slot:append>
              <q-icon name="search" />
            </template>
          </q-input>
          <q-btn
            flat
            round
            dense
            :icon="props.inFullscreen ? 'fullscreen_exit' : 'fullscreen'"
            @click="props.toggleFullscreen"
            class="q-ml-md"
          />
          <!-- Export butonu-->
          <q-btn
            color="secondary"
            icon-right="archive"
            label="Export to csv"
            no-caps
            class="q-mx-md"
            @click="exportTable"
          />
        </template>
      </q-table>
      <!-- Table end-->
      <div class="q-mt-md">Selected Value: {{ JSON.stringify(selected) }}</div>
    </div>
    <!-- Güncelleme Dialogu -->
    <q-dialog v-model="showUpdateDialog">
      <q-card style="width: 700px; max-width: 80vw">
        <div class="row justify-end">
          <q-btn dense icon="close" flat @click="showUpdateDialog = false" />
        </div>
        <q-card-section>
          <q-input
            class="q-mb-md"
            v-model="selectedToUpdate.user_name"
            label="Name"
          />
          <q-input
            class="q-mb-md"
            v-model="selectedToUpdate.user_email"
            label="Email"
          />
          <div class="rounded-borders q-my-md">Select User Status</div>
          <q-option-group
            v-model="selectedToUpdate.user_status"
            :options="options"
            color="red"
            inline
            dense
          >
          </q-option-group>
        </q-card-section>
        <q-card-actions align="center">
          <q-btn
            label="Update"
            color="primary"
            icon-right="check"
            @click="updateSelectedRows"
          />
          <q-btn
            label="Cancel"
            color="negative"
            icon-right="cancel"
            @click="showUpdateDialog = false"
          />
        </q-card-actions>
      </q-card>
    </q-dialog>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { useQuasar, QTableColumn, exportFile } from 'quasar';
import axios from 'axios';
import { IPerson } from '../interfaces/IPerson';

const options = [
  {
    label: '0',
    value: '0',
  },
  {
    label: '1',
    value: '1',
  },
];
const $q = useQuasar();

// tablo değişkenleri
const rows = ref<IPerson[]>([]); // tablo verilerini tut
const selected = ref<IPerson[]>([]); //seçilen verileri tut
// güncellenmek istenen veriyi tut
const selectedToUpdate = ref<IPerson>({
  id: 0,
  user_name: '',
  user_email: '',
  user_status: 0,
});
const filter = ref(''); // tablo filtresi
const showUpdateDialog = ref(false); // güncelleme dialogunu göster

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
    if (response.data.success === true) {
      rows.value = response.data.users; // tablo verilerini doldur
    }
  } catch (error) {
    console.error('Error fetching data:', error);
    triggerNotification('Error during data deletion!', 'negative');
  }
};
// seçilen satırları silme fonksiyonu
const deleteSelectedRows = async () => {
  try {
    const selectedIds = selected.value.map((IPerson) => IPerson.id);
    const response = await axios.post(
      'http://localhost/veri/ornek_api/api.php',
      {
        method: 'delete-user',
        ids: selectedIds, // ids parametresi bir dizi olarak gönderiliyor
      }
    );
    if (response.data.success === true) {
      selected.value = []; // seçilen verileri temizle
      fetchData(); // verileri tekrar çek
      triggerNotification('Selected users deleted successfully!', 'positive');
    }
  } catch (error) {
    console.error('Error deleting data:', error);
    triggerNotification('Error during data deletion!', 'negative');
  }
};
// Delete onay kutusu
const confirmDelete = () => {
  if (selected.value.length === 0) {
    triggerInfo('Please select at least one user to delete !');
    return;
  }
  $q.dialog({
    title: 'Please be careful !',
    message: 'Are you sure you want to delete the selected users ?',
    cancel: true,
    persistent: true,
  }).onOk(() => {
    deleteSelectedRows(); // seçilen verileri sil
  });
};
// seçilen verileri string olarak döndür
function getSelectedString() {
  return selected.value.length === 0
    ? ''
    : `${selected.value.length} record${
        selected.value.length > 1 ? 's' : ''
      } selected of ${rows.value.length}`;
}
// seçilen veriyi güncelleme
const updateSelectedRows = async () => {
  try {
    const response = await axios.post(
      'http://localhost/veri/ornek_api/api.php',
      {
        method: 'update-user',
        id: selectedToUpdate.value.id,
        name: selectedToUpdate.value.user_name,
        email: selectedToUpdate.value.user_email,
        status: selectedToUpdate.value.user_status,
      }
    );
    if (response.data.success === true) {
      showUpdateDialog.value = false; // Güncelleme dialogunu kapat
      fetchData(); // verileri tekrar çek
      triggerNotification('Record updated successfully!', 'positive');
      selected.value = []; // işaretli satırları temizle
    }
  } catch (error) {
    console.error('Error updating data:', error);
    triggerNotification('Error during data update!', 'negative');
  }
};
// Güncelleme Dialogu fonksiyonları
const confirmUpdate = () => {
  if (selected.value.length === 0) {
    triggerInfo('Please select a record to update!'); // Hiçbir kayıt seçilmemişse bilgi mesajı göster
    return;
  }
  if (selected.value.length !== 1) {
    triggerInfo('You can update only one record!'); // Sadece bir kullanıcı seçilmemişse bilgi mesajı göster
    return;
  }
  // Seçilen kullanıcının verilerini güncelleme dialogunda göstermek için seçilen verileri kopyala
  // selectedToUpdate.value = JSON.parse(JSON.stringify(selected.value[0]));
  selectedToUpdate.value = { ...selected.value[0] };
  showUpdateDialog.value = true; // Güncelleme dialogunu göster
};

// sayfa yüklendiğinde verileri çek
onMounted(() => {
  fetchData();
});
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
// Export işlemleri
type RowType = {
  id: number;
  user_name: string;
  user_email: string;
  user_status: number;
};

type FormatFunction = (val: string, row: RowType) => string;

const wrapCsvValue = (
  val: string,
  formatFn: FormatFunction | undefined,
  row: RowType
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
    $q.notify({
      message: 'Error exporting data!',
      color: 'negative',
      icon: 'warning',
    });
  }
};
</script>
