import { defineStore } from 'pinia';
import { IOfficalHoliday } from '../models/models';

// Tip güvenliği için kullanılacak olan arayüz
interface DataState {
  officalHolidays: IOfficalHoliday[];
}
// store oluşturma
export const useHolidayStore = defineStore('officalHoliday-store', {
  state: (): DataState => ({
    officalHolidays: [],
  }),
  // entries yüklenmesi
  actions: {
    async fetchEntries() {
      const response = await fetch('/data/official_holidays.json'); // JSON dosyasının al
      const data = await response.json(); // JSON dosyasını oku
      this.officalHolidays = data;
      console.log(this.officalHolidays); // Gelen veriyi konsola yaz
    },
  },
});
