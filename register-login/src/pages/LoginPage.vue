<template>
  <q-page class="flex flex-center">
    <q-card class="auth-card" style="min-width: 300px">
      <q-card-section>
        <div class="text-h6">Login</div>
      </q-card-section>

      <q-card-section>
        <q-form @submit="onSubmit" class="q-gutter-md">
          <q-input
            v-model="email"
            type="email"
            label="Email"
            filled
            :rules="[val => !!val || 'Email is required']"
          />

          <q-input
            v-model="password"
            type="password"
            label="Password"
            filled
            :rules="[val => !!val || 'Password is required']"
          />

          <div>
            <q-btn label="Login" type="submit" color="primary"/>
          </div>
        </q-form>
      </q-card-section>
    </q-card>
  </q-page>
</template>

<script setup lang="ts">
import { ref } from 'vue';

const email = ref('');
const password = ref('');

const onSubmit = () => {
  // Will implement later with backend
  console.log('Login:', { email: email.value, password: password.value });
};
</script>

<style scoped>
.auth-card {
  padding: 20px;
}
</style>
