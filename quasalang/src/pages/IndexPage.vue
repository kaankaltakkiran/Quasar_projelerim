<template>
  <q-page padding>
    <q-select
      class="q-mb-md"
      v-model="locale"
      :options="localeOptions"
      label="Quasar Language"
      dense
      borderless
      emit-value
      map-options
      options-dense
      filled
      style="min-width: 150px"
    />
    <q-btn :label="$t('hello')"></q-btn>
    <q-btn :label="$t('goodbye')"></q-btn>
    <q-btn :label="$t('thanks')"></q-btn>
    <q-btn :label="$t('buttHair')"></q-btn>
  </q-page>
</template>

<script setup>
import { useI18n } from "vue-i18n";

const { locale } = useI18n({ useScope: "global" });

const localeOptions = [
  { value: "en-US", label: "English" },
  { value: "de", label: "German" },
  { value: "fr", label: "French" },
  { value: "it", label: "Italian" },
  { value: "tr", label: "Turkish" },
];
</script>
