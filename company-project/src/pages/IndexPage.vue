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
          v-model="email"
          label="Your email *"
          lazy-rules
          :rules="[(val) => (val && val.length > 0) || 'Please type something']"
        />
        <q-input
          filled
          v-model="name"
          label="Your name *"
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

defineOptions({ name: 'IndexPage' });
const $q = useQuasar();

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

const onSubmit = () => {
  if (!accept.value) {
    $q.notify({
      color: 'red-5',
      textColor: 'white',
      icon: 'warning',
      message: 'You need to accept the license and terms first',
      position: 'top-right',
    });
  } else {
    $q.notify({
      color: 'green-4',
      textColor: 'white',
      icon: 'cloud_done',
      message: 'Submitted',
      position: 'top-right',
    });
    onReset();
  }
};

const onReset = () => {
  email.value = null;
  name.value = null;
  accept.value = false;
};
</script>
