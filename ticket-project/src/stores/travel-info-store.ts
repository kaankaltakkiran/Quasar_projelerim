import { defineStore } from 'pinia';
import { ITravelİnfo } from '../models/model';

export const useTravelStore = defineStore('travel', {
  //seyhat bilgileri
  state: () => ({
    travelInfo: {
      busName: 'Otobüs Firması',
      departureStation: 'Yolculuk Başlangıç Noktası',
      arrivalStation: 'Yolculuk Varış Noktası',
      passengerCount: 'Yolcu Sayısı',
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
