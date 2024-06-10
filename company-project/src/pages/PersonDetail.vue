<template>
  <div class="q-pa-md" v-if="user">
    <q-card style="max-width: 400px; margin: auto">
      <q-card-section>
        <div class="text-h6">User Details</div>
      </q-card-section>
      <q-card-section>
        <q-list>
          <q-item>
            <q-item-section>
              <q-item-label>Name</q-item-label>
              <q-item-label caption>{{ user.user_name }}</q-item-label>
            </q-item-section>
          </q-item>
          <q-item>
            <q-item-section>
              <q-item-label>Email</q-item-label>
              <q-item-label caption>{{ user.user_email }}</q-item-label>
            </q-item-section>
            <q-item-section>
              <q-item-label>Status</q-item-label>
              <q-item-label caption>{{ user.user_status }}</q-item-label>
            </q-item-section>
          </q-item>
        </q-list>
      </q-card-section>
      <q-card-actions align="right">
        <!--Persons sayfasına dön-->
        <q-btn
          flat
          label="Back To Person Page"
          icon-right="undo"
          color="secondary"
          @click="$router.push('/persons')"
        />
      </q-card-actions>
    </q-card>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import axios from 'axios';
import { IPerson } from '../interfaces/IPerson';

// Route bilgileri
const route = useRoute();
const router = useRouter();
// Kullanıcı bilgileri
const user = ref<IPerson | null>(null);

//gelen get adına göre veri çekme
const fetchUser = async (userid: number) => {
  try {
    const response = await axios.post(
      'http://localhost/veri/ornek_api/api.php',
      {
        method: 'get-user',
        id: userid,
      }
    );
    // console.log(response.data);
    if (response.data.success === true && response.data.user) {
      user.value = response.data.user;
    } else {
      //???????? Kullanıcı bulunamazsa 404 sayfasına yönlendir ????????
      router.push('/404');
    }
  } catch (error) {
    console.error('Error fetching data:', error);
  }
};

onMounted(() => {
  //route parametresinden userid'yi al
  const id = Number(route.params.userid);
  //id'ye göre kullanıcıyı çek
  fetchUser(id);
});
</script>
