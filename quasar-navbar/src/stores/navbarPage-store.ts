import { defineStore } from 'pinia';
import navbarJson from '../api/navbar.json';

// Tip güvenliği için kullanılacak olan arayüz
interface Page {
  id: number;
  parent_id: number;
  sort_order: number;
  page_name: string;
  page_slug: string;
  page_content: string;
  counter: number;
}
interface DataState {
    pages: Page[];
}

export const useNavbarStore = defineStore('navbarStore', {
  state: (): DataState => ({
    pages: [],
  }),
  // sayfaların yüklenmesi
  actions: {
    loadData() {
      this.pages = navbarJson.pages;
    },
  },
});
