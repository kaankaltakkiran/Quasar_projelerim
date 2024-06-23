<template>
  <div class="q-pa-md" v-if="user">
    <q-card style="max-width: 400px; margin: auto">
      <q-card-section>
        <div class="text-h6 text-red text-center">
          Detaylı Kullanıcı Bilgileri
        </div>
      </q-card-section>
      <q-card-section>
        <q-list>
          <q-item>
            <q-item-section>
              <q-item-label>İsim</q-item-label>
              <q-item-label caption>{{ user.user_name }}</q-item-label>
            </q-item-section>
          </q-item>
          <q-item>
            <q-item-section>
              <q-item-label>Email</q-item-label>
              <q-item-label caption>{{ user.user_email }}</q-item-label>
            </q-item-section>
          </q-item>
          <q-item>
            <q-item-section>
              <q-item-label>Durum</q-item-label>
              <q-item-label caption>
                {{ Number(user.user_status) === 1 ? 'Aktif' : 'Pasif' }}
              </q-item-label>
            </q-item-section>
          </q-item>
        </q-list>
      </q-card-section>
      <q-card-actions align="right">
        <!--Persons sayfasına dön-->
        <q-btn
          flat
          label="Kişiler sayfasına dön"
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
import { api } from 'boot/axios'; //axios instance
import { IPerson } from 'src/models/model';

// Route bilgileri
const route = useRoute();
const router = useRouter();
// Kullanıcı bilgileri
const user = ref<IPerson | null>(null);

// Kullanıcıyı çek
const fetchUser = async (id: number) => {
  try {
    const response = await api.post('api.php', {
      method: 'get-user',
      id: id,
    });
    //console.log('response:', response);
    if (response.data.success === true && response.data.user) {
      user.value = response.data.user;
    } else {
      router.push('/404'); // User yoksa 404 sayfasına yönlendir
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
