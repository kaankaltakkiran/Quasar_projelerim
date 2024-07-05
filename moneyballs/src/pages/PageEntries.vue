<template>
  <q-page>
    <div class="q-pa-md">
      <!-- Data yoksa -->
      <div class="q-mt-md" v-if="entryStore.entries.length == 0">
        <div class="row justify-center">
          <q-icon class="text-grey-6 q-mb-md" name="savings" size="100px" />
        </div>
        <div class="text-h6 text-center text-grey-8">
          No entries yet. Add your first entry.
        </div>
      </div>
      <q-list bordered separator v-if="entryStore.entries.length >= 1">
        <q-slide-item
          v-for="(entry, index) in entryStore.entries"
          :key="entry.id"
          @right="onEntrySlideRight($event, entry)"
          left-color="positive"
          right-color="negative"
          :draggable="isEditMode"
          @dragstart="onDragStart($event, index)"
          @dragover="onDragOver"
          @drop="onDrop($event, index)"
        >
          <template v-slot:right>
            <q-icon name="delete" />
          </template>
          <q-item>
            <q-item-section
              class="text-weight-bold"
              :class="useAmountColorClass(entry.amount)"
            >
              {{ entry.name }}
            </q-item-section>
            <q-item-section
              class="text-weight-bold"
              side
              :class="useAmountColorClass(entry.amount)"
            >
              {{ useCurrencify(entry.amount) }}
            </q-item-section>
            <!-- Edit Mode -->
            <q-item-section side>
              <q-icon v-if="isEditMode" name="menu" />
            </q-item-section>
          </q-item>
        </q-slide-item>
      </q-list>
    </div>
    <!-- Footer Section -->
    <q-footer class="bg-transparent">
      <div class="row q-mb-sm q-px-md q-my-sm shadow-up-3">
        <div class="col text-grey-7 text-h6">Balance:</div>
        <div
          :class="useAmountColorClass(balance)"
          class="row text-h6 text-right"
        >
          {{ useCurrencify(balance) }}
        </div>
      </div>
      <!-- Form Section -->
      <q-form
        @submit="addEntry"
        class="row q-px-sm q-pb-sm q-col-gutter-sm bg-primary"
      >
        <div class="col">
          <q-input
            outlined
            dense
            v-model="addEntryForm.name"
            ref="nameRef"
            placeholder="Name"
            bg-color="white"
            :rules="enableRules ? [validateName] : []"
            @blur="checkInput('name')"
          />
        </div>
        <div class="col">
          <q-input
            outlined
            input-class="text-right"
            dense
            step="0.01"
            v-model.number="addEntryForm.amount"
            placeholder="Amount"
            bg-color="white"
            :rules="enableRules ? [validateAmount] : []"
            @blur="checkInput('amount')"
            mask="#"
            reverse-fill-mask
          />
        </div>
        <div class="col col-auto">
          <q-btn color="primary" type="submit" round icon="add" />
        </div>
      </q-form>
    </q-footer>
  </q-page>
</template>

<script setup lang="ts">
import { ref, computed, reactive, watch, onMounted } from 'vue';
import { uid, useQuasar, LocalStorage } from 'quasar';
import { useEntryStore } from 'src/stores/entries-store';
// use importları
import { useCurrencify } from 'src/use/useCurrencify';
import { useAmountColorClass } from 'src/use/useAmountColorClass';

// useQuasar fonksiyonu, Quasar fonksiyonlarını kullanmamızı sağlar
const $q = useQuasar();

// EntryStore'u kullanma
const entryStore = useEntryStore();

//mounted olduğunda entryStore'dan verileri çekme
onMounted(() => {
  entryStore.fetchEntries();
});

// Props tanımlama
const props = defineProps({
  isEditMode: {
    type: Boolean,
    default: false,
  },
});
//emits ile editMode prop'unu güncelleme
const emits = defineEmits(['update:editMode']);

// Yerel editMode değeri
const localEditMode = ref(props.isEditMode);

// Props değiştiğinde yerel değeri güncelle
watch(localEditMode, (newValue) => {
  emits('update:editMode', newValue);
});

//props.select değiştiğinde localEditMode değerini güncelleme
watch(props, (newProps) => {
  localEditMode.value = newProps.isEditMode;
});

/* Bakiye hesaplama */
const balance = computed(() => {
  return entryStore.entries.reduce((accumulator, { amount }) => {
    return accumulator + amount;
  }, 0);
});

/* Ekleme işlemi */
const nameRef = ref<HTMLElement | null>(null);
const addEntryFormDefault = {
  name: '',
  amount: null as number | null,
};
const addEntryForm = reactive({
  ...addEntryFormDefault,
});

// Kuralların etkinleştirilmesi
const enableRules = ref(true);

const addEntryFormReset = () => {
  enableRules.value = false;
  Object.assign(addEntryForm, addEntryFormDefault);
  // nameRef.value?.focus(); // İlk inputa odaklan
  setTimeout(() => {
    enableRules.value = true;
    $q.notify({
      icon: 'check',
      color: 'positive',
      position: 'top',
      message: 'Entry added successfully',
      group: false,
    });
  }, 0);
};

const addEntry = () => {
  const newEntry = Object.assign(
    {},
    {
      id: uid(),
      name: addEntryForm.name,
      amount: addEntryForm.amount !== null ? addEntryForm.amount : 0,
    }
  );
  entryStore.entries.push(newEntry);
  addEntryFormReset();
};

/* Silme işlemi */
const isDeleteValue = LocalStorage.getItem('isDelete');

const onEntrySlideRight = (
  { reset }: { reset: () => void },
  entry: { id: string; name: string; amount: number }
) => {
  if (isDeleteValue === 'false') {
    deleteEntry(entry.id);
  } else {
    $q.dialog({
      title: 'Delete Entry',
      message: `Are you sure you want to delete this entry?
      <div class="q-mt-md text-weight-bold ${useAmountColorClass(
        entry.amount
      )}">
      ${entry.name}  ${useCurrencify(entry.amount)}
      </div>`,
      html: true,
      cancel: {
        color: 'primary',
        noCaps: true,
      },
      persistent: true,
      ok: {
        label: 'delete',
        color: 'negative',
        noCaps: true,
      },
    })
      .onOk(() => {
        deleteEntry(entry.id);
      })
      .onCancel(() => {
        reset();
      });
  }
};

const deleteEntry = (entryId: string) => {
  const index = entryStore.entries.findIndex((entry) => entry.id === entryId);
  entryStore.entries.splice(index, 1);
  $q.notify({
    icon: 'delete',
    color: 'negative',
    position: 'top',
    message: 'Entry deleted',
    group: false,
  });
};

/* Doğrulama ve Bildirim Gösterimi */
const validateName = (val: string) => {
  if (val && val.length > 0) {
    return true;
  } else {
    $q.notify({
      icon: 'warning',
      color: 'negative',
      position: 'top',
      message: 'Please enter a name for the entry',
      group: false,
    });
    return false;
  }
};

const validateAmount = (val: number | null) => {
  if (val !== null) {
    return true;
  } else {
    $q.notify({
      icon: 'warning',
      color: 'negative',
      position: 'top',
      message: 'Please enter an amount for the entry',
      group: false,
    });
    return false;
  }
};

const checkInput = (field: string) => {
  if (field === 'name') {
    validateName(addEntryForm.name);
  }
  if (field === 'amount') {
    validateAmount(addEntryForm.amount);
  }
};

/* Drag & Drop İşlemleri */

//onnDragStart sürükleme işlemi başladığında veriyi ayarlar
const onDragStart = (e: DragEvent, index: number) => {
  e.dataTransfer?.setData('index', index.toString());
};
//onDragOver tarayıcı davranışını durdurur
const onDragOver = (e: DragEvent) => {
  e.preventDefault();
};

//onDrop sürükleme işlemi bittiğinde veriyi ayarlar
const onDrop = (e: DragEvent, index: number) => {
  e.preventDefault();
  const draggedIndex = e.dataTransfer?.getData('index');
  if (draggedIndex !== undefined) {
    const draggedEntry = entryStore.entries.splice(
      parseInt(draggedIndex),
      1
    )[0];
    entryStore.entries.splice(index, 0, draggedEntry);
  }
};
</script>
