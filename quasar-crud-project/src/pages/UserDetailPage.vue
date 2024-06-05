<template>
  <div class="q-pa-md" v-if="user">
    <q-card style="max-width: 400px; margin: auto;">
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
              <q-item-label>Age</q-item-label>
              <q-item-label caption>{{ user.user_age }}</q-item-label>
            </q-item-section>
          </q-item>
        </q-list>
      </q-card-section>
      <q-card-actions align="right">
        <!--Persons sayfasına dön-->
        <q-btn flat label="Back To Person Page" icon-right="undo" color="secondary" @click="$router.push('/persons')" />
      </q-card-actions>
    </q-card>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import axios from 'axios';

// Route bilgileri
const route = useRoute();
// Kullanıcı bilgileri
const user = ref<{ user_name: string, user_age: number } | null>(null);
// Kullanıcıyı çek
const fetchUser = async (id: number) => {
  try {
    const response = await axios.get(`http://localhost/veri/crud-project/api.php?action=user&userid=${id}`);
    console.log('response:', response);
    if (response.status === 200) {
      user.value = response.data;
    }
  } catch (error) {
    console.error('Error fetching user:', error);
  }
};

onMounted(() => {
  //route parametresinden userid'yi al
  const id = Number(route.params.userid);
  //id'ye göre kullanıcıyı çek
  fetchUser(id);
});
</script>
