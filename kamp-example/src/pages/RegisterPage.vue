<template>
  <q-page class="flex flex-center">
    <q-card class="auth-card" style="min-width: 300px">
      <q-card-section>
        <div class="text-h6">Üye Ol</div>
      </q-card-section>

      <q-card-section>
        <q-form class="q-gutter-md">
          <q-input
            v-model="name"
            type="text"
            label="Name"
            filled
            :rules="[(val: string) => !!val || 'Adınız gereklidir']"
          />

          <q-input
            v-model="email"
            type="email"
            label="Email"
            filled
            :rules="[(val: string) => !!val || 'E-posta adresiniz gereklidir']"
          />

          <q-input
            v-model="password"
            type="password"
            label="Password"
            filled
            :rules="[(val: string) => !!val || 'Parolanız gereklidir']"
          />
          <q-input
            v-model="password_confirmation"
            type="password"
            label="Password Confirmation"
            filled
            :rules="[(val: string) => !!val || 'Parola onayınız gereklidir']"
          />
          <div>
            <q-btn label="Üye Ol" type="submit" color="primary" :loading="loading">
              <template v-slot:loading>
                <q-spinner-facebook />
              </template>
            </q-btn>
          </div>
        </q-form>
      </q-card-section>
    </q-card>
  </q-page>
</template>

<script setup lang="ts">
import { ref } from 'vue';
const name = ref('');
const email = ref('');
const password = ref('');
const password_confirmation = ref('');

const loading = ref(false);
</script>

<style scoped>
.auth-card {
  padding: 20px;
}
</style>
