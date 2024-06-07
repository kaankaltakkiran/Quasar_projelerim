<template>
  <div class="row justify-center">
    <div
      class="q-pa-md col-12 col-sm-10 col-md-8 col-lg-6"
      style="max-width: 400px"
    >
      <h4 class="text-center text-red">Add Person Form</h4>
      <q-form @submit="onSubmit" @reset="onReset" class="q-gutter-md">
        <q-input
          filled
          v-model="name"
          label="Your name *"
          lazy-rules
          :rules="[(val) => (val && val.length > 0) || 'Please type something']"
        />
        <q-input
          filled
          v-model="email"
          label="Your email *"
          lazy-rules
          :rules="[(val) => (val && val.length > 0) || 'Please type something']"
        />
        <div class="rounded-borders">Select User Status</div>
        <q-option-group
          v-model="status"
          :options="options"
          color="red"
          inline
          dense
        >
        </q-option-group>
        <q-toggle v-model="accept" label="I accept the license and terms" />
        <div>
          <q-btn label="Submit" type="submit" color="primary" />
          <q-btn
            label="Reset"
            type="reset"
            color="primary"
            flat
            class="q-ml-sm"
          />
        </div>
      </q-form>
    </div>
  </div>
</template>

<script setup lang="ts">
import { useQuasar } from 'quasar';
import { ref, Ref } from 'vue';
import axios from 'axios';

defineOptions({ name: 'IndexPage' });
//notify için
const $q = useQuasar();
//form değerlerini tanımla
const email: Ref<string | null> = ref(null);
const name: Ref<string | null> = ref(null);
const accept: Ref<boolean> = ref(false);
const status: Ref<string | null> = ref('0');

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

//form submit edilmişse
const onSubmit = async () => {
  if (!accept.value) {
    $q.notify({
      color: 'red-5',
      textColor: 'white',
      icon: 'warning',
      message: 'You need to accept the license and terms first',
      position: 'top-right',
    });
    return;
  }
  try {
    //form verilerini apiye gönder
    const response = await axios.post(
      'http://localhost/veri/ornek_api/api.php',
      {
        email: email.value,
        name: name.value,
        status: status.value,
        method: 'add-user',
      }
    );
    // console.log('Response:', response);
    //işlem başarılıysa
    if (response.data.success === true) {
      $q.notify({
        color: 'green-4',
        textColor: 'white',
        icon: 'cloud_done',
        message: 'User added successfully',
        position: 'top-right',
      });
      onReset(); //formu sıfırla
    } else {
      $q.notify({
        color: 'red-5',
        textColor: 'white',
        icon: 'warning',
        message: 'An error occurred while sending the form',
        position: 'top-right',
      });
    }
  } catch (error) {
    console.error('Error sending form:', error);
    $q.notify({
      color: 'red-5',
      textColor: 'white',
      icon: 'warning',
      message: 'An error occurred while sending the form',
      position: 'top-right',
    });
  }
};
//formu sıfırla
const onReset = () => {
  email.value = null;
  name.value = null;
  accept.value = false;
};
</script>
