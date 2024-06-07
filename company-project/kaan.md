<template>
  <div class="q-pa-md">
    <h1 class="text-center text-red">Persons</h1>
    <q-table
      :grid="$q.screen.xs"
      flat
      bordered
      title="Treats"
      :rows="rows"
      :columns="columns"
      row-key="name"
      :filter="filter"
      :selected-rows-label="getSelectedString"
      selection="multiple"
      :selected="selected"
      @selection="onSelection"
    >
      <template v-slot:top>
        <q-btn @click="addRow" color="primary" label="Add row" />
        <q-btn
          v-if="rows.length !== 0"
          @click="removeRow"
          class="q-ml-sm"
          color="primary"
          label="Remove row"
        />
        <q-space />
        <q-input
          borderless
          dense
          debounce="300"
          color="primary"
          v-model="filter"
          placeholder="Search"
        >
          <template v-slot:append>
            <q-icon name="search" />
          </template>
        </q-input>
      </template>
      <!-- Checkboxlar için satır seçimi -->
      <template v-slot:body-cell-selection="props">
        <q-checkbox v-model="props.selected" />
      </template>
    </q-table>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { QTableColumn } from 'quasar';
import { useQuasar } from 'quasar';

interface Row {
  name: string;
  calories: number;
  fat: number;
  carbs: number;
}
const columns: QTableColumn[] = [
  {
    name: 'desc',
    required: true,
    label: 'Dessert (100g serving)',
    align: 'left',
    field: (row: Row) => row.name,
    format: (val: string) => `${val}`,
    sortable: true,
  },
  {
    name: 'calories',
    align: 'center',
    label: 'Calories',
    field: 'calories',
    sortable: true,
  },
  { name: 'fat', label: 'Fat (g)', field: 'fat', sortable: true },
  { name: 'carbs', label: 'Carbs (g)', field: 'carbs' },
];
const rows: Row[] = [
  {
    name: 'Frozen Yogurt',
    calories: 159,
    fat: 6.0,
    carbs: 24,
  },
  {
    name: 'Ice cream sandwich',
    calories: 237,
    fat: 9.0,
    carbs: 37,
  },
  {
    name: 'Eclair',
    calories: 262,
    fat: 16.0,
    carbs: 23,
  },
  {
    name: 'Cupcake',
    calories: 305,
    fat: 3.7,
    carbs: 67,
  },
  {
    name: 'Gingerbread',
    calories: 356,
    fat: 16.0,
    carbs: 49,
  },
  {
    name: 'Jelly bean',
    calories: 375,
    fat: 0.0,
    carbs: 94,
  },
  {
    name: 'Lollipop',
    calories: 392,
    fat: 0.2,
    carbs: 98,
  },
  {
    name: 'Honeycomb',
    calories: 408,
    fat: 3.2,
    carbs: 87,
  },
  {
    name: 'Donut',
    calories: 452,
    fat: 25.0,
    carbs: 51,
  },
  {
    name: 'KitKat',
    calories: 518,
    fat: 26.0,
    carbs: 65,
  },
];
const filter = ref('');

const $q = useQuasar();

const selected = ref<Row[]>([]);
const lastIndex = ref<number | null>(null);
import { QTable } from 'quasar';

const tableRef = ref<QTable | null>(null);

function getSelectedString() {
  return selected.value.length === 0
    ? ''
    : `${selected.value.length} record${
        selected.value.length > 1 ? 's' : ''
      } selected of ${rows.length}`;
}

function onSelection({
  rows,
  added,
  evt,
}: {
  rows: readonly Row[];
  added: boolean;
  evt: Event;
}) {
  if (!tableRef.value) {
    return;
  }

  const row = rows[0];
  const filteredSortedRows = tableRef.value.filteredSortedRows;
  const rowIndex = filteredSortedRows.indexOf(row);
  const localLastIndex = lastIndex.value;

  lastIndex.value = rowIndex;
  document.getSelection()?.removeAllRanges();

  if ($q.platform.is.mobile === true) {
    evt = { ctrlKey: true } as MouseEvent;
  } else if (
    evt instanceof MouseEvent &&
    evt.shiftKey !== true &&
    evt.ctrlKey !== true
  ) {
    selected.value = added === true ? Array.from(rows) : [];
    return;
  }

  const operateSelection =
    added === true
      ? (selRow: Row) => {
          const selectedIndex = selected.value.indexOf(selRow);
          if (selectedIndex === -1) {
            selected.value = selected.value.concat(selRow);
          }
        }
      : (selRow: Row) => {
          const selectedIndex = selected.value.indexOf(selRow);
          if (selectedIndex > -1) {
            selected.value = selected.value
              .slice(0, selectedIndex)
              .concat(selected.value.slice(selectedIndex + 1));
          }
        };

  if (
    localLastIndex === null ||
    !(evt instanceof MouseEvent) ||
    evt.shiftKey !== true
  ) {
    operateSelection(row);
    return;
  }

  const from = localLastIndex < rowIndex ? localLastIndex : rowIndex;
  const to = localLastIndex < rowIndex ? rowIndex : localLastIndex;
  for (let i = from; i <= to; i += 1) {
    operateSelection(filteredSortedRows[i]);
  }
}

function addRow() {
  // Yeni bir satır ekleme işlevselliği eklenecek
}

function removeRow() {
  // Satırı kaldırma işlevselliği eklenecek
}
</script>
