import { defineStore } from 'pinia';
import { ITravelİnfo } from '../models/model';

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
  }),
  //seyhat bilgilerini güncelleme
  actions: {
    updateTravelInfo(travelInfo: ITravelİnfo) {
      this.travelInfo = travelInfo;
    },
  },
});
