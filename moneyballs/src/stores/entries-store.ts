import { defineStore } from 'pinia';
import { IEntry } from '../models/models';

// Tip güvenliği için kullanılacak olan arayüz
interface DataState {
  entries: IEntry[];
}
// store oluşturma
export const useEntryStore = defineStore('entriesStore', {
  state: (): DataState => ({
    entries: [],
  }),
  // entries yüklenmesi
  actions: {
    async fetchEntries() {
      const response = await fetch('/data/entries.json'); // JSON dosyasının al
      const data = await response.json(); // JSON dosyasını oku
      this.entries = data;
      console.log(this.entries);
    },
  },
});
