<template>
  <q-page padding>
    <q-btn label="English" @click="$i18n.locale = 'en-us'"> </q-btn>
    <q-btn label="French" @click="$i18n.locale = 'fr'"> </q-btn>
    <q-separator class="q-my-lg" />
    <q-btn :label="$t('failed')" />
    <q-btn :label="$t('success')" />
    <p>{{ $t("failed") }}</p>
    <p>{{ $t("success") }}</p>
  </q-page>
</template>

<script setup>
defineOptions({
  name: "IndexPage",
  mounted() {
    //this.$t("failed");
  },
});
</script>
