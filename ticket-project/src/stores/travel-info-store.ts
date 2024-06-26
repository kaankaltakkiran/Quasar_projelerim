import { defineStore } from 'pinia';
import { ITravelİnfo, ICountry } from '../models/model';

export const useTravelStore = defineStore('travel', {
  //seyhat bilgileri
  state: () => ({
    travelInfo: {
      companyName: 'Otobüs Firma Adı',
      departureStation: 'Yolculuk Başlangıç Noktası',
      arrivalStation: 'Yolculuk Varış Noktası',
      passengerCount: '1',
      selectedDate: 'Seçilen Tarih',
    } as ITravelİnfo,
    //istasyon listesi
    countryOptions: [] as ICountry[],
  }),
  //seyhat bilgilerini güncelleme
  actions: {
    updateTravelInfo(travelInfo: ITravelİnfo) {
      this.travelInfo = travelInfo;
    },
    async fetchCountries() {
      const response = await fetch('/data/countries.json'); // JSON dosyasının yolunu belirtin
      const data = await response.json(); // JSON dosyasını okuyun
      this.countryOptions = data;
      console.log(this.countryOptions);
    },
  },
});
