<template>
  <div class="q-pa-md">
    <h3 class="text-center text-red">Ürünler</h3>
    <q-table
      :grid="$q.screen.xs"
      flat
      bordered
      title="Ürünler"
      :rows="rows"
      :columns="columns"
      row-key="name"
      :filter="filter"
    >
      <!-- Tablo başlığında arama kutusu -->
      <template v-slot:top-right>
        <q-input borderless dense debounce="300" v-model="filter" placeholder="Ara...">
          <template v-slot:append>
            <q-icon name="search" />
          </template>
        </q-input>
      </template>
      <!-- Silme butonu -->
      <template v-slot:body-cell-delete="props">
        <q-td :props="props">
          <q-btn color="negative" icon-right="delete" dense>Ürün Sil</q-btn>
        </q-td>
      </template>
      <!-- Güncelleme butonu -->
      <template v-slot:body-cell-update="props">
        <q-td :props="props">
          <q-btn color="secondary" icon-right="update" dense>Ürün Güncelle</q-btn>
        </q-td>
      </template>
    </q-table>
  </div>
  <!-- Silme işlemi için dialog -->
  <q-dialog persistent>
    <q-card style="width: 700px; max-width: 80vw">
      <q-card-section class="row items-center">
        <span class="q-ml-sm">Ürünü silmek istediğinizden emin misiniz ?</span>
      </q-card-section>
      <q-card-actions align="right">
        <q-btn flat label="İptal" color="red" />
        <q-btn flat label="Evet" color="primary" />
      </q-card-actions>
    </q-card>
  </q-dialog>
  <!-- Güncelleme işlemi için dialog -->
  <q-dialog persistent>
    <q-card style="width: 700px; max-width: 80vw">
      <q-card-section>
        <div class="text-h6 text-center text-deep-orange">Ürünü Güncelle</div>
      </q-card-section>
      <q-card-section>
        <q-input label="Ürün Adı" v-model="product_name" />
        <q-input label="Ürün Açıklaması" v-model="product_description" />
        <q-input label="Ürün Fiyatı" v-model="product_price" />
        <q-input label="Ürün Stok" v-model="product_stock" />
      </q-card-section>
      <q-card-actions align="right">
        <q-btn flat label="İptal" color="negative" />
        <q-btn flat label="Kaydet" color="primary" />
      </q-card-actions>
    </q-card>
  </q-dialog>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import type { QTableColumn } from 'quasar';

// veri adını ve türlerini belirle
interface Product {
  id: number;
  product_name: string;
  product_description: string;
  product_price: number;
  product_stock: number;
}

// Reactive properties
const filter = ref('');
const product_name = ref('');
const product_description = ref('');
const product_price = ref<number | null>(null);
const product_stock = ref<number | null>(null);

// Sample data - replace with your actual data source
const rows = ref<Product[]>([
  {
    id: 1,
    product_name: 'Product 1',
    product_description: 'Product 1 Description',
    product_price: 100,
    product_stock: 10,
  },
]);

// tablo sütunlarını belirle
const columns: QTableColumn[] = [
  { name: 'id', align: 'left', label: 'id', field: 'id', sortable: true },
  {
    name: 'product_name',
    align: 'left',
    label: 'Ürün Adı',
    field: 'product_name',
    sortable: true,
  },
  {
    name: 'product_description',
    align: 'center',
    label: 'Ürün Açıklaması',
    field: 'product_description',
    sortable: true,
  },
  {
    name: 'product_price',
    align: 'center',
    label: 'Ürün Fiyatı',
    field: 'product_price',
    sortable: true,
  },
  {
    name: 'product_stock',
    align: 'center',
    label: 'Ürün Stok Adet',
    field: 'product_stock',
    sortable: true,
  },
  { name: 'delete', align: 'center', label: 'Sil', field: 'delete' },
  { name: 'update', align: 'center', label: 'Güncelle', field: 'update' },
];
</script>
