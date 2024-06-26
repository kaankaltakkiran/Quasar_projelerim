import { defineStore } from 'pinia';
import { IStation } from 'src/models/model';

export const useStationStore = defineStore('station', {
  state: () => ({
    //istasyon listesi
    stations: [] as IStation[],
    //kalkış istasyonu
    departureStation: null as string | null,
    //varış istasyonu
    arrivalOptions: [] as string[],
    //varış istasyonu opsionları
    arrivalStation: null as string | null,
  }),
  getters: {
    //istasyon isimlerini döndür
    stationNames: (state) => state.stations.map((station) => station.name),
  },
  actions: {
    //istasyonları getir
    async fetchStations() {
      const response = await fetch('/data/stations.json'); // JSON dosyasının yolunu belirtin
      const data = await response.json(); // JSON dosyasını okuyun
      this.stations = data; // istasyonları güncelle
      console.log(this.stations);
    },
    //varış istasyonu seçeneklerini güncelle
    updateArrivalOptions(selected: string | null) {
      if (selected) {
        const selectedStation = this.stations.find(
          (station) => station.name === selected
        );
        if (selectedStation) {
          this.arrivalOptions = this.stations
            .filter((station) =>
              selectedStation.targets.includes(parseInt(station.id))
            )
            .map((station) => station.name);
        } else {
          this.arrivalOptions = [];
        }
      } else {
        this.arrivalOptions = [];
      }
    },
    //kalkış istasyonu değiştiğinde
    handleDepartureChange(selected: string | null) {
      this.updateArrivalOptions(selected);
      this.arrivalStation = null;
    },
  },
});
