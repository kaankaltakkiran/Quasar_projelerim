<template>
  <q-page>
    <div class="q-pa-md q-mt-md">
      <q-list bordered padding>
        <q-item-label header>Notifications</q-item-label>

        <q-item tag="label" v-ripple>
          <q-item-section>
            <q-item-label>Prompt To Delete</q-item-label>
          </q-item-section>
          <q-item-section side>
            <q-toggle color="blue" v-model="isDelete" />
          </q-item-section>
        </q-item>

        <q-item tag="label" v-ripple>
          <q-item-section>
            <q-item-label>Show Running Balance</q-item-label>
            <q-item-label caption>Allow notification</q-item-label>
          </q-item-section>
          <q-item-section side top>
            <q-toggle color="blue" v-model="notif2" val="friend" />
          </q-item-section>
        </q-item>

        <q-item tag="label" v-ripple>
          <q-item-section>
            <q-input
              outlined
              dense
              v-model="Currency"
              label="Currency Symbol"
              @blur="updateCurrencySymbol"
            />
          </q-item-section>
        </q-item>
        <q-separator spaced />
        <q-item-label header>Appearance</q-item-label>

        <q-item tag="label" v-ripple>
          <q-item-section avatar>
            <q-radio v-model="theme" val="light" />
          </q-item-section>
          <q-item-section>
            <q-item-label>Light</q-item-label>
          </q-item-section>
        </q-item>

        <q-item tag="label" v-ripple>
          <q-item-section avatar>
            <q-radio v-model="theme" val="dark" />
          </q-item-section>
          <q-item-section>
            <q-item-label>Dark</q-item-label>
          </q-item-section>
        </q-item>

        <q-item tag="label" v-ripple>
          <q-item-section avatar top>
            <q-radio v-model="theme" val="auto" />
          </q-item-section>
          <q-item-section>
            <q-item-label>Auto</q-item-label>
            <q-item-label caption
              >Follow the preference of your device</q-item-label
            >
          </q-item-section>
        </q-item>
      </q-list>
    </div>
  </q-page>
</template>

<script setup lang="ts">
import { ref, watch, onMounted } from 'vue';
import { useQuasar, LocalStorage } from 'quasar';

const $q = useQuasar();

// Değişkenler
const isDelete = ref<boolean>(LocalStorage.getItem('isDelete') !== 'false');
const notif2 = ref<boolean>(LocalStorage.getItem('notif2') === true || false);
const Currency = ref<string>(LocalStorage.getItem('Currency') || '$');
const theme = ref<string>(LocalStorage.getItem('theme') || 'light');

// Tema değişikliğine göre ayarları kaydetme ve uygulama
watch(theme, (newVal) => {
  LocalStorage.set('theme', newVal);
  switch (newVal) {
    case 'light':
      $q.dark.set(false);
      break;
    case 'dark':
      $q.dark.set(true);
      break;
    case 'auto':
      $q.dark.set('auto');
      break;
  }
});

// Currency input değişikliğini güncelleme
const updateCurrencySymbol = () => {
  LocalStorage.set('Currency', Currency.value);
};

// Diğer ayarları izleme ve kaydetme
watch([isDelete, notif2, Currency], ([newisDelete, newNotif2, newCurrency]) => {
  LocalStorage.set('isDelete', newisDelete.toString()); // isDelete değerini string olarak yaz
  LocalStorage.set('notif2', newNotif2.toString()); // notif2 değerini string olarak yaz
  LocalStorage.set('Currency', newCurrency); // Currency değeri string olduğu için otomatik olarak string olarak yazılır
});

// Bileşen yüklendiğinde temayı ayarlama
onMounted(() => {
  switch (theme.value) {
    case 'light':
      $q.dark.set(false);
      break;
    case 'dark':
      $q.dark.set(true);
      break;
    case 'auto':
      $q.dark.set('auto');
      break;
  }
});
</script>
