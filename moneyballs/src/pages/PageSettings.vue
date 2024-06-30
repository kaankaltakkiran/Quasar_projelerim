<template>
  <q-page>
    <div class="q-pa-md q-mt-md">
      <q-list bordered padding>
        <q-item-label header>Notifications</q-item-label>

        <q-item tag="label" v-ripple>
          <q-item-section>
            <q-item-label>Prompt to delete</q-item-label>
          </q-item-section>
          <q-item-section side>
            <q-toggle color="blue" v-model="notif1" val="battery" />
          </q-item-section>
        </q-item>

        <q-item tag="label" v-ripple>
          <q-item-section>
            <q-item-label>Show Runnig Balance</q-item-label>
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
            />
          </q-item-section>
        </q-item>
        <q-separator spaced />
        <q-item-label header>Apperance</q-item-label>

        <q-item tag="label" v-ripple>
          <q-item-section avatar>
            <q-radio v-model="color" val="teal" />
          </q-item-section>
          <q-item-section>
            <q-item-label>Light</q-item-label>
          </q-item-section>
        </q-item>

        <q-item tag="label" v-ripple>
          <q-item-section avatar>
            <q-radio v-model="color" val="orange" />
          </q-item-section>
          <q-item-section>
            <q-item-label>Dark</q-item-label>
          </q-item-section>
        </q-item>

        <q-item tag="label" v-ripple>
          <q-item-section avatar top>
            <q-radio v-model="color" val="cyan" />
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
import { ref } from 'vue';

const notif1 = ref(true);
const notif2 = ref(false);
const Currency = ref('$');
const color = ref('teal');
</script>
