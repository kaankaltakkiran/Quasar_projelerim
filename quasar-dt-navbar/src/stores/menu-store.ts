// src/stores/userStore.js
import { defineStore } from 'pinia';
import { api } from 'src/boot/axios'; // Import the axios instance

// Menu arayüzünü içe aktar
interface Menu {
  id: number;
  parent_id: number;
  sort_order: number;
  page_name: string;
  page_slug: string;
  page_content: string;
  counter: number;
}
// Define the user store
export const useMenuStore = defineStore('menu', {
  state: () => ({
    menus: [] as Menu[], // Menu arayüzünü kullan
  }),
  actions: {
    async fetchMenus() {
      try {
        //http://localhost/veri/navbar-project/navbar-data/get_menu.php adresine GET isteği gönderir
        const response = await api.get('/get_menu.php');
        this.menus = response.data;
        console.log(this.menus);
      } catch (error) {
        console.error('Failed to fetch users:', error);
      }
    },
  },
});