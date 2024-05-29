<template>

 <div class="row justify-center">
    <div class="q-pa-md col-12 col-sm-10 col-md-8 col-lg-6" style="max-width: 400px">
      <h4 class="text-center text-red">Example Form</h4>
<q-form
  @submit="onSubmit"
  @reset="onReset"
  class="q-gutter-md"
>
  <q-input
    filled
    v-model="name"
    label="Your name *"
    hint="Name and surname"
    lazy-rules
    :rules="[ val => val && val.length > 0 || 'Please type something']"
  />

  <q-input
    filled
    type="number"
    v-model="age"
    label="Your age *"
    lazy-rules
    :rules="[
      val => val !== null && val !== '' || 'Please type your age',
      val => val > 0 && val < 100 || 'Please type a real age'
    ]"
  />

  <q-toggle v-model="accept" label="I accept the license and terms" />

  <div>
    <q-btn label="Submit" type="submit" color="primary"/>
    <q-btn label="Reset" type="reset" color="red"  class="q-ml-sm" />
  </div>
</q-form>

</div>
</div>
</template>

<script setup lang="ts">
import { useQuasar } from 'quasar'
import { ref } from 'vue'
import axios from 'axios'

defineOptions({
  name: 'IndexPage'
});

const $q = useQuasar()
//form verileri
const name = ref<string | null>(null)
const age = ref<number | null>(null)
const accept = ref<boolean>(false)
//form submit edilmişse
const onSubmit = async () => {
  if (!accept.value) {
    $q.notify({
      color: 'red-5',
      textColor: 'white',
      icon: 'warning',
      message: 'You need to accept the license and terms first',
      position: 'top-right'
    })
    return
  }
  //form verilerini apiye gönder
  try {
    const response = await axios.post('http://localhost/veri/crud-project/api.php', {
      name: name.value,
      age: age.value
    })
    $q.notify({
      color: 'green-4',
      textColor: 'white',
      icon: 'cloud_done',
      message: response.data.message, 
      position: 'top-right'
    })
    onReset();
  } catch (error) {
    $q.notify({
      color: 'red-5',
      textColor: 'white',
      icon: 'warning',
      message: 'An error occurred while sending the form',
      position: 'top-right'
    })
  }
}

//form resetle
const onReset = () => {
  name.value = null
  age.value = null
  accept.value = false
}
</script>
