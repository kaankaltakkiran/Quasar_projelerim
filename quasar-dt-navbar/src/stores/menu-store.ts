// src/stores/userStore.js
import { defineStore } from 'pinia';
import { api } from 'src/boot/axios'; // Import the axios instance

// Define the user store
export const useMenuStore = defineStore('menu', {
  state: () => ({
    menus: [],
  }),
  actions: {
    async fetchMenus() {
      try {
        //http://localhost/veri/get_menu.php adresine GET isteği gönderir
        const response = await api.get('/get_menu.php');
        this.menus = response.data;
        console.log(this.menus);
      } catch (error) {
        console.error('Failed to fetch users:', error);
      }
    },
  },
});